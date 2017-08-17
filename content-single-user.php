<?php



/**
 * Single User Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_notices' ); ?>




<style>
#members-car {
	float: right;
	width: 500px;
}

h1 img {
	margin-right: 15px;
	width: 128px;
	height: 128px;
	border-radius: 64px;
	position: relative;
	top: 50px;
}

</style>

<?php do_action( 'bbp_template_before_user_details' ); ?>

<h1>
	<?php

	echo get_avatar( bbp_get_displayed_user_field( 'user_email', 'raw' ), apply_filters( 'bbp_single_user_details_avatar_size', 500 ) );

	echo esc_html( bbp_get_displayed_user_field( 'display_name', 'raw' ) );

	if ( bbp_is_user_home() || current_user_can( 'edit_users' ) ) { ?>
	<small>(<a href="<?php bbp_user_profile_edit_url(); ?>" title="<?php printf( esc_attr__( "Edit %s's Profile", 'bbpress' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php _e( 'Edit', 'bbpress' ); ?></a>)</small><?php
	}

	?>
</h1>


<div id="members-car">
	<?php
	$image = get_user_meta( bbp_get_displayed_user_id(), 'bbpress_secondary_image', true );
	if ( isset( $image['full'] ) ) {
		echo '<img src="' . esc_url( $image['full'] ) . '" />';
	}
 	?>
</div><!-- #avatar -->


<?php do_action( 'bbp_template_after_user_details' ); ?>




<?php if ( bbp_is_single_user_edit()          ) bbp_get_template_part( 'form', 'user-edit'       ); ?>
<?php if ( bbp_is_single_user_profile()       ) {

	do_action( 'bbp_template_before_user_profile' ); ?>

	<?php if ( bbp_get_displayed_user_field( 'description' ) ) : ?>

		<p class="bbp-user-description"><?php bbp_displayed_user_field( 'description' ); ?></p>

	<?php endif; ?>

<ul id="src-members-social-links">
	<li><?php printf( __( 'Topics Started: %s',  'bbpress' ), bbp_get_user_topic_count_raw() ); ?></li>
	<li><?php printf( __( 'Replies Created: %s', 'bbpress' ), bbp_get_user_reply_count_raw() ); ?></li>
<?php
if ( '' != get_user_meta( bbp_get_displayed_user_id(), 'twitter', true ) ) {
	echo '<li><a href="' . esc_url( get_user_meta( bbp_get_displayed_user_id(), 'twitter', true ) ) . '">Twitter</a></li>';
}

if ( '' != get_user_meta( bbp_get_displayed_user_id(), 'facebook', true ) ) {
	echo '<li><a href="' . esc_url( get_user_meta( bbp_get_displayed_user_id(), 'facebook', true ) ) . '">Facebook</li>';
}

if ( '' != get_user_meta( bbp_get_displayed_user_id(), 'youtube', true ) ) {
	echo '<li><a href="' . esc_url( get_user_meta( bbp_get_displayed_user_id(), 'youtube', true ) ) . '">YouTube</li>';
}

if ( '' != get_user_meta( bbp_get_displayed_user_id(), 'steam', true ) ) {
	echo '<li><a href="' . esc_url( get_user_meta( bbp_get_displayed_user_id(), 'steam', true ) ) . '">Steam</a></li>';
}

?>
</ul><?php


// Output tagged images as gallery
$gallery_images = get_user_meta( bbp_get_displayed_user_id(), 'gallery_image' );
if ( is_array( $gallery_images ) ) {

	$attachment_ids = '';
	foreach ( $gallery_images as $key => $attachment_id ) {

		if ( '' !== $attachment_ids ) {
			$attachment_ids .= ',';
		}

		$attachment_ids .= $attachment_id;
	}

	echo do_shortcode( '[gallery ids="' . $attachment_ids . '"]' );

}


do_action( 'bbp_template_after_user_profile' );

}
