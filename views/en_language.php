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
		$a[] = "/** $key */";

		foreach ($value as $fl1) {
			$a[] =  "'".$fl1."' => '".$fl1."',";
		}
	}
	$a = array_unique($a);


$lang = 'English';
$output = "&lt;?php
/**
* $lang file for plugin $plugin_name
*
* @package wolf
* @subpackage $plugin_name
*
* @generated by djg_i18n_generator WolfCMS plugin - Michał Uchnast <djgprv@gmail.com>
*
**/
return array(
";
	foreach ($a as $value) {
		$output .= "$value \n";
	}
	$output .= ");";
	?>
	<textarea rows="20"><?php echo $output; ?></textarea>
	<?php

	/*

	*/

	$mtime = explode(' ', microtime());
	$totaltime = $mtime[0] + $mtime[1] - $starttime;
	printf('Loaded in %.3f seconds.', $totaltime);

endif;