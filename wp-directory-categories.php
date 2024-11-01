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
* - wp-content/plugins/wp-directory-list/wp-directory-categories.php *
* - Version 1.0.2                                                    *
*                                                                    *
*********************************************************************/
include(dirname(__FILE__).'/wp-directory-functions.php');
$wp_directory_table = $wpdb->prefix .'directory_link_cats';
$wp_directory_table2 = $wpdb->prefix .'directory_links';
  
// If Form Is Submitted

if($_POST['Submit']) {

  if($_POST['Submit']=='Add Category'){
  
    $cat_name = strip_tags(trim($_POST['catname']));
    $cat_desc = strip_tags(trim($_POST['catdesc']));
    $parent = intval($_POST['parent']);
    
    // Insert Category
    $sql = "INSERT INTO $wp_directory_table (category,cat_desc,parent) VALUES ( '$cat_name', '$cat_desc', $parent)";
  	$add_category = $wpdb->query($sql);

  	
    if(!$add_category) {
    
  		$text .= '<p style="color: red;">'.sprintf(__('Error In Adding Category \'%s\'.', 'wp-directory-list'), stripslashes($cat_name)).'</p>';
      
    }else{
    
      $text = '<p style="color: green;">'.sprintf(__('Category \'%s\' Added Successfully.', 'wp-directory-list'), stripslashes($cat_name)).'</a></p>';
    
    }
    
  }elseif($_POST['Submit']=='Update'){
  
    $cat_name = strip_tags(trim($_POST['catname']));
    $cat_desc = strip_tags(trim($_POST['catdesc']));
    $parent = intval($_POST['parent']);
    $catid = intval($_POST['catid']);
    
    // Update Category
    $sql = "UPDATE $wp_directory_table SET category='$cat_name',cat_desc='$cat_desc',parent=$parent WHERE id=$catid";
  	$add_category = $wpdb->query($sql);
  	
    if(!$add_category) {
    
  		$text .= '<p style="color: red;">'.sprintf(__('Error In Updating Category \'%s\'.', 'wp-directory-list'), stripslashes($cat_name)).'</p>';
      
    }else{
    
      $text = '<p style="color: green;">'.sprintf(__('Category \'%s\' Updated Successfully.', 'wp-directory-list'), stripslashes($cat_name)).'</a></p>';
    
    }
  
  
  }

}

