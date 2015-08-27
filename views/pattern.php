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
<h1><?php echo __('Pattern file'); ?></h1>
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
<input class="button" type="submit" value="<?php echo __('Generate pattern file'); ?>" />
</form>

<?php
if( (isset($_POST['plugin_name'])) && (!empty($_POST['plugin_name'])) ):
	$plugin_name = $_POST['plugin_name'];
	$file = CORE_ROOT.DS.'plugins'.DS.$plugin_name.DS.'i18n'.DS.'en-message.php';
	$lang = "pattern";
	$file_name = $lang.'-message.php';
	$header = "&lt;?php\n";
	$header .= DjgI18nGeneratorController::getHeader($lang,$plugin_name);
	$output = "";
	if(file_exists($file)):
		$fp = fopen($file, 'r');
		$lines = array();
		while ($line = fgets($fp)){
			$output .= preg_replace("/=> *?'(.*?)',/","=> '',",$line);
		}
	$output = preg_replace('/<\?php(.+?)return array/is','',$output);
	$output = $header.'return array'.$output
?>
<?php endif; echo '<textarea id="code" class="code content">'.$output.'</textarea>'; ?>

<img class="save_file" src="<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>wolf/plugins/djg_i18n_generator/images/32_save_file.png" alt="<?php echo __('Save file'); ?>" title="<?php echo __('Save file'); ?>" />
<a href="#" class="clipboard" >Copy to clipboard</a>
<?php endif; ?>
</div>

<script type="text/javascript"> 
//<![CDATA[
var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
	lineNumbers: true,
	matchBrackets: true,
	mode: "application/x-httpd-php",
	indentWithTabs: true,
	lineWrapping: <?php echo Plugin::getSetting('editor_line_wrapping','djg_i18n_generator'); ?>,
	extraKeys: {
        "F11": function(cm) {
          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function(cm) {
          if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
        }
    }
});
var charWidth = editor.defaultCharWidth(), basePadding = 4;
	editor.on("renderLine", function(cm, line, elt) {
		var off = CodeMirror.countColumn(line.text, null, cm.getOption("tabSize")) * charWidth;
		elt.style.textIndent = "-" + off + "px";
		elt.style.paddingLeft = (basePadding + off) + "px";
});
editor.refresh();
$(document).ready(function(){
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