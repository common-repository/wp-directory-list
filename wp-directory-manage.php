<?php
/*********************************************************************
*                                                                    *
*	WordPress 2.7 Plugin: Wp-Directory-List                            *
*	Copyright (c) 2009 Billie Kennedy Jr                               *
*                                                                    *
*	- http://themoneymakingwebsite.com                                 *
*                                                                    *
*	File Information:                                                  *
*	- Directory Manager                                                *
* - wp-content/plugins/wp-directory-list/wp-directory-manage.php     *
* - Version 1.2.0                                                    *
*                                                                    *
*********************************************************************/

$wp_directory_table = $wpdb->prefix .'directory_links';
$wp_directory_table2 = $wpdb->prefix .'directory_link_cats';

require_once(ABSPATH .'wp-config.php');
require_once(ABSPATH .'wp-includes/wp-db.php');
require_once(ABSPATH .'wp-includes/pluggable.php');

global $user_ID;

$get_page = intval($_GET['page']);
$get_category = intval($_GET['category']);

// If Form Is Submitted

if($_POST['Submit']) {
  
  global $current_user;
  get_currentuserinfo();

  $submitted_user = $current_user->ID;
  
  $linktitle = strip_tags(trim($_POST['link_title']));
  $linkdesc = strip_tags(trim($_POST['link_description']));
  $link_url = strip_tags(trim($_POST['dir_link_url']));
  $linkcats = intval($_POST['category']);
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
  
  
  if($_POST['Submit']=='Add Link'){
    $time = time();
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
            '$submitted_user',
            '$time' 
            )";
            
    $sql = $wpdb->prepare($sql);
  	$add_link = $wpdb->query($sql);
  	
    if(!$add_link) {
    
  		$text .= '<p style="color: red;">'.sprintf(__('Error In Adding Link \'%s\'.', 'wp-directory-list'), stripslashes($linktitle)).'</p>';
      
    }else{
    
      $text = '<p style="color: green;">'.sprintf(__('Link \'%s\' Added Successfully.', 'wp-directory-list'), stripslashes($linktitle)).'</a></p>';
    
    }
    
  }elseif($_POST['Submit']=='Update'){
  
    
    // Update Title
    $sql = "UPDATE $wp_directory_table SET 
    title='$linktitle',
    description='$linkdesc',
    url='$link_url',
    categories='$linkcats', 
    address1='$linkaddress1',
    address2='$linkaddress2',
    city='$linkcity',
    zipcode='$linkzipcode',
    country='$linkcountry',
    state='$linkstate',
    email='$linkemail',
    phone='$linkphone',
    fax='$linkfax' 
    WHERE id=$linkid";
    
    $sql = $wpdb->prepare($sql);
  	$add_link = $wpdb->query($sql);
  
  	
    if(!$add_link) {
    
  		$text .= '<p style="color: red;">'.sprintf(__('Error In Updating Link \'%s\'.', 'wp-directory-list'), stripslashes($linktitle)).'</p>';
      
    }else{
    
      $text = '<p style="color: green;">'.sprintf(__('Link \'%s\' Updated Successfully.', 'wp-directory-list'), stripslashes($linktitle)).'</a></p>';
    
    }
  
  }

}

if ( isset($_POST['action']) && ( -1 != $_POST['action'] || -1 != $_POST['action2'] ) ) {
	$doaction = ( -1 != $_POST['action'] ) ? $_POST['action'] : $_POST['action2'];

	switch ( $doaction ) {
  
  case 'edit':
    
    $cids = $_POST['dir_action'];
  
    $id = intval($cids[0]);
    
    $sql = "SELECT id,title,description,url,categories,address1,address2,city,country,state,zipcode,phone,fax,email,user,date_added FROM $wp_directory_table WHERE id=$id";
    $get_link = $wpdb->get_results($sql);
    
    foreach($get_link as $links){
      $linktitle = $links->title;
      $linkdesc = $links->description;
      $linkurl = $links->url;
      $linkcat = $links->categories;
      $editlinkid = $links->id;
      $address1 = $links->address1;
      $address2 = $links->address2;
      $city = $links->city;
      $editcountry = $links->country;
      $state = $links->state;
      $zipcode = $links->zipcode;
      $phone = $links->phone;
      $fax = $links->fax;
      $email = $links->email;
      
      
    }
    
  break;
  
  case 'delete':
    
   $lids = $_POST['dir_action'];
  
    foreach($lids as $lid){
 
      $sql = "DELETE FROM $wp_directory_table WHERE id=$lid";
      $get_links = $wpdb->get_results($sql);
      
    }  
    _e('<p style="color: green;">Link Deleted Successfully.</a></p>', 'wp-directory-list');
    
  break;
  
  case 'change':
    
    $lids = $_POST['dir_action'];
    
    if($lids) {

      foreach($lids as $lid){
        
        $sql = "SELECT status FROM $wp_directory_table WHERE id=".intval($lid);
        $get_links = $wpdb->get_results($sql);  
        
        foreach($get_links as $links){

          if($links->status==1){
            
            $sql="UPDATE $wp_directory_table SET status=0 WHERE id=".intval($lid);
            
          }else{
            
            $sql="UPDATE $wp_directory_table SET status=1 WHERE id=".intval($lid);
                        
          }
          
          $results = $wpdb->get_results($sql);
  
          unset($links);
        }
        
        unset($lid);
      }
    }
    
  break;
  }

}

