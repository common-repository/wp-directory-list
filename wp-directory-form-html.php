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
* - wp-content/plugins/wp-directory-list/wp-directory-form-hmtl.php  *
* - Version 1.2.0                                                    *
*                                                                    *
*********************************************************************/

global $user_ID;

$login_allowed = $user_ID;

$captcha = get_option('directory_captcha');
$privatekey = get_option('recap_priv');
$publickey = get_option('recap_pub');

if ( $login_allowed || !get_option('directory_requireuser')) {
include(dirname(__FILE__).'/wp-directory-countries-list.php');

  $format = stripslashes(nl2br(get_option('submit_format')));

  $parsed = str_replace('%TITLE%', '<input type="text" name="dir_link_name" size="30" value="" id="dir_link_name" />', $format);
  $parsed = str_replace('%DESCRIPTION%', '<textarea name="link_description" rows="10" cols="40" tabindex="1" value="" id="link_description"></textarea>', $parsed);
  $parsed = str_replace('%URL%','<input type="text" name="dir_link_url" size="30" tabindex="1" value="http://" id="dir_link_url" />',$parsed);       
  $parsed = str_replace('%ADDRESS1%', '<input type="text" name="directory_address1" size="30" tabindex="1" value="" id="directory_address1" />', $parsed);
  $parsed = str_replace('%ADDRESS2%', '<input type="text" name="directory_address2" size="30" tabindex="1" value="" id="directory_address2" />', $parsed);
  $parsed = str_replace('%CITY%', '<input type="text" name="directory_city" size="30" tabindex="3" value="" id="directory_city" />', $parsed);
  $parsed = str_replace('%STATE%', '<input type="text" name="directory_state" size="30" tabindex="4" value="" id="directory_state" />', $parsed);
  $parsed = str_replace('%ZIPCODE%', '<input type="text" name="directory_zipcode" size="30" tabindex="5" value="" id="directory_zipcode" />', $parsed);
  $country = '<select name="directory_country" tabindex="6" size="1">';
  $country .= get_country_options();
   

	$country .= '</select>';
  
  $parsed = str_replace('%COUNTRY%', $country, $parsed);
  $parsed = str_replace('%EMAIL%', '<input type="text" name="directory_email" size="30" tabindex="1" value="" id="directory_email" />', $parsed);
  $parsed = str_replace('%PHONE%', '<input type="text" name="directory_phone" size="30" tabindex="1" value="" id="directory_phone" />', $parsed);
  $parsed = str_replace('%FAX%', '<input type="text" name="directory_fax" size="30" tabindex="1" value="" id="directory_fax" />', $parsed);
  if($secret!=''){

  }
  
if($captcha!='' && $publickey!='' && $privatekey!=''){

 require_once('recaptchalib.php');
 $parsed = str_replace('%RECAPTCHA%', recaptcha_get_html($publickey,$error), $parsed);
}
  
  $catname = '<select name="category" tabindex="1" > size="5">
  
    				<option value="0">'; 
  $catname .= __('None', 'wp-directory-list'); 
  $catname .= '</option>';  	

            $sql = "SELECT id,category,cat_desc,parent,active FROM $wp_directory_table2";
      	$get_cats = $wpdb->get_results($sql);
          
  			if($get_cats) {
  
          foreach($get_cats as $category){	
          
            $catname .= '<option value="'.$category->id.'">'.$category->category.'</option>'; 
          }
        }	
  			$catname .= '</select>';
  
  $parsed = str_replace('%CATEGORY%', $catname, $parsed);   
       
   
  $parsed = stripslashes($parsed);
  
}else{
  
  _e('Sorry but you must be logged in to submit a link.', 'wp-directory-list'); 
  
}
?>
<div class="wrap">
<div id="icon-link-manager" class="icon32"><br /></div>
<form id="directory_user_link_add_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
<?php
echo $parsed;

?>
  <!-- Submit Button -->
  	<p class="submit">
      <input type="hidden" name="link_form" value="link_form" />
      <input type="hidden" name="dir_user_tag" value="" id="<?php echo $userdata->ID;?>" />
  		<input type="submit" name="Submit" class="button" value="<?php _e('Submit', 'wp-directory-list'); ?>" />

  	</p>
  	
</form>

				</div>
		<p class="footer_dl"><a href="http://themoneymakingwebsite.com/wp-directory-list/">Powered by  Wp-Directory-List</a></p>