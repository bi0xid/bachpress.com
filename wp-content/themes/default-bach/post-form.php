<?php
		// Get ID's to work with the post form
	
		$user			= get_userdata( $current_user->ID );
		$first_name		= attribute_escape( $user->first_name );

			$prioridades = get_option('bach_priorities'); 
			$priorities = get_cat_id($prioridades);
			$proyectos = get_option('bach_projects'); 
			$projects = get_cat_id($proyectos);
			$usuarios = get_option('bach_users'); 
			$users = get_cat_id($usuarios);


// POST FORM
?>
<div align="center">
<div id="postbox">
	<form id="new_post" name="new_post" method="post" action="<?php bloginfo( 'url' ); ?>/">
		<input type="hidden" name="action" value="post" />
		<?php wp_nonce_field( 'new-post' ); ?>

		<?php echo prologue_get_avatar( $user->ID, $user->user_email, 48 ); ?>

            <input type="text" name="postTitle" id="postTitle" tabindex="1" size="60" />
<br />
		<textarea name="posttext" id="posttext" rows="3" cols="60" tabindex="2" ></textarea>
			
			<label for="cats" id="tags"><?php _e('Categories', 'bach'); ?></label>
            <span style="padding-left: 70px;">  <?php wp_dropdown_categories( array(
                'hide_empty' => 0,
                'name' => 'prioridad',
                'orderby' => 'name',
                'class' => 'catSelection',
                'heirarchical' => 1,
                'child_of' => $priorities,
                'show_option_none' => __('Priority','bach'),
                //'selected' => ,  // how to select default cat by default?
                'tab_index' => 3
                )
            ); ?>
            <?php wp_dropdown_categories( array(
                'hide_empty' => 0,
                'name' => 'proyecto',
                'orderby' => 'name',
                'class' => 'catSelection',
                'heirarchical' => 1,
                'child_of' => $projects,
                'show_option_none' => __('Project','bach'),
                //'selected' => ,  // how to select default cat by default?
                'tab_index' => 4
                )
            ); ?>
            <?php wp_dropdown_categories( array(
                'hide_empty' => 0,
                'name' => 'usuario',
                'orderby' => 'name',
                'class' => 'catSelection',
                'heirarchical' => 1,
                'child_of' => $users,
                'show_option_none' => __('User','bach'),
                //'selected' => ,  // how to select default cat by default?
                'tab_index' => 5
                )
            ); ?>

		<input id="submit" type="submit" value="<?php _e('Publish','bach'); ?>" style="margin-left: 30px;margin-bottom: 20px;" tabindex="7" />
	</form>
</div> <!-- // postbox -->
</div><!-- // center -->
<?php echo $post_category; ?>

