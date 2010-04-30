<?php
/*
	Plugin Name: Tempus
	Plugin URI: http://tempus-project.wordpress.com/
	Description: Plugin para gerenciamento de eventos
	Version: 0.1
	Author: Patrick Kaminski, Sibanir J. Lombardi
	Author URI: http://patrickkaminski.wordpress.com/
*/
//ini_set('display_errors', true);
//error_reporting(E_ALL|E_STRICT);
if (!defined('WPTEMPUS_PLUGIN_BASENAME'))
	define('WPTEMPUS_PLUGIN_BASENAME', plugin_basename(__FILE__));

if (!defined('WPTEMPUS_PLUGIN_NAME'))
	define('WPTEMPUS_PLUGIN_NAME', trim(dirname(WPTEMPUS_PLUGIN_BASENAME), '/'));

if (!defined('WPTEMPUS_PLUGIN_DIR'))
	define('WPTEMPUS_PLUGIN_DIR', WP_PLUGIN_DIR.'/'.WPTEMPUS_PLUGIN_NAME);

global $wpdb;	
if (!defined('WPTEMPUS_TABLE'))
	define('WPTEMPUS_TABLE', $wpdb->prefix.'tempus_events');


class instalar{
public static function install(){
    global $wpdb;
    		$sqlevents="CREATE TABLE IF NOT EXISTS `".WPTEMPUS_TABLE."` (
					  `id` int(10) unsigned NOT NULL auto_increment,
					  `title` varchar(255) NOT NULL,
					  `description` text NOT NULL,
					  `start_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
					  `end_time` timestamp NULL default NULL,
					  `all_day` tinyint(1) NOT NULL default '0',
					  `image` varchar(255) default NULL,
					  PRIMARY KEY  (`id`),
					  KEY `id` (`id`),
					  KEY `start_time` (`start_time`),
					  KEY `end_time` (`end_time`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$wpdb->query($sqlevents);
      /*
      Rotina para adicionar pagina de eventos
      Implementar no futuro
		$post_date =date("Y-m-d H:i:s");
		$post_date_gmt =gmdate("Y-m-d H:i:s");
		$sql ="INSERT INTO ".$wpdb->posts."
		(post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order, post_type)
		VALUES
		('1', '$post_date', '$post_date_gmt', '[tempus-event]', '', 'Eventos', '', 'publish', 'closed', 'closed', '', 'events', '', '', '$post_date', '$post_date_gmt', '0', '0', 'page')";
		$wpdb->query($sql);
		$post_id = $wpdb->insert_id;
		update_option('tempus_url',  get_permalink($post_id));
        */
	}
}
register_activation_hook(__FILE__, array('instalar','install'));

if ( is_admin() ) {
	require_once WPTEMPUS_PLUGIN_DIR.'/admin.php';
	} else {
	require_once WPTEMPUS_PLUGIN_DIR.'/controller.php';
}


function post($indice,$tipo="s")
{
if ((isset($_POST[$indice]))&&($_POST[$indice]!="")) {
  $valor=$_POST[$indice];
  if ($tipo!="h") {
    $valor=strip_tags($valor);
  } else if ($tipo=="n") {
  	$valor=floor($valor);
	if ($valor==0) {
		$valor=null;
	}
  }
} else {
  $valor=null;
}
return $valor;
}

function get($indice,$tipo="s")
{
if ((isset($_GET[$indice]))&&($_GET[$indice]!="")) {
  $valor=$_GET[$indice];
} else {
  $valor=null;
}
$valor=strip_tags($valor);
$valor=addslashes($valor);
if ($tipo=="n") {
  $valor=floor($valor);
  if ($valor==0) {
    $valor=null;
  }
}
return $valor;
}

function get_events() {
	global $wpdb;
	$sql="SELECT * FROM `".WPTEMPUS_TABLE."` ORDER BY id";
	$events=$wpdb->get_results($sql);
	return $events;
}
function list_events() {
	$eventos=get_events();
	if (sizeof($eventos)>0) {
		echo '<div class="tempus-events">';
		foreach ($eventos as $evento) {
			//$url='tempus-events&amp;e='.base64_encode($evento->id);
			//$url=get_option('tempus_url').'e='.base64_encode($evento->id);
			$url=$evento->description;
			echo '<div class="tempus-event" id="event-'.$evento->id.'">';
			echo '<strong>';
			echo '<a href="?'.$url.'">';
			echo $evento->title;
			echo '</a>';
			echo '</strong>';
			echo '<br />';
			echo $evento->description;
			echo '<br />';
			if ($evento->image!='') {
				echo '<img src="'.$evento->image.'" alt="'.$evento->title.'" />';
			}
			echo '</div>';
			echo '<br />';
		}
		echo '</div>';
	} else {
		echo 'Nenhum evento encontrado';
	}
}

function tempus_init() {
    add_shortcode('list-events', 'list_events');
}

add_action('init','tempus_init');
