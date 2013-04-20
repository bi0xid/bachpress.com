<?php 	

// Variables needed

			$abierto = get_option('bach_open'); 
			$open = get_cat_id($abierto);
			$cerrado = get_option('bach_closed'); 
			$closed = get_cat_id($cerrado);
			$esperas = get_option('bach_wait'); 
			$espera = get_cat_id($esperas);


// Insert Post
	require_once dirname( __FILE__ ) . '/post-publish.php';
	get_header( ); 
?>

 <br /><div style="text-align:center;"><a href="<?php bloginfo('url'); ?>/wp-admin/"><?php _e('Administration','bach'); ?></a> || <a href="<?php bloginfo('url'); ?>/wp-admin/edit.php"><?php _e('Edit posts','bach'); ?></a> || <a href="<?php bloginfo('url'); ?>/wp-admin/post-new.php"><?php _e('New post', 'bach'); ?></a></div>

<?php
if( current_user_can( 'publish_posts' ) ) {
	require_once dirname( __FILE__ ) . '/post-form.php';	
}
?>

<div id="main">
	<h2><?php _e('Last tickets', 'bach'); ?> <a class="rss" href="<?php bloginfo( 'rss2_url' ); ?>"><acronym title="<?php _e('Really Simple Sindication','bach'); ?>"><?php _e('RSS','bach'); ?></acronym></a></h2><div style="float:right;margin-top: -40px;padding-right:60px;"><?php include (TEMPLATEPATH . '/searchform.php'); ?></div>

	<ul>

<?php

	// Pagination hack
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
	while( have_posts( ) ) {
		the_post( );

		$current_user_id = get_the_author_ID( );
		echo '<div style="padding-left:80px;">';
			echo prologue_get_avatar( $current_user_id, get_the_author_email( ), 48 );
		echo '</div>';

	global $wpdb; 
	//	$user_nicename = $wpdb->get_var("SELECT user_login from $wpdb->users WHERE ID = $post->post_author");


// foreach user category, if the post is in this category, get user id

	$class_users = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = $users ORDER BY term_id DESC");
	foreach ($class_users as $class_user){
		if (in_category($class_user)){
		$class_color = $class_user;
	}}


?>
<li id="prologue-<?php the_ID(); ?>" class="recuadro <?php echo 'color-'.$class_color; ?><?php if ( in_category( $espera ) ) { echo ' espera'; } ?>">



<?php
		

		// Show the new category images

			foreach((get_the_category()) as $category) { 
			$proyectos = get_option('bach_projects'); 
			$proyecto = get_cat_id($proyectos);
	
				if ( get_root_category($category) == $proyecto ) {
				    echo '<a href="'.get_bloginfo('url').'/?cat='.$category->cat_ID.'"><img src="'.trim(ereg_replace("</p>", "", category_description($category->cat_ID)),"<p>").'" alt="' . $category->cat_name . '" class="logo" height="48px" width="48px" style="float:right;" /><a>'; 
			} }




?>

				<h3 class="titulillo"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div align="center"><?php include (TEMPLATEPATH . '/clases.php'); ?></div>	
	<h4>
		<span class="meta">
			<?php _e('Categories','bach'); ?>: <?php the_category(' • ','') ?>
		</span>
	</h4>
	<div class="postcontent">
		<?php the_content( __( '(More ...)' ) ); ?>
	<div style="float:right; margin-top:-25px;">	<?php edit_post_link( __( 'modify' ) ); ?></div>
	</div> <!-- // postcontent -->

</li>
<ul style="list-style-type:none;">
<?php 
$comments = get_comments('order=ASC&post_id='.$post->ID);
  foreach($comments as $comm) : ?>


<li id="prologue-<?php the_ID(); ?>" class="comment-<?php echo strtolower($comm->user_id); ?>">
<?php echo nl2br($comm->comment_content); ?>
</li>

<?php endforeach; ?>
</ul>
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
