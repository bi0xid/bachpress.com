<?php
// Locale function

load_theme_textdomain( 'bach', TEMPLATEPATH.'/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH."/languages/$locale.php";

if ( is_readable($locale_file) )
	require_once($locale_file);
	
	
// END of locale

add_filter( 'the_content', 'make_clickable' );

function get_recent_post_ids( $return_as_string = true ) {
	global $wpdb;

	$recent_ids =  (array) $wpdb->get_results( "
		SELECT MAX(ID) AS post_id
		FROM {$wpdb->posts}
		WHERE post_type = 'post'
		  AND post_status = 'publish'      
		GROUP BY post_author
		ORDER BY post_date_gmt DESC
	", ARRAY_A );

	if( $return_as_string === true ) {
		$ids_string = '';
		foreach( $recent_ids as $post_id ) {
			$ids_string .= "{$post_id['post_id']}, ";
		}

		// Remove trailing comma
		$ids_string = substr( $ids_string, 0, -2 );

		return $ids_string;
	}

	$ids = array( );
	foreach( $recent_ids as $post_id ) {
		$ids[] = $post_id['post_id'];
	}

	return $ids;
}

function prologue_recent_projects_widget( $args ) {
	extract( $args );
	$options = get_option( 'prologue_recent_projects' );

	$title = empty( $options['title'] ) ? __( 'Recent Tags' ) : $options['title'];
	$num_to_show = empty( $options['num_to_show'] ) ? 35 : $options['num_to_show'];

	$num_to_show = (int) $num_to_show;

	$before = $before_widget;
	$before .= $before_title . $title . $after_title;

	$after = $after_widget;

	echo prologue_recent_projects( $num_to_show, $before, $after );
}

function prologue_recent_projects( $num_to_show = 35, $before = '', $after = '' ) {
	$cache = wp_cache_get( 'prologue_theme_tag_list', '' );
	if( !empty( $cache[$num_to_show] ) ) {
		$recent_tags = $cache[$num_to_show];
	}
	else {
		$all_tags = (array) get_tags( array( 'get' => 'all' ) );

		$recent_tags = array( );
		foreach( $all_tags as $tag ) {
			if( $tag->count < 1 )
				continue;

			$tag_posts = get_objects_in_term( $tag->term_id, 'post_tag' );
			$recent_post_id = max( $tag_posts );
			$recent_tags[$tag->term_id] = $recent_post_id;
		}

		arsort( $recent_tags );

		$num_tags = count( $recent_tags );
		if( $num_tags > $num_to_show ) {
			$reduce_by = (int) $num_tags - $num_to_show;

			for( $i = 0; $i < $reduce_by; $i++ ) {
				array_pop( $recent_tags );
			}
		}

		wp_cache_set( 'prologue_theme_tag_list', array( $num_to_show => $recent_tags ) );
	}

	echo $before;
	echo "<ul>\n";

	foreach( $recent_tags as $term_id => $post_id ) {
		$tag = get_term( $term_id, 'post_tag' );
		$tag_link = get_tag_link( $tag->term_id );

?>

<li>
<a class="rss" href="<?php echo get_tag_feed_link( $tag->term_id ); ?>">RSS</a>&nbsp;<a href="<?php echo $tag_link; ?>"><?php echo $tag->name; ?></a>&nbsp;(&nbsp;<?php echo $tag->count; ?>&nbsp;)
</li>

<?php
    } // foreach get_tags
?>

	</ul>

<p><a class="allrss" href="<?php bloginfo( 'rss2_url' ); ?>">All Updates RSS</a></p>

<?php
	echo $after;
}


function prologue_avatars( $num_to_show = 35, $before = 'Proyectos:<p>', $after = '</p>' ) {
	$cache = wp_cache_get( 'prologue_theme_tag_list', '' );
	if( !empty( $cache[$num_to_show] ) ) {
		$recent_tags = $cache[$num_to_show];
	}
	else {
		$all_tags = (array) get_tags( array( 'get' => 'all' ) );

		$recent_tags = array( );
		foreach( $all_tags as $tag ) {
			if( $tag->count < 1 )
				continue;

			$tag_posts = get_objects_in_term( $tag->term_id, 'post_tag' );
			$recent_post_id = max( $tag_posts );
			$recent_tags[$tag->term_id] = $recent_post_id;
		}

		arsort( $recent_tags );

		$num_tags = count( $recent_tags );
		if( $num_tags > $num_to_show ) {
			$reduce_by = (int) $num_tags - $num_to_show;

			for( $i = 0; $i < $reduce_by; $i++ ) {
				array_pop( $recent_tags );
			}
		}

		wp_cache_set( 'prologue_theme_tag_list', array( $num_to_show => $recent_tags ) );
	}

	echo $before;
	echo "<ul>\n";

	foreach( $recent_tags as $term_id => $post_id ) {
		$tag = get_term( $term_id, 'post_tag' );
		$tag_link = get_tag_link( $tag->term_id );

?>

<li>
<a class="rss" href="<?php echo get_tag_feed_link( $tag->term_id ); ?>">RSS</a>&nbsp;<a href="<?php echo $tag_link; ?>"><?php echo ("<img alt='' src='".bloginfo('template_directory')."/i/".$tag->slug.".jpg' class='avatar avatar-48' height='48px' width='48px' style='float:right;'/>") ?>
</a>&nbsp;(&nbsp;<?php echo $tag->count; ?>&nbsp;)
</li>

<?php
    } // foreach get_tags
?>

	</ul>

<p><a class="allrss" href="<?php bloginfo( 'rss2_url' ); ?>">All Updates RSS</a></p>

<?php
	echo $after;
}
function prologue_flush_tag_cache( ) {
	wp_cache_delete( 'prologue_theme_tag_list' );
}
add_action( 'save_post', 'prologue_flush_tag_cache' );

function prologue_recent_projects_control( ) {
	$options = $newoptions = get_option( 'prologue_recent_projects' );

	if( $_POST['prologue_submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['prologue_title'] ) );
		$newoptions['num_to_show'] = strip_tags( stripslashes( $_POST['prologue_num_to_show'] ) );
	}

	if( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'prologue_recent_projects', $options );
	}

	$title = attribute_escape( $options['title'] );
	$num_to_show = $options['num_to_show'];
?>

<input type="hidden" name="prologue_submit" id="prologue_submit" value="1" />

<p><label for="prologue_title"><?php _e('Title:') ?> 
<input type="text" class="widefat" id="prologue_title" name="prologue_title" value="<?php echo $title ?>" />
</label></p>

<p><label for="prologue_num_to_show"><?php _e('Num of tags to show:') ?> 
<input type="text" class="widefat" id="prologue_num_to_show" name="prologue_num_to_show" value="<?php echo $num_to_show ?>" />
</label></p>

<?php
}
wp_register_sidebar_widget( 'prologue_recent_projects_widget', __( 'Recent Projects' ), 'prologue_recent_projects_widget' );
wp_register_widget_control( 'prologue_recent_projects_widget', __( 'Recent Projects' ), 'prologue_recent_projects_control' );

function load_javascript( ) {
//	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_print_scripts', load_javascript );

if( function_exists('register_sidebar') )
	register_sidebar();


define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '%s/images/there-is-no-image.jpg'); 
define('HEADER_IMAGE_WIDTH', 726);
define('HEADER_IMAGE_HEIGHT', 150);
define('NO_HEADER_TEXT', true);

function prologue_admin_header_style( ) {
?>
<style type="text/css">
#headimg h1, #desc {
	display: none;
}
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}
</style>
<?php
}
add_custom_image_header( '', 'prologue_admin_header_style' );


