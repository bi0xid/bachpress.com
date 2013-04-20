<?php 


$data = file_get_contents('http://www.aemet.es/es/eltiempo/prediccion/localidades/almeria-04001');
$max = '/"texto_rojo">(.+?)&nbsp;/';
$min = '/"texto_azul">(.+?)&nbsp;/';
$cielo = '/gif" title="(.+?)"/';

preg_match_all($max,$data,$matchmax);
preg_match_all($min,$data,$matchmin);
preg_match_all($cielo,$data,$matchcielo);

$almeria_max = $matchmax[1][0];
$almeria_min = $matchmin[1][0];
$almeria_prev_max = $matchmax[1][1];
$almeria_prev_min = $matchmin[1][1];
echo $almeria_prev_max;
echo $almeria_prev_min
$imagen = $matchcielo[1][0];
$imagen_prev = $matchcielo[1][1];
echo $imagen_prev;
$imagen = strtolower($imagen);
$imagen = str_replace(' ','',$imagen);
echo $imagen;
$almeria_imagen32 = 'iconos/32/'.$imagen.'.png';
$almeria_imagen20 = 'iconos/20/'.$imagen.'.png';


$data = file_get_contents('http://www.aemet.es/es/eltiempo/prediccion/localidades/cadiz-11001');
$max = '/"texto_rojo">(.+?)&nbsp;/';
$min = '/"texto_azul">(.+?)&nbsp;/';
$cielo = '/gif" title="(.+?)"/';

preg_match_all($max,$data,$matchmax);
preg_match_all($min,$data,$matchmin);
preg_match_all($cielo,$data,$matchcielo);

$cadiz_max = $matchmax[1][0];
$cadiz_min = $matchmin[1][0];
$imagen = $matchcielo[1][0];
$imagen = strtolower($imagen);
$imagen = str_replace(' ','',$imagen);
$cadiz_imagen32 = 'iconos/32/'.$imagen.'.png';
$cadiz_imagen20 = 'iconos/20/'.$imagen.'.png';




$data = file_get_contents('http://www.aemet.es/es/eltiempo/prediccion/localidades/cordoba-14001');
$max = '/"texto_rojo">(.+?)&nbsp;/';
$min = '/"texto_azul">(.+?)&nbsp;/';
$cielo = '/gif" title="(.+?)"/';

preg_match_all($max,$data,$matchmax);
preg_match_all($min,$data,$matchmin);
preg_match_all($cielo,$data,$matchcielo);
$cordoba_max = $matchmax[1][0];
$cordoba_min = $matchmin[1][0];
$imagen = $matchcielo[1][0];
$imagen = strtolower($imagen);
$imagen = str_replace(' ','',$imagen);
$cordoba_imagen32 = 'iconos/32/'.$imagen.'.png';
$cordoba_imagen20 = 'iconos/20/'.$imagen.'.png';


$data = file_get_contents('http://www.aemet.es/es/eltiempo/prediccion/localidades/granada-18001');
$max = '/"texto_rojo">(.+?)&nbsp;/';
$min = '/"texto_azul">(.+?)&nbsp;/';
$cielo = '/gif" title="(.+?)"/';

preg_match_all($max,$data,$matchmax);
preg_match_all($min,$data,$matchmin);
preg_match_all($cielo,$data,$matchcielo);

$granada_max = $matchmax[1][0];
$granada_min = $matchmin[1][0];
$imagen = $matchcielo[1][0];
$imagen = strtolower($imagen);
$imagen = str_replace(' ','',$imagen);
$granada_imagen32 = 'iconos/32/'.$imagen.'.png';
$granada_imagen20 = 'iconos/20/'.$imagen.'.png';


$data = file_get_contents('http://www.aemet.es/es/eltiempo/prediccion/localidades/huelva-21001');
$max = '/"texto_rojo">(.+?)&nbsp;/';
$min = '/"texto_azul">(.+?)&nbsp;/';
$cielo = '/gif" title="(.+?)"/';

preg_match_all($max,$data,$matchmax);
preg_match_all($min,$data,$matchmin);
preg_match_all($cielo,$data,$matchcielo);

$huelva_max = $matchmax[1][0];
$huelva_min = $matchmin[1][0];
$imagen = $matchcielo[1][0];
$imagen = strtolower($imagen);
$imagen = str_replace(' ','',$imagen);
$huelva_imagen32 = 'iconos/32/'.$imagen.'.png';
$huelva_imagen20 = 'iconos/20/'.$imagen.'.png';


$data = file_get_contents('http://www.aemet.es/es/eltiempo/prediccion/localidades/jaen-23001');
$max = '/"texto_rojo">(.+?)&nbsp;/';
$min = '/"texto_azul">(.+?)&nbsp;/';
$cielo = '/gif" title="(.+?)"/';

