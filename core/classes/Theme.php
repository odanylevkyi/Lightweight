<?php

namespace core\classes;

use core\interfaces\ITheme;
use App;
use core\interfaces\IObject;

class Theme implements ITheme {
	protected static $_css = [];
	protected static $_js = [];
	protected static $_dependencies = [];
	
	public static function register() {
		foreach (self::$_css as $file) {
			print self::registerCssFile($file);
		}
		foreach (self::$_js as $file) {
			print self::registerJsFile($file);
		}
	}
	
	public static function addDependency($dependencies = []) {
		foreach ($dependencies as $theme_name) {
			$theme = 'themes\\'.$theme_name.'\\'.ucfirst($theme_name).'Theme';
			$data = $theme::init();
			if (!empty($data['css'])) {
				$theme::initCss($data['css']);
			}
			if (!empty($data['js'])) {
				$theme::initJs($data['js']);
			}
			if (!empty($data['dependency'])) {
				$theme::addDependency($data['dependency']);
			}
			array_push(self::$_dependencies, $theme_name);
		}
	}
	
	public static function initCss($css = []) {
		$theme = App::getAlias('themes').'/'.self::themeName().'/css';
		foreach ($css as $value) {
			array_push(self::$_css, $theme.'/'.$value);
		}
	}
	
	public static function initJs($js = []) {
		$theme = App::getAlias('themes').'/'.self::themeName().'/js';
		foreach ($js as $value) {
			array_push(self::$_js, $theme.'/'.$value);
		}
	}
	
	public static function registerCssFile($file) {
		return Html::cssFile($file);
	}
	public static function registerCss() {
	}
	
	public static function registerJSFile($file) {
		return Html::jsFile($file);
	}
	public static function registerJS() {
		
	}
	
	public static function themeName() {
		$className = get_called_class();
		$from = strpos($className, "\\") + 1;
		return strtolower(substr($className, $from, strrpos($className, "\\") - $from));
	}
}