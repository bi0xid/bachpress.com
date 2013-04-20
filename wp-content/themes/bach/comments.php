<?php
if( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'] ) )
	die( 'Please do not load this page directly. Thanks!' );

if( !empty( $post->post_password ) ) { // if there's a password
	if( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) { 
?>

<p class="nocomments">This post is password protected. Enter the password to view comments.</p>

<?php
		return;
	} // if cookie
} // if post_password

if( $comments ) {
	echo "<h3>Comentarios</h3>\n";
	//echo "<ul id=\"comments\" class=\"commentlist\">\n";

	foreach( $comments as $comment ) {
?>
<ul style="list-style-type:none;">

<li id="comment-<?php comment_ID( ); ?>" class="comment<?php echo strtolower($comment->comment_author); ?>">
	<?php echo prologue_get_avatar( $comment->user_id, $comment->comment_author_email, 32 ); ?>
	<h4>
		<span class="meta"><?php comment_time( ); ?> :: <?php comment_date( ); ?> | <a href="#comment-<?php comment_ID( ); ?>">permalink</a><?php edit_comment_link( __( 'editar' ), '&nbsp;|&nbsp;',''); ?></span>
	</h4>
	<?php comment_text( ); ?>
</li>

<?php
	} // foreach comments

	echo "</ul>\n";
} // if comments









if( 'open' == $post->comment_status ) {
?>


<?php
			$cerrado = get_option('bach_closed'); 
			$closed = get_cat_id($cerrado);

			if ( !in_category ( $closed ) ) {
?>


<h3>Nueva aportación</h3>

<?php
if ( get_option('comment_registration') && !$user_ID ) {
?>

<p>Debes estar <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>" title="Log in">logueado</a> para participar.</p>

<?php
} // if option comment_registration and not user_ID
else {
?>

<form id="commentform" action="<?php echo get_option( 'siteurl' ); ?>/wp-comments-post.php" method="post">
<?php 
if( $user_ID ) { 
?>

<p>Estás autentificado como <a href="<?php echo get_option( 'siteurl' ); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. Si no es tu usuario, <a href="<?php echo get_option( 'siteurl' ); ?>/wp-login.php?action=logout" title="Log out">pincha aquí</a>.</p>

<?php 
} // if user_ID 
else { 
?>

<p>Debes estar registrado en el sistema para participar.</p><?php } // else user_ID ?>

<div class="form"><textarea id="comment" name="comment" cols ="100" rows="18" tabindex="1"></textarea></div>


<div><input id="submit" name="submit" type="submit" value="Añadir comentario &raquo;" tabindex="5" /><input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" /></div>

</form>
<?php
} // if in category $closed
} // else option comment_registration and not user_ID
} // if open comment_status
