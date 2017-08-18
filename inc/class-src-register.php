<?php

/**
 * Register a user.
 *
 * @copyright Copyright (c), Ryan Hellyer
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @package SRC Theme
 * @since SRC Theme 1.0
 */
class SRC_Register extends SRC_Core {

	/**
	 * Constructor.
	 * Add methods to appropriate hooks and filters.
	 */
	public function __construct() {

		// Add action hooks
		add_action( 'init',                array( $this, 'init' ) );
		add_shortcode( 'src-register',     array( $this, 'register_shortcode' ) );
		add_action( 'src_register_start',  array( $this, 'register_start_fields' ) );
		add_action( 'src_register_end',    array( $this, 'register_end_fields' ) );

	}

	/**
	 * Display registration/login/profile page shortcode content.
	 *
	 * @param   array   $args  The shortcodes arguments
	 */
	public function register_shortcode( $args ) {

		// Don't show shortcode when logged in and on front page (form serves no purpose there then)
		if ( is_user_logged_in() && is_front_page() ) {
			return;
		}

		$url = '';
		if ( isset( $args['url'] ) ) {
			$url = $args['url'];
		}

		// Sanitize inputs
		$username = '';
		if ( isset( $_POST['src-name'] ) ) {
			$username = sanitize_user(  $_POST['src-name'] );
		}
		$email = '';
		if ( isset( $_POST['src-email'] ) ) {
			$email = sanitize_email( $_POST['src-email'] );
		}
		$display_name = '';
		if ( isset( $_POST['src-name'] ) ) {
			$display_name = sanitize_title( $_POST['src-name'] );
		}
		$password = '';
		if ( isset( $_POST['src-password'] ) ) {
			$password = $_POST['src-password'];
		}

		if ( defined( 'SRC_LOGIN_ERROR' ) ) {
			$message_text = __( 'We detected that you were already a member, so tried logging you in and something went wrong.', 'src' );
		} else if ( defined( 'SRC_IRACING_MEMBER_DOES_NOT_EXIST' ) ) {
			$message_text = __( 'The name you specified does not appear to exist within iRacing. Perhaps you entered your name incorrectly?', 'src' );
		} else if ( defined( 'SRC_USERNAME_EXISTS' ) || defined( 'SRC_EMAIL_EXISTS' ) ) {

			if ( defined( 'SRC_USERNAME_EXISTS' ) ) {
				$message_text = __( 'You already have an account ;) You can login with it if you like.', 'src' );
			}

			// If username exists, but email does, then stick display name into form and hide the email field
			if ( ! defined( 'SRC_USERNAME_EXISTS' ) || defined( 'SRC_EMAIL_EXISTS' ) ) {
				$user = get_user_by( 'email', $email );
				$message_text = __( 'You already have an account ;) You can login with it if you like.', 'src' );
				$display_name = $user->display_name;
				define( 'SRC_USERNAME_EXISTS', true );
			}

		}

		if ( isset( $message_text ) ) {
			echo '<p><strong>' . esc_html( $message_text ) . '</strong></p>';
		}

		echo '
<form action="' . esc_attr( $url ) . '" method="POST">
';

		do_action( 'src_register_start' );

		echo '

	<input name="src-name" type="text" value="' . esc_attr( $display_name ) . '" placeholder="iRacing name" required />';

		if ( ! defined( 'SRC_USERNAME_EXISTS' ) ) {
			echo '
	<input name="src-email" type="email" value="' . esc_attr( $email ) . '" placeholder="Email address" required />';
		}

		if (
			( defined( 'SRC_USERNAME_EXISTS' ) || defined( 'SRC_EMAIL_EXISTS' ) )
			||
			is_user_logged_in()
		) {

			if ( defined( 'SRC_USERNAME_EXISTS' ) || defined( 'SRC_EMAIL_EXISTS' ) ) {
				$password_placeholder = __( 'Enter your password', 'src' );
			} else {
				$password_placeholder = __( 'Enter a unique password', 'src' );
			}

			echo '
	<input name="src-password" type="password" value="' . esc_attr( $password ) . '" placeholder="' . esc_attr( $password_placeholder ) . '" required />';
		}

		do_action( 'src_register_end' );

		if ( defined( 'SRC_USERNAME_EXISTS' ) || defined( 'SRC_EMAIL_EXISTS' ) ) {
			$joinus_text = __( 'Sign in', 'src' );
		} else {
			$joinus_text = __( 'Join us', 'src' );
		}

		echo '
	<input type="submit" value="' . $joinus_text . '" />
</form>';
	}

