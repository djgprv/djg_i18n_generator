<?php
/*
 * Wolf CMS - Content Management Simplified. <http://www.wolfcms.org>
 * Copyright (C) 2008-2010 Martijn van der Kleijn <martijn.niji@gmail.com>
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
class Djgi18nGeneratorController extends PluginController {

    public function __construct() {
        $this->setLayout('backend');
		$this->assignToLayout('sidebar', new View('../../plugins/djg_i18n_generator/views/sidebar'));
    }
	public function index() {
        $this->documentation();
    }
    public function documentation() {
        $this->display('djg_i18n_generator/views/documentation');
    }
    function settings() {
        $this->display('djg_i18n_generator/views/settings', array('settings' => Plugin::getAllSettings('djg_i18n_generator')));
    }
    function save() {
		$settings = $_POST['settings'];

        $ret = Plugin::setAllSettings($settings, 'djg_i18n_generator');

        if ($ret)
            Flash::set('success', __('The settings have been updated.'));
        else
            Flash::set('error', 'An error has occurred while trying to save the settings.');

        redirect(get_url('plugin/djg_i18n_generator/settings'));
	}
    function en_language() {
		$plugins = Djgi18nGenerator::getPluginsList();
		$this->display('djg_i18n_generator/views/en_language', array('plugins' =>$plugins));
	}
    function other_languages() {
		$this->display('djg_i18n_generator/views/other_languages');
	}
}