<?php

/*
Plugin Name: Wp-Directory-List
Plugin URI: http://themoneymakingwebsite.com/wp-directory-list/
Description: Add a directory listing to your blog.
Version: 1.7.0
Author: Billie Kennedy Jr
Author URI: http://themoneymakingwebsite.com
*/

/*  Copyright 2010  Billie Kennedy Jr  (email : support@themoneymakingwebsite.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once(ABSPATH .'wp-config.php');
require_once(ABSPATH .'wp-includes/wp-db.php');
require_once(ABSPATH .'wp-includes/pluggable.php');

global $wp_directory_list_db_version;

// declare the current version
$wp_directory_list_db_version = "1.7.0";

$wp_directory_table = $wpdb->prefix .'directory_links';
$wp_directory_table2 = $wpdb->prefix .'directory_link_cats';
global $linkpage;
global $p_atts;

function wp_directory_url_click() {
    global $wpdb;
    global $wp_directory_table;

  if ($_GET['addclick'] != "") {
    
    $clickid = $_GET['addclick'];
    
    $redirect = $wpdb->get_row("SELECT url FROM $wp_directory_table WHERE id = '$clickid'", OBJECT);
    $update = "UPDATE ". $wp_directory_table ." SET clicks=clicks+1 WHERE id='$clickid'";
    $results = $wpdb->query( $update );
    header("Location: $redirect->url");
    
    exit;
  }
}

function dir_func($atts, $content=null) {
  global $wpdb;
  global $wp_directory_table;
  
	extract(shortcode_atts(array(
		'category' => 'category',
		'orderby' => 'orderby',
		'order' => 'order',
		'limit' => 'limit',
		'premium' => 'premium',
		'where' => 'where'
	), $atts));
  
  // place code here for premium/paid links.  They are treated differently.

  $get_category = intval($_GET['category']);
  $get_page = intval($_GET['dlpage']);
  
  // Lets build an SQL statement now.  
  if($_GET['category'] != "") {
    
    $sql = "SELECT * FROM $wp_directory_table WHERE status=1 and categories=".$get_category;
    
  }elseif($atts['category']=='all'){
    
    
    $sql = "SELECT * FROM $wp_directory_table WHERE status=1";
       
  }else{
  
    $sql = "SELECT * FROM $wp_directory_table WHERE status=1 and categories=".$atts['category'];
  
  }  
  
  if(isset($atts['orderby'])){
    
    $sql .= " ORDER BY ".$atts['orderby'];
      
  }
  
  if(isset($atts['order']) && isset($atts['orderby'])){
    
    $sql .= ' '.$atts['order'];
      
  }
  
  $numlinks = get_option('links_per_page');
  
  //if($numlinks=<0) echo 'here';
  
  if($get_page<1)$get_page=1;
  $limit = " Limit ".($get_page-1)*get_option('links_per_page').", ".get_option('links_per_page');
  
  $sql .= $limit;
  
  // SQL statement is built.  Let's get the data
  $get_links = $wpdb->get_results($sql);
  
  //Now that we have everything we need, lets build the output.  Grab the output file.
  
  include(dirname(__FILE__).'/wp-directory-link-html.php');

  $content .= $directory ;

	return $content;
}

add_shortcode('directory', 'dir_func');

function dir_cats($atts, $content=null) {
  global $wpdb;
  global $add_link,$text;
  global $wp_directory_table;
  
  extract(shortcode_atts(array(
		'rows' => 'rows',
		'sub' => 'sub',
	), $atts));
	
	$sql = "SELECT * FROM ".$wp_directory_table." WHERE 
  MATCH(title,description,url,address1,address2,city,state,country,zipcode,phone,fax,email,tags) 
  AGAINST ('".$searchterm."' WITH QUERY EXPANSION)";

  return $content;
  
}

add_shortcode('dir_cats', 'dir_cats');

function signup_func($atts, $content=null) {
  global $wpdb;
  global $add_link,$text;
  global $wp_directory_table2;
  
  if(!$add_link){
  include(dirname(__FILE__).'/wp-directory-form-html.php');
	}else{
  ?>
  <div class="wrap">
  <?php echo $text;
  ?>
  </div>
  <?php
  }

}

add_shortcode('directory_signup', 'signup_func');
add_action('init', 'wp_directory_url_click');

if($_POST['Submit'] && 'link_form'==$_POST['link_form']){
  
  include(dirname(__FILE__).'/wp-directory-process-form.php');


}

include(dirname(__FILE__).'/wp-directory-list-widget.php');

function wp_directory_list_install() {
   
  global $wpdb;
  $wp_directory_list_db_version = "1.7.0";
  
  $wp_directory_table = $wpdb->prefix .'directory_links';
  $wp_directory_table2 = $wpdb->prefix .'directory_link_cats';
  
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  
  include(dirname(__FILE__).'/wp-directory-install.php');
   
  $installed_ver = get_option( "wp_directory_list_db_version" );
    
  if(!$installed_ver){
  
    // set defaults here
    add_option('links_per_page','25');
    add_option('submitted_text','Your Link was Submitted Successfully.  It may take up to 48 hours before it appears in the directory.');
    add_option('links_per_row','1');
    add_option('links_format','<div class="post"><h2>%TITLE%</h2><div class="postinfo"><br class="clear" /></div><div class="entry">%DESCRIPTION%</div>
      <div class="entry" %HIDEADDRESS%>%ADDRESS1%
      %ADDRESS2%
      %CITY%, %STATE%  %ZIPCODE%  %COUNTRY%
      Ph:  %PHONE%
      F:  %FAX%</div><div class="entry"><p class="postinfo">Clicks: %HITS%</p></div><div class="entry"><p class="postinfo">Category:  %CATEGORY%</p></div></div>');
    
    add_option('submit_format','Please fill out completely.  All fields are required.  Once submitted, your link will be evaluated.  It may take up to 48 hours for your link to be included.
      <table><tr><td>Title:  </td><td>%TITLE%</td></tr>
      <tr><td colspan="2">example: Nifty blogging Plugin</td></tr><tr><td>Web Address: </td><td>%URL%</td></tr><tr><td colspan="2">example:  http://www.themoneymakingwebsite.com</td></tr><tr><td colspan="2">Description:</td></tr><tr><td>Category:  </td><td>%CATEGORY%</td></tr><tr><td colspan="2">%DESCRIPTION%</td></tr><tr><td>Address:  </td><td>%ADDRESS1%</td></tr><tr><td>Address2:  </td><td>%ADDRESS2%</td></tr><tr><td>City: </td><td>%CITY%</td></tr><tr><td>State:  </td><td>%STATE%</td></tr><tr><td>Zipcode:  </td><td>%ZIPCODE%</td></tr><tr><td>Country:  </td><td>%COUNTRY%</td></tr><tr><td>Phone:  </td><td>%PHONE%</td></tr><tr><td>Fax:  </td><td>%FAX%</td></tr><tr><td>Email:  </td><td>%EMAIL%</td></tr><tr><td colspan="2">%RECAPTCHA%</td></tr>
      </table>');    
    
  }
  
  if( $installed_ver != $wp_directory_list_db_version ) {
  
    switch($installed_ver){
     
      case "1.1.0":
        

        $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD date_added INT NOT NULL");
        
        add_option('links_per_page','25');
        add_option('submitted_text','Your Link was Submitted Successfully.  It may take up to 48 hours before it appears in the directory.');
        add_option('links_per_row','1');
        add_option('links_format','<div class="post"><h2>%TITLE%</h2><div class="postinfo"><br class="clear" /></div><div class="entry">%DESCRIPTION%</div>
<div class="entry" %HIDEADDRESS%>%ADDRESS1%
%ADDRESS2%
%CITY%, %STATE%  %ZIPCODE%  %COUNTRY%
Ph:  %PHONE%
F:  %FAX%</div><div class="entry"><p class="postinfo">Clicks: %HITS%</p></div><div class="entry"><p class="postinfo">Category:  %CATEGORY%</p></div></div>');
        add_option('submit_format','Please fill out completely.  All fields are required.  Once submitted, your link will be evaluated.  It may take up to 48 hours for your link to be included.
<table><tr><td>Title:  </td><td>%TITLE%</td></tr>
<tr><td colspan="2">example: Nifty blogging Plugin</td></tr><tr><td>Web Address: </td><td>%URL%</td></tr><tr><td colspan="2">example:  http://www.themoneymakingwebsite.com</td></tr><tr><td colspan="2">Description:</td></tr><tr><td colspan="2">%DESCRIPTION%</td></tr><tr><td>Category:  </td><td>%CATEGORY%</td></tr><tr><td>Address:  </td><td>%ADDRESS1%</td></tr><tr><td>Address2:  </td><td>%ADDRESS2%</td></tr><tr><td>City: </td><td>%CITY%</td></tr><tr><td>State:  </td><td>%STATE%</td></tr><tr><td>Zipcode:  </td><td>%ZIPCODE%</td></tr><tr><td>Country:  </td><td>%COUNTRY%</td></tr><tr><td>Phone:  </td><td>%PHONE%</td></tr><tr><td>Fax:  </td><td>%FAX%</td></tr><tr><td>Email:  </td><td>%EMAIL%</td></tr><tr><td colspan="2">%RECAPTCHA%</td></tr>
</table>');
$wpdb->query("ALTER TABLE ".$wp_directory_table." ADD tags TEXT;");        
      $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD FULLTEXT linktext (title ,description ,url ,address1 ,address2 ,city ,state ,country ,zipcode ,phone ,fax ,email ,tags); ");

        
        break;
        
      case "1.2.0":
        
        add_option('links_per_page','25');
        add_option('links_per_row','1');
        add_option('submitted_text','Your Link was Submitted Successfully.  It may take up to 48 hours before it appears in the directory.');
        add_option('links_format','<div class="post"><h2>%TITLE%</h2><div class="postinfo"><br class="clear" /></div><div class="entry">%DESCRIPTION%</div>
<div class="entry" %HIDEADDRESS%>%ADDRESS1%
%ADDRESS2%
%CITY%, %STATE%  %ZIPCODE%  %COUNTRY%
Ph:  %PHONE%
F:  %FAX%</div><div class="entry" %HIDE%><p class="postinfo">Clicks: %HITS%</p></div><div class="entry"><p class="postinfo">Category:  %CATEGORY%</p></div></div>');
        add_option('submit_format','Please fill out completely.  All fields are required.  Once submitted, your link will be evaluated.  It may take up to 48 hours for your link to be included.
<table><tr><td>Title:  </td><td>%TITLE%</td></tr>
<tr><td colspan="2">example: Nifty blogging Plugin</td></tr><tr><td>Web Address: </td><td>%URL%</td></tr><tr><td colspan="2">example:  http://www.themoneymakingwebsite.com</td></tr><tr><td colspan="2">Description:</td></tr><tr><td colspan="2">%DESCRIPTION%</td></tr><tr><td>Category:  </td><td>%CATEGORY%</td></tr><tr><td>Address:  </td><td>%ADDRESS1%</td></tr><tr><td>Address2:  </td><td>%ADDRESS2%</td></tr><tr><td>City: </td><td>%CITY%</td></tr><tr><td>State:  </td><td>%STATE%</td></tr><tr><td>Zipcode:  </td><td>%ZIPCODE%</td></tr><tr><td>Country:  </td><td>%COUNTRY%</td></tr><tr><td>Phone:  </td><td>%PHONE%</td></tr><tr><td>Fax:  </td><td>%FAX%</td></tr><tr><td>Email:  </td><td>%EMAIL%</td></tr><tr><td colspan="2">%RECAPTCHA%</td></tr>
</table>');
        $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD date_added INT NOT NULL");
        
$wpdb->query("ALTER TABLE ".$wp_directory_table." ADD tags TEXT;");        
      $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD FULLTEXT linktext (title ,description ,url ,address1 ,address2 ,city ,state ,country ,zipcode ,phone ,fax ,email ,tags); ");

        
        break;
      
      case "1.2.1":
      case "1.3.0":
      case "1.3.1":
      case "1.3.2":
      case "1.3.3":
        
        add_option('links_per_page','25');
        update_option('directory_email_text','');
        update_option('location_text','');
        update_option('email_text','');
        update_option('phone_text','');
        update_option('fax_text','');
        update_option('clicks_text','');
        update_option('submitted_text','');
        update_option('directory_cat_text','');
        add_option('links_format','<div class="post"><h2>%TITLE%</h2><div class="postinfo"><br class="clear" /></div><div class="entry">%DESCRIPTION%</div>
<div class="entry" %HIDEADDRESS%>%ADDRESS1%
%ADDRESS2%
%CITY%, %STATE%  %ZIPCODE%  %COUNTRY%
Ph:  %PHONE%
F:  %FAX%</div><div class="entry"><p class="postinfo">Clicks: %HITS%</p></div><div class="entry"><p class="postinfo">Category:  %CATEGORY%</p></div></div>');
        add_option('submit_format','Please fill out completely.  All fields are required.  Once submitted, your link will be evaluated.  It may take up to 48 hours for your link to be included.
<table><tr><td>Title:  </td><td>%TITLE%</td></tr>
<tr><td colspan="2">example: Nifty blogging Plugin</td></tr><tr><td>Web Address: </td><td>%URL%</td></tr><tr><td colspan="2">example:  http://www.themoneymakingwebsite.com</td></tr><tr><td colspan="2">Description:</td></tr><tr><td colspan="2">%DESCRIPTION%</td></tr><tr><td>Category:  </td><td>%CATEGORY%</td></tr><tr><td>Address:  </td><td>%ADDRESS1%</td></tr><tr><td>Address2:  </td><td>%ADDRESS2%</td></tr><tr><td>City: </td><td>%CITY%</td></tr><tr><td>State:  </td><td>%STATE%</td></tr><tr><td>Zipcode:  </td><td>%ZIPCODE%</td></tr><tr><td>Country:  </td><td>%COUNTRY%</td></tr><tr><td>Phone:  </td><td>%PHONE%</td></tr><tr><td>Fax:  </td><td>%FAX%</td></tr><tr><td>Email:  </td><td>%EMAIL%</td></tr><tr><td colspan="2">%RECAPTCHA%</td></tr>
</table>');
      $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD date_added INT NOT NULL");
      $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD tags TEXT;");        
      $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD FULLTEXT linktext (title ,description ,url ,address1 ,address2 ,city ,state ,country ,zipcode ,phone ,fax ,email ,tags); ");


      	break;
      	
      case "1.4.0":
        
        add_option('links_per_page','25');
      $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD date_added INT NOT NULL");
      $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD tags TEXT;");        
      $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD FULLTEXT linktext (title ,description ,url ,address1 ,address2 ,city ,state ,country ,zipcode ,phone ,fax ,email ,tags); ");

      	break;  
      
      case "1.5.0":
      case "1.5.1":
      case "1.5.2":
      $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD date_added INT NOT NULL");
      $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD tags TEXT;");        
      $wpdb->query("ALTER TABLE ".$wp_directory_table." ADD FULLTEXT linktext (title ,description ,url ,address1 ,address2 ,city ,state ,country ,zipcode ,phone ,fax ,email ,tags); ");
    
        break;
        
      default:
      
      break;
     
     }
      update_option( "wp_directory_list_db_version", $wp_directory_list_db_version );
  }


}

add_action('admin_menu', 'directory_menu');


function directory_menu() {
  global $wpdb;
  global $wp_directory_table;
  
  $sql = "SELECT title FROM ".$wp_directory_table." WHERE status=-1";
  $results = $wpdb->query($sql);
  
  $pending_count = print_r($results,true);
  $pending_menu = __('Pending Links','wp-directory-links');
  
  if(0<$pending_count){
  
    $pending_menu .= "<span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . "</span></span>";
  
  }
   
  add_menu_page( 'Directory List', 'Directory List', 8, __FILE__,'directory_plugin');
  
  add_submenu_page(__FILE__,__('Manage Directory','wp-directory-list'),__('Manage Directory','wp-directory-list'), 8,'wp-directory-list/wp-directory-manage.php');
  add_submenu_page(__FILE__,$pending_menu,$pending_menu, 8,'wp-directory-list/wp-directory-pending-links.php');
  add_submenu_page(__FILE__,__('Categories','wp-directory-list'),__('Categories','wp-directory-list'), 8,'wp-directory-list/wp-directory-categories.php');
  add_submenu_page(__FILE__,__('Options','wp-directory-list'),__('Options','wp-directory-list'), 8,'wp-directory-list/wp-directory-options.php');

	
}

// This function is for normal links.
function page_func( $atts = array(), $content = NULL ) {
		if ( NULL === $content ) return '';
  global $wpdb;
  global $wp_directory_table;
  global $p_atts;
  
  $get_category = intval($_GET['category']);
  $get_page = intval($_GET['dlpage']);
   
  // Lets build an SQL statement now.
  $sql = "SELECT * FROM $wp_directory_table WHERE status=1";
  
  if($p_atts['category']!='all'){
    // Lets build an SQL statement now.  
    if($_GET['category'] != "") {
      
      $sql .= " and categories=".$get_category;
      
    }elseif(isset($atts['category'])){
    
      $sql .= " and categories=".$p_atts['category'];
    
    }  
   } 
  $results = $wpdb->query($sql);
  $count = print_r($results,true);
  
  $per_page = get_option("links_per_page");
  $pages .='<div class="Browse">';
  $num_pages = ceil($count/$per_page);
  $perma = get_permalink();
  for($i=0;$i<$num_pages;++$i){
    if(get_option('permalink_structure')){
      $pages .='<a href="'.$perma.'?dlpage='.($i+1).'">';
    }else{
      $pages .='<a href="'.$perma.'&dlpage='.($i+1).'">';
    }
    
    if(($i+1)==$get_page){
    
      $pages .='<strong>'. ($i+1).'</strong></a>  ';
    
    }else{
      $pages .= ($i+1).'</a>  ';
    }
  }
  $pages .='</div>';
  $content .= $pages;
  
		return do_shortcode( $content );
	}
add_shortcode('pages', 'page_func');

function directory_plugin(){

?>
<div class="wrap">
<h2><?php _e('Directory List Plugin', 'wp-directory-list'); ?></h2>

<?php include(dirname(__FILE__).'/wp-directory-html.php');
?>

</div>
<?php

}

register_activation_hook(__FILE__,'wp_directory_list_install');

function by_line ($content = '') {
    global $wp_directory_list_db_version;
    
      $content .= '<div class="clear">
				<br class="clear" />
				</div><div class="footer_dl" id="footer_dl">
		Powered by <a href="http://themoneymakingwebsite.com/wp-directory-list/" title="Wp Directory List - Business Link Directory Plugin for Wordpress">Wp-Directory-List</a>
      Version:  '.$wp_directory_list_db_version.'</div>';

     return $content;
}
add_filter('the_content', 'by_line');

?>
