<?php
$settings = array(
    'version' => '0.0.1',
	'comment_file' => '1',
);
// Insert the new ones
if (Plugin::setAllSettings($settings, 'djg_i18n_generator'))
    Flash::setNow('success', __('djg_i18n_generator - plugin settings initialized.'));
else
    Flash::setNow('error', __('djg_i18n_generator - unable to store plugin settings!'));
