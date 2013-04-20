<?php /*
Template Name: Transferir
*/
?>
<?php get_header( ); ?>

<?php

// Obtenemos el ticket a poner en espera

$transfer = $_POST['transfer'];
$usuario = $_POST['usuario'];
$submit = $_POST['submit'];
$action = $_POST['action'];
$prioridad = $_POST['prioridad'];


//Variables necesarias para la operación
$bach_users = get_option('bach_users');
$users = get_cat_id($bach_users);
$categories = get_categories( 'child_of=' . $users ); 
$waiting = get_option('bach_wait'); 
$wait = get_cat_id($waiting);
$abierto = get_option('bach_open'); 
$open = get_cat_id($abierto);
$closed2 = get_option('bach_closed'); 
$closed = get_cat_id($closed2);
$priorities = get_option('bach_priorities'); 
$priority = get_cat_id($priorities);


if ( $action == 'prioridad' ) {
	
		$children = $wpdb->get_results( "SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = '$priority'");	
		foreach ($children as $child){
			$taxonomy_child = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$child->term_id'");
			$wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$transfer' AND term_taxonomy_id = '$taxonomy_child'");
		}
		
		$taxonomy_priority = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$prioridad'");
		$wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($transfer, $taxonomy_priority, 0)");

} // CHANGE PRIORITY


if ( $action == 'reopen' ) {

	$taxonomy_open = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$open'");
	$wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($transfer, $taxonomy_open, 0)");
	$taxonomy_wait = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$usuario'");
	$wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($transfer, $taxonomy_wait, 0)");
	$taxonomy_priority = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$prioridad'");
	$wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($transfer, $taxonomy_priority, 0)");
	
	$taxonomy_closed = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$closed'");
	$wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$transfer' AND term_taxonomy_id = '$taxonomy_closed'");


} // REOPEN

if ( $action == 'close' ) {

	$taxonomy_closed = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$closed'");
	$wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($transfer, $taxonomy_closed, 0)");
		// Priorities
	    $children = $wpdb->get_results( "SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = '$priority'");	
		foreach ($children as $child){
			$taxonomy_child = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$child->term_id'");
			$wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$transfer' AND term_taxonomy_id = '$taxonomy_child'");
		}
		// Users
		$children = $wpdb->get_results( "SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = '$users'");	
		foreach ($children as $child){
			$taxonomy_child = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$child->term_id'");
			$wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$transfer' AND term_taxonomy_id = '$taxonomy_child'");
		}
		// Status
		$taxonomy_wait = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$wait'");
		$taxonomy_open = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$open'");
		$wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$transfer' AND term_taxonomy_id = '$taxonomy_wait'");
		$wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$transfer' AND term_taxonomy_id = '$taxonomy_open'");


} // CLOSE

if ( $action == 'reactivar') {

	$taxonomy_open = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$open'");
	$wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($transfer, $taxonomy_open, 0)");
	$taxonomy_wait = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$wait'");
	$wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$transfer' AND term_taxonomy_id = '$taxonomy_wait'");

} // REACTIVAR


if ( $action == 'claim' ) {

	foreach ( $categories as $category ) {
		if ( $category->cat_ID != $usuario ) { 
			$taxonomy_open = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$category->cat_ID'");
 			$wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$transfer' AND term_taxonomy_id = '$taxonomy_open'");
 		}
 	}
	 $taxonomy_wait = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$usuario'");
	 $wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($transfer, $taxonomy_wait, 0)");
} // CLAIM


if ( $action == 'wait' ) {

 $taxonomy_wait = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$wait'");
 $wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($transfer, $taxonomy_wait, 0)");
 $taxonomy_open = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$open'");
 $wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$transfer' AND term_taxonomy_id = '$taxonomy_open'");

} // WAIT


if ( $action == 'transfer' ) {

 $taxonomy_wait = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$usuario'");
 $wpdb->query("INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($transfer, $taxonomy_wait, 0)");

	foreach ( $categories as $category ) {
		if ( $category->cat_ID != $usuario ) { 
			$taxonomy_open = $wpdb->get_var("SELECT term_taxonomy_id from $wpdb->term_taxonomy WHERE term_id = '$category->cat_ID'");
 			$wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id = '$transfer' AND term_taxonomy_id = '$taxonomy_open'");
 		}
 	}
} // TRANSFER 
?>


<form name="commentform" id="commentform" action="<?php echo get_option( 'siteurl' ); ?>/wp-comments-post.php" method="post">
<div class="form"><input type="hidden" id="comment" name="comment" value="<strong>Ticket <?php if ( $action == 'transfer' ) : ?> transferido a <?php echo $submit; ?> <?php elseif ( $action == 'claim' ): ?> reclamado<?php elseif ($action == 'wait'): ?> puesto en espera <?php elseif ( $action == 'reactivar' ): ?> reactivado <?php elseif ( $action == 'close' ): ?> cerrado <?php elseif ( $action == 'reopen' ): ?> reabierto <?php elseif ( $action == 'prioridad' ): ?> cambiado de prioridad <?php endif; ?> por <?php echo $user_identity; ?>. Timestamp: <?php echo date(DATE_RFC822); ?></strong>"></div>
<div><input type="hidden" name="especial" value="no_enviar"></div>
<div><input type="hidden" name="comment_post_ID" value="<?php echo $transfer; ?>" /></div>
</form>

<?php


get_footer( );

?>
 <SCRIPT>
document.commentform.submit();

</SCRIPT> 