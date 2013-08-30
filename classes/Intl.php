<?php

namespace Intl;

class Intl{

    /**
     * Instance for singleton usage.
     */
    public static $_instance = false;

    /**
     * Language config defaults.
     */
    protected $_defaults;

    public function __construct(){
        $this->_defaults['language'] = \Config::get("language");
        $this->_defaults['language_fallback'] = \Config::get("language_fallback");
        $this->_defaults['locale'] = \Config::get("locale");
    }

    /**
     * The forge() method saves call the constructor and saves the defaults values in $_instance()
     * todo: provare __init() e levare il costruttore
     */
    public static function forge(){
        if(static::$_instance === false){
            $instance = new static();
            static::$_instance = $instance;
        }

        return static::$_instance;
    }

    /** Set the system (language and locale) and gettext locale.
     *  @use _getClientLanguage() if no $language is provided
     */
    public function setLanguage($language=null,$force=false){
        if(!isset($language)){
            $language = self::getClientLanguage(true);
        }

        if(preg_match("|([a-z]+)_([a-zA-Z]+)|",$language,$matches)){
            $langcode = $matches[1];
            $countrycode = $matches[2];
        }else{
            $langcode = $language;
            $countrycode = strtoupper($language);
        }

        $bFully_supported = self::isSupportedLanguage($langcode,$countrycode);
        $bHalf_supported = self::isSupportedLanguage($langcode);
        $bIs_the_default = $langcode == $this->getDefaultLanguage() && $langcode."_".$countrycode == $this->getDefaultLocale() ? true : false;

        if($bFully_supported && !$force && !$bIs_the_default){
            $locale = $langcode."_".$countrycode;
            self::_setSystemLanguage($langcode);
            self::_setSystemLocale($locale);
            self::_setGettextLocale($locale);
        }elseif($bHalf_supported && !$force && !$bIs_the_default){ //if we have the langcode but not the countrycode then take the first locale available
            $supported_locales = self::getSupportedLanguages();

            foreach($supported_locales as $l){
                if(preg_match("|".$langcode."_([a-zA-Z]+)|",$l,$matches)){
                    $countrycode = $matches[1]; //get the first countrycode and exit
                    break;
                }
            }

            $locale = $langcode."_".$countrycode;
            self::_setSystemLanguage($langcode);
            self::_setSystemLocale($locale);
            self::_setGettextLocale($locale);
        }
    }

    public static function isSupportedLanguage($langcode,$countrycode=null){
        if(!isset($countrycode)){
            return in_array($langcode,self::getSupportedLanguages(true));
        }else{
            $language = $langcode."_".$countrycode;
            return in_array($language,self::getSupportedLanguages());
        }
    }

    private static function getSupportedLanguages($only_langcode = false){
        $dirs = glob(\Config::get("i18n.locales_directory",APPPATH."locale/").'*',GLOB_ONLYDIR);
        $languages = array();
        if(!empty($dirs)){
            foreach($dirs as $d){
                $dirname = basename($d);
                if($only_langcode){
                    $langcode = explode("_",$dirname);
                    $languages[] = $langcode[0]; //get the langcode and not the country code
                }else{
                    $languages[] = $dirname; //get langcode and country code
                }
            }
        }
        return $languages;
    }

    private static function _setGettextLocale($locale){
        setlocale(LC_ALL, $locale);
        setlocale(LC_TIME, $locale);
        putenv("LANG=$locale");
        bindtextdomain("messages", APPPATH."/locale");
        bind_textdomain_codeset("messages",'UTF-8');
        textdomain("messages");
    }

    private static function _setSystemLanguage($language){
        \Config::set('language',$language);
        return true;
    }

    private static function _setSystemLocale($locale){
        \Config::set('locale',$locale);
        return true;
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
     * Detect client browser language using HTTP_ACCEPT_LANGUAGE.
     * @param bool $get_locale whatever return only the langcode or the full locale.
     * @return string the langcode or the locale from the user browser
     */
    public static function getClientLanguage($get_locale = false){
        $langcode = (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
        $langcode = (!empty($langcode)) ? explode(";", $langcode) : $langcode;
        $langcode = (!empty($langcode['0'])) ? explode(",", $langcode['0']) : $langcode;

        if($get_locale){
            $locale = preg_replace("|-|","_",$langcode[0]);
            return $locale;
        }else{
            $langcode = (!empty($langcode['0'])) ? explode("-", $langcode['0']) : $langcode;
            return $langcode['0'];
        }
    }

    /**
     * Returns the "language" as set in config.php
     * @static
     * @return mixed
     * @usage $Intl = Intl::forge()->getDefaultLanguage();
     */
    public function getDefaultLanguage(){
        return $this->_defaults['language'];
    }

    /**
     * Returns the "locale" as set in config.php
     * @static
     * @return mixed
     * @usage $Intl = Intl::forge()->getDefaultLocale();
     */
    public function getDefaultLocale(){
        return $this->_defaults['locale'];
    }

    public static function translateUri($language,$fullurl=true){
        /*$uri = \Uri::string();
        $baseurl = \Uri::base();
        $langcodes = self::_getSupportedLanguages();
        $pattern = "[".implode("|",array_keys($langcodes))."]";

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
        }*/
        return Uri::translateCurrent($language,$fullurl);
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