
<?php
?>

<div id="sidebar">


<?

// Projects list idea

global $wpdb;

// Functions

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

global $blog_id;

// Prioridad
	echo '<div style="padding-bottom:10px;padding-top:10px;font-weight:bold;font-size:2em;"><strong>Priority</strong></div>';
	$categories = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = $priorities ORDER BY term_id DESC");
	foreach ($categories as $cat) {
		$estatus = status($cat);
		$berre = '<br />';
		echo $estatus;
		echo $berre;
		$isparent = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = '$cat'");
		echo '<div style="padding-left:10px;">';				
		foreach ($isparent as $parent) {
			echo '<div style="padding-left:15px;background:url(i/flechita.png) no-repeat;">';
			$estatus = status($parent);
			$berre = '<br />';
			echo $estatus;
			echo $berre;
			echo '</div>';
			$categories = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = '$parent'");
			echo '<div style="padding-left:20px;">';			
			foreach ($categories as $cat) {
				echo '<div style="padding-left:15px;background:url(i/flechita.png) no-repeat;">';
				$estatus = status($cat);
				$berre = '<br />';
				echo $estatus;
				echo $berre;
				echo '</div>';
			}
			echo '</div>';
		}
		echo '</div>';
	}
	
	
// 	Estado
	echo '<div style="padding-bottom:10px;padding-top:10px;font-weight:bold;font-size:2em;"><strong>Status</strong></div>';
	$categories = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = $padre");
	foreach ($categories as $cat) {
		$estatus = status($cat);
		$berre = '<br />';
		echo $estatus;
		echo $berre;
	}

// Usuarios
	echo '<div style="padding-bottom:10px;padding-top:10px;font-weight:bold;font-size:2em;"><strong>Users</strong></div>';
	$categories = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = $users");
	foreach ($categories as $cat) {
		$estatus = status($cat);
		$berre = '<br />';
		echo $estatus;
		echo $berre;
		$isparent = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = '$cat'");
		echo '<div style="padding-left:10px;">';				
		foreach ($isparent as $parent) {
			echo '<div style="padding-left:15px;background:url(i/flechita.png) no-repeat;">';
			$estatus = status($parent);
			$berre = '<br />';
			echo $estatus;
			echo $berre;
			echo '</div>';
			$categories = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = '$parent'");
			echo '<div style="padding-left:20px;">';			
			foreach ($categories as $cat) {
				echo '<div style="padding-left:15px;background:url(i/flechita.png) no-repeat;">';
				$estatus = status($cat);
				$berre = '<br />';
				echo $estatus;
				echo $berre;
				echo '</div>';
			}
			echo '</div>';
		}
		echo '</div>';
	}

   
// Projects
	echo '<div style="padding-bottom:10px;padding-top:10px;font-weight:bold;font-size:2em;"><strong>Projects</strong></div>';
	$categories = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = $projects");
	foreach ($categories as $cat) {
		$estatus = status($cat);
		$berre = '<br />';
		echo $estatus;
		echo $berre;
		$isparent = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = '$cat'");
		echo '<div style="padding-left:10px;">';				
		foreach ($isparent as $parent) {
			echo '<div style="padding-left:15px;background:url(i/flechita.png) no-repeat;">';
			$estatus = status($parent);
			$berre = '<br />';
			echo $estatus;
			echo $berre;
			echo '</div>';
			$categories = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = '$parent'");
			echo '<div style="padding-left:20px;">';			
			foreach ($categories as $cat) {
				echo '<div style="padding-left:15px;background:url(i/flechita.png) no-repeat;">';
				$estatus = status($cat);
				$berre = '<br />';
				echo $estatus;
				echo $berre;
				echo '</div>';
			}
			echo '</div>';
		}
		echo '</div>';
	}


?>
<br /><br />
<ul>
<?php 
if( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) { 
	$before = "<li><h2>Recent projects</h2>\n";
	$after = "</li>\n";

	$num_to_show = 35;

	echo prologue_recent_projects( $num_to_show, $before, $after );
} // if dynamic_sidebar
?>


		<li class="credits">
			<p><a href="http://mecus.es/">Bach Ticket Manager</a><br /> <a href="http://mecus.es/">Mecus</a></p>
		</li>
	</ul>



</div> <!-- // sidebar -->
