<?php

namespace Intl;

class Uri extends \Fuel\Core\Uri
{
    /**
     * @param null $uri
     * @param array $variables
     * @param array $get_variables
     * @param null $secure
     * @return string
     */
    public static function create($uri = null, $variables = array(), $get_variables = array(), $secure = null){
        if(\Config::get("i18n.active",false) && \Config::get("118n.rewrite_uri",true)){
            $current_lang = \Intl::getCurrentLanguage();
            $default_lang = \Config::get("language");
            $client_lang = \Intl::getClientLanguage();
            if($current_lang != $default_lang OR $current_lang != $client_lang){
                if($uri == "/" || $uri == $current_lang){
                    $uri = "/$current_lang";
                }else{
                    $uri = $current_lang."/".$uri;
                }
            }
        }
        $url = parent::create($uri,$variables,$get_variables,$secure);
        return $url;
    }

    /**
     * @param $language
     * @param bool $fullurl
     * @return string
     */
    public static function translateCurrent($language,$fullurl = false){
        $uri = \Uri::string();
        $baseurl = \Uri::base();
        $langcodes = Intl::getSupportedLanguages(true);
        $pattern = "[".implode("|",$langcodes)."]";

        if(preg_match($pattern,\Uri::segment(1),$matches) == 1){
            if($matches[0] == $language){
                if($fullurl) return $baseurl.$uri; else return $uri;
            }
            else{
                $segments = \Uri::segments();
                if(in_array($segments[0],$langcodes) && !isset($segment[1])){
                    $segments[0] = $language;
                    $new_uri = implode("/",$segments);
                    return $baseurl.$new_uri;
                }else{
                    $segments[0] = $language;
                    $new_uri = implode("/",$segments);
                    if($fullurl) return $baseurl.$new_uri; else return $new_uri;
                }
            }
        }else{
            if($fullurl) return $baseurl."{$language}/".$uri; else return "{$language}/".$uri;
        }
    }
}