function prologue_get_avatar( $wpcom_user_id, $email, $size, $rating = '', $default = 'http://s.wordpress.com/i/mu.gif' ) {
	if( !empty( $wpcom_user_id ) && $wpcom_user_id !== false && function_exists( 'get_avatar' ) ) {
		return get_avatar( $wpcom_user_id, $size );
	}
	elseif( !empty( $email ) && $email !== false ) {
		$default = urlencode( $default );

		$out = 'http://www.gravatar.com/avatar.php?gravatar_id=';
		$out .= md5( $email );
		$out .= "&amp;size={$size}";
		$out .= "&amp;default={$default}";

		if( !empty( $rating ) ) {
			$out .= "&amp;rating={$rating}";
		}

		return "<img alt='' src='{$out}' class='avatar avatar-{$size}' height='{$size}' width='{$size}' />";
	}
	else {
		return "<img alt='' src='{$default}' />";
	}
}

function get_root_category($category_id){
	$category = $category_id->cat_ID;
    global $wpdb;
    	$parent = $wpdb->get_var( "SELECT parent FROM $wpdb->term_taxonomy WHERE term_id = '$category'");
		if ($parent == 0) {
			return $category;
		}
		while ($parent != 0){
			$category = $parent;
			$parent = $wpdb->get_var( "SELECT parent FROM $wpdb->term_taxonomy WHERE term_id = '$category'");
			if ($parent == 0) {
				return $category;
			}else{
				$category = $parent;
			}
		}
}


