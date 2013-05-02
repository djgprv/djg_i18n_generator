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
 * The djg_i18n_generator plugin

 * @author Michał Uchnast <djgprv@gmail.com>,
 * @copyright kreacjawww.pl
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 license
 */
?>
<p class="button"><a href="<?php echo get_url('plugin/djg_i18n_generator/en_language'); ?>"><img src="<?php echo URL_PUBLIC; ?>wolf/plugins/djg_i18n_generator/images/32_en.png" align="middle" alt="settings icon" /> <?php echo __('English language'); ?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/djg_i18n_generator/other_languages'); ?>"><img src="<?php echo URL_PUBLIC; ?>wolf/plugins/djg_i18n_generator/images/32_euro.png" align="middle" alt="settings icon" /> <?php echo __('Other languages'); ?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/djg_i18n_generator/settings'); ?>"><img src="<?php echo URL_PUBLIC; ?>wolf/plugins/djg_i18n_generator/images/32_settings.png" align="middle" alt="settings icon" /> <?php echo __('Settings'); ?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/djg_i18n_generator/documentation/'); ?>"><img src="<?php echo URL_PUBLIC; ?>wolf/plugins/djg_i18n_generator/images/32_documentation.png" align="middle" alt="documentation icon" /> <?php echo __('Documentation'); ?></a></p>
<div class="box">
    <h2><?php echo __('Dialog window'); ?></h2>
	<p>_</p>
</div>
