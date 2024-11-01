<?php
/***********************************************************************
*                                                                      *
*	WordPress 2.7 Plugin: Wp-Directory-List                              *
*	Copyright (c) 2009 Billie Kennedy Jr                                 *
*                                                                      *
*	- http://themoneymakingwebsite.com                                   *
*                                                                      *
*	File Information:                                                    *
*	- Configure Directory Options                                        *
* - wp-content/plugins/wp-directory-list/wp-directory-link-html.php    *
* - Version 2.1.2                                                      *
*                                                                      *
***********************************************************************/

$wp_directory_table = $wpdb->prefix .'directory_links';
$wp_directory_table2 = $wpdb->prefix .'directory_link_cats';

if($get_links) {

  // lets get the options just once.
  
  $my_blog = get_option('blogurl');
  $links_per_row = intval(get_option('links_per_row'));
  
  if(get_option('create_link_thumbs')){
    include(dirname(__FILE__).'/stw.class.php');
    $stwargs['stwu'] = get_option('stw_secret');
    $stwargs['STWAccessKeyId'] = get_option('stw_access');
  }
  
  if($links_per_row<1){
    
    $links_per_row = 1;
    
    
  }
  $width = 100/$links_per_row;
  $format = nl2br(get_option('links_format'));
  $num = 0;
  $directory .= '<table width="100%"><tr width="100%" valign="top">';
  
  foreach($get_links as $links){
    if($links_per_row<=$num){
    
      $num = 0;
      $directory.='<tr width="100%" valign="top">';
    }
    $directory .= '<td width="'.$width.'%">';
    $sql = "SELECT id,category FROM $wp_directory_table2 WHERE id=$links->categories";
    $get_cats = $wpdb->get_results($sql);
    $catname='';
    
    foreach($get_cats as $linkcategory){
      
      $catname .= '<a href="'.$my_blog.'index.php?category='.$linkcategory->id.'">'.$linkcategory->category."</a>";

    }

    $title ='';
    if($links->url){
      $title .= '<a href="';
    
      if(get_option('directory_redirect')){
      
        $title .= $my_blog.'index.php?addclick='.$links->id; 
        
      }else{
      
        $title .= $links->url;
      
      }
      
      if (get_option('links_nofollow')){
      
        $title .='" rel="nofollow';
      
      }
      if (get_option('links_newpage')){
      
        $title .='" target="_blank';
      
      }
     $title .= '" rel="bookmark" title="'. $links->title; 
     $title .='">'.$links->title;
     $title .='</a>';
     
   }else{
   
    $title .= $links->title;
   
   }
  
  if(get_option('create_link_thumbs') && $links->url){
    $img_url = AppSTW::queryRemoteThumbnail($links->url, $stwargs);
    if($img_url){
      $stwimg = '<a href="';
      
      if(get_option('directory_redirect')){
      
        $stwimg .= $my_blog.'index.php?addclick='.$links->id; 
        
      }else{
      
        $stwimg .= $links->url;
      
      }
      
      if (get_option('links_nofollow')){
      
        $stwimg .='" rel="nofollow';
      
      }
      if (get_option('links_newpage')){
      
        $stwimg .='" target="_blank';
      
      }
     $stwimg .= '" rel="bookmark" title="'. $links->title; 
     $stwimg .='"><img src="';
     $stwimg .= $img_url;
     $stwimg .='"></a>';
     
     }else{
     
      $stwimg = '';
     
     }
  }else{
  
    $stwimg = '';
    
  }
  

  $parsed = str_replace('%TITLE%', $title, $format);
  $parsed = str_replace('%DESCRIPTION%', nl2br($links->description), $parsed);
  $parsed = str_replace('%STWIMG%',$stwimg,$parsed);         
  $parsed = str_replace('%ADDRESS1%', $links->address1, $parsed);
  if($links->address2){
    $parsed = str_replace('%ADDRESS2%', $links->address2, $parsed);
  }else{
    $parsed = str_replace('%ADDRESS2%', '', $parsed);
  }
  $parsed = str_replace('%CITY%', $links->city, $parsed);
  $parsed = str_replace('%STATE%', $links->state, $parsed);
  $parsed = str_replace('%ZIPCODE%', $links->zipcode, $parsed);
  $parsed = str_replace('%COUNTRY%', $links->country, $parsed);
  $parsed = str_replace('%HIDEADDRESS%','', $parsed);
  
  if(!$links->address1){
  
    $parsed = str_replace('%HIDEADDRESS%','style="display:none;"', $parsed);
  
  }
  
  $parsed = str_replace('%EMAIL%', $links->email, $parsed);
  $parsed = str_replace('%PHONE%', $links->phone, $parsed);
  $parsed = str_replace('%FAX%', $links->fax, $parsed);
  $parsed = str_replace('%HITS%', $links->clicks, $parsed);
  $parsed = str_replace('%CATEGORY%', $catname, $parsed);          
   
  $directory .= stripslashes($parsed);
  ++$num;
  $directory .= '</td>';
  if($links_per_row<=$num){
    
      $directory.='</tr>';
    }
  }
}
if($links_per_row>$num){
    
      $directory.='</tr>';
  }
$directory .='</table>';

