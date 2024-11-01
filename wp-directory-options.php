<?php
/******************************************************************
*                                                                 *
*	WordPress 3.0.1 Plugin: Wp-Directory-List                         *
*	Copyright (c) 2010 Billie Kennedy Jr                            *
*                                                                 *
*	- http://themoneymakingwebsite.com                              *
*                                                                 *
*	File Information:                                               *
*	- Configure Directory Options                                   *
* - wp-content/plugins/wp-directory-list/wp-directory-options.php *
* - Version 1.4.0                                                 *
*                                                                 *
******************************************************************/


// If Form Is Submitted

if($_POST['Submit']) {
  
  $address = strip_tags(trim($_POST['address']));
  $url = strip_tags(trim($_POST['url']));
  $directory_redirect = strip_tags(trim($_POST['directory_redirect']));
  $display_address = strip_tags(trim($_POST['display_address']));
  $display_hours = strip_tags(trim($_POST['display_hours']));
  $display_category = strip_tags(trim($_POST['show_category']));
  $links_per_row = intval($_POST['links_per_row']);
  $links_per_page = intval($_POST['links_per_page']);
  $links_nofollow = strip_tags(trim($_POST['links_nofollow']));
  $links_newpage = strip_tags(trim($_POST['links_newpage']));
  $links_format = trim($_POST['links_format']);  
  $email = strip_tags(trim($_POST['email']));
  $requireuser = strip_tags(trim($_POST['requireuser']));
  $default_country = strip_tags(trim($_POST['directory_country']));
  $directory_captcha = strip_tags(trim($_POST['directory_captcha']));
  $recap_pub = strip_tags(trim($_POST['recap_pub']));
  $recap_priv = strip_tags(trim($_POST['recap_priv']));
  $google_api_key = strip_tags(trim($_POST['google_api_key']));
  $create_link_thumbs = strip_tags(trim($_POST['create_link_thumbs']));
  $stw_access = strip_tags(trim($_POST['stw_access']));
  $stw_secret = strip_tags(trim($_POST['stw_secret']));
  $submit_format = trim($_POST['submit_format']);
  $notify = strip_tags(trim($_POST['directory_notify']));
  
  $submitted_text = trim($_POST['submitted_text']);

  update_option('directory_address', $address); 
  update_option('directory_country',$default_country);
  update_option('directory_url', $url); 
  update_option('directory_redirect',$directory_redirect);
  update_option('directory_email',$email);

  update_option('directory_requireuser',$requireuser);
  update_option('directory_show_cat',$display_category);
  update_option('links_per_row',$links_per_row);
  update_option('links_per_page',$links_per_page);
  update_option('links_nofollow',$links_nofollow);
  update_option('links_newpage',$links_newpage);
  update_option('links_format',$links_format);
  update_option('directory_captcha',$directory_captcha);
  update_option('recap_pub',$recap_pub);
  update_option('recap_priv',$recap_priv);
  update_option('google_api_key',$google_api_key);
  update_option('stw_access',$stw_access);
  update_option('stw_secret',$stw_secret);
  update_option('create_link_thumbs',$create_link_thumbs);
  update_option('submit_format',$submit_format);
  update_option('directory_notify',$notify);
  
  //update_option('directory_google_maps', $google_maps);
  
  // update text fields
  update_option('submitted_text',$submitted_text);

}

// Options Form
?>
<form id="directory_options_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
<div class="wrap">
 <div id="icon-options-general" class="icon32"><br /></div>

