<?php
require_once('./includes/phpunit_setup.inc');

class kservices_module_TestCase extends DrupalTddTestCase{

  var $admin_user;
  var $web_user;
  var $anon_user;
  var $node;

  function setup() {
    parent::setup();
    drupal_install_modules(array('kasahorow', 'kservices', 'devel'));
  }
  
  public function testAllowedSyncKeysSetting() {
    variable_set('kservices_sync_keys', 'appengine');
    $keys = _kservices_allowed_sync_keys();
    $this->assertEquals(array('appengine'), $keys);
    
    variable_set('kservices_sync_keys', 'key1
                 key2');
    $keys = _kservices_allowed_sync_keys();
    $this->assertEquals(array('key1', 'key2'), $keys,
                        'Keys should be separated by newlines');
  }

  public function testSanitizeNodeLoadsRelations() {
  
  }  
}
