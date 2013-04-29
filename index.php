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
    'version'     => '0.0.1-dev.',
   	'license'     => 'GPL',
	'author'      => 'Michał Uchnast',
    'website'     => 'http://www.kreacjawww.pl/',
    'update_url'  => 'http://kreacjawww.pl/public/wolf_plugins/plugin-versions.xml',
    'require_wolf_version' => '0.7.3',
	'type'			=>	'both'
));

Plugin::addController('djg_i18n_generator', __('[djg] i18n Generator'), true, true);
Dispatcher::addRoute(array(
	/* backend */
	'/djg_i18n_generator/save_file.php' => '/plugin/djg_i18n_generator/save_file', //ajax
	'/djg_i18n_generator/translate_file.php' => '/plugin/djg_i18n_generator/translate_file' //ajax
));