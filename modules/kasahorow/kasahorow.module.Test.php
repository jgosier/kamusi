<?php
require_once('PHPUnit/Extensions/Database/TestCase.php');
require_once('./includes/phpunit_setup.inc');
class kasahorow_module_Test extends DrupalTddTestCase {
  public function setup() {
    parent::setup();
    drupal_install_modules(array('kasahorow', 'devel'));
  }

  function testDateTime() {
     $ts = strtotime('Jan 13, 2005 3:01');
     $ts_array = _ktime_array($ts);
     $this->assertEquals('13', $ts_array['day']); 
     $this->assertEquals('1', $ts_array['month']); 
     $this->assertEquals('2005', $ts_array['year']); 
     $this->assertEquals('3', $ts_array['hour']); 
     $this->assertEquals('01', $ts_array['minute']); 

     $this->assertEquals($ts, _kmktime($ts_array));
  }

 function testServiceName() {
   global $conf;
   $conf['service_name'] = 'museke';
   $this->assertEquals('museke', _service_name());
 }
}

