<?php

/***********************************************************************
*                                                                      *
*	WordPress 3.0.1 Plugin: Wp-Directory-List                              *
*	Copyright (c) 2009 Billie Kennedy Jr                                 *
*                                                                      *
*	- http://themoneymakingwebsite.com                                   *
*                                                                      *
*	File Information:                                                    *
*	- Configure Directory Options                                        *
* - wp-content/plugins/wp-directory-list/wp-directory-process-form.php *
* - Version 1.2.0                                                      *
*                                                                      *
***********************************************************************/
require_once('recaptchalib.php');
require_once(ABSPATH .'wp-config.php');
require_once(ABSPATH .'wp-includes/wp-db.php');
require_once(ABSPATH .'wp-includes/pluggable.php');

$captcha = get_option('directory_captcha');
$secret = get_option('stw_secret');
$privatekey = get_option('recap_priv');
$publickey = get_option('recap_pub');

if($captcha!='' && $publickey!='' && $privatekey!=''){

  $resp = recaptcha_check_answer ($privatekey,
                                  $_SERVER["REMOTE_ADDR"],
                                  $_POST["recaptcha_challenge_field"],
                                  $_POST["recaptcha_response_field"]);
  
  if (!$resp->is_valid) {
    die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
         "(reCAPTCHA said: " . $resp->error . ")");
  }
}

  $wp_directory_table = $wpdb->prefix .'directory_links';

  $linktitle = strip_tags(trim($_POST['dir_link_name']));
  $linkdesc = strip_tags(trim($_POST['link_description']));
  $link_url = strip_tags(trim($_POST['dir_link_url']));
  $linkcats = intval($_POST['category']);
  
  // got to have something in it.
  if($linkcats<1){
  
    $linkcats = -1;
    
  }
  
  $linkid = intval($_POST['linkid']);
  $linkaddress1 = strip_tags(trim($_POST['directory_address1']));
  $linkaddress2 = strip_tags(trim($_POST['directory_address2']));
  $linkcity = strip_tags(trim($_POST['directory_city']));
  $linkcountry = strip_tags(trim($_POST['directory_country']));
  $linkstate = strip_tags(trim($_POST['directory_state']));
  $linkzipcode = strip_tags(trim($_POST['directory_zipcode']));
  $linkemail = strip_tags(trim($_POST['directory_email']));
  $linkphone = strip_tags(trim($_POST['directory_phone']));
  $linkfax = strip_tags(trim($_POST['directory_fax']));
  $linkuserid = intval($_POST['dir_user_tag']);
  

  if( FALSE ===strpos($link_url,'http://') ){
    $link_url = "http://".$link_url;
  }
  
  $missing_parameter = false;
  
  if(get_option('directory_url') && !$link_url){
    
    $missing_parameter = true;
    
  
  }
  
  if(!$linktitle || !$linkdesc || !$linkcats){
    
    $missing_parameter = true;
  
  }
	
	
  if('Submit'==$_POST['Submit'] && false==$missing_parameter){
    
      $submit_time = time();
      //$my_user = get_currentuserinfo();

      $submitted_user = $linkuserid;
      // Insert Title
      $sql = "INSERT INTO $wp_directory_table 
              (title,
              description,
              url,
              categories,
              address1,
              address2,
              city,
              zipcode,
              country,
              state,
              email,
              phone,
              fax,
              status,
              user,
              date_added) 
              VALUES ( '$linktitle',
              '$linkdesc',
              '$link_url',
              '$linkcats',
              '$linkaddress1',
              '$linkaddress2',
              '$linkcity',
              '$linkzipcode',
              '$linkcountry',
              '$linkstate',
              '$linkemail',
              '$linkphone',
              '$linkfax',
              -1,
              $linkuserid,
              $submit_time        
              )";
             
      $sql = $wpdb->prepare($sql);
            
    	$add_link = $wpdb->query($sql);
    	
      if(!$add_link) {
      
    		$text .= '<p style="color: red;">'.sprintf(__('Error In Adding Link \'%s\'.', 'wp-directory-list'), stripslashes($linktitle)).'</p>';
        
      }else{
      
        $text .= '<p style="color: green;">'.get_option('submitted_text').'</p>';
        
        if(get_option('directory_notify')){
       
          $headers ='';
          $attachments ='';
          $subject = __("New Directory Link Submitted.");
          $to = get_bloginfo('admin_email');
          $message = __("You have recieved a new link submission from:  ").$linktitle.__(" at ").get_bloginfo('name');      
          wp_mail( $to, $subject, $message.$redirect_url, $headers, $attachments );
          
        }
      
      }
    
  }

?>
