<?php
/*
    Geo Sitemap generator - Leaflet Maps Marker Plugin
*/
//info: construct path to wp-load.php
while(!is_file('wp-load.php')){
  if(is_dir('../')) chdir('../');
  else die('Error: Could not construct path to wp-load.php - please check <a href="http://mapsmarker.com/path-error">http://mapsmarker.com/path-error</a> for more details');
}
include( 'wp-load.php' );
function hide_email($email) { $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz'; $key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999); for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])]; $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";'; $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));'; $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"'; $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")"; $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>'; return '<span id="'.$id.'">[javascript protected email address]</span>'.$script; }
//info: check if plugin is active (didnt use is_plugin_active() due to problems reported by users)
function lmm_is_plugin_active( $plugin ) {
	return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || lmm_is_plugin_active_for_network( $plugin );
}
function lmm_is_plugin_active_for_network( $plugin ) {
	if ( !is_multisite() )
		return false;
	$plugins = get_site_option( 'active_sitewide_plugins');
	if ( isset($plugins[$plugin]) )
				return true;
	return false;
}
if (!lmm_is_plugin_active('leaflet-maps-marker/leaflet-maps-marker.php') ) {
	echo 'The WordPress plugin <a href="http://www.mapsmarker.com" target="_blank">Leaflet Maps Marker</a> is inactive on this site and therefore this API link is not working.<br/><br/>Please contact the site owner (' . hide_email(get_bloginfo('admin_email')) . ') who can activate this plugin again.';
} else {
global $wpdb;
$table_name_markers = $wpdb->prefix.'leafletmapsmarker_markers';
$table_name_layers = $wpdb->prefix.'leafletmapsmarker_layers';
$lmm_options = get_option( 'leafletmapsmarker_options' );

  $sql = 'SELECT m.id as mid, m.createdon as mcreatedon, m.updatedon as mupdatedon FROM '.$table_name_markers.' AS m';
  $markers = $wpdb->get_results($sql, ARRAY_A);

  $sql2 = 'SELECT l.id as lid, l.createdon as lcreatedon, l.updatedon as lupdatedon FROM '.$table_name_layers.' AS l WHERE l.id != 0';
  $layers = $wpdb->get_results($sql2, ARRAY_A);

  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-Type:text/xml; charset=utf-8'); 
  echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
  echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:geo="http://www.google.com/geo/schemas/sitemap/1.0">'.PHP_EOL;

  foreach ($markers as $marker) {
	if  ( ($marker['mupdatedon'] == NULL) || ($marker['mupdatedon'] == '0000-00-00 00:00:00') ){ 
		$date_kml =  strtotime($marker['mcreatedon']);
	} else {
		$date_kml =  strtotime($marker['mupdatedon']);
	}
	echo '<url>'.PHP_EOL;
	echo '<loc>'. LEAFLET_PLUGIN_URL . 'leaflet-kml.php?marker=' . $marker['mid'] . '</loc>'.PHP_EOL;
	echo '<geo:geo>'.PHP_EOL;
	echo '<geo:format>kml</geo:format>'.PHP_EOL;
	echo '</geo:geo>'.PHP_EOL;
	echo '<lastmod>' . date("Y-m-d", $date_kml) . '</lastmod>'.PHP_EOL;
	echo '</url>'.PHP_EOL;
  }

  foreach ($layers as $layer) {
	if  ( ($layer['lupdatedon'] == NULL) || ($layer['lupdatedon'] == '0000-00-00 00:00:00') ){ 
		$date_kml =  strtotime($layer['lcreatedon']);
	} else {
		$date_kml =  strtotime($layer['lupdatedon']);
	}
	echo '<url>'.PHP_EOL;
	echo '<loc>'. LEAFLET_PLUGIN_URL . 'leaflet-kml.php?layer=' . $layer['lid'] . '</loc>'.PHP_EOL;
	echo '<geo:geo>'.PHP_EOL;
	echo '<geo:format>kml</geo:format>'.PHP_EOL;
	echo '</geo:geo>'.PHP_EOL;
	echo '<lastmod>' . date("Y-m-d", $date_kml) . '</lastmod>'.PHP_EOL;
	echo '</url>'.PHP_EOL;
  }
  echo '</urlset>';
} //info: end plugin active check
?>