<?php
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


function bach_init(){
			$abierto = get_option('bach_open'); 
			$open = get_cat_id($abierto);
			$cerrado = get_option('bach_closed'); 
			$closed = get_cat_id($cerrado);
			$esperas = get_option('bach_wait'); 
			$espera = get_cat_id($esperas);
			$email_users = get_option('bach_emails');
			$bach_users = get_option('bach_users');
			$prioridades = get_option('bach_priorities'); 
			$priorities = get_cat_id($prioridades);
			$proyectos = get_option('bach_projects'); 
			$proyecto = get_cat_id($proyectos);
			$users = get_cat_id($bach_users);

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
<a class="rss" href="<?php echo get_tag_feed_link( $tag->term_id ); ?>">RSS</a>&nbsp;<a href="<?php echo $tag_link; ?>"><?php echo ("<img alt='' src='http://interno.mecus.es/wp-content/themes/prologue/i/".$tag->slug.".jpg' class='avatar avatar-48' height='48px' width='48px' style='float:right;'/>") ?>
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


/* MENÚ DE ADMINISTRACIÓN */
/* MENÚ DE ADMINISTRACIÓN */
/* MENÚ DE ADMINISTRACIÓN */
/* MENÚ DE ADMINISTRACIÓN */
/* MENÚ DE ADMINISTRACIÓN */
/* MENÚ DE ADMINISTRACIÓN */
/* MENÚ DE ADMINISTRACIÓN */



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
$categories_tmp = array_unshift($bach_categories, "Seleccione una categoría:");

// THESE ARE THE DIFFERENT FIELDS

$options = array (

				array(	"name" => "Opciones generales",
						"type" => "heading"),


				array(	"name" => "Logo",
						"desc" => "Pegue la URL completa de su imagen de logo para reemplazar el logo por defecto.<br />Si el campo está vacío, se utilizará un logo por defecto.",
						"id" => $shortname."_logo",
						"std" => "",
						"type" => "text"),												    

				array(	"name" => "Asignación de categorías",
						"type" => "heading"),
						
				array( 	"name" => "Categoría Usuarios",
					   	"desc" => "Seleccione la categoría padre que contiene a los usuarios.",
						"id" => $shortname."_users",
						"std" => "Seleccione una categoría:",
						"type" => "select",
						"options" => $bach_categories),						

				array( 	"name" => "Categoría Proyectos",
					   	"desc" => "Seleccione la categoría padre que contiene a los Proyectos.",
						"id" => $shortname."_projects",
						"std" => "Seleccione una categoría:",
						"type" => "select",
						"options" => $bach_categories),						

				array( 	"name" => "Categoría Prioridades",
					   	"desc" => "Seleccione la categoría padre que contiene a los Prioridades.",
						"id" => $shortname."_priorities",
						"std" => "Seleccione una categoría:",
						"type" => "select",
						"options" => $bach_categories),						

				array( 	"name" => "Categoría Abierto",
					   	"desc" => "Seleccione la categoría de Abierto.",
						"id" => $shortname."_open",
						"std" => "Seleccione una categoría:",
						"type" => "select",
						"options" => $bach_categories),						

				array( 	"name" => "Categoría En Espera",
					   	"desc" => "Seleccione la categoría de En Espera.",
						"id" => $shortname."_wait",
						"std" => "Seleccione una categoría:",
						"type" => "select",
						"options" => $bach_categories),						

				array( 	"name" => "Categoría Cerrado",
					   	"desc" => "Seleccione la categoría de Cerrado.",
						"id" => $shortname."_closed",
						"std" => "Seleccione una categoría:",
						"type" => "select",
						"options" => $bach_categories),
						
				array(	"name" => "Emails de envío",
						"desc" => "Escriba aquí los correos a los que se notificarán los cambios del sistema. <b>En esta versión la notificación es global para todos estos usuarios.</b>",
						"id" => $shortname."_emails",
						"std" => "",
						"type" => "text"),												    						
																														
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

<?php		
	$bach_users = get_option('bach_users');
	$users = get_cat_id($bach_users);
 	$categories = get_categories( 'child_of=' . $users ); 
?>

			<h3 class="title">Usuarios (todavía no en uso, servirá para especificar los colores de usuario)</h3>
				<table class="maintable">
				<?php foreach ( $categories as $category ) { ?>
					<tr class="mainrow">


						<td class="titledesc"><?php echo $category->name; ?></td>
						<td class="forminp">
		        			<input name="<?php echo $category->cat_ID; ?>" id="<?php echo $category->cat_ID; ?>" type="text" value="<?php get_option($category->name); ?>" />
							<?php add_option($category->name, $value); ?>
						</td>
					</tr>
					<?php } //end foreach ?> 

				</table> 



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


// Funciones derivadas
/*function status( $category_status_id) {
	global $wpdb;
$abiertos = $wpdb->get_results("SELECT * FROM wp_term_relationships WHERE term_taxonomy_id = '$category_status_id'");
// Counter variable init
$close = 0;
$wait = 0;
$open = 0;
foreach ($abiertos as $abierto){
	$theid = $abierto->object_id;
	$cerrados = $wpdb->get_results("SELECT * FROM wp_term_relationships WHERE object_id = '$theid'");
	foreach ($cerrados as $cerrado){
		if ($cerrado->term_taxonomy_id == 66){
			$close = $close + 1;
		}
		if ($cerrado->term_taxonomy_id == 67){
			$wait = $wait + 1;
		}
		if ($cerrado->term_taxonomy_id == 68){
			$open = $open + 1;
		}
	}
}
	echo '<font color="green">('.$open.')</font> <font color="grey">('.$wait.')</font><font color="red"> ('.$close.')</font>';

}
*/

function status( $term_id ){
	// Dabatase inicialization
	global $wpdb;
			$abierto = get_option('bach_open'); 
			$catopen = get_cat_id($abierto);
			$cerrado = get_option('bach_closed'); 
			$catclosed = get_cat_id($cerrado);
			$esperas = get_option('bach_wait'); 
			$catespera = get_cat_id($esperas);

	// Get object_id from category $term_id
	$term_taxonomy_id = $wpdb->get_var("SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE term_id = '$term_id'");
	$objetos = $wpdb->get_col("SELECT object_id  FROM wp_term_relationships WHERE term_taxonomy_id = '$term_taxonomy_id'");
	$cat = $wpdb->get_var("SELECT name FROM wp_terms WHERE term_id = '$term_id'");
	// Counter variable init
	$closed = 0;
	$wait = 0;
	$open = 0;
	
	// Count open, closed and waiting tickets
	foreach ($objetos as $objeto){
		$contopen = $wpdb->get_var("SELECT COUNT(*)  FROM wp_term_relationships WHERE object_id = '$objeto' and term_taxonomy_id = '$catopen'");
		$contwait = $wpdb->get_var("SELECT COUNT(*)  FROM wp_term_relationships WHERE object_id = '$objeto' and term_taxonomy_id = '$catespera'");
		$contclosed = $wpdb->get_var("SELECT COUNT(*)  FROM wp_term_relationships WHERE object_id = '$objeto' and term_taxonomy_id = '$catclosed'");

		$open = $open + $contopen;
		$closed = $closed + $contclosed;
		$wait = $wait + $contwait;
	}
	// Print tickets
	echo '<a href="http://interno.mecus.es/?cat='.$term_id.'">' . $cat . '</a>  ';
	echo '<font color="green">('.$open.')</font> &nbsp; <font color="grey">('.$wait.')</font> &nbsp; <font color="red">('.$closed.')</font>';

}


/* Creamos la función compleja que nos servirá para enviar los correos de notificación */

function wp_notify_mail($title,$post_content,$post_category,$assigned){

	global $current_user;

		$user			= get_userdata( $current_user->ID );
		$first_name		= attribute_escape( $user->first_name );

		$abierto_cat = get_option('bach_open');
		$abierto = get_cat_ID($abierto_cat);

		$cerrado_cat = get_option('bach_closed');
		$cerrado = get_cat_ID($cerrado_cat);

		$wait_cat = get_option('bach_wait');
		$wait = get_cat_ID($wait_cat);



	foreach ($post_category as $category){
		if ($category == $abierto){
			$ticket = 'abierto';
		}
		if ($category == $cerrado){
			$ticket = 'cerrado';
		}
		if ($category == $espera){
			$ticket = 'puesto en espera';
		}
		$string = get_cat_name($category);
		$stringtocat = '[' . $string . ']';
		$categorias .= $stringtocat . ' ';
	}

	$notify_message .= nl2br($post_content) . '<br /><br />';
	$notify_message .= 'Ticket ' . $ticket . ' por ' . $first_name . '<br /><br />';
	$notify_message .= 'Enlace: ' . get_bloginfo("url") . '/' . $assigned .  '/';





		$from = "From: Bach Mecus <bach@mecus.es>";
		$message_headers = "MIME-Version: 1.0\n" . "$from\n" . "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";

	$subject = $categorias . $title;

	$emails = get_option('bach_emails');
	$emails_array = explode(",", $emails);
		
		foreach ($emails_array as $email) {
			wp_mail($email, $subject, $notify_message, $message_headers);
		}

}

function wp_notify_comments($comment_id) {
	global $wpdb;
	
	$comment = $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_ID='$comment_id' LIMIT 1");
	$post = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID='$comment->comment_post_ID' LIMIT 1");
	$term_taxonomy = $wpdb->get_col("SELECT term_taxonomy_id FROM $wpdb->term_relationships WHERE object_id='$post->ID' ORDER BY term_taxonomy_id DESC");
	foreach ($term_taxonomy as $term_tax){
		$term_id = $wpdb->get_var("SELECT term_id FROM $wpdb->term_taxonomy WHERE term_taxonomy_id = '$term_tax'");
		$name = $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE term_id = '$term_id'");
		$categorias_cat .= '[' . $name . '] ';
	}
				
			$notify_message .= '<strong>Ticket principal: </strong><br/><br />';
			$notify_message .= nl2br($post->post_content) . '<br /><hr>';
			$notify_message .= sprintf( __('<strong>Comentario nuevo de %s:</strong>'), $comment->comment_author ) ;
			$notify_message .= "<br /><br />" . nl2br($comment->comment_content) . "<br /><br />";
			$notify_message .= sprintf( __('Enlace:  %1$s'), get_permalink($comment->comment_post_ID));
	

	$title = $post->post_title;
	$subject = $categorias_cat . $title;

		$from = "From: Bach Mecus <bach@mecus.es>";
		$message_headers = "MIME-Version: 1.0\n" . "$from\n" . "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";
		
	$emails = get_option('bach_emails');
	$emails_array = explode(",", $emails);
		
		foreach ($emails_array as $email) {
			wp_mail($email, $subject, $notify_message, $message_headers);
		}

}


/* Creamos ahora la función que nos notificará en los comentarios y al publicar */

//add_action('wp_insert_post', 'wp_notify_mail'); // Esta función está llamada en index.php
add_action('comment_post', 'wp_notify_comments');



	?>