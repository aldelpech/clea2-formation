<?php
/**
 * 
 * this file is designed to provide specific functions for the child theme
 *
 * @package    clea-2-formation
 * @subpackage Functions
 * @version    0.9.0
 * @since      0.1.0
 * @author     Anne-Laure Delpech <ald.kerity@gmail.com>  
 * @copyright  Copyright (c) 2015 Anne-Laure Delpech
 * @link       
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


// Do theme setup on the 'after_setup_theme' hook.
add_action( 'after_setup_theme', 'clea_formation_theme_setup', 11 ); 

// Remove cleaner-gallery css. Necessary for jetpack gallery.
add_action( 'wp_enqueue_scripts', 'clea_formation_remove_cleaner_gallery', 99 );

/* support thumbnails for LearnDash contents */ 
add_action( 'init', 'clea_learndash_featured_thumbnail' );

/* add support for learndash : excerpt */
add_action( 'init', 'clea_add_excerpt_to_learndash' );

// Get the child template directory and make sure it has a trailing slash.
$child_dir = trailingslashit( get_stylesheet_directory() );
// require_once( $child_dir . 'inc/setup.php' );



function clea_formation_theme_setup() {

	/* Register and load scripts. */
	add_action( 'wp_enqueue_scripts', 'clea_formation_enqueue_scripts' );

	/* Register and load styles. */
	add_action( 'wp_enqueue_scripts', 'clea_formation_enqueue_styles', 4 ); 

	/* Set content width. */
	hybrid_set_content_width( 700 );	
	
	// add theme support for WordPress post thumbnails
	add_theme_support( 'post-thumbnails' ); 
	
}
 

function clea_formation_remove_cleaner_gallery() {
	// necessary if using jetpack gallery
	// source http://themehybrid.com/board/topics/loads-gallery-min-css-twice
	wp_dequeue_style( 'gallery' );	
}


	
 
function clea_formation_enqueue_styles() {

	// feuille de style pour l'impression
	wp_enqueue_style( 'print', get_stylesheet_directory_uri() . '/css/print.css', array(), false, 'print' );



}

function clea_formation_enqueue_scripts() {

	// embed font awesome 
	wp_enqueue_script( 'font-awesome', 'https://use.fontawesome.com/af5aa524e2.js', false );


}

function clea_learndash_featured_thumbnail() {
	add_theme_support( 'post-thumbnails', array( 
		'sfwd-certificates', 
		'sfwd-courses', 
		'sfwd-lessons', 
		'sfwd-topic', 
		'sfwd-quiz', 
		'sfwd-assignment', 
		'sfwd-essays' ) 
	);
}

/**
 * add excerpt support to learndash custom post types
 
 * @since  0.1
 * @access public
 * @return void
 */
function clea_add_excerpt_to_learndash() {
	add_post_type_support( 'sfwd-courses', 'excerpt' );
	add_post_type_support( 'sfwd-lessons', 'excerpt' );
	add_post_type_support( 'sfwd-topic', 'excerpt' );
}


?>