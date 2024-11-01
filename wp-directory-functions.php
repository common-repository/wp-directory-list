<?php
/*********************************************************************
*                                                                    *
*	WordPress 2.7 Plugin: Wp-Directory-List                            *
*	Copyright (c) 2009 Billie Kennedy Jr                               *
*                                                                    *
*	- http://themoneymakingwebsite.com                                 *
*                                                                    *
*	File Information:                                                  *
*	- Directory Functions                                              *
* - wp-content/plugins/wp-directory-list/wp-directory-functions.php  *
* - Version 1.0.0                                                    *
*                                                                    *
*********************************************************************/

function get_link_count($category){
  global $wpdb;
  global $wp_directory_table2;
  
  $sql = "SELECT title FROM ".$wp_directory_table2." WHERE categories=".$category;
  $results = $wpdb->query($sql);
  
  $count = print_r($results,true);
  
  return $count;
}

function directory_get_link($category){
  global $wp_rewrite;
	
	$pagestruct = $wp_rewrite->get_page_permastruct();
	
  if ( '' != $pagestruct){
    $category = str_replace(' ', '_', $category);
    $link = str_replace('%pagename%', $category, $pagestruct);
   
	} else { // if they're not using the fancy permalink option
    // don't do anything acutally.
  }


  return $link;
}


?>
