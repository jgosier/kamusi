<?php
require_once('PHPUnit/Extensions/Database/TestCase.php');
require_once('./includes/phpunit_setup.inc');
class swen_module_Test extends DrupalTddTestCase {
  var $primary_vocabulary = 1;
  public function setup() {
    parent::setup();
    drupal_install_modules(array('swen', 'devel'));
  }

  function testPaldoBackfill() {
    $en_sw_vars = _swen_paldoclient_vars('dog', 'en', 'sw');
    $this->assertEquals('dog', $en_sw_vars['kclient_query'], 'Query not set'); 
    $this->assertEquals('en', $en_sw_vars['kclient_db'], 'DB not set'); 
    $this->assertEquals('sw', $en_sw_vars['kclient_link'], 'Link not set'); 
    $this->assertEquals('http://translate.fienipa.com', $en_sw_vars['kclient_dom'],
                        'Domain not set'); 
    $this->assertEquals('js', $en_sw_vars['kclient_format'], 'Javascript format'); 
    $this->assertEquals('kamusi', $en_sw_vars['kclient_key'], 'Key not set'); 
  }
}

