<?php
/**
 * Created by LostCore
 * Date: 26/03/13
 * Time: 13.52
 */

namespace Intl;

/**
 * Test_Intl class tests
 *
 * @group LostCore
 * @group Intl
 */
class Test_Intl extends \Fuel\Core\TestCase
{
    protected $intl;

 	public function setUp(){
        $this->intl = new Intl();
    }

    public function testInitialize() {
         $this->assertEquals(\Config::get("language"),$this->intl->getDefaultLanguage());
         $this->assertEquals(\Config::get("locale"),$this->intl->getDefaultLocale());
    }

    public function testChangeLanguage(){
        $init_language = \Config::get("language");
        $init_locale = \Config::get("locale");

        $this->intl->setLanguage("en");

        $this->assertEquals($init_language,$this->intl->getDefaultLanguage());
        $this->assertEquals($init_locale,$this->intl->getDefaultLocale());
        $this->assertEquals("en",\Config::get("language"));
        $this->assertEquals(\Config::get("language"),$this->intl->getCurrentLanguage());
        $this->assertEquals(\Config::get("locale"),$this->intl->getCurrentLocale());
    }

    /**
     * @expectedException InvalidLangCodeException
     */
    public function testInvalidLangcode(){
        $this->intl->setLanguage("or");
    }
}
 