/*************************
		ADMIN MENU
*************************/



function bach_admin_head() { ?>
<style>

h2 { margin-bottom: 20px; }
.title { margin: 0px !important; background: #D4E9FA; padding: 10px; font-family: Georgia, serif; font-weight: normal !important; letter-spacing: 1px; font-size: 18px; }
.container { background: #EAF3FA; padding: 10px; }
.maintable { font-family:"Lucida Grande","Lucida Sans Unicode",Arial,Verdana,sans-serif; background: #EAF3FA; margin-bottom: 20px; padding: 10px 0px; }
.mainrow { padding-bottom: 10px !important; border-bottom: 1px solid #D4E9FA !important; float: left; margin: 0px 10px 10px 10px !important; }
.titledesc { font-size: 14px; font-weight:bold; width: 220px !important; margin-right: 20px !important; }
.forminp { width: 700px !important; valign: middle !important; }
.forminp input, .forminp select, .forminp textarea { margin-bottom: 9px !important; background: #fff; border: 1px solid #D4E9FA; width: 500px; padding: 4px; font-family:"Lucida Grande","Lucida Sans Unicode",Arial,Verdana,sans-serif; font-size: 12px; }
.forminp span { font-size: 10px !important; font-weight: normal !important; ine-height: 14px !important; }
.forminp .checkbox { width:20px }
.info { background: #FFFFCC; border: 1px dotted #D8D2A9; padding: 10px; color: #333; }
.info a { color: #333; text-decoration: none; border-bottom: 1px dotted #333 }
.info a:hover { color: #666; border-bottom: 1px dotted #666; }
.warning { background: #FFEBE8; border: 1px dotted #CC0000; padding: 10px; color: #333; font-weight: bold; }

</style>
<?php }

// VARIABLES

$themename = "Bach 1.0";
$shortname = "bach";
$manualurl = 'http://bach.mecus.es/';
$options = array();

add_option("bach_settings",$options);

$template_path = get_bloginfo('template_directory');

$layout_path = TEMPLATEPATH . '/layouts/'; 
$layouts = array();

$alt_stylesheet_path = TEMPLATEPATH . '/styles/';
$alt_stylesheets = array();

$functions_path = TEMPLATEPATH . '/functions/';

$bach_categories_obj = get_categories('hide_empty=0');
$bach_categories = array();

$bach_pages_obj = get_pages('sort_column=post_parent,menu_order');
$bach_pages = array();

if ( is_dir($layout_path) ) {
	if ($layout_dir = opendir($layout_path) ) { 
		while ( ($layout_file = readdir($layout_dir)) !== false ) {
			if(stristr($layout_file, ".php") !== false) {
				$layouts[] = $layout_file;
			}
		}	
	}
}	

if ( is_dir($alt_stylesheet_path) ) {
	if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
		while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
			if(stristr($alt_stylesheet_file, ".css") !== false) {
				$alt_stylesheets[] = $alt_stylesheet_file;
			}
		}	
	}
}	

if ( is_dir($modules_path) ) {
	if ($modules_dir = opendir($modules_path) ) { 
		while ( ($module_file = readdir($modules_dir)) !== false ) {
			if(stristr($module_file, ".php") !== false) {
				$file_tmp = substr($module_file, 0, -4);
				$modules[$file_tmp] = $module_file;
			}
		}	
	}
}

if ( is_dir($ads_path) ) {
	if ($ads_dir = opendir($ads_path) ) { 
		while ( ($ads_file = readdir($ads_dir)) !== false ) {
			if((stristr($ads_file, ".jpg") !== false) || (stristr($ads_file, ".png") !== false) || (stristr($ads_file, ".gif") !== false)) {
				$ads[] = $ads_file;
			}
		}	
	}
}

foreach ($bach_categories_obj as $bach_cat) {
	$bach_categories[$bach_cat->cat_ID] = $bach_cat->cat_name;
}

foreach ($bach_pages_obj as $bach_page) {
	$bach_pages[$bach_page->ID] = $bach_page->post_name;
}


$other_entries = array("Seleccione un número:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
$categories_tmp = array_unshift($bach_categories, "Select a category:");
$bach_pages_tmp = array_unshift($bach_pages, "Select a page:");

// THIS IS THE DIFFERENT FIELDS

$options = array (

				array(	"name" => "General options",
						"type" => "heading"),


				array(	"name" => "Logo",
						"desc" => "Put complete URL to replace default logo.",
						"id" => $shortname."_logo",
						"std" => "",
						"type" => "text"),												    

				array(	"name" => "Categories",
						"type" => "heading"),
						
				array( 	"name" => "Users",
					   	"desc" => "Select users parent category.",
						"id" => $shortname."_users",
						"std" => "Select a category:",
						"type" => "select",
						"options" => $bach_categories),						

				array( 	"name" => "Projects",
					   	"desc" => "Select projects category.",
						"id" => $shortname."_projects",
						"std" => "Select a category:",
						"type" => "select",
						"options" => $bach_categories),						

				array( 	"name" => "Priorities",
					   	"desc" => "Select priorities category.",
						"id" => $shortname."_priorities",
						"std" => "Select a category:",
						"type" => "select",
						"options" => $bach_categories),						

				array( 	"name" => "Open",
					   	"desc" => "Select open category.",
						"id" => $shortname."_open",
						"std" => "Select a category:",
						"type" => "select",
						"options" => $bach_categories),						

				array( 	"name" => "Wait",
					   	"desc" => "Select wait category.",
						"id" => $shortname."_wait",
						"std" => "Select a category:",
						"type" => "select",
						"options" => $bach_categories),						

				array( 	"name" => "Closed",
					   	"desc" => "Select closed category.",
						"id" => $shortname."_closed",
						"std" => "Select a category:",
						"type" => "select",
						"options" => $bach_categories)						
																														
		  );

// ADMIN PANEL

function bach_add_admin() {

	 global $themename, $options;
	
	if ( $_GET['page'] == basename(__FILE__) ) {	
        if ( 'save' == $_REQUEST['action'] ) {
	
                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;
							update_option($up_opt, $_REQUEST[$up_opt] );
						}
					}
				}

                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } 
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;						
							if( isset( $_REQUEST[ $up_opt ] ) ) { update_option( $up_opt, $_REQUEST[ $up_opt ]  ); } else { delete_option( $up_opt ); } 
						}
					}
				}
						
				header("Location: admin.php?page=functions.php&saved=true");								
			
			die;

		} else if ( 'reset' == $_REQUEST['action'] ) {
			delete_option('sandbox_logo');
			
			header("Location: admin.php?page=functions.php&reset=true");
			die;
		}

	}

add_menu_page($themename." Opciones", $themename." Opciones", 'edit_themes', basename(__FILE__), 'bach_page');

}

function bach_page (){

		global $options, $themename, $manualurl;
		
		?>

<div class="wrap">

    			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

						<h2><?php echo $themename; ?> Opciones</h2>

						<?php if ( $_REQUEST['saved'] ) { ?><div style="clear:both;height:20px;"></div><div class="warning"><?php echo $themename; ?> se ha actualizado</div><?php } ?>
						<?php if ( $_REQUEST['reset'] ) { ?><div style="clear:both;height:20px;"></div><div class="warning"><?php echo $themename; ?> se ha reseteado</div><?php } ?>						
						
						<div style="clear:both;height:20px;"></div>
						
						<div class="info">
					
							<div style="width: 70%; float: left; display: inline;padding-top:4px;"><strong>¿Atascado?</strong> <a href="<?php echo $manualurl; ?>" target="_blank">Lea la documentación</a>.</div>
							<div style="width: 30%; float: right; display: inline;text-align: right;"><input name="save" type="submit" value="Guardar cambios" /></div>
							<div style="clear:both;"></div>
						
						</div>	    			
						
						<!--START: GENERAL SETTINGS-->
     						
     						<table class="maintable">
     							
							<?php foreach ($options as $value) { ?>
	
									<?php if ( $value['type'] <> "heading" ) { ?>
	
										<tr class="mainrow">
										<td class="titledesc"><?php echo $value['name']; ?></td>
										<td class="forminp">
		
									<?php } ?>		 
	
									<?php
										
										switch ( $value['type'] ) {
										case 'text':
		
									?>
									
		        							<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
		
									<?php
										
										break;
										case 'select':
		
									?>
		
	            						<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
	                					<?php foreach ($value['options'] as $option) { ?>
	                						<option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
	                					<?php } ?>
	            						</select>
		
									<?php
		
										break;
										case 'textarea':
										$ta_options = $value['options'];
		
									?>
									
										<textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="<?php echo $ta_options['cols']; ?>" rows="8"><?php  if( get_settings($value['id']) != "") { echo stripslashes(get_settings($value['id'])); } else { echo $value['std']; } ?></textarea>
		
									<?php
										
										break;
										case "radio":
		
 										foreach ($value['options'] as $key=>$option) { 
				
													$radio_setting = get_settings($value['id']);
													
													if($radio_setting != '') {
		    											
		    											if ($key == get_settings($value['id']) ) { $checked = "checked=\"checked\""; } else { $checked = ""; }
													
													} else {
													
														if($key == $value['std']) { $checked = "checked=\"checked\""; } else { $checked = ""; }
									} ?>
									
	            					<input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><?php echo $option; ?><br />
		
									<?php }
		 
										break;
										case "checkbox":
										
										if(get_settings($value['id'])) { $checked = "checked=\"checked\""; } else { $checked = ""; }
									
									?>
		            				
		            				<input type="checkbox" class="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
		
									<?php
		
										break;
										case "multicheck":
		
 										foreach ($value['options'] as $key=>$option) {
 										
	 											$bach_key = $value['id'] . '_' . $key;
												$checkbox_setting = get_settings($bach_key);
				
 												if($checkbox_setting != '') {
		    		
		    											if (get_settings($bach_key) ) { $checked = "checked=\"checked\""; } else { $checked = ""; }
				
												} else { if($key == $value['std']) { $checked = "checked=\"checked\""; } else { $checked = ""; }
				
									} ?>
									
	            					<input type="checkbox" class="checkbox" name="<?php echo $bach_key; ?>" id="<?php echo $bach_key; ?>" value="true" <?php echo $checked; ?> /><label for="<?php echo $bach_key; ?>"><?php echo $option; ?></label><br />
									
									<?php }
		 
										break;
										case "heading":

									?>
									
										</table> 
		    							
		    									<h3 class="title"><?php echo $value['name']; ?></h3>
										
										<table class="maintable">
		
									<?php
										
										break;
										default:
										break;
									
									} ?>
	
									<?php if ( $value['type'] <> "heading" ) { ?>
	
										<?php if ( $value['type'] <> "checkbox" ) { ?><br/><?php } ?><span><?php echo $value['desc']; ?></span>
										</td></tr>
	
									<?php } ?>		
	
							<?php } ?>	
							
							</table>	

							<p class="submit">
								<input name="save" type="submit" value="Guardar cambios" />    
								<input type="hidden" name="action" value="save" />
							</p>							
							
							<div style="clear:both;"></div>		
						
						<!--END: GENERAL SETTINGS-->						
             
            </form>

</div><!--wrap-->

<div style="clear:both;height:20px;"></div>
 
 <?php

};

function bach_wp_head() { 
     $style = $_REQUEST[style];
     if ($style != '') {
          ?> <link href="<?php bloginfo('template_directory'); ?>/styles/<?php echo $style; ?>.css" rel="stylesheet" type="text/css" /><?php 
     } else { 
          $stylesheet = get_option('bach_alt_stylesheet');
          if($stylesheet != ''){
               ?><link href="<?php bloginfo('template_directory'); ?>/styles/<?php echo $stylesheet; ?>" rel="stylesheet" type="text/css" /><?php         
          }
     }     
}

add_action('wp_head', 'bach_wp_head');
add_action('admin_menu', 'bach_add_admin');
add_action('admin_head', 'bach_admin_head');	


function status( $term_id ){
	// Dabatase inicialization
	global $wpdb;

	// Global ID's
	
		$abierto = get_option('bach_open'); 
		$term_id_open = get_cat_id($abierto);
		$cerrado = get_option('bach_closed'); 
		$term_id_closed = get_cat_id($cerrado);
		$esperas = get_option('bach_wait'); 
		$term_id_wait = get_cat_id($esperas);
	
	// Get term_taxonomy_id from term_id's

	$term_taxonomy_id_open = $wpdb->get_var("SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE term_id = '$term_id_open'");
	$term_taxonomy_id_closed = $wpdb->get_var("SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE term_id = '$term_id_closed'");
	$term_taxonomy_id_wait = $wpdb->get_var("SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE term_id = '$term_id_wait'");
	



	// Get object_id from category $term_id
	$term_taxonomy_id = $wpdb->get_var("SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE term_id = '$term_id'");
	$objetos = $wpdb->get_col("SELECT object_id  FROM $wpdb->term_relationships WHERE term_taxonomy_id = '$term_taxonomy_id'");
	$cat = $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE term_id = '$term_id'");
	// Counter variable init
	$closed = 0;
	$wait = 0;
	$open = 0;
	
	// Count open, closed and waiting tickets
	foreach ($objetos as $objeto){	
		$contopen = $wpdb->get_var("SELECT COUNT(*)  FROM $wpdb->term_relationships WHERE object_id = '$objeto' and term_taxonomy_id = '$term_taxonomy_id_open'");
		$contwait = $wpdb->get_var("SELECT COUNT(*)  FROM $wpdb->term_relationships WHERE object_id = '$objeto' and term_taxonomy_id = '$term_taxonomy_id_wait'");
		$contclosed = $wpdb->get_var("SELECT COUNT(*)  FROM $wpdb->term_relationships WHERE object_id = '$objeto' and term_taxonomy_id = '$term_taxonomy_id_closed'");

		$open = $open + $contopen;
		$closed = $closed + $contclosed;
		$wait = $wait + $contwait;
	}
	// Print tickets
	echo '<a href="'.get_bloginfo('url').'/?cat='.$term_id.'">' . $cat . '</a>  ';
	echo '<font color="green">('.$open.')</font> &nbsp; <font color="grey">('.$wait.')</font> &nbsp; <font color="red">('.$closed.')</font>';

}

// Get BACH global options

/*function bach_options (){
		global $wpdb;
			$prioridades = get_option('bach_priorities'); 
			$priorities = get_cat_id($prioridades);
			$proyectos = get_option('bach_projects'); 
			$projects = get_cat_id($proyectos);
			$usuarios = get_option('bach_users'); 
			$users = get_cat_id($usuarios);
			$abierto = get_option('bach_open'); 
			$open = get_cat_id($abierto);
			$padre = $wpdb->get_var("SELECT parent from $wpdb->term_taxonomy WHERE term_id = '$open'");
			$cerrado = get_option('bach_closed'); 
			$closed = get_cat_id($cerrado);
			$esperas = get_option('bach_wait'); 
			$espera = get_cat_id($esperas);


}*/


	?>