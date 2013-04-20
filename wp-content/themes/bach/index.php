<?php 	// get ID's

bach_init();

			$abierto = get_option('bach_open'); 
			$open = get_cat_id($abierto);
			$cerrado = get_option('bach_closed'); 
			$closed = get_cat_id($cerrado);
			$esperas = get_option('bach_wait'); 
			$espera = get_cat_id($esperas);

if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'post' ) {
	if ( ! is_user_logged_in() )
		auth_redirect();

	if( !current_user_can( 'publish_posts' ) ) {
		wp_redirect( get_bloginfo( 'url' ) . '/' );
		exit;
	}

	check_admin_referer( 'new-post' );  // This executes the post form. Status = open by default

	$user_id		= $current_user->user_id;
	$post_content	= $_POST['posttext'];
	$tags			= $_POST['tags'];
	$post_title    = strip_tags($_POST['postTitle']);
	$estado = $open;
	$prioridad = $_POST['prioridad'];
	$proyecto = $_POST['proyecto'];
	$usuario = $_POST['usuario']; 
	$post_category = array($estado,$prioridad,$proyecto,$usuario);
	global $wpdb;
		$proyecto_nombre = $wpdb->get_var("SELECT name FROM wp_terms WHERE term_id = '$proyecto'");


function generatePassword($length=9) {
	$consonants = 'BDGHJLMNPQRSTVWXZ';
	$numbers .= '123456789';
	$password = '';
	for ($i = 0; $i < 3; $i++) {
			$password .= $consonants[(rand() % strlen($consonants))];
		}
	$password .= '-';
	for ($i = 3; $i < $length; $i++) {
			$password .= $numbers[(rand() % strlen($numbers))];
		}
	
	return $password;
}
 
 	$assigned = generatepassword(7);

	$title = '#' . $assigned . ' ' . $post_title . ' ('. $proyecto_nombre . ')';



		// if no category was selected, unset it & default will be used
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
		'post_name'		=> $assigned,
		'tags_input'	=> $tags,
		'post_status'	=> 'publish'
	) );

	wp_notify_mail($title,$post_content,$post_category,$assigned);
	wp_redirect( get_bloginfo( 'url' ) . '/' );
	exit;
}

get_header( ); 

?>

 <br /><div style="text-align:center;"><a href="<?php bloginfo('url'); ?>/wp-admin/">Administración</a> || <a href="<?php bloginfo('url'); ?>/wp-admin/edit.php">Edición de posts</a> || <a href="<?php bloginfo('url'); ?>/wp-admin/post-new.php">Nuevo artículo</a></div>

<?php
if( current_user_can( 'publish_posts' ) ) {
	require_once dirname( __FILE__ ) . '/post-form.php';
}
?>

<div id="main">
	<div style="float:right;margin-top: -40px;padding-right:60px;"><?php include (TEMPLATEPATH . '/searchform.php'); ?></div>

	<ul>

<?php
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;


if( have_posts( ) ) {
	?>	
	<div class="navigation">
    <?php
    if(function_exists('pagination'))
        pagination(2,array("&#8592; m&#225;s recientes"," m&#225;s antiguas &#8594;"));
    ?>
		</div>
<? 	
	$previous_user_id = 0;
	while( have_posts( ) ) {
		the_post( );

			foreach((get_the_category()) as $category) { 
	
				if ( get_root_category($category) == $users ) {
					$usuario = $category->slug;
				} //endif 
			} // end foreach

?>
<?php 

// if ( in_category( $closed ) && !is_single() ) continue; 
		// Don't show the avatar if the previous post was by the same user
		$current_user_id = get_the_author_ID( );
		//if( $previous_user_id !== $current_user_id ) {
		echo '<div style="padding-left:80px;">';
			echo prologue_get_avatar( $current_user_id, get_the_author_email( ), 48 );
		echo '</div>';
		//}
		$previous_user_id = $current_user_id;


?>
<li id="prologue-<?php the_ID(); ?>" class="recuadro<?php if ( in_category( $closed )): echo 'closed'; else: echo $usuario; endif; ?><?php if ( in_category( $espera ) ) { echo ' espera'; } ?>">



<?php
		

		// Show the new category images
			$proyectos = get_option('bach_projects'); 
			$proyecto = get_cat_id($proyectos);

			foreach((get_the_category()) as $category) { 
	
				if ( get_root_category($category) == $proyecto ) {
				    echo '<a href="'.get_bloginfo('url').'/?cat='.$category->cat_ID.'"><img src="'.trim(ereg_replace("</p>", "", category_description($category->cat_ID)),"<p>").'" alt="' . $category->cat_name . '" class="logo" height="48px" width="48px" style="float:right;" /><a>'; 
			} }




?>

				<h3 class="titulillo"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div align="center"><?php include (TEMPLATEPATH . '/clases.php'); ?></div>	
	<h4>
		<span class="meta">
			Categorías: <?php the_category(' • ','') ?>
		</span>
	</h4>
	<div class="postcontent">
		<?php the_content( __( '(More ...)' ) ); ?>
	<div style="float:right; margin-top:-25px;">	<?php edit_post_link( __( 'modificar' ) ); ?></div>
	</div> <!-- // postcontent -->

</li>


<!-- Código para mostrar u ocultar los comentarios (javascript) -->


<div id="show-comments" style="text-align:center;margin-bottom:15px;font-size:12px; background-color:#eee;"><a href="javascript:void(0);" onclick="js_toggle('comments-<?php the_ID(); ?>');"><?php _e( 'Mostrar comentarios', 'bach' ); ?> (<?php comments_number('0','1','%'); ?>)</p></a></div>

<div id="comments-<?php the_ID(); ?>" style="display:none;">	
<ul style="list-style-type:none;">

<?php 
$comments = get_comments('order=ASC&post_id='.$post->ID);
  foreach($comments as $comm) : ?>

<li id="prologue-<?php the_ID(); ?>" class="comment<?php echo strtolower($comm->comment_author); ?>">
<?php echo nl2br($comm->comment_content); ?>
</li>
<?php endforeach; ?>
</ul>
</div> <!-- fin de comentarios -->



<?php
	
	} // while have_posts
	?>	<div class="navigation">
    <?php
    if(function_exists('pagination'))
        pagination(2,array("&#8592; m&#225;s recientes"," m&#225;s antiguas &#8594;"));
    ?>
		</div>				

<?

} // if have_posts
?>

	</ul>

</div> <!-- // main -->

<?php

get_sidebar();
get_footer( );

?>

