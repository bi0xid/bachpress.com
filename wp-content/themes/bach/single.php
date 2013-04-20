<?php 
get_header( ); 

/* Cargamos las variables que utilizaremos */

			$abierto = get_option('bach_open'); 
			$open = get_cat_id($abierto);
			$cerrado = get_option('bach_closed'); 
			$closed = get_cat_id($cerrado);
			$esperas = get_option('bach_wait'); 
			$espera = get_cat_id($esperas);
			$prioridades = get_option('bach_priorities'); 
			$priorities = get_cat_id($prioridades);
			$proyectos = get_option('bach_projects'); 
			$proyecto = get_cat_id($proyectos);
			$bach_users = get_option('bach_users');
			$users = get_cat_id($bach_users);


/* Entramos en el loop */
if( have_posts( ) ) {
	$first_post = true;

	while( have_posts( ) ) {
		the_post( );
?>

<div id="postpage">
<div id="main">
	<h2>
		<?php echo prologue_get_avatar( get_the_author_ID( ), get_the_author_email( ), 48 ); ?>
		<?php 		// Show the new category images and get the user who must do the task.

			foreach((get_the_category()) as $category) { 
	
				if ( get_root_category($category) == $proyecto ) {
				    echo '<a href="'.get_bloginfo('url').'/?cat='.$category->cat_ID.'"><img src="'.trim(ereg_replace("</p>", "", category_description($category->cat_ID)),"<p>").'" alt="' . $category->cat_name . '" class="logo single" height="48px" width="48px" style="float:right;padding-top:50px;" /><a>'; 
				} // endif 				
				if ( get_root_category($category) == $users ) {
					$usuario = $category->slug;
					$nombre = $category->name;
					echo 'Tarea de '; echo $nombre;
				} //endif 
			} // end foreach
global $current_user;
get_currentuserinfo();			
			
?>
	</h2>
	
	<ul>
<li id="prologue-<?php the_ID(); ?>" class="recuadro<?php if ( in_category( $closed )): echo 'closed'; else: echo $usuario; endif; ?><?php if ( in_category( $espera ) ) { echo ' espera'; } ?>">
							<h3 class="titulillo"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>			
</h3>
						<div align="center"><?php include (TEMPLATEPATH . '/clases.php'); ?></div>	
			<h4>
				Publicado a las <?php the_time( "h:i:s a" ); ?> el día <?php the_time( "j F, Y" ); ?>
				| <?php edit_post_link( __( 'modificar' ) ); ?>
				<span class="meta">
					| 
					<br />
			Categorías: <?php the_category(' • ','') ?>
				</span>
			</h4>
			<?php the_content( __( '(More ...)' ) ); ?>



		<?php if (in_category ( $closed )) { ?>
			<?php $user = get_category_by_slug( $current_user->user_login );
			$cat_id = $user->term_id; ?>

	<form id="reopen" name="reopen" method="post" action="<?php bloginfo( 'url' ); ?>/transfer/" style="float:right;">
		<input type="hidden" name="action" value="reopen" />

            <span style="padding-left: 70px;">  <?php wp_dropdown_categories( array(
                'hide_empty' => 0,
                'name' => 'prioridad',
                'orderby' => 'name',
                'class' => 'catSelection',
                'heirarchical' => 1,
                'child_of' => $priorities,
                'show_option_none' => __('Prioridad'),
                //'selected' => ,  // how to select default cat by default?
                'tab_index' => 4
                )
            ); ?>
</span>

		<input type="hidden" value="<?php echo $post->ID; ?>" name="transfer"/>
		<input type="hidden" value="<?php echo $cat_id; ?>" name="usuario">
		<input id="submit" type="submit" value="Reabrir" style="margin-left: 30px;margin-bottom: 20px;" tabindex="7"/>
	</form>		


<?php } else { //not closed 

?>		<h4 style="float:right;"><form method="post" action="<?php bloginfo('url'); ?>/transfer/"><input type="hidden" value="<?php echo $post->ID; ?>" name="transfer"/><input type="hidden" value="close" name="action"><input type="submit" name="submit" value="Cerrar ticket"></form></h4>
<?php
	if ( $usuario != $current_user->user_login ){ 
			$user = get_category_by_slug( $current_user->user_login );
			$cat_id = $user->term_id;

	?>
		<h4 style="float:right;"><form method="post" action="<?php bloginfo('url'); ?>/transfer/"><input type="hidden" value="<?php echo $cat_id; ?>" name="usuario"><input type="hidden" value="<?php echo $post->ID; ?>" name="transfer"/><input type="hidden" value="claim" name="action"><input type="submit" name="submit" value="Reclamar"></form></h4>
<?php	}

	
 	// Categorías de usuario
 	$categories = get_categories( 'child_of=' . $users ); 
	foreach ( $categories as $category ) {
		if ( $category->slug != $current_user->user_login ) { 
			if ( $category->slug != $usuario ) { ?>
		<h4 style="float:right;"><form method="post" action="<?php bloginfo('url'); ?>/transfer/"><input type="hidden" value="<?php echo $post->ID; ?>" name="transfer"/><input type="hidden" value="<?php echo $category->cat_ID; ?>" name="usuario"><input type="hidden" value="transfer" name="action"><input type="submit" name="submit" value="<?php echo $category->name; ?>"></form></h4>
		
	<?php	}}}  ?>



		<?php if (in_category ( $espera )) { ?>
		<h4 style="float:right;"><form method="post" action="<?php bloginfo('url'); ?>/transfer/"><input type="hidden" value="<?php echo $post->ID; ?>" name="transfer"/><input type="hidden" value="reactivar" name="action"><input type="submit" name="submit" value="Reactivar"></form></h4>
		
<?php } ?>


		<?php if (in_category ( $open )) { ?>
		<h4 style="float:right;"><form method="post" action="<?php bloginfo('url'); ?>/transfer/"><input type="hidden" value="<?php echo $post->ID; ?>" name="transfer"/><input type="hidden" value="wait" name="action"><input type="submit" name="submit" value="Poner en espera"></form></h4>
<?php } ?>

	<form id="prioridad" name="prioridad" method="post" action="<?php bloginfo( 'url' ); ?>/transfer/" style="float:right;">
		<input type="hidden" name="action" value="prioridad" />

            <span style="padding-left: 70px;">  <?php wp_dropdown_categories( array(
                'hide_empty' => 0,
                'name' => 'prioridad',
                'orderby' => 'name',
                'class' => 'catSelection',
                'heirarchical' => 1,
                'child_of' => $priorities,
                'show_option_none' => __('Prioridad'),
                //'selected' => ,  // how to select default cat by default?
                'tab_index' => 4
                )
            ); ?>
</span>

		<input type="hidden" value="<?php echo $post->ID; ?>" name="transfer"/>
		<input id="submit" type="submit" value="Modificar prioridad" style="margin-left: 30px;margin-bottom: 20px;" tabindex="7"/>
	</form>		


<?php } //end in_category( $closed );
		?>

		

<br /><br /><br />

		</li>
	</ul>

	<br>
	<div class="navigation">
     	<div class="clear-page"></div>
     	<div class="alignleft"><span class="post-link-format"><?php previous_post_link('&laquo; %link') ?></span></div>
      	<div class="alignright"><span class="post-link-format"><?php next_post_link('%link &raquo;') ?></span></div>
      	<div class="clear-page"></div>
    </div>
    <br><br>
<?php

		comments_template( );
	} // while have_posts
} // if have_posts
?>
</div> <!-- // main -->
</div> <!-- // postpage -->
<?php
get_sidebar();
get_footer( );
