<?php

/**
 * Core functionalities.
 * Methods used across multiple classes.
 *
 * @copyright Copyright (c), Ryan Hellyer
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @package Undycar Theme
 * @since Undycar Theme 1.0
 */
class SRC_Core {

	/**
	* Event types.
	*
	* @return array
	*/
	protected function event_types() {

		$types = array(
			'FP1'        => 'Free Practice 1',
			'FP2'        => 'Free Practice 1',
			'Qualifying' => 'Qualifying',
			'Race 1'     => 'Race 1',
			'Race 2'     => 'Race 2',
			'Race 3'     => 'Race 3',
		);

		return $types;
	}

	/**
	 * The championship table.
	 *
	 * @param  string  $content   The post content
	 * @param  bool    $bypass    true if bypassing post-type check
	 * @param  int     $limit     the max number of drivers to show
	 * @param  string  $title     title to use
	 */
	static function championship( $content, $bypass = false, $limit = 100, $title = false ) {

		if ( 'season' !== get_post_type() && true !== $bypass ) {
			return $content;
		}

		if ( is_front_page() ) {

			if ( '' === get_option( 'current-season' ) ) {
				$season_id = get_option( 'last-season' );
			} else {
				$season_id = get_option( 'current-season' );
			}

		} else {
			$season_id = get_the_ID();
		}

		// Get all events from that season
		$query = new WP_Query( array(
			'posts_per_page'         => 100,
			'post_type'              => 'event',

			'meta_key'               => 'season',
			'meta_value'             => $season_id,

			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		) );
		$stored_results = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				foreach ( array( 1, 2, 3 ) as $key => $race_number ) {

					$results = get_post_meta( get_the_ID(), '_results_' . $race_number, true );
					$results = json_decode( $results, true );

					$points_positions = get_post_meta( $season_id, 'points_positions', true );

					if ( is_array( $results ) ) {

						foreach ( $results as $pos => $result ) {

							$name = $result['name'];
							if ( isset( $points_positions[$pos - 1] ) ) {

								if ( isset( $stored_results[$name] ) ) {
									$stored_results[$name] = $stored_results[$name] + $points_positions[$pos - 1];
								} else {
									$stored_results[$name] = $points_positions[$pos - 1];
								}

							}
						}

					}

				}

			}
		}

		arsort( $stored_results );
		wp_reset_query();

		if ( false === $title ) {
			$title = __( 'Championship', 'src' );
		}

		if ( array() !== $stored_results ) {
			$content .= '<h3>' . esc_html( $title ) . '</h3>';
			$content .= '<table id="src-championship">';

			$content .= '<thead><tr>';

			$content .= '
				<th class="col-pos">Pos</th>
				<th class="col-name">Name</th>
				<th class="col-number">Num</th>
				<th class="col-nationality">Nationality</th>
				<th class="col-pts">Pts</th>';
			$content .= '</tr></thead>';

			$content .= '<tbody>';

			$position = 0;
			$car_number = '';
			$nationality = '';
			foreach ( $stored_results as $name => $points ) {
				$position++;

				// Limit the number of drivers shown
				if ( $position > $limit ) {
					continue;
				}

				$linked_name = $name;
				$member = get_user_by( 'login', sanitize_title( $name ) );
				if ( isset( $member->data->ID ) ) {
					$member_id = $member->data->ID;

					if ( '' !== get_user_meta( $member_id, 'car_number', true ) ) {
						$car_number = get_user_meta( $member_id, 'car_number', true );
					}

					if ( '' !== get_user_meta( $member_id, 'nationality', true ) ) {
						$nationality = get_user_meta( $member_id, 'nationality', true );
					}

					$linked_name = '<a href="' . esc_url( home_url() . '/member/' . sanitize_title( $name ) . '/' ) . '">' . esc_html( $name ) . '</a>';

				}

				$content .= '<tr>';

				$content .= '<td class="col-pos">' . esc_html( $position ) . '</td>';
				$content .= '<td class="col-name">' . $linked_name . '</td>';
				$content .= '<td class="col-number">' . esc_html( $car_number ) . '</td>';
				$content .= '<td class="col-nationality">' . esc_attr( $nationality ) . '</td>';
				$content .= '<td class="col-pts">' . esc_html( $points ) . '</td>';

				$content .= '</tr>';
			}
			$content .= '</tbody>';

			$content .= '</table>';
		}

		return $content;
	}

	/**
	 * Register the user.
	 *
	 * @todo Complete PHPDoc
	 */
	public function register_user( $username, $display_name, $password, $email, $member_info ) {
		// Create the user
		$user_data = array(
			'user_login'   => $username,
			'display_name' => $display_name,
			'user_pass'    => $password,
			'user_email'   => $email,
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
				'custid',
			);
			foreach ( $meta_keys as $meta_key ) {
				if ( isset( $member_info[$meta_key] ) ) {
					update_user_meta(
						$user_id,
						esc_html( $meta_key ),
						esc_html( $member_info[$meta_key] )
					);
				}
			}

			update_user_meta( $user_id, 'receive_extra_communication', 1 );

			return true;

		} else {
			define( 'SRC_LOGIN_ERROR', true );

			return false;
		}
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

		/*
		$stats = file_get_contents( $dir['basedir'] . '/iracing-members.json' );
		$stats = json_decode( $stats, true );

		// If user exists in iRacing, then return their stats, otherwise return false
		if ( isset( $stats[$display_name] ) ) {
			return $stats[$display_name];
		} else {
			return false;
		}
		*/



		$stats = file_get_contents( $dir['basedir'] . '/iracing-members-simple.json' );
		$stats = json_decode( $stats, true );

		if ( in_array ( $display_name , $stats ) ) {
			return true;
		}


	}

	/**
	 * Get all drivers from a specific season.
	 * Defaults to all seasons.
	 *
	 * @param  string  $season  The season to get drivers from
	 * @return array  all the drivers for the chosen season
	 */
	public function get_seasons_drivers( $season = 'all' ) {
		$drivers = array();

		$all_drivers = get_users();
		foreach ( $all_drivers as $driver ) {
			$driver_id = $driver->ID;

			// Ignore season 1 drivers who haven't set their password (means they never intended to register for the site)
			if (
				'reserve' === $season
				&&
				(
					'1' !== get_user_meta( $driver_id, 'password_set' )
					&&
					'1' !== get_user_meta( $driver_id, 'season', true )
				)
				&&
				get_post_field( 'post_name', get_post( get_option( 'next-season' ) ) ) !== get_user_meta( $driver_id, 'season', true )
			) {

				// check if super admin, to avoid Ryan's personal account appearing in list
				if ( ! is_super_admin( $driver_id ) ) {
					$drivers[] = $driver->ID;
				}

			} else if  (
				'all' === $season || $season === get_user_meta( $driver_id, 'season', true )
				&&
				'reserve' !== $season
			) {
				$drivers[] = $driver->ID;
			}

		}

		return $drivers;
	}

}