if(!empty($text)) { 
  
  echo '<!-- Last Action --><div id="message">'.stripslashes($text).'</div>'; 
  
}

?>


<form id="directory_category_list_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 

  <div class="wrap">
  
    <h2><?php _e('Manage Directory', 'wp-directory-list'); ?></h2>
    <div class="alignleft actions">
      <select name="action">
      <option value="-1" selected="selected"><?php _e('Actions', 'wp-directory-list'); ?></option>
      <option value="edit"><?php _e('Edit', 'wp-directory-list'); ?></option>
      <option value="delete"><?php _e('Delete', 'wp-directory-list'); ?></option>
      <option value="change"><?php _e('Change Status', 'wp-directory-list'); ?></option>
      </select>
      <input type="submit" value="<?php _e('Apply', 'wp-directory-list'); ?>" name="doaction" id="doaction" class="button-secondary action" />
      <br class="clear" />
    </div>
    <div class="alignright actions">
      <input type="text" name="dir_search" size="30" />
      <input type="submit" value="<?php _e('Search', 'wp-directory-list'); ?>" name="dosearch" id="dosearch" class="button-secondary action" />
      <br class="clear" />
    </div>
    <div class="alignright actions">
      <select name="sort"> 
        <option value="0"><?php _e('None', 'wp-directory-list'); ?></option>
      <?php
            $sql = "SELECT id,category FROM $wp_directory_table2";
      	$get_cats = $wpdb->get_results($sql);
          
  			if($get_cats) {
  
        foreach($get_cats as $category){	
          
          if($category->id == $linkcat){
            echo "<option value=\"$category->id\" selected=\"selected\">$category->category</option> "; 
          }else{
            echo "<option value=\"$category->id\">$category->category</option> "; 
          }
        }
        }?>		
      </select>
      <input type="submit" value="<?php _e('Sort', 'wp-directory-list'); ?>" name="dosort" id="dosort" class="button-secondary action" />
      <br class="clear" />
    </div>
  </div>
  <table class="widefat post fixed" cellspacing="0">
    <thead>
    	<tr>
      	<th scope="col" id="dir" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
      	<th scope="col" id="cat_id" class="manage-column column-comments num" style=""><?php _e('Id', 'wp-directory-list');?></th>
      	<th scope="col" id="cat_desc" class="manage-column column-title" style=""><?php _e('Link Title', 'wp-directory-list');?></th>
      	<th scope="col" id="cat_desc" class="manage-column column-title" style=""><?php _e('URL', 'wp-directory-list');?></th>
      	<th scope="col" id="clicks" class="manage-column column-author" style=""><?php _e('Clicks', 'wp-directory-list');?></th>
      	<th scope="col" id="cat_name" class="manage-column column-categories" style=""><?php _e('Categories', 'wp-directory-list');?></th>
      	<th scope="col" id="status" class="manage-column column-author" style=""><?php _e('Status', 'wp-directory-list');?></th>
    	</tr>
  	</thead>
  
  	<tfoot>

    	<tr>
      	<th scope="col" id="dir" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
      	<th scope="col" id="cat_id" class="manage-column column-comments num" style=""><?php _e('Id', 'wp-directory-list');?></th>
      	<th scope="col" id="cat_desc" class="manage-column column-title" style=""><?php _e('Link Title', 'wp-directory-list');?></th>
      	<th scope="col" id="cat_desc" class="manage-column column-title" style=""><?php _e('URL', 'wp-directory-list');?></th>
      	<th scope="col" id="clicks" class="manage-column column-author" style=""><?php _e('Clicks', 'wp-directory-list');?></th>
      	<th scope="col" id="cat_name" class="manage-column column-categories" style=""><?php _e('Categories', 'wp-directory-list');?></th>
      	<th scope="col" id="status" class="manage-column column-author" style=""><?php _e('Status', 'wp-directory-list');?></th>
    	</tr>
    	
  	</tfoot>
  
    <tbody>
  		
  		<?php // start loop here>
  		
  		  if($_GET['category'] != "" || $_POST['dosort']) {
  		    
          $get_category = ( 0 != $_POST['sort'] ) ? $_POST['sort'] : $get_category;  
      
          $sql = "SELECT id,title,url,categories,status,clicks FROM $wp_directory_table WHERE status!= -1 and categories=".$get_category;
    
        }elseif($_POST['dosearch']){
          
          $term = $_POST['dir_search'];
          
          $sql = "SELECT * FROM ".$wp_directory_table." WHERE MATCH (title,description,url,address1,address2,city,state,country,zipcode,phone,fax,email,tags) AGAINST ('".$term."' WITH QUERY EXPANSION)";
          
        }else{
        
  		    $sql = "SELECT id,title,url,categories,status,clicks FROM $wp_directory_table WHERE status!= -1";
  		    
  		  }
  		  /*
  		// ?>
  		// <tr><td colspan=5>
  		// <?php
  		// echo $sql;
  		// ?>
  	//	 </td></tr>
  	//	 <?php
  	*/
      	$get_links = $wpdb->get_results($sql);
  
  			if($get_links) {
  
        foreach($get_links as $links){
            $sql = "SELECT id,category FROM $wp_directory_table2 WHERE id=$links->categories";
    	      $get_cats = $wpdb->get_results($sql);
    	      $catname = '';
    	      
    	      foreach($get_cats as $linkcategory){
              $catname .= '<a href="'.$my_blog.'admin.php?page=wp-directory-list/wp-directory-manage.php&category='.$linkcategory->id.'">'.$linkcategory->category."</a>";
            }
      ?>
      
      <tr>
        <th scope="row" class="check-column">
          <input type="checkbox" name="dir_action[]" value='<?php echo $links->id;?>''>
        </th>
        <td class="manage-column column-comments num">
          <?php echo $links->id;?>
        </td>
        <td class="manage-column column-title">
          <?php echo $links->title;?>
        </td>
        <td class="manage-column column-title">
          <a href="<?php echo $links->url;?>" target="_blank"><?php echo $links->url;?></a>
        </td>
        <td class="manage-column column-categories">
          <?php echo $links->clicks;?>
        </td>
        <td class="manage-column column-categories">
          <?php echo $catname;?>
        </td>
        <td class="manage-column column-author">
          <?php
          if( $links->status==1){
            
            _e('Active', 'wp-directory-list');
            
          }elseif($links->status==-1){
          
            _e('Pending','wp-directory-list');
          
          }else{
            
            _e('Inactive', 'wp-directory-list');
          
          }?>
        </td>
      </tr>
      <?php 
        }
      }
      //end loop here?> 
      
    </tbody>
  </table>
  
  <div class="tablenav">
  
  
    <div class="alignleft actions">
      <select name="action2">
      <option value="-1" selected="selected"><?php _e('Actions', 'wp-directory-list'); ?></option>
      <option value="edit"><?php _e('Edit', 'wp-directory-list'); ?></option>
      <option value="delete"><?php _e('Delete', 'wp-directory-list'); ?></option>
      <option value="change"><?php _e('Change Status', 'wp-directory-list'); ?></option>
      </select>
      <input type="submit" value="<?php _e('Apply', 'wp-directory-list'); ?>" name="doaction2" id="doaction2" class="button-secondary action" />
    </div>

    <br class="clear" />
  
  </div>


