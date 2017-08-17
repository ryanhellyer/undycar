<?php

add_option( 'src_featured_page', '' );
add_option( 'src-season', '' );

/*
if (  'dev-hellyer.kiwi' !== $_SERVER['HTTP_HOST']      && ! is_user_logged_in() && ! is_admin() && $GLOBALS['pagenow'] != 'wp-login.php' ) {

	echo '
	<style>
	body {background:#000;}
	img {display:block;margin:50px auto 0 auto;width:160px;height:auto;}
	p {font-family:sans-serif;color:#fff;font-size:32px;text-align:center;}
	#the-form {
		width:350px;margin:0 auto;text-align:left;
		margin-top:100px;
	}
	input {font-size:24px;display: block;width:100%;}
	#the-form p {
		text-align:left;
		font-size:24px;
	}
	</style>
	<img src="https://seacrestracing.club/wp-content/themes/src/images/logo.png" />
	<p>Website coming soon :)</p>

	<div id="the-form">
	';
	wp_login_form();
	echo '</div>';
	die;
}
*/


require( 'inc/class-src-core.php' );
require( 'inc/class-src-admin.php' );
require( 'inc/class-src-setup.php' );
require( 'inc/class-src-gallery.php' );
require( 'inc/class-src-cron.php' );
require( 'inc/class-src-tracks.php' );
require( 'inc/class-src-seasons.php' );
require( 'inc/class-src-events.php' );
require( 'inc/class-src-register.php' );

require( 'inc/functions.php' );

new SRC_Admin;
new SRC_Gallery;
new SRC_Cron();
new SRC_Tracks();
new SRC_Seasons();
new SRC_Events();
new SRC_Register();
