<?php
/**
 * Created by LostCore
 * Date: 03/04/13
 * Time: 09.40
 */

namespace Intl;

use \Twig_Extension;
use \Twig_Function_Function;
use \Twig_Function_Method;

class Intl_TwigExtension extends \Twig_Extension{

    public function getName(){
   		return 'intl_twig';
   	}

    public function getFunctions(){
        return array_merge(parent::getFunctions(),array(
           '_t' => new Twig_Function_Function('Intl::_t')
        ));
    }
}