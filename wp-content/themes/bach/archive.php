<?php get_header(); 

$ifdone = $_COOKIE['muestradone'];

?>

<script language="JavaScript">
/*	This function sets the cookie	*/
function done(){
   document.cookie = 'muestradone=si;'
}
function nodone(){
   document.cookie = 'muestradone=no;'
}
/*	end of cookie function	*/
</script>




<div id="main">
	<?php if (have_posts()) : ?>


 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Archivo para <?php single_cat_title(); ?></h2>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle"><?php single_tag_title(); ?></h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Archivo para <?php the_time('F jS, Y'); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Archivo para <?php the_time('F Y'); ?></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Archivo para <?php the_time('Y'); ?></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle"><?php the_author(); ?></h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Archivo del blog</h2>
<?php } 

	?>
	
	<!-- Con esto vemos las tareas pendientes o todas las tareas -->
	
	<div style="float:right;margin-top: -20px;padding-right:60px;"><h2><a class="rss" href="." onclick="done()">ver pendientes</a> <a class="rss" href="." onclick="nodone()">ver todos</a></h2>
	<br> <?php if ($ifdone == 'si') {echo 'Mostrando pendientes';} else {echo 'Mostrando todos';} ?>
</div><br />

	
	
<br /><br />
		<div class="navigation">
    <?php
    if(function_exists('pagination'))
        pagination(2,array("&#8592; m&#225;s recientes"," m&#225;s antiguas &#8594;"));
    ?>
		</div>

		<?php while (have_posts()) : the_post(); ?>
		
<?php
			$cerrados = get_option('bach_closed'); 
			$cerrado = get_cat_id($cerrados);
			$esperas = get_option('bach_wait'); 
			$espera = get_cat_id($esperas);

	if (($ifdone == 'si') && (in_category( $cerrado ))) {
	?> <?php 
	} else { ?>
			<div  class="recuadro<?php if ( in_category( 58 ) ) { echo 'luis'; } elseif ( in_category( 57 ) ) { echo 'raven'; } elseif ( in_category( 144 ) ) { echo 'rocio'; } ?><?php if ( in_category( $espera ) ) { echo ' espera'; } ?>" id="post-<?php the_ID(); ?>">
				<?php 		// Show the new category images

			foreach((get_the_category()) as $category) { 
			$proyectos = get_option('bach_projects'); 
			$proyecto = get_cat_id($proyectos);
	
				if ( get_root_category($category) == $proyecto ) {
				    echo '<a href="'.get_bloginfo('url').'/?cat='.$category->cat_ID.'"><img src="'.trim(ereg_replace("</p>", "", category_description($category->cat_ID)),"<p>").'" alt="' . $category->cat_name . '" class="logo" height="48px" width="48px" style="float:right;" /><a>'; 
			} }
?>
				
				<h3 class="titulillo"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div align="center"><?php include (TEMPLATEPATH . '/clases.php'); ?></div>	

				<div class="post-datos">
					Subido por <a href="<?php bloginfo('url'); ?>/category/usuarios/<?php the_author(); ?>/"><?php the_author() ?></a>
				<div class="post-tags">
					Propiedades: 
					<?php the_category(' • ','') ?>
				</div>

				</div>
				<div class="post-contenido">
					<?php the_content('Leer el resto del art&iacute;culo &raquo;'); ?>
				</div>
			</div>
<?php 
$comments = get_comments('order=ASC&post_id='.$post->ID);
if ($comments) :	?>
<ul style="list-style-type:none;">

<?php	foreach($comments as $comm) : ?>

<li id="prologue-<?php the_ID(); ?>" class="comment<?php echo strtolower($comm->comment_author); ?>">
<?php echo nl2br($comm->comment_content); ?>
</li>

<?  endforeach; ?>
</ul>

<?	endif;	?>
			<br /><br />
			<?php }	?>		
			
		<?php endwhile; ?>

		<div class="navigation">
    <?php
    if(function_exists('pagination'))
        pagination(2,array("&#8592; m&#225;s recientes"," m&#225;s antiguas &#8594;"));
    ?>
		</div>

	<?php else : ?>

		<h2 class="center">No encontrado</h2>
		<p class="center">Lo sentimos pero la p&aacute;gina que busca no ha sido encontrada.</p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>

	<?php endif; ?>
			
		</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>






