<?php

namespace Intl;

class Intl{

    public static $_instance = false; //used in __callStatic
    protected static $_langcodes = array(); //the supported langcodes configured in i18n.php
    protected static $_default_language; //used in forge()
    protected static $_default_locale; //used in forge()
    protected $_defaults; //used in __construct()

    public function __construct(){
        $this->_defaults['language'] = \Config::get("language");
        $this->_defaults['language_fallback'] = \Config::get("language_fallback");
        $this->_defaults['locale'] = \Config::get("locale");
        static::$_default_language = \Config::get("language");
        static::$_default_locale = \Config::get("locale");
        \Config::load("i18n",true);
        static::$_langcodes = \Config::get("i18n.langcodes");
    }

    public static function forge(){
        if(static::$_instance === false){
      		$instance = new static();
            static::$_instance = $instance;
      	}

        return static::$_instance;
    }

    public function setLanguage($language=null){
        if(!isset($language)){
            $language = $this->_getClientLanguage();
        }
        if($this->_isValidLangCode($language)){
            \Config::set('language',$language);
            $locale = $this->_setLocale($language);
            $this->_inizializeGettext($locale);
        }else{
            throw new InvalidLangCodeException($language.' is an invalid langcode');
        }
    }

    private function _setLocale($language){
        if($this->_isValidLangCode($language)){
            $locale = static::$_langcodes[$language];
            \Config::set('locale',$locale);
            return $locale;
        }else{
            throw new InvalidLangCodeException($language.' is an invalid langcode');
        }
    }

    private function _inizializeGettext($locale){
        setlocale(LC_ALL, $locale);
        setlocale(LC_TIME, $locale);
        putenv("LANG=$locale");
        bindtextdomain("messages", APPPATH."/locale");
        bind_textdomain_codeset("messages",'UTF-8');
        textdomain("messages");
    }

    /**
     * Returns the "language" as set in config.php
     * @static
     * @return mixed
     * @usage $Intl = Intl::forge()->getDefaultLanguage();
     */
    public function getDefaultLanguage(){
        return static::$_default_language;
    }

    /**
     * Returns the "locale" as set in config.php
     * @static
     * @return mixed
     * @usage $Intl = Intl::forge()->getDefaultLocale();
     */
    public function getDefaultLocale(){
        return static::$_default_locale;
    }

    /**
     * Returns Config::get("language"). setLanguage() alter this field, so it may differ from the initial value.
     * @static
     * @return mixed
     */
    public static function getCurrentLanguage(){
        return \Config::get("language");
    }

    /**
     * Returns Config::get("locale"). setLanguage() alter this field, so it may differ from the initial value.
     * @static
     * @return mixed
     */
    public static function getCurrentLocale(){
        return \Config::get("locale");
    }

    /**
     * Detect client browser language
     * @static
     * @return mixed
     */
    public static function getClientLanguage(){
        return static::_getClientLanguage();
    }

    public static function isSupported($language){
        if(array_key_exists($language,static::$_langcodes)){
            return true;
        }else{
            return false;
        }
    }

    public static function translateUri($language,$fullurl=true){
        $uri = \Uri::string();
        $baseurl = \Uri::base();
        $pattern = "[".implode("|",array_keys(static::$_langcodes))."]";

        if(preg_match($pattern,\Uri::segment(1),$matches) == 1){
            if($matches[0] == $language){
                if($fullurl) return $baseurl.$uri; else return $uri;
            }
            else{
                $segments = \Uri::segments();
                $segments[0] = $language;
                $new_uri = implode("/",$segments);
                if($fullurl) return $baseurl.$new_uri; else return $new_uri;
            }
        }else{
            if($fullurl) return $baseurl."{$language}/".$uri; else return "{$language}/".$uri;
        }
    }

    public static function _t($string,$domain_path=null){
        if(!isset($domain_path)){
            $translated_string = _($string);
        }else{
            bindtextdomain("messages", $domain_path."/locale");
            bind_textdomain_codeset("messages",'UTF-8');
            textdomain("messages");
            $translated_string = _($string);
            bindtextdomain("messages", APPPATH."/locale");
            bind_textdomain_codeset("messages",'UTF-8');
            textdomain("messages");
        }
        return $translated_string;
    }

    private static function _getClientLanguage(){
        $langcode = (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
        $langcode = (!empty($langcode)) ? explode(";", $langcode) : $langcode;
        $langcode = (!empty($langcode['0'])) ? explode(",", $langcode['0']) : $langcode;
        $langcode = (!empty($langcode['0'])) ? explode("-", $langcode['0']) : $langcode;
        return $langcode['0'];
    }

    private static function _isValidLangCode($language){
        if(!array_key_exists($language,static::$_langcodes)) throw new InvalidLangCodeException($language.' is an invalid langcode');
        else return true;
    }

    public static function __callStatic($method, $args = array()){
   		if(static::$_instance === false){
   			$instance = static::forge();
   			static::$_instance = $instance;
   		}

   		if(is_callable(array(static::$_instance, $method))){
   			return call_user_func_array(array(static::$_instance, $method), $args);
   		}

   		throw new \BadMethodCallException('Invalid method: '.get_called_class().'::'.$method);
   	}
}

class IntlException extends \Fuel\Core\FuelException {}
class InvalidLangCodeException extends \Fuel\Core\FuelException {}