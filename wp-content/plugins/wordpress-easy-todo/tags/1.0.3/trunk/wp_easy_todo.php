<?php
/*
Plugin Name: WordPress Easy ToDo
Version: 1.0.3
Plugin URI: http://www.crispijnverkade.nl/blog/wordpress-easy-todo
Description: Create a To Do list on the WordPress Dashboard. This tool is very interesting if you're writing posts with several authors.
Author: Crispijn Verkade
Author URI: http://crispijnverkade.nl/

Copyright (c) 2009
Released under the GPL license
http://www.gnu.org/licenses/gpl.txt

    This file is part of WordPress.
    WordPress is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	INSTALL: 
	Just install the plugin in your blog and activate
*/

//Changelog
$current_version = array('1.0');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	wpet_add($_POST);	
}

//add stuff the the admin header
function wpet_admin_header(){
	global $wpdb, $post, $table_prefix, $mootools12;
	
	if($_SERVER['REQUEST_URI'] == get_settings('siteurl').'/wp-admin/' || $_SERVER['REQUEST_URI'] == get_settings('siteurl').'/wp-admin/index.php'){
		echo PHP_EOL.'<link rel="stylesheet" type="text/css" media="screen" href="'.get_settings('siteurl').'/wp-content/plugins/wordpress-easy-todo/wp_easy_todo.css" />'.PHP_EOL;
		echo '<script type="text/javascript" src="'.get_settings('siteurl').'/wp-content/plugins/wordpress-easy-todo/js/jquery-1.3.2.js"></script>'.PHP_EOL;
		echo '<script type="text/javascript" src="'.get_settings('siteurl').'/wp-content/plugins/wordpress-easy-todo/js/jquery-color.js"></script>'.PHP_EOL;
    ?>
<script type="text/javascript">
$(document).ready(function(){
    
	$("a#wpet_add").click(function() {
		$("div#wpet_formcontainer").toggle();
	});

	$('a.wpet_ready').click(function(e) {
		if(confirm('Are your sure this item is already done?') == true){
			e.preventDefault();
			var parent = $(this).parent().parent();
			$.ajax({
				type: 'get',
				url: '<?php echo get_settings('siteurl');?>/wp-content/plugins/wordpress-easy-todo/wp_easy_todo_ajax.php',
				data: 'ajax=1&delete=' + parent.attr('id').replace('wpet-',''),
				beforeSend: function() {
					parent.animate({'backgroundColor':'#fb6c6c'},300);
				},
				success: function() {
					parent.slideUp(300,function() {
						parent.remove();
					});
				}
			});
		}
	});
});
</script>    
    <?php
	}
}

//init the dashboard widget
function my_wp_dashboard_setup() {
	wp_add_dashboard_widget( 'wp-easy-todo', __( 'Easy ToDo' ), 'wpet_dashboard_widget' );
}

//Load the to do items from the datbase
function wpet_get_todos(){
	global $wpdb, $table_prefix;
	
	$sql = "SELECT
				t.id,
				t.title,
				t.add_user,
				t.for_user,
				t.status,
				t.date_todo AS date_added,
				u.display_name,
				u.user_email
			FROM
				". $table_prefix."easy_todo AS t
		INNER JOIN
			".$table_prefix."users AS u
		ON
			t.for_user = u.ID
			WHERE
				t.status != 'solved'
			ORDER BY 
				t.priority ASC,
				t.date_todo ASC";
	$res = $wpdb->get_results($sql);
	
	$i = 0;
	$todo = '';
	
	//ouput of the current todos
	foreach($res as $row){
		$i++;
		$class = (!($i % 2)) ? 'wpet_even' : 'wpet_odd';
		
		$todo .= '
		<div id="wpet-'.$row->id.'" class="wpet_todo '.$class.'">
			<img style="float: right;" src="http://www.gravatar.com/avatar/'.md5($row->user_email).'?s=50&d=http://www.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=50&r=G"><span class="wpet_user">'.$row->display_name.'</span><br />
			<span class="wpet_descr">'.$row->title.'</span><br />
			<span class="wpet_additional">Added by '.$row->add_user.' on '.$row->date_added.'</span>
			<div class="wpet_actions">
				<a href="#ready-'.$row->id.'" class="wpet_ready">Ready</a><!-- | <a href class="wpet_delete">Delete</a>-->
			</div>
		</div>';
	}
	
	return $todo;
}

