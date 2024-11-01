<?php
add_action('plugins_loaded', 'widget_directory_init');

function widget_directory_list($args) {
  extract($args);
  global $wpdb;
  
  $wp_directory_table = $wpdb->prefix .'directory_links';
  
  $options = get_option("widget_directory");
  
  if (!is_array( $options ))
	{
      $options = array(
      'title' => 'Random Links',
      'directory_widget_limit' => '5',
      );
  }      

    echo $before_widget;
    echo $before_title;
    echo $options['title'];
    echo $after_title;
 
    // lets build up the widget.
 
    $sql = "SELECT id,title,url,clicks FROM $wp_directory_table WHERE status=1";
    
    if($options['widget-premium-only']){
    
      $sql .=" AND premium=1";
    
    }
    
    if($options['widget-premium-only'] && $options['directory_cat']){
      $sql .=" AND ";
    }
    
    if( $options['directory_cat']>0){ 
      $category = $options['directory_cat'];
      $sql .="category=$category";
    }
    
    $sql.=" ORDER BY";
    
    switch($options['widget-dir-order']){
    
    
      case "1":
        // Random
        $sql.=" RAND()";
        break;
      
      case "2":
          // Popular
        $sql.=" clicks";
        break;

      case "3":
          // Newest
          $sql.=" date_added";
        break;

      default:
      
        // Default is Random
        $sql.=" RAND()";
        
        break;
    
    }
    
    $limit = $options['directory_widget_limit'];
    $sql.= " DESC LIMIT $limit";
    $my_blog = get_option('blogurl');
    $directory_redirect = get_option('directory_redirect');
    
    $get_links = $wpdb->get_results($sql);
    foreach($get_links as $links){
    ?>
    <div class="dir-link-widget"><a href="<?php 
          if($directory_redirect){
            echo $my_blog.'index.php?addclick='.$links->id; 
          }else{
            echo $links->url;
          }
          ?>" rel="bookmark" title="<?php echo $links->title; ?>"><?php echo $links->title; ?></a></div>
    <?php
    }
              
    //Our Widget Content
  echo $after_widget;
}

function widget_directory_control(){
  global $wpdb;
  
  $wp_directory_table = $wpdb->prefix .'directory_links';
  $wp_directory_table2 = $wpdb->prefix .'directory_link_cats';

  $options = get_option("widget_directory");

  if (!is_array( $options ))
	{
		$options = array(
      'title' => 'Random Links',
      'directory_widget_limit' => '5',
      'directory-widget-link-size' => '10',
      'directory-widget-link-color' => '#000000',
      );
  }      

  if ($_POST['Directory-Widget-Submit'])
  {
    $options['title'] = strip_tags(stripslashes($_POST['Directory-WidgetTitle']));
    $options['directory_widget_limit'] = intval($_POST['Directory-WidgetLimit']);
    $options['widget-premium-only'] = strip_tags(stripslashes($_POST['widget-premium-only']));
    $options['directory_cat'] = intval($_POST['category']);
    $options['widget-dir-order'] = intval($_POST['widget-dir-order']);
    $options['directory-widget-link-size'] = trim($_POST['Directory-widget-link-size']);
    $options['directory-widget-link-color'] = trim($_POST['Directory-widget-link-color']);

    update_option("widget_directory", $options);
  }

?>
  <fieldset>
  <p><?php _e('Empty field will use default value.', 'wp-directory-list'); ?></p>
  <div>
    <label for="Directory-WidgetTitle"><?php _e('Widget Title:', 'wp-directory-list'); ?> </label>
    <input type="text" id="Directory-WidgetTitle" name="Directory-WidgetTitle" value="<?php echo $options['title'];?>" />
    <input type="hidden" id="Directory-Widget-Submit" name="Directory-Widget-Submit" value="1" />
    <br /><br />
  </div>
  <?php /*
  <div>
  <label for="widget-premium-only" style="line-height:35px;display:block;">
        <?php _e('Premium Links only:', 'wp-directory-list'); 
        <br />
				<input type="checkbox" id="widget-premium-only" name="widget-premium-only" <?php if ( $options['widget-premium-only'] == 1 ) echo 'checked="checked"';  value="1" />
			</label>
			<br /><br />
		</div>
*/?>
  <div>
  <label for="widget-dir-random">
        <?php _e('How to order the links:', 'wp-directory-list'); ?>
        <br />
				<input type="radio" id="widget-dir-order" name="widget-dir-order" <?php if ( $options['widget-dir-order'] == 1 ) echo 'checked="checked"'; ?> value="1" />  <?php _e('Random', 'wp-directory-list'); ?><br />
				<input type="radio" id="widget-dir-order" name="widget-dir-order" <?php if ( $options['widget-dir-order'] == 2 ) echo 'checked="checked"'; ?> value="2" />  <?php _e('Popular', 'wp-directory-list'); ?><br />
				<input type="radio" id="widget-dir-order" name="widget-dir-order" <?php if ( $options['widget-dir-order'] == 3 ) echo 'checked="checked"'; ?> value="3" />  <?php _e('Newest', 'wp-directory-list'); ?><br />
			</label>
			<br /><br />
	</div>
  <div>
    <label for="Directory-WidgetLimit"><?php _e('Number of Links in Widget:', 'wp-directory-list'); ?>: </label>
    <input type="text" id="Directory-WidgetLimit" name="Directory-WidgetLimit" value="<?php echo $options['directory_widget_limit'];?>" />
  
    <br /><br />
  </div>
  <div>
  <label for="category"><?php _e('Category', 'wp-directory-list'); ?></label>
  <br />
	<select name="category" tabindex="1" size="1">
  
    				<option value="-1"><?php _e('All', 'wp-directory-list'); ?></option>  	
            <?php
            $sql = "SELECT id,category,cat_desc,parent,active FROM $wp_directory_table2";
      	$get_cats = $wpdb->get_results($sql);
          
  			if($get_cats) {
  
        foreach($get_cats as $category){	
          
          if($category->id == $options['directory_cat']){
            echo "<option value=\"$category->id\" selected=\"selected\">$category->category</option> "; 
          }else{
            echo "<option value=\"$category->id\">$category->category</option> "; 
          }
        }
        }?>		
  			 </select>
  			 <br /><br />
</div>
  
  </fieldset>
<?php
}

function widget_directory_init()
{
  register_sidebar_widget(__('Random Links'), 'widget_directory_list');
  register_widget_control(   'Random Links', 'widget_directory_control' );
}


?>
