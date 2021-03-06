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
<h1><?php echo __('Settings'); ?></h1>
<form action="<?php echo get_url('plugin/djg_i18n_generator/save'); ?>" method="post">
    <fieldset style="padding: 0.5em;">
        <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
			<tr>
                <td class="label"><label for="settings_omment_file"><?php echo __('Header'); ?>: </label></td>
                <td class="field">
					<textarea id="settings_omment_file" rows="10" cols="50" name="settings[comment_header]"><?php echo $settings['comment_header']; ?></textarea>
				</td>
				<td><?php echo __(''); ?></td>
			</tr>
			<tr>
                <td class="label"><label for="settings_comment_file"><?php echo __('Add a comments'); ?>: </label></td>
                <td class="field" colspan="2">
					<select id="settings_comment_file" name="settings[comment_file]">
						<option value="1" <?php if($settings['comment_file'] == "1") echo 'selected="selected"' ?>><?php echo __('Yes'); ?></option>
						<option value="0" <?php if($settings['comment_file'] == "0") echo 'selected="selected"' ?>><?php echo __('No'); ?></option>
					</select>	
				</td>
			</tr>
			<tr>
                <td class="label"><label for="settings_array_unique"><?php echo __('Array unique'); ?>: </label></td>
                <td class="field" colspan="2">
					<select id="settings_array_unique" name="settings[array_unique]">
						<option value="1" <?php if($settings['array_unique'] == "1") echo 'selected="selected"' ?>><?php echo __('Yes'); ?></option>
						<option value="0" <?php if($settings['array_unique'] == "0") echo 'selected="selected"' ?>><?php echo __('No'); ?></option>
					</select>	
				</td>
			</tr>
			<tr>
                <td class="label"><label for="settings_editor_line_wrapping"><?php echo __('Line wrapping'); ?>: </label></td>
                <td class="field" colspan="2">
					<select id="settings_editor_line_wrapping" name="settings[editor_line_wrapping]">
						<option value="1" <?php if($settings['editor_line_wrapping'] == "1") echo 'selected="selected"' ?>><?php echo __('Yes'); ?></option>
						<option value="0" <?php if($settings['editor_line_wrapping'] == "0") echo 'selected="selected"' ?>><?php echo __('No'); ?></option>
					</select>	
				</td>
			</tr>

        </table>
    </fieldset>
    <br/>
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
    </p>
</form>

<script type="text/javascript">
// <![CDATA[
    function setConfirmUnload(on, msg) {
        window.onbeforeunload = (on) ? unloadMessage : null;
        return true;
    }

    function unloadMessage() {
        return '<?php echo __('You have modified this page.  If you navigate away from this page without first saving your data, the changes will be lost.'); ?>';
    }

    $(document).ready(function() {
        // Prevent accidentally navigating away
        $(':input').bind('change', function() { setConfirmUnload(true); });
        $('form').submit(function() { setConfirmUnload(false); return true; });
    });
// ]]>
</script>