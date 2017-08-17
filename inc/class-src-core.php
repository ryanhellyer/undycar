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

NEED TO ADD CRON JOB FOR UPDATE JSON FILE
AND CRON JOB FOR DOWNLOADING RAW FILES FROM IRACING

	public function convert_iracing_users_to_json() {

		$stats['oval'] = file_get_contents( get_template_directory() . '/Oval_driver_stats.csv' );
		$stats['road'] = file_get_contents( get_template_directory() . '/Road_driver_stats.csv' );

		// Loop through each type of racing individually
		$new_stats = array();
		foreach ( $stats as $type => $x ) {

			$stats[$type] = explode( "\n", $stats[$type] );

			// Loop through each drivers stats individually
			unset( $stats[$type][0] );
			$stat_count = count( $stats[$type] );
			//$stat_count = 2;
			foreach ( $stats[$type] as $key => $values ) {
				$values = str_replace( '"', '', $values );

				$values = explode( ',', $values );

				// Get drivers name for array key
				$drivers_name = esc_html( $values[0] );

				// Creating individual drivers data array
				$data = array();
				if ( isset( $values[2] ) ) {
					$data['location'] = $values[2];
				}
				if ( isset( $values[12] ) ) {
					$data[$type . '_avg_inc'] = $values[12];
				}
				if ( isset( $values[13] ) ) {
					$data[$type . '_license'] = substr( $values[13], 0, 1 );
				}
				if ( isset( $values[14] ) ) {
					$data[$type . '_irating'] = $values[14];
				}

				// Sanitizing inputs
				foreach ( $data as $x => $d ) {
					$x = mb_strimwidth ( esc_html( $x ), 0 , 100 );
					$d = mb_strimwidth ( esc_html( $d ), 0 , 30 );
					$data[$x] = $d;
				}

				// Seems to prevent it running out of memory, not sure why
				if ( $key > $stat_count ) {
					continue;
				}

				// Create array with alphabetical key
				$new_stats[$type][$drivers_name] = $data;

			}

			unset( $stats[$type] );

		}

		$new_stats = array_replace_recursive( $new_stats['oval'], $new_stats['road'] );

		file_put_contents( get_template_directory() . '/iracing-members.json', json_encode( $new_stats ) );
		unset( $new_stats );

		//$new_stats = json_decode( file_get_contents( get_template_directory() . '/test.txt' ), true );

	}

}
