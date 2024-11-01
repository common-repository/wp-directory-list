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
* - wp-content/plugins/wp-directory-list/wp-directory-html.php       *
* - Version 1.0.3                                                    *
*                                                                    *
*********************************************************************/
$wp_directory_list_db_version = get_option( "wp_directory_list_db_version" );;
?>
<div class="wrap">
The Wp-Directory-List plugin was created to add categorized lists of URLs or businesses.  All you need to do is install it and start adding your URLs.
<p />
Currently, the Directory List does not support Paypal for paid inclusion.  But it is scheduled to be included as a necessary feature of the Directory.
<p />
Configure the plugin to use either URLs or Addresses.  Or even both.  Add a page for the Form so that users can add their link.  Add a page for each Category.
<p />
<h3>Future plans:</h3><ul>
<li>Paypal inclusion</li>
<li>Ranking</li>
<li>Link Upgrading</li>
<li>Full Page Descriptions</li>
<li>Custom CSS</li>
</ul>


</div>
<div class="footer_dl">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="2426749">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

</div>
		<p class="footer_dl"><?php printf(__('&copy; Copyright 2010 <a href="http://themoneymakingwebsite.com/" title="Directory List Wordpress Plugin">The Money Making Website</a> | <a href="http://wordpress.org/extend/plugins/http://wordpress.org/extend/plugins/wp-directory-list/">Wp-Directory-List</a> | Version %s', 'wp-directory-list'), $wp_directory_list_db_version); ?>
    
</p>
