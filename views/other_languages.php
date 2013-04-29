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
<h1><?php echo __('Other languages generator.'); ?></h1>
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
<label for="lang"><?php echo __('Language'); ?>: </label>
<select id="lang" name="lang">
	<?php
		foreach(DjgI18nGeneratorController::getLangs() as $key=>$lang)
		{
			if( (!empty($_POST['lang'])) and ($_POST['lang'] == $key) ) $selected = 'selected'; else $selected = '';
			if ($key != 'en') echo '<option '.$selected.'  value="'.$key.'"> '.$lang['name'].' </option>';
		}
		
	?>
</select>
<input class="button" type="submit" value="<?php echo __('Read en-message.php file'); ?>" />
</form>
<?php




if( (isset($_POST['plugin_name'])) && (!empty($_POST['plugin_name'])) ):
	$plugin_name = $_POST['plugin_name'];
	$file = CORE_ROOT.DS.'plugins'.DS.$plugin_name.DS.'i18n'.DS.'en-message.php';
	$file_name = $_POST['lang'].'-message.php';
	if(file_exists($file)):
		$fp = fopen($file, 'r');
		$lines = array();
		while ($line = fgets($fp)){
			$matches = array();		
			preg_match_all("/'(.*?)'/", $line, $matches);
			if(count($matches[1])>0):
				$json2[]['line'] = $matches[1][1];
			endif;
		}

	
	
	endif;
?>
<textarea class="content"></textarea>
<img class="translate_file" src="<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>wolf/plugins/djg_i18n_generator/images/32_translate.png" alt="<?php echo __('Translate file'); ?>" title="<?php echo __('Translate file'); ?>" />
<?php
endif;
?>
</div>
<script type="text/javascript">  
$(document).ready(function(){
	$(".translate_file").click(function(){
		var action = confirm('<?php echo __('It can take a view minutes.'); ?>');
		if(action){
			jQuery.each(<?php echo json_encode($json2); ?>, function(i, val) {
				$.ajax({ 
					type: "GET", 
					data: {'lang':'<?php echo $_POST['lang']; ?>','line':val['line']},
					dataType: "json", cache: true,
					url: '<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>djg_i18n_generator/translate_file.php',
					contentType: "application/json; charset=utf-8", 
					beforeSend: function() {},
					error: function() {alert('<?php echo __('Unspecified ajax error.'); ?>');}, 
					success: function(data) {
						$(".content").append(data['line'] + "\n");
						if(data.error!=0)
						{
							//alert('<?php echo __('Translate line error.'); ?>');
						}else{
							$(".content").append(data['line'] + "\n");
						}
					},
					complete: function() {}
				});
			}); 
		};
		return false;
	});
});
 </script>