<h2><?php _e('Directory Options', 'wp-directory-list'); ?></h2>
  <table class="form-table">
    
		 <tr valign="top">
		 <th scope="row"><?php _e('Submission Settings', 'wp-directory-list'); ?></th>
			<td >
        <fieldset><legend class="hidden">Default Submission settings</legend>
        <label for="directory_captcha">
            <input type="checkbox" name="directory_captcha" value="directory_captcha" <?php checked('directory_captcha',get_option('directory_captcha')); ?>><?php _e('Require Captcha Validation for User submitted links?', 'wp-directory-list'); ?>
          <br />
          </label>
        <label for="requireuser">
            <input type="checkbox" name="requireuser" value="requireuser" <?php checked('requireuser',get_option('directory_requireuser')); ?>><?php _e('Require User to be logged in to create a link.', 'wp-directory-list'); ?>
            <br />
          </label>
          <label for="url">
            <input type="checkbox" name="url" value="url" <?php checked('url',get_option('directory_url')); ?>><?php _e('Require URL', 'wp-directory-list'); ?>
            <br />
          </label>
          <label for="email">
            <input type="checkbox" name="email" value="email" <?php checked('email',get_option('directory_email')); ?>>  <?php _e('Require Contact Email', 'wp-directory-list'); ?>
            <br />
          </label>
          <label for="address">
            <input type="checkbox" name="address" value="address" <?php checked('address',get_option('directory_address')); ?>><?php _e('Require Full Address', 'wp-directory-list'); ?>
            <br />
          </label>
          <label for="directory_default_country">
