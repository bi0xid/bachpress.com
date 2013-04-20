<?php 

	// YOU DON'T NEED TO EDIT THIS PAGE

	if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'post' ) {
	if ( ! is_user_logged_in() )
		auth_redirect();

	if( !current_user_can( 'publish_posts' ) ) {
		wp_redirect( get_bloginfo( 'url' ) . '/' );
		exit;
	}

	check_admin_referer( 'new-post' );  // This executes the post form. Status = open by default
	$abierto = get_option('bach_open'); 
	$open = get_cat_id($abierto);
   	$user_id		= $current_user->user_id;
	$post_content	= $_POST['posttext'];
	$tags			= $_POST['tags'];
	$post_title    = strip_tags($_POST['postTitle']);
	$prioridad = $_POST['prioridad'];
	$proyecto = $_POST['proyecto'];
	$usuario = $_POST['usuario']; 
	$post_category = array($prioridad,$proyecto,$usuario,$open);
	
	global $wpdb;
		$proyecto_nombre = $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE term_id = '$proyecto'");

	$title = $post_title . ' ('. $proyecto_nombre . ')';



		// if no category was selected, unset it & default will be used // Code for Bach 1.1
 /*       if ($post_category == '-1') {
            unset($post_category);
        } elseif ( isset($post_category) ) {
           $post_category = array($post_category);
        }
*/


	$post_id = wp_insert_post( array(	// Inserts the post with our options
		'post_author'	=> $user_id,
		'post_title'	=> $title,
		'post_content'	=> $post_content,
		'post_category' => $post_category,
		'tags_input'	=> $tags,
		'post_status'	=> 'publish'
	) );

	wp_redirect( get_bloginfo( 'url' ) . '/' );
	exit;
}


?>