<?php
/*********************************************************************
*                                                                    *
*	WordPress 2.7 Plugin: Wp-Directory-List                            *
*	Copyright (c) 2009 Billie Kennedy Jr                               *
*                                                                    *
*	- http://themoneymakingwebsite.com                                 *
*                                                                    *
*	File Information:                                                  *
*	- Directory Categories                                             *
* - wp-content/plugins/wp-directory-list/wp-directory-countries.php  *
* - Version 1.0.1                                                    *
*                                                                    *
*********************************************************************/
function get_country_options($editcountry=null){

  global $wpdb;
  
  $country_table = $wpdb->prefix .'directory_country_list';
  $results = '';

 
  if($editcountry){
    
    $sql = "SELECT name FROM $country_table WHERE iso3='".$editcountry."'";
    $get_country = $wpdb->get_results($sql);
    $results .= '<option value="'.$editcountry.'" selected>'.$get_country[0]->name.'</option>';

  }elseif(''!=get_option('directory_country')){
  
    $sql = "SELECT name FROM $country_table WHERE iso3='".get_option('directory_country')."'";
    $get_country = $wpdb->get_results($sql);
    $results .= '<option value="'.get_option('directory_country').'" selected>'.$get_country[0]->name.'</option>';
    
  }else{

    $results .= '<option value="  " selected>(please select a country)</option>';

  }

  $results .= '<option value="--">none</option>';

  $sql = "SELECT name,iso3 FROM $country_table";
  $get_countries = $wpdb->get_results($sql);

  if($get_countries){
    foreach($get_countries as $countries){
  
    $results .= '<option value="'.$countries->iso3.'">'.$countries->name.'</option>';
    
    }
    
   }
 
  return $results;
 
 }
 ?>