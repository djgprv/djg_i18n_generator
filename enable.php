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
$comment_header = "/**\n* {{language}} file for plugin {{plugin_name}}\n*\n* @package wolf\n* @subpackage {{plugin_name}}\n*\n* @generated by djg_i18n_generator WolfCMS plugin - Michał Uchnast <djgprv@gmail.com>\n*\n**/\n";
$settings = array(
    'version' => '0.3',
	'comment_header' => $comment_header,
	'comment_file' => '1',
	'array_unique' => '1',
	'editor_line_wrapping' => '0',
	
);
// Insert the new ones
if (Plugin::setAllSettings($settings, 'djg_i18n_generator'))
    Flash::setNow('success', __('djg_i18n_generator - plugin settings initialized.'));
else
    Flash::setNow('error', __('djg_i18n_generator - unable to store plugin settings!'));
