<?php
/*
 * Wolf CMS - Content Management Simplified. <http://www.wolfcms.org>
 * Copyright (C) 2008-2013 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Wolf CMS. Wolf CMS is licensed under the GNU GPLv3 license.
 * Please see license.txt for the full license text.
 */

/* Security measure */
if (!defined('IN_CMS')) { exit(); }

/**
 *
 * Note: to use the settings and documentation pages, you will first need to enable
 * the plugin!
 *
 * @package Plugins
 * @subpackage djg_i18n_generator
 *
 * @author Michał Uchnast <djgprv@gmail.com>,
 * @copyright kreacjawww.pl
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 license
 */
?>
<h1><?php echo __('Basic en-message.php file generator.'); ?></h1>
<div id="djg_i18n_generator">
<form method="POST">
<label for="plugin_name"><?php echo __('Plugin'); ?>: </label>
<select id="plugin_name" name="plugin_name">
<option value=""><?php echo __('- chose plugin -'); ?></option>
	<?php
		foreach($plugins as $plugin)
		{
			if( (!empty($plugin)) and ($_POST['plugin_name'] == $plugin) ) $selected = 'selected'; else $selected = '';
			echo '<option '.$selected.' value="'.$plugin.'"> '.$plugin.' </option>';
		}
	?>
</select>
<?php

$file_ext = (isset($_POST['files']))?$_POST['files']:'*.*';
$add_comments = Plugin::getSetting('comment_file','djg_i18n_generator');
?>
<label for="files"><?php echo __('Files'); ?>: </label>
<input name="files" id="files" value="<?php echo $file_ext; ?>"/>
<input class="button" type="submit" accesskey="s" value="<?php echo __('Generate'); ?>" />
</form>

<?php
if( (isset($_POST['plugin_name'])) && (!empty($_POST['plugin_name'])) ):

	$starttime = explode(' ', microtime());
	$starttime = $starttime[1] + $starttime[0];
	function rglob($pattern='*', $flags = 0, $path='')
	{
		$paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
		$files=glob($path.$pattern, $flags);
		foreach ($paths as $path) { $files=array_merge($files,rglob($pattern, $flags, $path)); }
		return $files;
	}

	$plugin_name = $_POST['plugin_name'];
	$file_name = 'en-message.php';
	chdir(CORE_ROOT.DS.'plugins'.DS.$plugin_name);

	$files_list = array();
	$files_list = array_merge($files_list,rglob($file_ext));


	$lines_array = array();

	foreach ($files_list as $file) {
		//echo file_exists($file);	
		$fp = fopen($file, 'r');
		$lines = array();
		while ($line = fgets($fp)){
			$matches = array();		
			preg_match_all("/__\('(.*?)'\)/", $line, $matches);
			if(count($matches[1])>0):
				foreach ($matches[1] as $matches) {
					if (strpos($matches, ',array')) $matches = substr($matches, 0, strpos($matches, ',array')-1); // remove like __('Poll (:id) is :status now!',array(':id'=>$id,':status'=>'inactive')
					if (strpos($matches, ', array')) $matches = substr($matches, 0, strpos($matches, ', array')-1); // remove like __('Poll (:id) is :status now!', array(':id'=>$id,':status'=>'inactive')
					$lines_array[$file][] = $matches;
				}
			endif;
		}
	}

	/** unique */
	$a = array();
	foreach ($lines_array as $key => $value) {
		if($add_comments == '1')$a[] = "/** $key */";

		foreach ($value as $fl1) {
			$a[] =  "'".$fl1."' => '".$fl1."',";
		}
	}
	$a = array_unique($a);

$lang = DjgI18nGeneratorController::getLangs();
$lang = $lang['en']['name'];
$output = "&lt;?php\n";
$output .= DjgI18nGeneratorController::getHeader($lang,$plugin_name);
$output .= "return array(\n";
	foreach ($a as $value) {
		$output .= "$value \n";
	}
	$output .= ");";
	?>
	<textarea class="content"><?php echo $output; ?></textarea>
	<img class="save_file" src="<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>wolf/plugins/djg_i18n_generator/images/32_save_file.png" alt="<?php echo __('Save file'); ?>" title="<?php echo __('Save file'); ?>" />
	<?php

	/*

	*/

	$mtime = explode(' ', microtime());
	$totaltime = $mtime[0] + $mtime[1] - $starttime;
	printf('Loaded in %.3f seconds.', $totaltime);

endif;
?>
</div>
<script type="text/javascript">

var picsArray = new Array();
var value = null;
function sendNames() {
	return false;
};
$(document).ready(function() {
	$(".save_file").click(function(){
		var action = confirm('<?php echo __('Do you want to change the existing file?'); ?>');
		if(action){
			$.ajax({ 
					type: "GET", 
					data: {'file_name':'<?php echo $file_name; ?>','plugin_name':'<?php echo $plugin_name; ?>','content':$('.content').val()},
					dataType: "json", cache: true,
					url: '<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>djg_i18n_generator/save_file.php',
					contentType: "application/json; charset=utf-8", 
					beforeSend: function() {},
					error: function() {alert('<?php echo __('Unspecified ajax error.'); ?>');}, 
					success: function(data) {
						if(data.error!=0)
						{
							alert('<?php echo __('The file was not saved.'); ?>');
						}else{
							alert('<?php echo __('The file has been saved as :name',array(':name'=>$file_name)); ?>');
						}
					},
					complete: function() {}
				});
		};
		return false;
	});
});
</script>