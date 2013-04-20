<?php
/*************************
		PRIORITY
*************************/

// Priority (from 1 to 6) are controlled with whis if-else sentence in version 1.0
// If you are not using the default import file provided, please change it with your own categories

		
			if  (in_category( 5 )) { echo '<p class="uno"></p>'; }
		elseif (in_category( 6 )) { echo '<p class="dos"></p>'; }
		elseif (in_category( 7 )) { echo '<p class="tres"></p>'; }
		elseif (in_category( 8 )) { echo '<p class="cuatro"></p>'; }
		elseif (in_category( 9 )) { echo '<p class="cinco"></p>'; }
		elseif (in_category( 10 )) { echo '<p class="seis"></p>'; }
		else { }
 ?>