</form>
    <br class="clear" />
    
    <br class="clear" />
    
<!-- Add/Edit Form -->
<form id="directory_category_add_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
  <div class="wrap">
  <div id="icon-link-manager" class="icon32"><br /></div>
    <h2>
    <?
      if($doaction=='edit'){
    
    	   _e('Edit Link', 'wp-directory-list');
    	   
      }else{
      
      _e('Add Link', 'wp-directory-list');
      
      }?>
    </h2>
    <br class="clear">
    <div id="post-body" class="has-sidebar">
    <div id="post-body-content" class="has-sidebar-content">
    <div id="namediv" class="stuffbox">
      <h3><label for="link_title"><? _e('Title', 'wp-directory-list'); ?></label></h3>
    <div class="inside">
	    <input type="text" name="link_title" size="30" tabindex="1" value="<?php echo $linktitle?>" id="link_title" />
      <p><? _e('Example: Nifty blogging plugin', 'wp-directory-list'); ?></p>

    </div>
    </div>
    

    <div id="addressdiv" class="stuffbox">
      <h3><label for="link_url"><? _e('Web Address', 'wp-directory-list'); ?></label></h3>
    <div class="inside">
    	<input type="text" name="dir_link_url" size="30" tabindex="1" value="<?php echo $linkurl?>" id="dir_link_url" />
      <p><? _e('Example:', 'wp-directory-list'); ?> <code><? echo get_option('siteurl');?></code> &#8212; <? _e('don&#8217;t forget the', 'wp-directory-list'); ?> <code>http://</code></p>
    
    </div>
    </div>

    
    <div id="descriptiondiv" class="stuffbox">
      <h3><label for="link_description"><? _e('Link Description', 'wp-directory-list'); ?></label></h3>
    <div class="inside">
      <textarea name="link_description" rows="10" cols="80" tabindex="1" id="link_description"><?php echo $linkdesc; ?></textarea>
      <p><? _e('This will be shown below the link.', 'wp-directory-list'); ?></p>
    </div>
    </div>
    
    <div id="categorydiv" class="stuffbox">
