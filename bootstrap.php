<?php

Autoloader::add_core_namespace("Intl");

Autoloader::add_classes(array(
    'Intl\\Intl'                        => __DIR__.'/classes/Intl.php',
    'Intl\\IntlException'               => __DIR__.'/classes/Intl.php',
    'Intl\\InvalidLangCodeException'    => __DIR__.'/classes/Intl.php',
    'Intl\\Intl_TwigExtension' 	        => __DIR__.'/classes/twig_extension.php',
));
 
