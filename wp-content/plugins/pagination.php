<?php
/*
Plugin Name: *WP-Digg Style Paginator
Plugin URI: http://www.mis-algoritmos.com/2007/03/12/wp-digg-style-pagination-plugin/
Description: Adds a <strong>digg style pagination</strong> to Wordpress.
Version: 0.2
Author: Victor De la Rocha
Author URI: http://www.mis-algoritmos.com
*/
function pagination($adjacents=1,$nav = array("Previous","Next")){
		//$total_pages,$limit,$page=1,$file="paginator.php",$adjacents=2
		global $request, $posts_per_page, $wpdb, $paged, $query_string;

		if(is_single()||is_page())return true;
		
		preg_match('{FROM\s(.*)\sLIMIT}siU', $request, $matches);
		$fromwhere = $matches[1];
		$fromwhereorderby = $matches[0];
		if (is_category()) {
        	$fromwhere = str_replace("GROUP BY wp_posts.ID", " ", $fromwhere);
    	}
    	
    	//C—digo para que s—lo muestre el nœmero de p‡ginas de los post que no sean de la categor’a 'Visto en la prensa'
		$count = 0;
		$all_post = "SELECT ID FROM $fromwhere";
		$all_post_request = $wpdb->get_col($all_post);
		if($all_post_request){
			foreach($all_post_request as $post_request){
			
				$id = $post_request;
				$tax_cat = 131;
				$cat = "SELECT object_id FROM wp_term_relationships WHERE object_id = ".$id." AND term_taxonomy_id = 131";
				$query_cat = $wpdb->get_var($cat);
				if($query_cat == '')
					$count++;
			}
		}
		
		$sqlStr = "SELECT count(*) FROM $fromwhere";

		
		$total_pages = $count;
		//$total_pages = $wpdb->get_var($sqlStr);//total number of rows in data table
		$limit = $posts_per_page;//how many items to show per page
		if(!empty($paged))$page = $paged; else $page = 1;

		/* Setup vars for query. */	
		if($page) 
				$start = ($page - 1) * $limit; 			//first item to display on this page
			else
				$start = 0;								//if no page var is given, set start to 0
		
		/* Setup page vars for display. */
		if ($page == 0) $page = 1;					//if no page var is given, default to 1.
		$prev = $page - 1;							//anterior page is page - 1
		$siguiente = $page + 1;							//siguiente page is page + 1
		$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1
		
		/* 
			Now we apply our rules and draw the pagination object. 
			We're actually saving the code to a variable in case we want to draw it more than once.
		*/
		ob_start();
		if($lastpage > 1){
		
				echo "<div class=\"pagination\">";
				//anterior button
				if($page > 1)
						echo "<a href=\"".get_pagenum_link($prev)."\" class=\"mas\"> $nav[0]</a>";
					else
						echo "<div class=\"anteriores_deshabilitado\"> $nav[0]</div>";
				//pages	
				if ($lastpage < 7 + ($adjacents * 2)){//not enough pages to bother breaking it up
						for ($counter = 1; $counter <= $lastpage; $counter++){
								if ($counter == $page)
										echo "<span class=\"current\">$counter</span>";
									else
										echo "<a href=\"".get_pagenum_link($counter)."\" class=\"numero\">$counter</a>";
							}
					}
				elseif($lastpage > 5 + ($adjacents * 2)){//enough pages to hide some
						//close to beginning; only hide later pages
						if($page < 1 + ($adjacents * 2)){
								for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
										if ($counter == $page)
												echo "<span class=\"current\">$counter</span>";
											else
												echo "<a href=\"".get_pagenum_link($counter)."\" class=\"numero\">$counter</a>";
									}
								echo "<div class=\"puntos\">...</div>";
								echo "<a href=\"".get_pagenum_link($lpm1)."\" class=\"numero\">$lpm1</a>";
								echo "<a href=\"".get_pagenum_link($lastpage)."\" class=\"numero\">$lastpage</a>";
							}
						//in middle; hide some front and some back
						elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
								echo "<a href=\"".get_pagenum_link(1)."\" class=\"numero\">1</a>";
								echo "<a href=\"".get_pagenum_link(2)."\" class=\"numero\">2</a>";
								echo "<div class=\"puntos\">...</div>";
								for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
									if ($counter == $page)
											echo "<span class=\"current\">$counter</span>";
										else
											echo "<a href=\"".get_pagenum_link($counter)."\" class=\"numero\">".$counter."</a>";
								echo "<div class=\"puntos\">...</div>";
								echo "<a href=\"".get_pagenum_link($lpm1)."\" class=\"numero\">$lpm1</a>";
								echo "<a href=\"".get_pagenum_link($lastpage)."\" class=\"numero\">$lastpage</a>";
							}
						//close to end; only hide early pages
						else{
								echo "<a href=\"".get_pagenum_link(1)."\" class=\"numero\">1</a>";
								echo "<a href=\"".get_pagenum_link(2)."\" class=\"numero\">2</a>";
								echo "<div class=\"puntos\">...</div>";
								for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
									if ($counter == $page)
											echo "<span class=\"current\">$counter</span>";
										else
											echo "<a href=\"".get_pagenum_link($counter)."\" class=\"numero\">$counter</a>";
							}
					}
				//siguiente button
        if ($page < $counter - 1)
						echo "<a href=\"".get_pagenum_link($siguiente)."\" class=\"mas2\">$nav[1] </a>";
					else
						echo "<div class=\"siguientes_deshabilitado\">$nav[1] </div>";

				echo "</div>\n";
			}
		echo ob_get_clean();
	}
	
	function pagination2($adjacents=1,$nav = array("Previous","Next")){
		//$total_pages,$limit,$page=1,$file="paginator.php",$adjacents=2
		global $request, $posts_per_page, $wpdb, $paged;

		if(is_single()||is_page())return true;
		
		preg_match('{FROM\s(.*)\sLIMIT}siU', $request, $matches);
		$fromwhere = $matches[1];
		if (is_category()) {
        $fromwhere = str_replace("GROUP BY wp_posts.ID", " ", $fromwhere);
    }
		$sqlStr = "SELECT count(*) FROM $fromwhere";

	
		$total_pages = $wpdb->get_var($sqlStr);//total number of rows in data table
		$limit = $posts_per_page;//how many items to show per page
		if(!empty($paged))$page = $paged; else $page = 1;

		/* Setup vars for query. */	
		if($page) 
				$start = ($page - 1) * $limit; 			//first item to display on this page
			else
				$start = 0;								//if no page var is given, set start to 0
		
		/* Setup page vars for display. */
		if ($page == 0) $page = 1;					//if no page var is given, default to 1.
		$prev = $page - 1;							//anterior page is page - 1
		$siguiente = $page + 1;							//siguiente page is page + 1
		$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1
		
		/* 
			Now we apply our rules and draw the pagination object. 
			We're actually saving the code to a variable in case we want to draw it more than once.
		*/
		ob_start();
		if($lastpage > 1){
		
				echo "<div class=\"paginacion\">";
				echo "<div class=\"anterior\">";
        //anterior button
				if($page > 1)
						echo "<a href=\"".get_pagenum_link($prev)."\" class=\"texto_anterior\"> $nav[0]</a>";
					else
						echo "<div class=\"texto_anterior_des\"> $nav[0]</div>";
				echo "</div>";
				echo "<div class=\"numeros\">";
				//pages	
				if ($lastpage < 7 + ($adjacents * 2)){//not enough pages to bother breaking it up
						for ($counter = 1; $counter <= $lastpage; $counter++){
								if ($counter == $page)
										echo "<div class=\"actual\">$counter</div>";
									else
										echo "<a href=\"".get_pagenum_link($counter)."\" class=\"enlace_numero\">$counter</a>";
							}
					}
				elseif($lastpage > 5 + ($adjacents * 2)){//enough pages to hide some
						//close to beginning; only hide later pages
						if($page < 1 + ($adjacents * 2)){
								for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
										if ($counter == $page)
												echo "<div class=\"actual\">$counter</div>";
											else
												echo "<a href=\"".get_pagenum_link($counter)."\" class=\"enlace_numero\">$counter</a>";
									}
								echo "<div class=\"puntos\">...</div>";
								echo "<a href=\"".get_pagenum_link($lpm1)."\" class=\"enlace_numero\">$lpm1</a>";
								echo "<a href=\"".get_pagenum_link($lastpage)."\" class=\"enlace_numero\">$lastpage</a>";
							}
						//in middle; hide some front and some back
						elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
								echo "<a href=\"".get_pagenum_link(1)."\" class=\"enlace_numero\">1</a>";
								echo "<a href=\"".get_pagenum_link(2)."\" class=\"enlace_numero\">2</a>";
								echo "...";
								for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
									if ($counter == $page)
											echo "<div class=\"actual\">$counter</div>";
										else
											echo "<a href=\"".get_pagenum_link($counter)."\" class=\"enlace_numero\">".$counter."</a>";
								echo "<div class=\"puntos\">...</div>";
								echo "<a href=\"".get_pagenum_link($lpm1)."\" class=\"enlace_numero\">$lpm1</a>";
								echo "<a href=\"".get_pagenum_link($lastpage)."\" class=\"enlace_numero\">$lastpage</a>";
							}
						//close to end; only hide early pages
						else{
								echo "<a href=\"".get_pagenum_link(1)."\" class=\"enlace_numero\">1</a>";
								echo "<a href=\"".get_pagenum_link(2)."\" class=\"enlace_numero\">2</a>";
								echo "<div class=\"puntos\">...</div>";
								for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
									if ($counter == $page)
											echo "<div class=\"actual\">$counter</div>";
										else
											echo "<a href=\"".get_pagenum_link($counter)."\" class=\"enlace_numero\">$counter</a>";
							}
					}
				echo "</div>\n";
        echo "<div class=\"siguiente\">";
        //siguiente button
        if ($page < $counter - 1)
						echo "<a href=\"".get_pagenum_link($siguiente)."\" class=\"texto_siguiente\">$nav[1] </a>";
					else
						echo "<div class=\"texto_siguiente_des\">$nav[1] </div>";
        echo "</div>\n";
				echo "</div>\n";
			}
		echo ob_get_clean();
	}
?>