<h3><label for="category"><?php _e('Categories', 'wp-directory-list'); ?></label></h3>
<div class="inside">
	<select name="category" tabindex="1" size="1">
  
    				<option value="0"><?php _e('None', 'wp-directory-list'); ?></option>  	
            <?php
            $sql = "SELECT id,category,cat_desc,parent,active FROM $wp_directory_table2";
      	$get_cats = $wpdb->get_results($sql);
          
  			if($get_cats) {
  
        foreach($get_cats as $category){	
          
          if($category->id == $linkcat){
            echo "<option value=\"$category->id\" selected=\"selected\">$category->category</option> "; 
          }else{
            echo "<option value=\"$category->id\">$category->category</option> "; 
          }
        }
        }?>		
  			 </select>
</div>
</div>
<div id="addressdiv" class="stuffbox">
<h3><label for="directory_address1"><? _e('Address', 'wp-directory-list'); ?></label></h3>
<div class="inside">
	<input type="text" name="directory_address1" size="30" tabindex="1" value="<?php echo $address1; ?>" id="directory_address1" />
    <? _e('Address 1', 'wp-directory-list'); ?><br />
</div>
<div class="inside">
	<input type="text" name="directory_address2" size="30" tabindex="2" value="<?php echo $address2; ?>" id="directory_address2" />
    <? _e('Address 2', 'wp-directory-list'); ?><br /><br />
</div>
<div class="inside">
	<input type="text" name="directory_city" size="30" tabindex="3" value="<?php echo $city; ?>" id="directory_city" />
    <? _e('City', 'wp-directory-list'); ?><br /><br />
</div>
<div class="inside">
	<input type="text" name="directory_state" size="30" tabindex="4" value="<?php echo $state; ?>" id="directory_state" />
    <? _e('State', 'wp-directory-list'); ?><br /><br />
</div>
<div class="inside">
	<input type="text" name="directory_zipcode" size="30" tabindex="5" value="<?php echo $zipcode; ?>" id="directory_zipcode" />
    <? _e('Zipcode', 'wp-directory-list'); ?><br /><br />
</div>
<div class="inside">
	<select name="directory_country" tabindex="6" size="1">
	
	<?php
   include(dirname(__FILE__).'/wp-directory-countries-list.php');
   echo get_country_options($editcountry);
  ?>
	</select>
    <? _e('Country', 'wp-directory-list'); 
    ?><br /><br />
</div>
</div>
<div id="phonediv" class="stuffbox">
<h3><label for="directory_phone"><? _e('Phone', 'wp-directory-list'); ?></label></h3>
<div class="inside">
	<input type="text" name="directory_phone" size="30" tabindex="1" value="<?php echo $phone; ?>" id="directory_phone" />
    <? _e('Phone', 'wp-directory-list'); ?><br />
</div>
<div class="inside">
	<input type="text" name="directory_fax" size="30" tabindex="2" value="<?php echo $fax; ?>" id="directory_fax" />
    <? _e('Fax', 'wp-directory-list'); ?><br /><br />
</div>
</div>

<div id="emaildiv" class="stuffbox">
<h3><label for="directory_email"><? _e('Contact Email', 'wp-directory-list'); ?></label></h3>
<div class="inside">
	<input type="text" name="directory_email" size="30" tabindex="1" value="<?php echo $email; ?>" id="directory_email" />
</div>
</div>

  <!-- Submit Button -->
  	<p class="submit">
  	<?php
  	if($doaction=='edit'){
  		   echo "<input type=\"hidden\" name=\"linkid\" value=\"$editlinkid\">";?>
  	   <input type="submit" name="Submit" class="button" value="<?php _e('Update', 'wp-directory-list'); ?>" />
  	   <?php
    }else{?>
  		<input type="submit" name="Submit" class="button" value="<?php _e('Add Link', 'wp-directory-list'); ?>" />
  	<?php }?>
  	</p>
  </div>
  </div>
  </div>
</form> 