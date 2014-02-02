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
class DjgI18nGeneratorController extends PluginController {
	static $langArray = array();

	function __construct() {
        AuthUser::load();
        if (defined('CMS_BACKEND')) {
            $this->setLayout('backend');
			self::$langArray['en']['name'] =  'English';
			self::$langArray['pl']['name'] =  'Polish';
			self::$langArray['ru']['name'] =  'Russian';
			self::$langArray['de']['name'] =  'German';
			$this->assignToLayout('sidebar', new View('../../plugins/djg_i18n_generator/views/sidebar'));
        }
    }
	public function index() {
        $this->en_language();
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
		$plugins = self::getPluginsList();
		$this->display('djg_i18n_generator/views/en_language', array('plugins' => $plugins));
	}
    function other_languages() {
		$plugins = self::getPluginsList();
		$this->display('djg_i18n_generator/views/other_languages', array('plugins' => $plugins));
	}
	/********/
	/* HELP */
	/********/
	function getPluginsList()
	{
		$directory = CORE_ROOT.DS.'plugins';
		if ($handle = opendir($directory)) while (false !== ($file = readdir($handle))) if ($file != "." && $file != "..") if (is_dir($directory. "/" . $file)) $array_items[] = $file; closedir($handle);
		return $array_items;	
	}
	public static function translate_tmp($lang,$text)
	{
		$url = 'http://google-translate-api.herokuapp.com/translate?from=en&to='.$lang.'&text%5B%5D='.urlencode($text);
		$obj = (array)json_decode(file_get_contents($url));
		return $obj[$text];
	}
	public static function translate($phrase, $from ='en', $to = 'de'){
		$ret ='';
		if(strlen($phrase)>1600){ //split and translate each part individually to get around 2048 size limit:
		$phrases = str_split($phrase,1600);
		foreach($phrases as $p) $ret .= translate($p, $from, $to);
		return($ret);
		}else{
		$url = "http://translate.google.com/translate_a/t?client=p&q=".urlencode($phrase)."&hl={$to}&sl={$from}&tl={$to}&ie=UTF-8&oe=UTF-8&multires=0" ;
		$out = json_decode(self::curl($url), 1);
		foreach($out['sentences'] as $sentence) {
		$ret .= $sentence['trans'].' ';
		}
		return stripslashes(trim($ret));
		}
		}
	
	public static function curl($url,$params = array(),$is_coockie_set = false)
	{
		if(!$is_coockie_set){
		/* STEP 1. let's create a cookie file */
		$ckfile = tempnam ("/tmp", "CURLCOOKIE");
		 
		/* STEP 2. visit the homepage to set the cookie properly */
		$ch = curl_init ($url);
		curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec ($ch);
		}
		 
		$str = ''; $str_arr= array();
		foreach($params as $key => $value)
		{
		$str_arr[] = urlencode($key)."=".urlencode($value);
		}
		if(!empty($str_arr))
		$str = '?'.implode('&',$str_arr);
		 
		/* STEP 3. visit cookiepage.php */
		 
		$Url = $url.$str;
		 
		$ch = curl_init ($Url);
		curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		 
		$output = curl_exec ($ch);
		return $output;
	}
	
	
	

	
	
	
	
	
	public static function getLangs()
	{
		return self::$langArray;
	}
	public static function getHeader($lang=null,$plugin_name=null)
	{
		if($lang && $plugin_name):
			$comment_header = Plugin::getSetting('comment_header','djg_i18n_generator');
			$comment_header = str_replace('{{language}}',$lang, $comment_header);
			$comment_header = str_replace('{{plugin_name}}',$plugin_name, $comment_header);
			return $comment_header;
		else:
			return false;
		endif;
	}
	/********/
	/* AJAX */
	/********/
	function save_file()
	{
		$json2['error'] = 1;
		$file_name = $_POST['file_name'];
		$plugin_name = $_POST['plugin_name'];
		$content = $_POST['content'];
		if (!file_exists(CORE_ROOT.DS.'plugins'.DS.$plugin_name.DS.'i18n')) mkdir(CORE_ROOT.DS.'plugins'.DS.$plugin_name.DS.'i18n', 0755, true);
		if(file_put_contents(CORE_ROOT.DS.'plugins'.DS.$plugin_name.DS.'i18n'.DS.$file_name, $content)) $json2['error'] = 0;
		echo json_encode($json2);
		exit();
	}
	function translate_file()
	{
		$json2['error'] = 0;
		if((strpos($_GET['aa'],'[=>]')) === false):
			$json2['line'] = $_GET['aa'];
		elseif($_GET['aa'] == '[=>]'):
			$json2['line'] = "'' => '',\n";
		else:
			$a = explode('[=>]',$_GET['aa']);
			$json2['line'] = "'" . $a[0] . "' => '" . self::translate($a[1],'en',$_GET['lang']) . "',\n";
		endif;
		echo json_encode($json2);
		exit();
	}
}