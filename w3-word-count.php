<?php
/*
Plugin Name: W3 Word Count
Plugin URI: https://github.com/w3programmers/w3-word-count
Description: Count Words from any WordPress Post
Version: 1.0
Author: Masud Alam
Author URI: http://w3programmers.com/bangla
License: GPLv2 or later
Text Domain: w3-word-count
Domain Path: /languages/
*/

function w3_word_count_load_text_domain() {
    load_plugin_textdomain( 'w3-word-count', false, dirname( __FILE__ ) . "/languages" );
}

add_action( "plugins_loaded", 'w3_word_count_load_text_domain' );


add_filter( 'the_content', 'w3_word_count' );
function w3_word_count( $content ) {
    $content_without_html_tags = strip_tags( $content );
    $total_words      = str_word_count( $content_without_html_tags );
    $label            = __( 'Total Number of Words', 'w3-word-count' );
    $label            = apply_filters( "w3_word_count_heading", $label );
    $tag              = apply_filters( 'w3_word_count_tag', 'h4' );
    $new_content      = sprintf( '<%s>%s: %s</%s>', $tag, $label, $total_words, $tag );
    $new_content     .=$content;
    $all_info         =[$new_content,$total_words];
    return apply_filters_ref_array("w3_reading_time",$all_info);
}

add_filter("w3_reading_time","w3_word_count_reading_time",10,2);

function w3_word_count_reading_time( ...$args ) {
    //var_dump($args);
    $content=$args[0];
    $total_words=$args[1];
    $reading_minute   = floor( $total_words / 200 );
    $reading_seconds  = floor( $total_words % 200 / ( 200 / 60 ) );
    $is_visible       = apply_filters( 'w3_word_count_display_reading_time', 1 );
    if ( $is_visible ) {
        $label   = __( 'Total Reading Time', 'w3-word-count' );
        $label   = apply_filters( "w3_word_count_reading_time_heading", $label );
        $tag     = apply_filters( 'w3_word_count_reading_time_tag', 'h4' );
        $new_content = sprintf( '<%s>%s: %s minutes %s seconds</%s>', $tag, $label, $reading_minute, $reading_seconds, $tag );
        $new_content .=$content;
    }

    return $new_content;
}




