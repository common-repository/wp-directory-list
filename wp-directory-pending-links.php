<?php
/****************************************************************************
*                                                                           *
*	WordPress 2.7 Plugin: Wp-Directory-List                                   *
*	Copyright (c) 2009 Billie Kennedy Jr                                      *
*                                                                           *
*	- http://themoneymakingwebsite.com                                        *
*                                                                           *
*	File Information:                                                         *
*	- Directory Manager                                                       *
* - wp-content/plugins/wp-directory-list/wp-directory-pending-links.php     *
* - Version 1.0.3                                                           *
*                                                                           *
****************************************************************************/

$wp_directory_table = $wpdb->prefix .'directory_links';
$wp_directory_table2 = $wpdb->prefix .'directory_link_cats';

if ( isset($_POST['action']) && ( -1 != $_POST['action'] || -1 != $_POST['action2'] ) ) {
	$doaction = ( -1 != $_POST['action'] ) ? $_POST['action'] : $_POST['action2'];

	switch ( $doaction ) {
  
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
  
    <h2><?php _e('Pending Links', 'wp-directory-list'); ?></h2>
    <div class="alignleft actions">
      <select name="action">
      <option value="-1" selected="selected"><?php _e('Actions', 'wp-directory-list'); ?></option>
      <option value="edit"><?php _e('Edit', 'wp-directory-list'); ?></option>
      <option value="delete"><?php _e('Delete', 'wp-directory-list'); ?></option>
      <option value="change"><?php _e('Change Status', 'wp-directory-list'); ?></option>
      </select>
      <input type="submit" value="<?php _e('Apply', 'wp-directory-list'); ?>" name="doaction2" id="doaction2" class="button-secondary action" />
    </div>
  </div>
  <table class="widefat post fixed" cellspacing="0">
    <thead>
    	<tr>
      	<th scope="col" id="dir" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
      	<th scope="col" id="cat_id" class="manage-column column-comments num" style=""><?php _e('Id', 'wp-directory-list');?></th>
      	<th scope="col" id="cat_desc" class="manage-column column-title" style=""><?php _e('Link Title', 'wp-directory-list');?></th>
      	<th scope="col" id="cat_desc" class="manage-column column-title" style=""><?php _e('URL', 'wp-directory-list');?></th>
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
      	<th scope="col" id="cat_name" class="manage-column column-categories" style=""><?php _e('Categories', 'wp-directory-list');?></th>
      	<th scope="col" id="status" class="manage-column column-author" style=""><?php _e('Status', 'wp-directory-list');?></th>
    	</tr>
    	
  	</tfoot>
  
    <tbody>
  		
  		<?php // start loop here>
  		  $sql = "SELECT id,title,url,categories,status FROM $wp_directory_table WHERE status=-1";
      	$get_links = $wpdb->get_results($sql);
  
  			if($get_links) {
  
        foreach($get_links as $links){
            $sql = "SELECT category FROM $wp_directory_table2 WHERE id=$links->categories";
    	      $get_cats = $wpdb->get_results($sql);
    	    
    	      foreach($get_cats as $linkcategory){
              $catname = $linkcategory->category;
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
    