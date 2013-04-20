<?php 

/*************************
	HEADER + VARIABLES
*************************/
get_header( ); 

			$abierto = get_option('bach_open'); 
			$open = get_cat_id($abierto);
			$cerrado = get_option('bach_closed'); 
			$closed = get_cat_id($cerrado);
			$esperas = get_option('bach_wait'); 
			$espera = get_cat_id($esperas);
			$usuarios = get_option('bach_users'); 
			$users = get_cat_id($usuarios);

		global $current_user;
    	  get_currentuserinfo();
		$wp_categories = $wpdb->get_results("SELECT * FROM $wpdb->terms");

/*************************
	GET THE SINGLE LOOP
*************************/


if( have_posts( ) ) {
	$first_post = true;

	while( have_posts( ) ) {
		the_post( );

		$email_md5		= md5( get_the_author_email( ) );
		$default_img	= urlencode( 'http://use.perl.org/images/pix.gif' );
?>

<div id="postpage">
<div id="main">
	<h2>
		<?php echo prologue_get_avatar( get_the_author_ID( ), get_the_author_email( ), 48 ); ?>
		<?php 		// Show the new category images

			foreach((get_the_category()) as $category) { 
			$proyectos = get_option('bach_projects'); 
			$proyecto = get_cat_id($proyectos);
	
				if ( get_root_category($category) == $proyecto ) {
				    echo '<a href="'.get_bloginfo('url').'/?cat='.$category->cat_ID.'"><img src="'.trim(ereg_replace("</p>", "", category_description($category->cat_ID)),"<p>").'" alt="' . $category->cat_name . '" class="logo" height="48px" width="48px" style="float:right;" /><a>'; 
			} }
?>
		<?php the_author_posts_link( ); ?>
	</h2>
	<?php 
	//	$IDclass = $wpdb->get_col("SELECT ID from $wpdb->users ORDER BY ID");


// foreach user category, if the post is in this category, get user id
 
	$class_users = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = $users ORDER BY term_id DESC");
	foreach ($class_users as $class_user){
		if (in_category($class_user)){
		$class_color = $class_user;
	}}
	 ?>
	<ul>
<li id="prologue-<?php the_ID(); ?>" class="recuadro <?php echo 'color-'.$class_color; ?><?php if ( in_category( $espera ) ) { echo ' espera'; } ?>">
							<h3 class="titulillo"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>			
</h3>
						<div align="center"><?php include (TEMPLATEPATH . '/clases.php'); ?></div>	
			<h4>
				<?php _e('Published at','bach'); ?> <?php the_time( "h:i:s a" ); ?> <?php _e('in','bach'); ?> <?php the_time( "j F, Y" ); ?>
				| <?php edit_post_link( __( 'modify' ) ); ?> <?php echo $user_ID; ?>
				<span class="meta">
					<?php comments_popup_link( __( 'no comments' ), __( 'one comment' ), __( '% comments' ) ); ?> |
					<br />
			<?php _e('Categories','bach'); ?>: <?php the_category(' • ','') ?>
				</span>
			</h4>
			<?php the_content( __( '(More ...)' ) ); ?>

<?php 
	// transform into categories
	$prior_user = get_categories('hide_empty=0&child_of='.$users);

	// get current user 
	foreach ($wp_categories as $wp_category){
		if ($current_user->user_login == $wp_category->slug) $curr_user = $wp_category->term_id;	
	}

	// get user of the task
	foreach ($prior_user as $p_user){
		foreach((get_the_category()) as $category) { 
			if ($p_user->category_nicename == $category->category_nicename){ $lastuser = $category->cat_ID;}
		}
	}

	// If ticket is not yours, you can claim it. 
	if ($curr_user != $lastuser){

?>
		<input type="button" onclick="claim_ticket(<?php echo $post->ID; ?>,<?php echo $curr_user; ?>,<?php echo $lastuser; ?>);" value="<?php _e('Claim','bach'); ?>">
<?php 
}

	// If ticket is yours, you can transfer it
	
		if ($curr_user == $lastuser){
	
			$cat_usuarios = get_categories('hide_empty=0&child_of='.$users);			
			foreach($cat_usuarios as $category) { 
				if ($category->cat_ID != $curr_user){ ?>
					<input type="button" onclick="transfer_ticket(<?php echo $post->ID; ?>,<?php echo $lastuser; ?>,<?php echo $category->cat_ID; ?>);" value="<?php _e('Transfer to '.$category->category_nicename,'bach'); ?>">
		<?php	}
			}

		}
	
	// Also, you can transfer tickets that are not yours (in version 1.1)


?>
		<?php if (in_category ( $espera )) { ?>
		<input type="button" onclick="reactivate_ticket(<?php echo $post->ID; ?>);" value="<?php _e('Reactivate','bach'); ?>">
<?php } ?>

		<?php if (in_category ( $closed )) { ?>
		<input type="button" onclick="reopen_ticket(<?php echo $post->ID; ?>);" value="<?php _e('Re-open','bach'); ?>">
<?php } ?>

		<?php if (in_category ( $open )) { ?>
		<input type="button" onclick="wait_ticket(<?php echo $post->ID; ?>);" value="<?php _e('Wait','bach'); ?>">
<?php } ?>

		<?php if (in_category ( $espera ) || (in_category ( $open ))) { ?>
		<input type="button" onclick="close_ticket(<?php echo $post->ID; ?>);" value="<?php _e('Close','bach'); ?>">
<?php } ?>




		</li>
	</ul>

<?php

		comments_template( );
	} // while have_posts
} // if have_posts
?>
</div> <!-- // main -->
</div> <!-- // postpage -->
<?php
get_footer( );
