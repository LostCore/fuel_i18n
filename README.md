# FuelPHP Internationalization Package

This package provide i18n capabilities to php and twig templates.

## Usage

1 - Copy config\i18n.php in APPPATH/config and set the desidered values

2 - Use following tags in templates

2.1 - PHP

    Intl::_t("Text in original language")

2.2 - TWIG

    {{ _t(Text in original language) }}

4 - Put

    <?php
        if(Config::get(i18n.active,true)){
            Intl::forge()->setLanguage(<language>);
        }
    ?>

Where you want to set language for the first time (usually in a method that run early during app bootstrap).

## Methods

**Intl::getCurrentLanguage();**

Get current language:

**Intl::getClientLanguage();**

Get client language (from browser):

Example:

    <?php
        if(Config::get(i18n.active,true)) Intl::forge()->setLanguage(Intl::getClientLanguage());
    ?>

**Intl::isSupportedLanguage(<language>);**

Check if a language is supported; it looks in locales directory if there is a folder named after the language.
This step is done automatically in setLanguage();

## Intl\Uri

This class overwrites core's Uri create() method, make it able to adds /<language>/ before actual uri.

You can use it like this:

    if(<you want to prepend language to uri>){
        $class = '\Intl\Uri';
    }else{
        $class = '\Uri';
    }

    $link = $class::create("uri/path");

