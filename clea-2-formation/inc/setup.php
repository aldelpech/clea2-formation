<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package    stargazer
 * @subpackage Functions
 * @version    1.0.0
 * @author     Anne-Laure Delpech <ald.kerity@gmail.com>
 * @copyright  Copyright (c) 2016 - 2019, Anne-Laure Delpech
 * @link       http://parcours-performance.com
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
 

# Change Read More link in automatic Excerpts
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wpse_custom_wp_trim_excerpt');


/*******************************************
* Change Read More link in Excerpts 
*
* see 
* http://wordpress.stackexchange.com/questions/207050/read-more-tag-shows-up-on-every-post
* http://wordpress.stackexchange.com/questions/141125/allow-html-in-excerpt/141136#141136
*  

*******************************************/

if ( ! function_exists( 'wpse_custom_wp_trim_excerpt' ) ) : 

	function wpse_allowedtags() {
		// Add custom tags to this string
		// <a>,<img>,<video>,<script>,<style>,<audio> are not in
		return '<br>,<em>,<i>,<ul>,<ol>,<li>,<p>'; 
	}
  
endif; 
	
	
if ( ! function_exists( 'wpse_custom_wp_trim_excerpt' ) ) : 

    function wpse_custom_wp_trim_excerpt($wpse_excerpt) {
		$raw_excerpt = $wpse_excerpt;
		
		// text for the "read more" link
		$rm_text = __( 'La suite &raquo;', 'stargazer' ) ;
		$excerpt_end = ' <a class="more-link" href="'. esc_url( get_permalink() ) . '">' . $rm_text . '</a>'; 
		
		
        if ( '' == $wpse_excerpt ) {  

            $wpse_excerpt = get_the_content('');
            $wpse_excerpt = strip_shortcodes( $wpse_excerpt );
            $wpse_excerpt = apply_filters('the_content', $wpse_excerpt);
            $wpse_excerpt = str_replace(']]>', ']]&gt;', $wpse_excerpt);
            $wpse_excerpt = strip_tags($wpse_excerpt, wpse_allowedtags()); /*IF you need to allow just certain tags. Delete if all tags are allowed */

            //Set the excerpt word count and only break after sentence is complete.
                $excerpt_word_count = 75;
                $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count); 
                $tokens = array();
                $excerptOutput = '';
                $count = 0;

                // Divide the string into tokens; HTML tags, or words, followed by any whitespace
                preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens);

                foreach ($tokens[0] as $token) { 

                    if ($count >= $excerpt_length && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) { 
                    // Limit reached, continue until , ; ? . or ! occur at the end
                        $excerptOutput .= trim($token);
                        break;
                    }

                    // Add words to complete sentence
                    $count++;

                    // Append what's left of the token
                    $excerptOutput .= $token;
                }

            $wpse_excerpt = trim(force_balance_tags($excerptOutput));
		   
				// $wpse_excerpt .= $excerpt_end ;
				$excerpt_more = apply_filters( 'excerpt_more', ' ' . $excerpt_end ); 

                $pos = strrpos($wpse_excerpt, '</');
                if ($pos !== false) {
					// Inside last HTML tag
					$wpse_excerpt = substr_replace($wpse_excerpt, $excerpt_end, $pos, 0); // Add read more next to last word 
				} else {
					// After the content
					$wpse_excerpt .= $excerpt_more; //Add read more in new paragraph 
				}
                
            return $wpse_excerpt;   

        } /* else {
			return 'AAA ! ' . $raw_excerpt;
		} */
		
		// add read more link to the manual extract
		$wpse_excerpt .= $excerpt_end ;
		// return the manual extract
        // return apply_filters('wpse_custom_wp_trim_excerpt', 'AAA ! ' . $wpse_excerpt, $raw_excerpt);
		return apply_filters('wpse_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt);
    }
  
endif; 


?>