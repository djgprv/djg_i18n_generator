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

Plugin::setInfos(array(
    'id'          => 'djg_i18n_generator',
    'title'       => __('[djg] i18n Generator'),
    'description' => __('[djg] i18n Generator'),
    'version'     => '0.4',
   	'license'     => 'GPL',
	'author'      => 'Michał Uchnast',
    'website'     => 'http://www.kreacjawww.pl/',
    'update_url'  => 'https://raw.githubusercontent.com/djgprv/djg_i18n_generator/master/versions.xml',
    'require_wolf_version' => '0.7.3',
	'type'			=>	'both'
));
AutoLoader::addFolder(dirname(__FILE__) . '/models');
Plugin::addController('djg_i18n_generator', __('[djg] i18n Generator'), true, true);
Plugin::addJavascript('djg_i18n_generator', 'assets/jquery.zclip.js');
/** code mirror */
Plugin::addJavascript('djg_i18n_generator', 'assets/codemirror/codemirror.js');
Plugin::addJavascript('djg_i18n_generator', 'assets/codemirror/matchbrackets.js');
Plugin::addJavascript('djg_i18n_generator', 'assets/codemirror/htmlmixed.js');
Plugin::addJavascript('djg_i18n_generator', 'assets/codemirror/xml.js');
Plugin::addJavascript('djg_i18n_generator', 'assets/codemirror/javascript.js');
Plugin::addJavascript('djg_i18n_generator', 'assets/codemirror/css.js');
Plugin::addJavascript('djg_i18n_generator', 'assets/codemirror/clike.js');
Plugin::addJavascript('djg_i18n_generator', 'assets/codemirror/php.js');
Plugin::addJavascript('djg_i18n_generator', 'assets/codemirror/fullscreen.js');

Dispatcher::addRoute(array(
	/* backend */
	'/djg_i18n_generator/save_file.php' => '/plugin/djg_i18n_generator/save_file', //ajax
	'/djg_i18n_generator/translate_file.php' => '/plugin/djg_i18n_generator/translate_file' //ajax
));