preg_match_all($max,$data,$matchmax);
preg_match_all($min,$data,$matchmin);
preg_match_all($cielo,$data,$matchcielo);

$jaen_max = $matchmax[1][0];
$jaen_min = $matchmin[1][0];
$imagen = $matchcielo[1][0];
$imagen = strtolower($imagen);
$imagen = str_replace(' ','',$imagen);
$jaen_imagen32 = 'iconos/32/'.$imagen.'.png';
$jaen_imagen20 = 'iconos/20/'.$imagen.'.png';


$data = file_get_contents('http://www.aemet.es/es/eltiempo/prediccion/localidades/malaga-29001');
$max = '/"texto_rojo">(.+?)&nbsp;/';
$min = '/"texto_azul">(.+?)&nbsp;/';
$cielo = '/gif" title="(.+?)"/';

preg_match_all($max,$data,$matchmax);
preg_match_all($min,$data,$matchmin);
preg_match_all($cielo,$data,$matchcielo);

$malaga_max = $matchmax[1][0];
$malaga_min = $matchmin[1][0];
$imagen = $matchcielo[1][0];
$imagen = strtolower($imagen);
$imagen = str_replace(' ','',$imagen);
$malaga_imagen32 = 'iconos/32/'.$imagen.'.png';
$malaga_imagen20 = 'iconos/20/'.$imagen.'.png';


$data = file_get_contents('http://www.aemet.es/es/eltiempo/prediccion/localidades/sevilla-41001');
$max = '/"texto_rojo">(.+?)&nbsp;/';
$min = '/"texto_azul">(.+?)&nbsp;/';
$cielo = '/gif" title="(.+?)"/';

preg_match_all($max,$data,$matchmax);
preg_match_all($min,$data,$matchmin);
preg_match_all($cielo,$data,$matchcielo);

$sevilla_max = $matchmax[1][0];
$sevilla_min = $matchmin[1][0];
$imagen = $matchcielo[1][0];
$imagen = strtolower($imagen);
$imagen = str_replace(' ','',$imagen);
$sevilla_imagen32 = 'iconos/32/'.$imagen.'.png';
$sevilla_imagen20 = 'iconos/20/'.$imagen.'.png';

$content = "var almeria_max = ".$almeria_max.";\n";
$content .= "var almeria_min = ".$almeria_min.";\n";
$content .= "var almeria_imagen32 = '".$almeria_imagen32."';\n";
$content .= "var almeria_imagen20 = '".$almeria_imagen20."';\n";

$content .= "var cadiz_max = ".$cadiz_max.";\n";
$content .= "var cadiz_min = ".$cadiz_min.";\n";
$content .= "var cadiz_imagen32 = '".$cadiz_imagen32."';\n";
$content .= "var cadiz_imagen20 = '".$cadiz_imagen20."';\n";


$content .= "var cordoba_max = ".$cordoba_max.";\n";
$content .= "var cordoba_min = ".$cordoba_min.";\n";
$content .= "var cordoba_imagen32 = '".$cordoba_imagen32."';\n";
$content .= "var cordoba_imagen20 = '".$cordoba_imagen20."';\n";


$content .= "var granada_max = ".$granada_max.";\n";
$content .= "var granada_min = ".$granada_min.";\n";
$content .= "var granada_imagen32 = '".$granada_imagen32."';\n";
$content .= "var granada_imagen20 = '".$granada_imagen20."';\n";

$content .= "var huelva_max = ".$huelva_max.";\n";
$content .= "var huelva_min = ".$huelva_min.";\n";
$content .= "var huelva_imagen32 = '".$huelva_imagen32."';\n";
$content .= "var huelva_imagen20 = '".$huelva_imagen20."';\n";


$content .= "var jaen_max = ".$jaen_max.";\n";
$content .= "var jaen_min = ".$jaen_min.";\n";
$content .= "var jaen_imagen32 = '".$jaen_imagen32."';\n";
$content .= "var jaen_imagen20 = '".$jaen_imagen20."';\n";


$content .= "var malaga_max = ".$malaga_max.";\n";
$content .= "var malaga_min = ".$malaga_min.";\n";
$content .= "var malaga_imagen32 = '".$malaga_imagen32."';\n";
$content .= "var malaga_imagen20 = '".$malaga_imagen20."';\n";

$content .= "var sevilla_max = ".$sevilla_max.";\n";
$content .= "var sevilla_min = ".$sevilla_min.";\n";
$content .= "var sevilla_imagen32 = '".$sevilla_imagen32."';\n";
$content .= "var sevilla_imagen20 = '".$sevilla_imagen20."';\n";


//$file = 'tiempoand.js';
//$f=file_put_contents($file, $content); // (PHP 5) 

?>