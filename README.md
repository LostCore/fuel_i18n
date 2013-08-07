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

- Get current language:

    Intl::getCurrentLanguage();

- Get client language (from browser):

    Intl::getClientLanguage();

Example:

    <?php
        if(Config::get(i18n.active,true)) Intl::forge()->setLanguage(Intl::getClientLanguage());
    ?>

- Check if a language is supported

    Intl::isSupportedLanguage(<language>);

This check in locales directory if there a folder for the language.

This is done automatically in Intl::setLanguage();

