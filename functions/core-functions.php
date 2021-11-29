<?php

/**
 * Define functions using in Woo Custom Plugin
 */

function olek_pro_elementor_grid_col_cnt( $settings ) {

	$col_cnt = array(
		'xl'  => isset( $settings['col_cnt_xl'] ) ? (int) $settings['col_cnt_xl'] : 0,
		'lg'  => isset( $settings['col_cnt'] ) ? (int) $settings['col_cnt'] : 0,
		'md'  => isset( $settings['col_cnt_tablet'] ) ? (int) $settings['col_cnt_tablet'] : 0,
		'sm'  => isset( $settings['col_cnt_mobile'] ) ? (int) $settings['col_cnt_mobile'] : 0,
		'min' => isset( $settings['col_cnt_min'] ) ? (int) $settings['col_cnt_min'] : 0,
	);

	return $col_cnt;
}

if ( ! function_exists( 'olek_strip_script_tags' ) ) :
	function olek_strip_script_tags( $content ) {
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = preg_replace( '/<script.*?\/script>/s', '', $content ) ? : $content;
		$content = preg_replace( '/<style.*?\/style>/s', '', $content ) ? : $content;
		return $content;
	}
endif;
