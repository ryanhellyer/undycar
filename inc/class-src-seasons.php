<?php

/**
 * Seasons.
 *
 * @copyright Copyright (c), Ryan Hellyer
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @package SRC Theme
 * @since SRC Theme 1.0
 */
class SRC_Seasons extends SRC_Core {

	/**
	 * Constructor.
	 * Add methods to appropriate hooks and filters.
	 */
	public function __construct() {

		// Add action hooks
		add_action( 'init',            array( $this, 'init' ) );
		add_action( 'the_content',     array( $this, 'schedule' ) );

	}

	/**
	 * Init.
	 */
	public function init() {

		register_post_type(
			'season',
			array(
				'public'       => true,
				'label'        => __( 'Season', 'src' ),
				'supports'     => array( 'thumbnail', 'title', 'editor' ),
				'show_in_menu' => 'edit.php?post_type=event',
			)
		);

	}

	public function schedule( $content ) {

		if ( 'season' === get_post_type() ) {
			$content .= '<table><thead><tr><th>Num</th><th>Event</th><th>FP 1</th><th>FP 2</th><th>Qualifying</th><th>Race 1</th><th>Race 2</th><th></th></tr></thead><tr><td>1</td><td>Hockenheim <span></span></td><td>20:00 CET <span>1 April 2017</span></td><td>20:00 CET <span>7 April 2017</span></td><td>19:40 CET <span>8 April 2017</span></td><td>20:00 CET <span>8 April 2017</span></td><td>20:45 CET <span>8 April 2017</span></td><td><span class="tick-mark"></div></td></tr><tr><td>2</td><td>Nürburgring <span></span></td><td>20:00 CET <span>15 April 2017</span></td><td>20:00 CET <span>21 April 2017</span></td><td>19:40 CET <span>22 April 2017</span></td><td>20:00 CET <span>22 April 2017</span></td><td>20:45 CET <span>22 April 2017</span></td><td><span class="tick-mark"></div></td></tr><tr><td>3</td><td>Laguna Seca <span></span></td><td> <span></span></td><td>20:00 CET <span>28 April 2017</span></td><td>19:40 CET <span>29 April 2017</span></td><td>20:00 CET <span>29 April 2017</span></td><td>20:45 CET <span>29 April 2017</span></td><td><span class="tick-mark"></div></td></tr><tr><td>4</td><td>Monza <span></span></td><td>20:00 CET <span>6 May 2017</span></td><td>20:00 CET <span>12 May 2017</span></td><td>19:40 CET <span>13 May 2017</span></td><td>20:00 CET <span>13 May 2017</span></td><td>20:45 CET <span>13 May 2017</span></td><td><span class="tick-mark"></div></td></tr><tr><td>5</td><td>Bathurst <span>Four hour enduro race. Double points awarded.</span></td><td>20:00 CET <span>27 May 2017</span></td><td>20:00 CET <span>2 June 2017</span></td><td>13:45 CET <span>3 June 2017</span></td><td>15:00 CET <span>3 June 2017</span></td><td> <span></span></td><td><span class="tick-mark"></div></td></tr><tr><td>6</td><td>Silverstone <span></span></td><td>20:00 CET <span>10 June 2017</span></td><td>20:00 CET <span>16 June 2017</span></td><td>19:40 CET <span>17 June 2017</span></td><td>20:00 CET <span>17 June 2017</span></td><td>20:45 CET <span>17 June 2017</span></td><td><span class="tick-mark"></div></td></tr><tr><td>7</td><td>Daytona oval race <span>Oval race. Double points awarded.</span></td><td>20:00 CET <span>24 June 2017</span></td><td>20:00 CET <span>30 June 2017</span></td><td>19:40 CET <span>1 July 2017</span></td><td>20:00 CET <span>1 July 2017</span></td><td>20:45 CET <span>1 July 2017</span></td><td><span class="tick-mark"></div></td></tr><tr><td>8</td><td>Road Atlanta <span></span></td><td>20:00 CET <span>1 July 2017</span></td><td>20:00 CET <span>7 July 2017</span></td><td>19:40 CET <span>8 July 2017</span></td><td>20:00 CET <span>8 July 2017</span></td><td>20:45 CET <span>8 July 2017</span></td><td><span class="tick-mark"></div></td></tr><tr><td>9</td><td>Red Bull Ring <span></span></td><td>20:00 CET <span>15 July 2017</span></td><td>20:00 CET <span>21 July 2017</span></td><td>19:40 CET <span>22 July 2017</span></td><td>20:00 CET <span>22 July 2017</span></td><td>20:45 CET <span>22 July 2017</span></td><td><span class="tick-mark"></div></td></tr><tr><td>10</td><td>Norisring <span></span></td><td>20:00 CET <span>29 July 2017</span></td><td>20:00 CET <span>4 August 2017</span></td><td>19:40 CET <span>5 August 2017</span></td><td>20:00 CET <span>5 August 2017</span></td><td>20:45 CET <span>5 August 2017</span></td><td><span class="tick-mark"></div></td></tr></table>';
		}

		return $content;
	}

}