	/**
	 * Init.
	 */
	public function init() {

		if ( isset( $_POST['src-name'] ) || isset( $_POST['src-email'] ) ) {

			// Sanitize inputs
			$username     = sanitize_user(  $_POST['src-name'] );
			$email        = sanitize_email( $_POST['src-email'] );
			$display_name = esc_html( $_POST['src-name'] );
			if ( isset( $_POST['src-password'] ) ) {
				$password = $_POST['src-password'];
			} else {
				$password = md5( rand() . $username . $email ); // They haven't entered a password, so we just make one for them
			}

			// Check if iRacing member exists
			if ( ! $member_info = $this->iracing_member_info( $display_name ) ) {
				define( 'SRC_IRACING_MEMBER_DOES_NOT_EXIST', true );
				return;
			}

			// Check if username exists
			if ( username_exists( $username ) ) {
				define( 'SRC_USERNAME_EXISTS', true );
				return;
			}

			// Check if email address exists
			if ( email_exists( $email ) ) {
				define( 'SRC_EMAIL_EXISTS', true );
				return;
			}

			// Create the user
			$user_data = array(
				'user_login'   => $username,
				'display_name' => $display_name,
				'user_pass'    => $password,
			);
			$user_id = wp_insert_user( $user_data ) ;

			// If no error, then add meta keys and log the person in
			if ( ! is_wp_error( $user_id ) ) {

				// Add some meta keys
				$meta_keys = array(
					'location',
					'oval_avg_inc',
					'oval_license',
					'oval_irating',
					'road_avg_inc',
					'road_license',
					'road_irating',
				);
				foreach ( $meta_keys as $meta_key ) {
					update_user_meta(
						$user_id,
						esc_html( $meta_key ),
						esc_html( $member_info[$meta_key] )
					);
				}

				// Log the user in
				$this->attempt_user_login( $user_id, $username, $email, $password );
			} else {
				define( 'SRC_LOGIN_ERROR', true );
			}

		}

	}

	/*
	 * The initial input fields for profile editing.
	 *
	 * @global  object  $wpdb  Accessed to allow for faster querying
	 */
	public function register_start_fields() {
		global $wpdb;

		if ( is_user_logged_in() ) {

			// Work out ID of special events
			$post_title = 'Special Events';
			$special_event_col = $wpdb->get_col( "select ID from $wpdb->posts where post_title LIKE '" . $post_title . "%' ");
			$query_args = array(
				'post__in'               => $special_event_col,
				'post_type'              => 'season',
				'posts_per_page'         => 100,
				'post_status'            => array( 'any' ),
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'fields'                 => 'ids',
			);
			$seasons = new WP_Query( $query_args );
			if ( $seasons->have_posts() ) {
				while ( $seasons->have_posts() ) {
					$seasons->the_post();
					$special_events_id = get_the_ID();
				}
			}

			// Get all the Special Events
			$query_args = array(
				'post_type'              => 'event',
				'posts_per_page'         => 10,
				'post_status'            => array( 'future' ),
				'meta_query' => array(
					array(
						'key'     => 'season',
						'value'   => array( $special_events_id ),
						'compare' => 'IN',
					),
				),
				'no_found_rows'          => true,  // useful when pagination is not needed.
				'update_post_meta_cache' => false, // useful when post meta will not be utilized.
				'update_post_term_cache' => false, // useful when taxonomy terms will not be utilized.
				'fields'                 => 'ids',
			);

			echo '<p>' . __( 'Which events would you like to sign up for?', 'src' );
			echo '<input type="checkbox" name="src-events[]" value="' . esc_attr( 'regular-season' ) . '"><label>' . esc_html__( 'Regular Season', 'src' ) . '</label>';
			$posts = new WP_Query( $query_args );
			if ( $posts->have_posts() ) {
				while ( $posts->have_posts() ) {
					$posts->the_post();

					echo '<input type="checkbox" name="src-events[]" value="' . esc_attr( get_the_title( get_the_ID() ) ) . '"><label>' . esc_html( get_the_title( get_the_ID() ) ) . '</label>';
				}
			}
			echo '</p>';

		}

	}

	/**
	 * Extra input fields for profile editing.
	 */
	public function register_end_fields() {

		if ( is_user_logged_in() ) {
			echo '
	<input name="src-website" type="website" placeholder="Website address" />
	<textarea name="src-description" placeholder="Add a description about yourself here. Perhaps include previous sim or real world racing experience, favourite games etc."></textarea>';
		}

	}

	/**
	 * Attempt to log the user in.
	 */
	public function attempt_user_login( $user_id, $username, $email, $password ) {

		$credentials = array();
		$credentials['user_login'] = $username;
		$credentials['user_password'] = $password;
		$credentials['remember'] = true;

		$user = wp_signon( $credentials, false );
		if ( is_wp_error( $user ) ) {

			// Don't worry about invalid username errors, since we're just going to register them if they get it wrong anyway
			if ( ! isset( $user->errors['invalid_username'] ) ) {
				$this->error_messages[] = $user->get_error_message();
			} else {
				$this->invalid_username = true;
			}

			// Set var here so that most of the form can be hidden and replaced with a confirmation page
			$this->confirm = true;

			return;
		}

		// Redirect after login
		$redirect_to = home_url( '/member/' . sanitize_title( $username ) . '/' );
		wp_safe_redirect( $redirect_to, 302 );
		exit();

	}

	/**
	 * Does the name exist within iRacing?
	 * If they do return their info.
	 *
	 * @param  string  $display_name  Name of member
	 * @return array|bool   array if member exists in iRacing, otherwise false
	 */
	public function iracing_member_info( $display_name ) {
		$dir = wp_upload_dir();

		$stats = file_get_contents( $dir['basedir'] . '/iracing-members.json' );
		$stats = json_decode( $stats, true );

		// If user exists in iRacing, then return their stats, otherwise return false
		if ( isset( $stats[$display_name] ) ) {
			return $stats[$display_name];
		} else {
			return false;
		}

	}

}
