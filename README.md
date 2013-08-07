# FuelPHP Internationalization Package

This package provide i18n capabilities to php and twig templates.

## Usage

1 - Set desidered language and langcodes in config\i18n.php

2 - Use following tags in templates

2.1 - PHP

    Intl::_t("Text in original language")

2.2 - TWIG

    {{ _t(Text in original language) }}

3 - Put

    $intl = Intl::forge();
    $intl->setLanguage(<language>);

Where you want to set language.

