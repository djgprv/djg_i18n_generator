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
		$fp = fopen($file, 'r');
		$lines = array();
		$lines_tmp = array();
		while ($line = fgets($fp)){
			$lines_tmp = array_merge(array_filter(explode(';',$line)));
			foreach($lines_tmp as $new_line ) $lines[] = ltrim($new_line);
		}
		foreach($lines as $line){
			$matches = array();	
			preg_match_all("/\/\/(.*?)/", $line, $matches0); //comments
			preg_match_all("/\/\*(.*?)/", $line, $matches1);	/* comments	*/
			preg_match_all("/__\(('|\")(?P<phrase>((?!__)|(?! ?array ?\().)+)('\)|'*,|\"\)|\",)/u", $line, $matches2);
			if( (count($matches0[1])>0) || (count($matches1[1])>0) ):
				continue;
			elseif($matches2['phrase']):
				$lines_array[$file][] = trim($matches2['phrase'][0]);
			endif;
		}
	}
	//echo'<pre>';print_r($lines_array);echo'</pre>';
	/** unique */
	$a = array();
	foreach ($lines_array as $key => $value) {
		if($add_comments == '1')$a[] = "/** $key */";

		foreach ($value as $fl1) {
			$a[] =  "'".$fl1."' => '".$fl1."',";
		}
	}
	$a = (Plugin::getSetting('array_unique','djg_i18n_generator')) ? array_unique($a) : $a; // unique array
	

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
	<textarea id="code" class="code content"><?php echo $output; ?></textarea>
	<img class="save_file" src="<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>wolf/plugins/djg_i18n_generator/images/32_save_file.png" alt="<?php echo __('Save file'); ?>" title="<?php echo __('Save file'); ?>" />
	<a href="#" class="clipboard" >Copy to clipboard</a>
	<?php
	$mtime = explode(' ', microtime());
	$totaltime = $mtime[0] + $mtime[1] - $starttime;
	$totaltime = number_format($totaltime,2);
	echo __('Loaded in :time second(s).',array(':time'=> $totaltime));
endif;
?>
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
$(document).ready(function() {
	$(".save_file").click(function(){
		var action = confirm('<?php echo __('Do you want to modify the existing file?'); ?>');
		if(action){
			$.ajax({ 
					type: "POST", 
					data: {'file_name':'<?php echo $file_name; ?>','plugin_name':'<?php echo $plugin_name; ?>','content':editor.getValue()},
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
		copy: function() {return editor.getValue();},
        afterCopy:function(){ showAlert('<?php echo __('Copied to clipboard'); ?>','alert'); }
    });
});
//]]>
</script>