//Add the widget to the dashboard
function wpet_dashboard_widget() {
	$todo = '<div id="wpet_list">';
	$list = wpet_get_todos();
	
		if(empty($list)){
			$todo .= '<strong>There are no things todo!</strong>';
		}else{
			$todo .= wpet_get_todos();
		}
	
	//form and other stuff
	$todo .= '	<div class="wpet_clear"></div>
			</div>
			<a href="#top" id="wpet_add" class="button">Add new item</a>
			<div id="wpet_formcontainer">
				<form id="wpet_formulier" method="post" action="'.$_SERVER['REQUEST_URI'].'">
					<p><label for"todo">To Do</label><input type="text" name="todo" id="todo" style="width: 250px;"/><p>
					<p><label for"user">For user</label><select name="user" id="user">'.wpet_users().'</select><p>
					<p><label for="but">&nbsp;</label><input type="submit" value="Submit To Do" class="button-primary" /></p>
				</form>
			</div>';
				
	echo $todo;
}

//Get the blog users
function wpet_users(){
	global $wpdb, $table_prefix;
	
	$sql = "SELECT
				u.ID,
				u.display_name
			FROM
				".$table_prefix."users AS u
			ORDER BY
				u.display_name ASC";
	
	$res = $wpdb->get_results($sql);
	
	$users = '';
	
	foreach($res as $row){
		$selected = ($row->ID == wp_get_current_user()->ID) ? ' selected="selected"' : '';
		
		$users .= '<option value="'.$row->ID.'"'.$selected.'>'.$row->display_name.'</option>';
	}
	
	return $users;
}

//Add a item to the todo list with an ajax request
function wpet_add($Post){
	global $wpdb, $table_prefix, $current_user;
	
	if(!empty($Post['todo']) && !empty($Post['user'])){
		
		$sql = "INSERT INTO 
					".$table_prefix."easy_todo
				(
					title,
					add_user,
					for_user,
					date_todo
				) VALUES (
					'".$wpdb->escape($Post['todo'])."',
					'".$current_user->user_login."',
					'".$wpdb->escape($Post['user'])."',
					NOW()
					)";
		$res = $wpdb->query($sql);
		
		//Loose the post data
		if($res){
			header('Location: '.$_SERVER['REQUEST_URI']);
		}
	}
}

//Remove a item to the todo list with an ajax request
function wpet_remove($delete){
	global $wpdb, $table_prefix;
	
	$sql = "UPDATE
				".$table_prefix."easy_todo
			SET
				status = 'solved'
			WHERE
				id = ".(int) $delete."
			LIMIT 1";
	$res = $wpdb->query($sql);

	die;
}

//Installl the plugin
function wpet_installation(){
	global $wpdb, $table_prefix, $wpet_version, $current_user;
	
	$table_name = $table_prefix.'easy_todo';
	
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE ".$table_name." (
				id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				title VARCHAR( 255 ) NOT NULL ,
				priority INT NOT NULL ,
				date_todo DATETIME NOT NULL,
				add_user INT NOT NULL ,
				for_user INT NOT NULL ,
				status VARCHAR( 10 ) NOT NULL
				) ENGINE = MYISAM ;";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		add_option("wpet_dbversion", end($wpet_version));
		  
		$sql = "INSERT INTO 
					".$table_name."
				(
				 	title,
					priority,
					add_user,
					for_user,
					date_todo
				) VALUES (
					'Your Easy Todo List is ready to use!',
					'high',
					'admin',
					'".$current_user->ID."',
					NOW()
					)";
		$res = $wpdb->query($sql);
	}
}


add_action('admin_head', 'wpet_admin_header');
add_action('wp_dashboard_setup', 'my_wp_dashboard_setup');
add_action('wp_ajax_wpet_add', 'wpet_add' );
add_action('wp_ajax_wpet_remove', 'wpet_remove' );
register_activation_hook(__FILE__,'wpet_installation');