if ( isset($_POST['action']) && ( -1 != $_POST['action'] || -1 != $_POST['action2'] ) ) {
	$doaction = ( -1 != $_POST['action'] ) ? $_POST['action'] : $_POST['action2'];

	switch ( $doaction ) {
  
  case 'edit':
    
    $cids = $_POST['dir_action'];
  
    $id = intval($cids[0]);
    
    $sql = "SELECT id,category,cat_desc,parent,active FROM $wp_directory_table WHERE id=$id";
    $get_cat = $wpdb->get_results($sql);
    
    foreach($get_cat as $category){
      $catname = $category->category;
      $catdesc = $category->cat_desc;
      $parentid = $category->parent;
      $editcatid= $category->id;
    }

  break;
  
  case 'delete':
    
    $cids = $_POST['dir_action'];
  
    $id = intval($cids[0]);
    
    $sql = "DELETE FROM $wp_directory_table WHERE id=$id";
    $get_cat = $wpdb->get_results($sql);
    
    _e('<p style="color: green;">Category Deleted Successfully.</a></p>', 'wp-directory-list');
    
  break;
  
  case 'change':
    
    $cids = $_POST['dir_action'];
    
    if($cids) {

      foreach($cids as $cid){
        
        $sql = "SELECT active FROM $wp_directory_table WHERE id=".intval($cid);
        $get_cats = $wpdb->get_results($sql);  
        
        foreach($get_cats as $cats){
        
          if($cats->active==1){
            
            $sql="UPDATE $wp_directory_table SET active=0 WHERE id=".intval($cid);
            
          }else{
            
            $sql="UPDATE $wp_directory_table SET active=1 WHERE id=".intval($cid);
                        
          }
          $wpdb->get_results($sql);
          unset($cats);
        }
        
        unset($cid);
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

    <h2><?php _e('Categories', 'wp-directory-list'); ?></h2>
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
  </div>
  <table class="widefat post fixed" cellspacing="0">
  	<thead>
    	<tr>
      	<th scope="col" id="dir" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
      	<th scope="col" id="cat_id" class="manage-column column-comments num" style=""><?php _e('Id', 'wp-directory-list');?></th>
        <th scope="col" id="cat_name" class="manage-column column-categories" style=""><?php _e('Category Name', 'wp-directory-list');?></th>
      	<th scope="col" id="cat_desc" class="manage-column column-title" style=""><?php _e('Category Description', 'wp-directory-list');?></th>
      	<th scope="col" id="parent" class="manage-column column-comments num" style=""><?php _e('Parent Id', 'wp-directory-list');?></th>
      	<th scope="col" id="status" class="manage-column column-comments num" style=""><?php _e('# of Links', 'wp-directory-list');?></th>
      	<th scope="col" id="status" class="manage-column column-author" style=""><?php _e('Status', 'wp-directory-list');?></th>
    	</tr>
  	</thead>

  	<tfoot>
    	<tr>
      	<th scope="col" id="dir" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
      	<th scope="col" id="cat_id" class="manage-column column-comments num" style=""><?php _e('Id', 'wp-directory-list');?></th>
        <th scope="col" id="cat_name" class="manage-column column-categories" style=""><?php _e('Category Name', 'wp-directory-list');?></th>
      	<th scope="col" id="cat_desc" class="manage-column column-title" style=""><?php _e('Category Description', 'wp-directory-list');?></th>
      	<th scope="col" id="parent" class="manage-column column-comments num" style=""><?php _e('Parent Id', 'wp-directory-list');?></th>
      	<th scope="col" id="status" class="manage-column column-comments num" style=""><?php _e('# of Links', 'wp-directory-list');?></th>
      	<th scope="col" id="status" class="manage-column column-author" style=""><?php _e('Status', 'wp-directory-list');?></th>
    	</tr>
  	</tfoot>

  <tbody>
		
		<?php // start loop here>
		  $sql = "SELECT id,category,cat_desc,parent,active FROM $wp_directory_table";
    	$get_cats = $wpdb->get_results($sql);

			if($get_cats) {

      foreach($get_cats as $category){
        $numlinks = get_link_count($category->id);
    ?>
    <tr>
      <th scope="row" class="check-column">
        <input type="checkbox" name="dir_action[]" value='<?php echo $category->id;?>''>
      </th>
      <td class="manage-column column-comments num">
        <?php echo $category->id;?>
      </td>
      <td class="manage-column column-categories">
        <?php echo $category->category;?>
      </td>
      <td class="manage-column column-title">
        <?php echo $category->cat_desc;?>
      </td>
      <td class="manage-column column-comments num">
        <?php echo $category->parent;?>
      </td>
      <td class="manage-column column-comments num">
        <?php echo $numlinks;?>
      </td>
      <td class="manage-column column-author">
        <?php
        if( $category->active){
          
          _e('Active', 'wp-directory-list');
          
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
      <br class="clear" />
    </div>
  <br class="clear" />

</div>
</form>

<form id="directory_category_add_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
<div class="wrap">
<div id="icon-link-manager" class="icon32"><br /></div>
<h2><?php if($doaction=='edit'){
	   _e('Edit Category', 'wp-directory-list');
	   
  }else{
  _e('Add Category', 'wp-directory-list');
  }?></h2>
  <table class="form-table">  
    <tr>
		  <td><?php _e('Category Name', 'wp-directory-list'); ?>  <input type="text" name="catname" value="<?php 
      echo $catname;
       ?>"></td>
    </tr>
    <tr>
		  <td valign="top"><?php _e('Category Description', 'wp-directory-list'); ?>  <textarea name="catdesc"><?php
      echo $catdesc;
      ?></textarea></td>
    </tr>
    <tr>
      <td><?php _e('Parent', 'wp-directory-list'); ?>:  
        <select name="parent" size="1">
  				<option value="0"><?php _e('None', 'wp-directory-list'); ?></option>  	
          <?php
          $sql = "SELECT id,category,cat_desc,parent,active FROM $wp_directory_table";
    	$get_cats = $wpdb->get_results($sql);

			if($get_cats) {

      foreach($get_cats as $category){	
        
        if($category->id == $parentid){
          echo "<option value=\"$category->id\" selected=\"selected\">$category->category</option> "; 
        }else{
          echo "<option value=\"$category->id\">$category->category</option> "; 
        }
      }
      }?>		
			 </select>
			 
		  </td>
		</tr>
  </table>
<!-- Submit Button -->
	<p class="submit">
	<?php

	if($doaction=='edit'){
	
	   echo "<input type=\"hidden\" name=\"catid\" value=\"$editcatid\" />";?>
	   <input type="submit" name="Submit" class="button" value="<?php _e('Update', 'wp-directory-list'); ?>" />
	   <?php
	   
  }else{?>
		<input type="submit" name="Submit" class="button" value="<?php _e('Add Category', 'wp-directory-list'); ?>" />
	<?php }?>
	</p>
</div>
</form> 