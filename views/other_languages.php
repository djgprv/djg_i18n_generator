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
			//if( (!empty($plugin)) and ($_POST['plugin_name'] == $plugin) ) $selected = 'selected'; else $selected = '';
			if ($key != 'en') echo '<option '.$lang['name'].' value="'.$key.'"> '.$lang['name'].' </option>';
		}
	?>
</select>
<input class="button" type="submit" value="<?php echo __('Translate'); ?>" />
</form>
<?php
if( (isset($_POST['plugin_name'])) && (!empty($_POST['plugin_name'])) ):
?>
<textarea class="example"></textarea>
<textarea class="content"></textarea>
<input class="button" type="submit" value="<?php echo __('Translate'); ?>" />
<?
	echo 'Works very sloooow but is for free;) => '. DjgI18nGeneratorController::translate($_POST['lang'],'Works very sloooow but is for free;)');
endif;
?>
</div>