<?php
class TempusAdmin{
	public static function create_menu() {
	
	add_menu_page('Configura&ccedil;&atilde;o de Eventos', 'Eventos', 'administrator', 'tempus-top-menu', array('TempusAdmin', 'tempus_list_events_page'));
	add_submenu_page('tempus-top-menu', 'Listar Eventos', 'Listar Eventos', 'administrator', 'tempus-list-events', array('TempusAdmin', 'tempus_list_events_page'));
	add_submenu_page('tempus-top-menu', 'Adicionar Evento', 'Adicionar Evento', 'administrator', 'tempus-add-event', array('TempusAdmin','tempus_add_event_page'));
	add_submenu_page('tempus-top-menu', 'Remover Evento', NULL, 'administrator', 'tempus-del-event', array('TempusAdmin', 'tempus_del_event_page'));
	add_submenu_page('tempus-top-menu', 'Editar Evento', NULL, 'administrator', 'tempus-edit-event', array('TempusAdmin', 'tempus_edit_event_page'));
	add_submenu_page('tempus-top-menu', 'Sobre o plugin', '<strong>Sobre o Plugin</strong>', 'administrator', 'tempus-about-page', array('TempusAdmin','tempus_about_page'));
	}

public static function tempus_add_event_page() {
if (post('send-add-event')) {
global $wpdb;
echo '<div class="wrap tempus">';
echo '<h2>Evento adicionado</h2>';
$title=post('title');
$description=post('description');
$start=post('start-time');
$end=post('end-time');
$allday=(bool)post('all-day');
$image=post('upload_image');


$sql="INSERT INTO `".WPTEMPUS_TABLE."` (title,description,start_time,end_time,all_day,image) VALUES ('".$title."','".$description."','".$start."','".$end."','".$allday."','".$image."')";
$adicionado=$wpdb->query($sql);
if($adicionado) {
	echo 'Evento adicionado com sucesso';
	echo '<br />';
	echo '<a href="admin.php?page=tempus-list-events">Listar eventos</a>';
	echo '</div>';
	return;
} else {
	echo 'Ocorreu algum problema ao adicionar o evento';
}
echo '<br />';
echo '<a href="admin.php?page=tempus-list-events">Listar eventos</a>';
echo '</div>';
}
?>
<style type="text/css">
.tempus label{
	clear:both;
	display:block;
}
.tempus label.no-clear{
	clear:none;
	display:inline;
}
</style>
<div class="wrap tempus">
<h2>Adicionar evento</h2>

<form method="post" action="admin.php?page=tempus-add-event&e=<?php echo base64_encode((int)$evento->id); ?>">
    <label>
    	T&iacute;tulo
    </label>
    <input type="text" id="title" name="title" value="" />
    <br />
    <label>
    	Descri&ccedil;&atilde;o
    </label>
    <textarea name="description" id="description" cols="30" rows="5"></textarea>
    
    
    <br />
    <label>
    	Data inicial
    </label>
    <input type="text" id="start-time" name="start-time" value="" />
    <br />
    
    <label>
    	Data final
    </label>
    <input type="text" id="end-time" name="end-time" value="" />
    <br />
    <input type="checkbox" id="all-day" name="all-day" value="all-day" />
    <label class="no-clear">
    	O dia todo
    </label>
    <br />
    
    <label>
    	Imagem
    </label>
	<label for="upload_image">
		<input id="upload_image" type="text" size="36" name="upload_image" value="" />
		<input id="upload_image_button" type="button" value="Upload Image" />
		<br />Informe uma URL ou envie uma imagem do seu computador
	</label>
    <br />
    <p class="submit">
    <input type="submit" id="send-add-event" name="send-add-event" class="button-primary" value="<?php _e('Add') ?>" />
    </p>

</form>
</div>
<?php }


public static function tempus_edit_event_page() {
global $wpdb;
$e=(int)base64_decode($_GET['e']);
if ($e==0) {
	throw new Exception('Codigo de evento invalido');
} else {
	$sql="SELECT * FROM `".WPTEMPUS_TABLE."` WHERE id='".$e."' LIMIT 1";
	$evento=$wpdb->get_results($sql);
	$evento=$evento[0];
}
if (post('send-edit-event')) {
echo '<div class="wrap tempus">';
echo '<h2>Adicionado evento</h2>';
$title=post('title');
$description=post('description');
$start=post('start-time');
$end=post('end-time');
$allday=(bool)post('all-day');
$image=post('upload_image');
if ($image!='') {
$sql="UPDATE `".WPTEMPUS_TABLE."` SET title='".$title."',description='".$description."',start_time='".$start."',end_time='".$end."',all_day='".$allday."',image='".$image."' WHERE id='".(int)$evento->id."' LIMIT 1";
} else {
$sql="UPDATE `".WPTEMPUS_TABLE."` SET title='".$title."',description='".$description."',start_time='".$start."',end_time='".$end."',all_day='".$allday."' WHERE id='".(int)$evento->id."' LIMIT 1";
}
$alterado=$wpdb->query($sql);
if($alterado) {
	echo 'Evento alterado com sucesso';
	echo '<br />';
	echo '<a href="admin.php?page=tempus-list-events">Listar eventos</a>';
	echo '</div>';
	return;
} else {
	echo 'Ocorreu algum problema ao alterar o evento';
}
echo '<br />';
echo '<a href="admin.php?page=tempus-list-events">Listar eventos</a>';
echo '</div>';
}

?>
<style type="text/css">
.tempus label{
	clear:both;
	display:block;
}
.tempus label.no-clear{
	clear:none;
	display:inline;
}
</style>
<div class="wrap tempus">
<h2>Editar evento</h2>

<form method="post" action="admin.php?page=tempus-edit-event&e=<?php echo base64_encode((int)$evento->id); ?>">
    <label>
    	T&iacute;tulo
    </label>
    <input type="text" id="title" name="title" value="<?php echo $evento->title; ?>" />
    <br />
    <label>
    	Descri&ccedil;&atilde;o
    </label>
    <textarea name="description" id="description" cols="30" rows="5"><?php echo $evento->description; ?></textarea>
    
    
    <br />
    <label>
    	Data inicial
    </label>
    <input type="text" id="start-time" name="start-time" value="<?php echo $evento->start_time; ?>" />
    <br />
    
    <label>
    	Data final
    </label>
    <input type="text" id="end-time" name="end-time" value="<?php echo $evento->end_time; ?>" />
    <br />
    <input type="checkbox" id="all-day" name="all-day" value="all-day" <?php if ($evento->all_day) {echo "checked=\"checked\"";} ?> />
    <label class="no-clear">
    	O dia todo
    </label>
    <br />

    <p class="submit">
    <input type="submit" id="send-edit-event" name="send-edit-event" class="button-primary" value="<?php _e('Save Changes') ?>" />
    <input type="button" class="button-secundary" value="<?php _e('Excluir') ?>" onclick="if(confirm('Tem certeza que deseja excluir?')){document.location.href='admin.php?page=tempus-del-event&e=<?php echo base64_encode((int)$evento->id); ?>';}" />
    </p>

</form>
</div>
<?php }


public static function tempus_del_event_page() {
    global $wpdb;
echo '<div class="wrap tempus">';
echo '<h2>Remover evento</h2>';
$e=(int)base64_decode($_GET['e']);
if ($e==0) {
	throw new Exception('Codigo de evento invalido');
} else {
	$sql="SELECT * FROM `".WPTEMPUS_TABLE."` WHERE id='".$e."' LIMIT 1";
	$evento=$wpdb->get_results($sql);
	if (sizeof($evento)==1) {
		$sql="DELETE FROM `".WPTEMPUS_TABLE."` WHERE id='".$e."' LIMIT 1";
		$evento=$wpdb->query($sql);
		echo 'Evento removido com sucesso';
	} else {
		echo 'Evento n&atilde;o encontrado';
	}
}
echo '<br />';
echo '<a href="admin.php?page=tempus-list-events">Listar eventos</a>';
echo '</div>';
}

public static function tempus_list_events_page() {
    global $wpdb;
?>
<style type="text/css">
.tempus table{
	width:80%;
	margin:auto;
}
.tempus table th{
	background-color:#444;
	color:#fff;
	margin:3px;
	padding:5px;
}
.tempus td{
	margin:3px;
	border:1px solid #444;
	padding:5px;
}
</style>
<div class="wrap tempus">
<h2>Eventos</h2>
<?php
	$sql="SELECT * FROM `".WPTEMPUS_TABLE."` ORDER BY id";
	$eventos=$wpdb->get_results($sql);
?>
<?php if (sizeof($eventos)>0): ?>
<table>
	<tr>
		<th>
			Evento
		</th>
		<th>
			Data Inicial
		</th>
		<th>
			Data Final
		</th>
		<th colspan="2">
			A&ccedil;&otilde;es
		</th>
	</tr>

<?php foreach ($eventos as $evento): ?>
	<tr>
		<td>
			<?php echo $evento->title; ?>
		</td>
		<td>
			<?php echo $evento->start_time; ?>
		</td>
		<td>
			<?php echo $evento->end_time; ?>
		</td>
		<td>
			<a href="admin.php?page=tempus-edit-event&e=<?php echo base64_encode((int)$evento->id); ?>">Editar</a>
		</td>
		<td>
			<a href="admin.php?page=tempus-del-event&e=<?php echo base64_encode((int)$evento->id); ?>" onclick="if(confirm('Tem certeza que deseja excluir?')){return true;}else{return false;}">Excluir</a>
		</td>
	</tr>

<?php endforeach; ?>
</table>
<?php else: ?>
	Nenhum evento cadastrado!
	<br />
	<a href="admin.php?page=tempus-add-event">Adicione agora mesmo!</a>
<?php endif; ?>
</div>
<?php }



public function tempus_about_page() {
?>
<div class="wrap tempus">
<h2>Sobre o Tempus Project</h2>
Este plugin foi criado por <a href="http://patrickkaminski.com/" target="_blank">Patrick Kaminski</a> e Sibanir J. Lombardi para atender as necessidades que muitas pessoas encontram no momento em que desejam incluir uma listagem de eventos em sites baseados no Wordpress.<br /><br />
Maiores informa&ccedil;&otilde;es: <a href="http://tempusproject.wordpress.com/" target="_blank">http://tempus-project.wordpress.com/</a>
<?php
}
}


add_action('admin_menu', array('TempusAdmin','create_menu'));  



function my_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_register_script('my-upload', WP_PLUGIN_URL.'/'.WPTEMPUS_PLUGIN_NAME.'/script.js', array('jquery','media-upload','thickbox'));
wp_enqueue_script('my-upload');
}

function my_admin_styles() {
wp_enqueue_style('thickbox');
}

if ((isset($_GET['page']))&&(($_GET['page']=='tempus-add-event')||($_GET['page']=='tempus-edit-event'))) {
add_action('admin_print_scripts', 'my_admin_scripts');
add_action('admin_print_styles', 'my_admin_styles');
}

 
