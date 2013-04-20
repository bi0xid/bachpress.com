<?php

/*************************
	HEADER + VARIABLES
*************************/


 get_header(); 
 $ifdone = $_COOKIE['muestradone'];

			$cerrados = get_option('bach_closed'); 
			$cerrado = get_cat_id($cerrados);
			$esperas = get_option('bach_wait'); 
			$espera = get_cat_id($esperas);
			$usuarios = get_option('bach_users'); 
			$users = get_cat_id($usuarios);


?>

<script language="JavaScript">
/*	This function sets the cookie to show closed tasks	*/
function done(){
   document.cookie = 'muestradone=si;'
}
function nodone(){
   document.cookie = 'muestradone=no;'
}
/*	end of cookie function	*/
</script>

<?php
/*************************
	ARCHIVE FUNCTIONS
*************************/
?>


<div id="main">
	<?php if (have_posts()) : ?>


 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle"><?php __('Archive for ','bach'); ?> <?php single_cat_title(); ?></h2>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle"><?php single_tag_title(); ?></h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle"><?php __('Archive for ','bach'); ?> <?php the_time('F jS, Y'); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle"><?php __('Archive for ','bach'); ?> <?php the_time('F Y'); ?></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle"><?php __('Archive for ','bach'); ?> <?php the_time('Y'); ?></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle"><?php the_author(); ?></h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle"><?php __('Blog archive','bach'); ?></h2>
<?php } 



/*************************
		PAGE CODE
*************************/

// Small script to control the show of closed tasks
	?>
		
	<div style="float:right;margin-top: -20px;padding-right:60px;"><h2><a class="rss" href="." onclick="done()"><?php _e('show pending','bach');?></a> <a class="rss" href="." onclick="nodone()"><?php _e('show all','bach');?></a></h2>
	<br> <?php if ($ifdone == 'si') { _e('Showing pending','bach');} else {_e('Showing all','bach');} ?>
</div>

<br /><br /><br />
	
<?php // Pagination plugin (always shown)?>
<div class="navigation">
    <?php
    if(function_exists('pagination'))
        pagination(2,array("&#8592; m&#225;s recientes"," m&#225;s antiguas &#8594;"));
    ?>
		</div>
		<?php 
		// WordPress Loop
		while (have_posts()) : the_post(); ?>
		
<?php

	if (($ifdone == 'si') && (in_category( $cerrado ))) {
	?> <?php 
	} else { ?>

<?php
// foreach user category, if the post is in this category, get user id

	$class_users = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent = $users ORDER BY term_id DESC");
	foreach ($class_users as $class_user){
		if (in_category($class_user)){
		$class_color = $class_user;
	}}

?>

			<div class="recuadro <?php echo 'color-'.$class_color; ?><?php if ( in_category( $espera ) ) { echo ' espera'; } ?>" id="post-<?php the_ID(); ?>">
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
					<?php _e('Submitted by','bach');?> <a href="<?php bloginfo('url'); ?>/category/usuarios/<?php the_author(); ?>/"><?php the_author() ?></a>
				<div class="post-tags">
					<?php _e('Properties','bach');?>: 
					<?php the_category(' • ','') ?>
				</div>

				</div>
				<div class="post-contenido">
					<?php the_content('Read the rest of the post &raquo;'); ?>
				</div>
			</div>
<?php 
$comments = get_comments('order=ASC&post_id='.$post->ID);
if ($comments) :	?>
<ul style="list-style-type:none;">

<?php	foreach($comments as $comm) : ?>

<li id="prologue-<?php the_ID(); ?>" class="comment-<?php echo strtolower($comm->user_id); ?>">
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

		<h2 class="center"><?php _e('Not found','bach');?></h2>
		<p class="center"><?php _e('Sorry, the page you are looking for is not here','bach');?>.</p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>

	<?php endif; ?>
			
		</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>






