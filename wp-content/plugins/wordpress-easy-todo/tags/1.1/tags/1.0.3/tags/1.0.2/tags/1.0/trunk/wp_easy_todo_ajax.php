<?php
require_once('../../../wp-load.php' );
require_once('../../../wp-content/plugins/wp-easy-todo/wp_easy_todo.php');

if(!empty($_GET['delete']) && ctype_digit($_GET['delete'])){
	wpet_remove($_GET['delete']);				
}
?>