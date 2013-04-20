/* based on prologue-projects.js by Sam Bauers, Jane Wells and Noriyko Tate */ 


function js_toggle( toggleID )
{
	var projectList = document.getElementById( toggleID );
	if ( !projectList ) {
		return;
	}
	if ( projectList.style.display == 'none' ) {
		projectList.style.display = 'block';
	} else {
		projectList.style.display = 'none';
	}
}

