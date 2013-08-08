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
        \Config::set("language","it");
        \Config::set("locale","it-IT");

        $this->intl->setLanguage("en");

        if(Intl::isSupportedLanguage("en")){
            $this->assertEquals("en",\Config::get("language"));
        }else{
            $this->assertNotEquals("en",\Config::get("language"));
        }

        $this->assertEquals(\Config::get("language"),Intl::getCurrentLanguage());
        $this->assertEquals(\Config::get("locale"),Intl::getCurrentLocale());
    }

    public function testInvalidLanguage(){
        $this->assertEquals(false,Intl::isSupportedLanguage("or"));
    }
}