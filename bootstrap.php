<?php

Autoloader::add_core_namespace("Intl");

Autoloader::add_classes(array(
    'Intl\\Intl'                        => __DIR__.'/classes/Intl.php',
    'Intl\\Uri'                    		=> __DIR__.'/classes/uri.php',
    'Intl\\Intl_TwigExtension' 	        => __DIR__.'/classes/twig_extension.php',
));
 
