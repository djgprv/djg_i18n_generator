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
		echo '<div class="flow";><ol class="lines">'; 
		while ($line = fgets($fp)){
			$matches = array();		
			preg_match_all("/=> *?'(.*?)',/", $line, $matches);
			//print_r($matches[1]);
			if(count($matches[1])>0):
				echo '<li class="code">'.$matches[1][0].'[=>]'.$matches[1][0].'</li>';
			elseif($line!=''):
				echo '<li>'.$line.'</li>';
			endif;
			
		}
		echo '</ol></div>';
		
?>
<img class="translate_file" src="<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>wolf/plugins/djg_i18n_generator/images/32_translate.png" alt="<?php echo __('Translate file'); ?>" title="<?php echo __('Translate file'); ?>" />
<img style="display: none;" class="preloader" src="<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>wolf/plugins/djg_i18n_generator/images/32_preloader.gif" alt="<?php echo __('Please wait'); ?>" title="<?php echo __('Please wait'); ?>" />
<img style="display: none;" class="stop_translate_process" src="<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>wolf/plugins/djg_i18n_generator/images/32_stop.png" alt="<?php echo __('Stop process'); ?>" title="<?php echo __('Stop process'); ?>" />
<?php endif; ?>
<textarea class="content"></textarea>
<img style="display: none;" class="save_file" src="<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>wolf/plugins/djg_i18n_generator/images/32_save_file.png" alt="<?php echo __('Save file'); ?>" title="<?php echo __('Save file'); ?>" />
<a href="#" style="display: none;" class="clipboard" >Copy to clipboard</a>
<?php
endif;
?>
</div>
<script type="text/javascript"> 
//<![CDATA[
var linesArray = new Array();
var value = '';
var translate_in_progress = 0;
function translate() {
	value = linesArray.shift();
	console.log(value);
	$.ajax({ 
		type: "GET", 
		data: {'lang':'<?php echo $_POST['lang']; ?>','aa':value},
		dataType: "json", cache: true,
		url: '<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>djg_i18n_generator/translate_file.php',
		contentType: "application/json; charset=utf-8", 
		beforeSend: function() {},
		error: function() {}, 
		success: function(data) {
			if(data.error!=0)
			{
				$('.content').append("<?php echo __('Translate line error.'); ?>\n");
			}else{
				$('.content').append(data['line']);
			}
		},
		complete: function() {
			if ((linesArray.length > 0) && (translate_in_progress == 1)) {
				translate();
			}else{
				console.log('done');
				$('.translate_file').show();
				$('.preloader').hide();
				$('.stop_translate_process').hide();
				$('.save_file').show();
			};
		}
	});
};
$(document).ready(function(){
	$(".translate_file").click(function(){
		var action = confirm('<?php echo __('It can take a few minutes.'); ?>');
		if(action){
			translate_in_progress = 1;
			$('.translate_file').hide();
			$('.preloader').show();
			$('.stop_translate_process').show();
			$('.lines').find('li').each(function(){ linesArray.push( $(this).text() ); });;
			$('.content').html('&lt;?php\n');
			translate();
		};
		return false;
	});
	$(".stop_translate_process").click(function(){
		translate_in_progress = 0;
	});
	$(".save_file").click(function(){
		var action = confirm('<?php echo __('Do you want to modify the existing file?'); ?>');
		if(action){
			$.ajax({ 
					type: "POST", 
					data: {'file_name':'<?php echo $file_name; ?>','plugin_name':'<?php echo $plugin_name; ?>','content':$('.content').val()},
					dataType: "json",
					url: '<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>djg_i18n_generator/save_file.php',
					beforeSend: function() {},
					error: function(jqXHR, exception) {alert('<?php echo __('Ajax error status: '); ?>'+jqXHR.status);}, 
					success: function(data) {
						if(data.error!=0)
						{
							showAlert('<?php echo __('The file was not saved.'); ?>','error');
						}else{
							showAlert('<?php echo __('The file has been saved as :name',array(':name'=>$file_name)); ?>','ok');
						}
					},
					complete: function() {}
				});
		};
		return false;
	});
	$('.clipboard').zclip({
		path:'<?php echo PLUGINS_URI; ?>djg_i18n_generator/assets/ZeroClipboard.swf',
		copy:$('.content').val(),
        afterCopy:function(){ showAlert('<?php echo __('Copied to clipboard'); ?>','alert'); }
    });
});

//]]>
</script>