<select name="directory_country" tabindex="1"> size="1">
	<?php
   include(dirname(__FILE__).'/wp-directory-countries-list.php');
   echo get_country_options($editcountry);
   
  ?>
	</select>
    <? _e('Default Country', 'wp-directory-list'); ?>
          </label>
          <br />
          <label for="submitted_text">
          <input type="text" name="submitted_text" value="<?php echo get_option('submitted_text'); ?>"><?php _e('Text to Display after user submits the form.', 'wp-directory-list'); ?>
          <br />
          </label>
          <label for="directory_notify">
            <input type="checkbox" name="directory_notify" value="directory_notify" <?php checked('directory_notify',get_option('directory_notify')); ?>>  <?php _e('Notify on New Link Submission?', 'wp-directory-list'); ?>
          <br />
          </label>
        </fieldset>
      </td>
     </tr> 
     
     <tr valign="top">
      <th scope="row"><?php _e('Link Display Settings', 'wp-directory-list'); ?></th>
			<td >
        <fieldset><legend class="hidden">Default Link Display settings</legend>
          <label for="directory_redirect">
            <input type="checkbox" name="directory_redirect" value="directory_redirect" <?php checked('directory_redirect',get_option('directory_redirect')); ?>><?php _e('Redirect URL for click counting', 'wp-directory-list'); ?>
            <br />
          </label>
          <label for="directory_show_clicks">
            <input type="checkbox" name="directory_show_clicks" value="directory_show_clicks" <?php checked('directory_show_clicks',get_option('directory_show_clicks')); ?>><?php _e('Show number of clicks below the description for each link.', 'wp-directory-list'); ?>
            <br />
          </label>
          <label for="show_category">
            <input type="checkbox" name="show_category" value="show_category" <?php checked('show_category',get_option('directory_show_cat')); ?>><?php _e('Show Category when shortcode category set to "all".', 'wp-directory-list'); ?>
          <br />
          </label>
          <label for="create_link_thumbs">
            <input type="checkbox" name="create_link_thumbs" value="create_link_thumbs" <?php checked('create_link_thumbs',get_option('create_link_thumbs')); 
            
          if( phpversion() < '5.0.0' || !extension_loaded('gd') || !function_exists('gd_info') ){
          echo " disabled";
          }?>><?php _e('Show Thumbnail Images of linked websites.  You must have a key for this function.  You can find one at .', 'wp-directory-list'); ?><a href="http://www.shrinktheweb.com">ShrinkTheWeb</a> <?php 
        if( phpversion() < '5.0.0' || !extension_loaded('gd') || !function_exists('gd_info') ){
          _e('Requires PHP 5.0.0 or greater and the GD Library to be installed.', 'wp-directory-list');
          }?>
          <br />
          </label>
          <label for="links_nofollow">
            <input type="checkbox" name="links_nofollow" value="links_nofollow" <?php checked('links_nofollow',get_option('links_nofollow')); ?>><?php _e('Set all outgoing Links to "NO FOLLOW".', 'wp-directory-list'); ?>
          <br />
          </label>
          <label for="links_newpage">
            <input type="checkbox" name="links_newpage" value="links_newpage" <?php checked('links_newpage',get_option('links_newpage')); ?>><?php _e('Open Link in a new page.', 'wp-directory-list'); ?>
          <br />
          </label>
          <label for="links_per_row">
          <input type="text" name="links_per_row" value="<?php echo get_option('links_per_row'); ?>"><?php _e('How many Links to display per table row.', 'wp-directory-list'); ?>
          <br />
          </label>
          <label for="links_per_page">
          <input type="text" name="links_per_page" value="<?php echo get_option('links_per_page'); ?>"><?php _e('How many Links to display per page.', 'wp-directory-list'); ?>
          <br />
          </label>
          <label for="links_format">
          <?php _e('Format of your Links:', 'wp-directory-list'); ?><br />
          <textarea name="links_format" rows="10" cols="60" value="" id="links_format"><?php echo stripslashes(get_option('links_format')); ?></textarea>
          <br />
          </label>
          <label for="submit_format">
          <?php _e('Format of your Submit Form:', 'wp-directory-list'); ?><br />
          <textarea name="submit_format" rows="10" cols="60" value="" id="submit_format"><?php echo stripslashes(get_option('submit_format')); ?></textarea>
          <br />
          </label>
        </fieldset>
      </td>
     </tr>

     <tr valign="top">
      <th scope="row"><?php _e('Keys', 'wp-directory-list'); ?></th>
			<td >
        <fieldset><legend class="hidden">Default Key settings</legend>
          <label for="recap_priv">
          <input type="text" name="recap_priv" value="<?php echo get_option('recap_priv'); ?>"><?php _e('Private key for reCAPTCHA provided by ', 'wp-directory-list'); ?><a href="http://recaptcha.net/api/getkey?app=php">reCAPTCHA</a>
          <br />
          </label>
          <label for="recap_pub">
          <input type="text" name="recap_pub" value="<?php echo get_option('recap_pub'); ?>"><?php _e('Public key for reCAPTCHA provided by ', 'wp-directory-list'); ?><a href="http://recaptcha.net/api/getkey?app=php">reCAPTCHA</a>
          <br />
          </label>
          <label for="stw_access">
          <input type="text" name="stw_access" value="<?php echo get_option('stw_access'); ?>" 
          <?php
          if( phpversion() < '5.0.0' || !extension_loaded('gd') || !function_exists('gd_info') ){
          echo 'disabled';
          }
          ?>><?php _e('Access key for Shrink the Web provided by ', 'wp-directory-list'); ?><a href="http://www.shrinktheweb.com">ShrinkTheWeb</a>
          <br />
          <?php
          if( phpversion() < '5.0.0' || !extension_loaded('gd') || !function_exists('gd_info') ){
            _e('Requires PHP 5.0.0 or greater and the GD Library to be installed.', 'wp-directory-list');
          }
          ?>
          </label>
          <label for="stw_secret">
          <input type="text" name="stw_secret" value="<?php echo get_option('stw_secret'); ?>" 
          <?php
          if( phpversion() < '5.0.0' || !extension_loaded('gd') || !function_exists('gd_info') ){
          echo 'disabled';
          }
          ?>><?php _e('Secret key for Shrink the Web', 'wp-directory-list'); ?>
          <br />
          </label>
        </fieldset>
      </td>
     </tr>

  </table>
<!-- Submit Button -->
	<p class="submit">
		<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'wp-directory-list'); ?>" />
	</p>
</div>
</form> 
<p class="footer_dl"><a href="http://themoneymakingwebsite.com/wp-directory-list/">Powered by  Wp-Directory-List</a></p>