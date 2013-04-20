<?php 
/* 
Template name: Actions
*/

/*************************
	HEADER + VARIABLES
*************************/

// Header

	get_header(); 

// Get options
			global $wpdb;
			$closed2 = get_option('bach_closed'); 
			$closed = get_cat_id($closed2);
			$espera = get_option('bach_wait'); 
			$wait = get_cat_id($espera);
			$abierto = get_option('bach_open'); 
			$open = get_cat_id($abierto);
	 		$priorities = get_option('bach_priorities'); 
			$priority = get_cat_id($priorities);
			$taxonomy_wait = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$wait'");
			$taxonomy_open = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$open'");
 			$taxonomy_closed = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$closed'");



// Get variables

 $cerrado = $_GET['cerrado'];
 $reab = $_GET['reab'];
 $reac = $_GET['reac'];
 $en_espera = $_GET['en_espera'];
 $claim = $_GET['claim'];
 $transfer = $_GET['transfer'];
 $currentuser = $_GET['curruser'];
 $lastuser = $_GET['lastuser'];
 $newuser = $_GET['newuser'];


/*************************
	CHANGING STATUS
*************************/

	// Closing a task
	if ($cerrado != ''){
		// Adding status 'closed'
			
 			$wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($cerrado, $taxonomy_closed, 0)");


		// removing priority

		    $children = $wpdb->get_results( "SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = '$priority'");	
			foreach ($children as $child){
				$taxonomy_child = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$child->term_id'");
				$wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$cerrado' AND term_taxonomy_id = '$taxonomy_child'");
			}

		// removing status
		
			$wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$cerrado' AND term_taxonomy_id = '$taxonomy_wait'");
			$wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$cerrado' AND term_taxonomy_id = '$taxonomy_open'");
	
		// Get notification ID

			$notification = $cerrado;
			$option_ticket = 'closed';
	}

	// Reopening a task
	if ($reab != ''){		
		// Adding status 'open'
		 $wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($reab, $taxonomy_open, 0)");
		
		// Removing status 'closed'
		 $wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$reab' AND term_taxonomy_id = '$taxonomy_closed'");
	
		// Get notification ID
			$notification = $reab;
			$option_ticket = 'reopened';
	}

	// Reactivating a waiting task
	if ($reac != '') {
		// Adding status 'open'
		 $wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($reac, $taxonomy_open, 0)");

		// removing status 'wait'
		 $wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$reac' AND term_taxonomy_id = '$taxonomy_wait'");
		 
		// Get notification ID
			$notification = $reac;
			$option_ticket = 'reactivated';
	}

	// Put a task waiting
	if ($en_espera != ''){
		// Adding status 'wait'
		 $wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($en_espera, $taxonomy_wait, 0)");

		// Removing status 'open'
		 $wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$en_espera' AND term_taxonomy_id = '$taxonomy_open'");
		
		// Get notification ID
			$notification = $en_espera;
			$option_ticket = 'waiting';
	}

	// Claim a task
	if ($claim !='' ){
		// Removing prior user
		 $taxonomy_user = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = $lastuser");
		 $wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$claim' AND term_taxonomy_id = '$taxonomy_user'");
		 
		// Adding new user
		 $taxonomy_user = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = $currentuser");
 		 $wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($claim, $taxonomy_user, 0)");
 		 
 		// Get notification ID
 			$notification = $claim;
 			$option_ticket = 'claimed';
 	}

	// Transfer a task
	if ($transfer !='' ){
		// Removing prior user
		 $taxonomy_user = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = $currentuser");
		 $wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$transfer' AND term_taxonomy_id = '$taxonomy_user'");
		 
		// Adding new user
		 $taxonomy_user = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = $newuser");
 		 $wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($transfer, $taxonomy_user, 0)");
 		 
 		// Get notification ID
 			$notification = $transfer;
 			$option_ticket = 'transferred';
 	}


/*************************
  SENDING NOTIFICATIONS
*************************/

	$post = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID='$notification' LIMIT 1");
	$author = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE ID='$post->post_author' LIMIT 1");
	$blogname = get_option('blogname');
	$comment_author_domain = @gethostbyaddr($comment->comment_author_IP);
	
	$queryposts = 'p='.$notification;
	query_posts($queryposts);
		$notify_message .= '<strong>Categories: </strong>';
		$categories = get_the_category();
		foreach((get_the_category()) as $category) { 
 	   $notify_message .= $category->cat_name . ' | '; 
	} 
		$notify_message .= '<br />';

			$notify_message .= sprintf( __('<strong>Owned by %s</strong>'), $author->display_name ) . "<br /><br />";
			$contenido = nl2br($post->post_content);
			$notify_message .= sprintf( __('<p style="border: 1px solid gray; padding: 8px;">%s</p>'), $contenido ) . "<br /><br /><hr><br />";

			$notify_message .= '<strong>Ticket '.$option_ticket.'</strong><br />';


		$subject = sprintf( __('BACH: %s :: "%2$s"'), $categories[0]->name, $post->post_title );
			$from = "From: Bach Mecus <bach@mecus.es>";
			$message_headers = "MIME-Version: 1.0\n"
				. "$from\n"
				. "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";
				
$email = 'raven@mecus.es,bach@zemos98.org';

wp_mail($email, $subject, $notify_message, $message_headers);


/*************************
  SEND COMMENT + FOOTER
*************************/


// Send Comment

?>

<form name="commentform" id="commentform" action="<?php echo get_option( 'siteurl' ); ?>/wp-comments-post.php" method="post">

<br /><br />

<?php _e('Ticket','bach'); ?> <?php echo $option_ticket; ?>

<br /><br />

<?php _e('Redirecting...','bach'); ?>
<div class="form"><input type="hidden" id="comment" name="comment" value="<strong>Ticket <?php echo $option_ticket; ?> by <?php echo $user_identity; ?>. Timestamp: <?php echo date(DATE_RFC822); ?></strong>"></div>
<div><input type="hidden" name="especial" value="no_enviar"></div>
<div><input type="hidden" name="cerrar" value="cerrar"></div>
<div><input type="hidden" name="comment_post_ID" value="<?php echo $notification; ?>" /></div>

</form>

</div> <!-- // main -->

<?php

// Footer and submission

get_footer( );

?>
 <SCRIPT>
document.commentform.submit();

</SCRIPT> 