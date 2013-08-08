<?php

namespace Intl;

class Uri extends \Fuel\Core\Uri
{
    public static function create($uri = null, $variables = array(), $get_variables = array(), $secure = null){
        if(\Config::get("i18n.active",false) && \Config::get("118n.rewrite_uri",true)){
            $current_lang = \Intl::getCurrentLanguage();
            $default_lang = \Config::get("language");
            $client_lang = \Intl::getClientLanguage();
            if($current_lang != $default_lang OR $current_lang != $client_lang){
                if($uri == "/"){
                    $uri = "/$current_lang";
                }else{
                    $uri = $current_lang."/".$uri;
                }
            }
        }
        $url = parent::create($uri,$variables,$get_variables,$secure);
        return $url;
    }
}