<?php
require_once('PHPUnit/Extensions/Database/TestCase.php');
require_once('./includes/phpunit_setup.inc');
require_once('kentry.admin.inc');
class kentry_module_Test extends DrupalTddTestCase {
  var $primary_vocabulary = 1;
  public function setup() {
    parent::setup();
    drupal_install_modules(array('search', 'kasahorow', 'kentry', 'devel'));
    $this->path = drupal_get_path('module', 'kentry');
    //Create a node and add some relations
    $this->relid = 'testrel';
    $this->node1 = new stdClass();
    $this->node2 = new stdClass();
    $this->node3 = new stdClass();
    $this->node4 = new stdClass();
    $this->node5 = new stdClass();
    $this->node1->title = 'Test node 1';
    node_save($this->node1);
    $this->node2->title = 'Test node 2';
    node_save($this->node2);
    $this->node3->title = 'Test node 3';
    node_save($this->node3);
    $this->node4->title = 'Test node 4';
    node_save($this->node4);
    $this->node5->title = 'Test node 5';
    node_save($this->node5);
    //Outgoing link from node1 to node2: ancestor(node1) = node2
    _kentry_add_link($this->node1, $this->node2, $this->relid);
    //Incoming link from node3 to node1: descendant(node1) = node3
    _kentry_add_link($this->node3, $this->node1, $this->relid);
    // ancestor(node2) = node4
    _kentry_add_link($this->node2, $this->node4, $this->relid);
    // descendant(node3) = node5
    _kentry_add_link($this->node5, $this->node3, $this->relid);
  }
  
  public function testConstantExistsFEATURED_DATABASE(){
    $this->assertTrue(defined('FEATURED_DATABASE'), 'FEATURED_DATABASE not defined');
  }
  
  public function testConstantExistsKDECODER_DICT(){
    $this->assertTrue(defined('KDECODER_DICT'), 'KDECODER_DICT not defined');
  }
  
  public function testConstantExistsKENCODER_BASE(){
    $this->assertTrue(defined('KENCODER_BASE'), 'KENCODER_BASE not defined');
  }
  
  public function testConstantExistsKENCODER_DICT(){
    $this->assertTrue(defined('KENCODER_DICT'), 'KENCODER_DICT not defined');
  }
  
  public function testHookHelp() {
    $this->assertTrue(function_exists('kentry_help'), 'kentry_help() not defined');
    $path = 'admin/modules#description';
    $this->assertTrue(strlen(kentry_help($path)) > 0, 
                      "kentry_help($path) should return non-empty string");
  }
  
  public function testHookPerm() {
    $type = new StdClass();
    $type->iso = 'test';
    $type->name = 'Test type';
    $created_type = _kentry_create_type($type);
    $perms = kentry_perm();
    $this->assertTrue(in_array($t = sprintf('suggest %s entry only', $created_type),
                               $perms), "$t not in permissions");
    $this->assertTrue(in_array($p = 'administer dictionary', $perms), "$p not in permissions");
    $this->assertTrue(in_array($p = 'use dictionary', $perms), "$p not in permissions");
    $this->assertTrue(in_array($p = 'view entry', $perms), "$p not in permissions");
    $this->assertTrue(in_array($p = 'review entry', $perms), "$p not in permissions");
    $this->assertTrue(in_array($p = 'suggest entry', $perms), "$p not in permissions");
    $this->assertTrue(in_array($p = 'create relations', $perms), "$p not in permissions");
  }
  
  public function testHookMenu() {
    $items = kentry_menu();
    $perms = kentry_perm();
    foreach($items as $path => $info) {
      $this->assertTrue(in_array($info['access arguments'][0], $perms),
                        sprintf('%s access arguments not found in kentry_perm()',
                        $info['access arguments'][0]));
      if(isset($info['file'])) {//Check that those files actually exist
        $file_path = $this->path.'/'.$info['file'];
        $this->assertTrue(file_exists($file_path), 
                          sprintf('%s file not found in %s',$file_path, getcwd()));
        //If they do, check that the callback path exists
        include_once($file_path);
        $this->assertTrue(function_exists($info['page callback']),
                          sprintf('%s not defined', $info['page callback']));
        
      }
    }
    $this->assertEquals($items['all']['page callback'], $items['browse']['page callback'], 
                        'Callback path for "all" should be the same as for "browse"');
  }
  
  public function testHookNodeInfoHasSameNameAsContentType() {
    $this->assertTrue(function_exists('kentry_node_info'), 
                      'kentry_node_info() not defined');
    $type = new StdClass();
    $type->iso = 'test';
    $type->name = 'Test type';
    $created_type = _kentry_create_type($type);
    $drupal_name = node_get_types('name', $created_type);
    $kentry_names = kentry_node_info();    
    $this->assertEquals($drupal_name, $kentry_names[$created_type]['name']);
  }
  
  public function testHookLink() {
    $this->assertTrue(function_exists('kentry_link'),
                      'kentry_link not defined');
    $this->assertEquals(array(), kentry_link('unknown_type', $this->node1),
                        'Unknown link type should return no links');
    
    $this->node1->previous = new StdClass();
    $links = kentry_link('node', $this->node1);
    $this->assertEquals($this->node1->previous, $links['p_link'],
                        '"Previous" link should be set');
    
    $this->node1->next = new StdClass();
    $links = kentry_link('node', $this->node1);//Now both should be set
    $this->assertEquals($this->node1->previous, $links['p_link'],
                        '"Previous" link should be set');
    $this->assertEquals($this->node1->next, $links['n_link'],
                        '"Next" link should be set');
  }

  
  public function testHookInsert() {
    $this->assertTrue(function_exists('kentry_insert'), 
                      'kentry_insert not defined');
    $status = kentry_insert($this->node1);
    $this->assertTrue($status, 'Could not insert node1');
    
    $entry = kentry_load($this->node1, 1);  //Make sure we're not loading from cache 
    $this->assertEquals($this->node1->vid, $entry->vid, 'vid must be equal');
    $this->assertEquals($this->node1->title, $entry->word, 
                        'Title and root must be equal if node->lexeme not set');
    $this->assertNull($entry->cached, '$cached->vid should not be set');
    
  }
  
  public function testHookInsertWithLexeme() {
    $this->node1->lexeme = 'lexeme';
    $status = kentry_insert($this->node1);
    $this->assertTrue($status, 'Could not insert node1');
    $entry = kentry_load($this->node1, 1);
    $this->assertNull($entry->cached, '$entry->cached should not be set');
    $this->assertEquals($this->node1->vid, $entry->vid, 'vid must be equal');
    $this->assertEquals($this->node1->lexeme, $entry->word, 
                        '$entry->word should be equal to $node->lexeme');
  }
  
  public function testHookInsertWithTaxonomy() {
    $p_tid = 10;
    $this->node1->posvid = $this->primary_vocabulary;
    $this->node1->taxonomy[$this->primary_vocabulary] = $p_tid;
    $status = kentry_insert($this->node1);
    $this->assertTrue($status, 'Could not insert node1');
    $entry = kentry_load($this->node1, 1);
    $this->assertEquals($this->node1->vid, $entry->vid, 'vid must be equal');
    $this->assertEquals($p_tid, $entry->p_tid, 'primary term id not set');
    
    //Now try with a multidimensional node->taxonomy array
    kentry_delete($this->node1); //Delete existing entry first
    $this->node1->taxonomy[$this->primary_vocabulary] = array($p_tid, 0);
    $status = kentry_insert($this->node1);
    $this->assertTrue($status, 'Could not insert node1');
    $entry = kentry_load($this->node1);
    $this->assertEquals($this->node1->vid, $entry->vid, 'vid must be equal');
    $this->assertEquals($p_tid, $entry->p_tid, 'primary term id not set');
  }
  public function testHookLoadExists() {
     $this->assertTrue(function_exists('kentry_load'), 
                       'kentry_load not defined');
  }
  
  public function testHookLoadWithUncachedNode() {
    $entry = kentry_load($this->node1);
  }
  
  public function testClearAllLinks() {
    $num_links = db_result(db_query("SELECT COUNT(*) FROM {kentry_synonyms}
                                    WHERE vid=%d OR svid=%d", $this->node1->vid,
                                    $this->node1->vid));
    $this->assertEquals(2, (int)$num_links);
    $status = kentry_delete_relations($this->node1->vid);
    $this->assertTrue($status);
    //Deleting all links should delete all outgoing links as well as all incoming
    //links
    $num_links = db_result(db_query("SELECT COUNT(*) FROM {kentry_synonyms}
                                    WHERE vid=%d OR svid=%d", $this->node1->vid,
                                    $this->node1->vid));
    $this->assertEquals(0, (int)$num_links);    
  }
  //TODO(paakwesi): Add more assertions
  public function testSpaceSplitter() {
    $rows = _space_splitter('', 'db');
    $this->assertEquals(array(), $rows);
  }
  
  public function testFormatExampleTildeIsReplacedByRoot() {
    $this->node1->word = 'root';
    $this->node1->example['default'] = '~';
    $themed = theme_kentry_format_example($this->node1, 'default');
    $this->assertEquals($themed, 
    	"<span class='example default'>".$this->node1->word.'</span>'); 
  }
  
  public function testCreateType() {
    $type = new StdClass();
    $type->iso = 'test';
    $type->name = 'Test type';
    $created_type = _kentry_create_type($type);    
    $node_types = array_keys(node_get_types());    
    $this->assertTrue(in_array($created_type, $node_types),
                      sprintf('%s not created', $created_type));
  }
  
  public function testNextPrevLinks() {

    //create and save test nodes
    $this->object1=new StdClass();
    $this->object2=new StdClass();
    $this->object3=new StdClass();
    node_save($this->object1);
    node_save($this->object2);
    node_save($this->object3);

    //the next/previous links set for all nodes in kentry_nodeapi
    //during the 'load' operation
    $this->assertTrue(function_exists('kentry_nodeapi'),'kentry_nodeapi() not defined');
    $op='load';
    $boskele=kentry_nodeapi(&$this->object1,$op);
    $this->assertNodesEqual($boskele['next'],$this->object2,'"Next" link should be set');
    $boskele=kentry_nodeapi(&$this->object2,$op);
    $this->assertNodesEqual($boskele['previous'],$this->object1,'"Previous" link should be set');
    $this->assertNodesEqual($boskele['next'],$this->object3,'"Next" link should be set');
    $boskele=kentry_nodeapi(&$this->object3,$op);
    $this->assertNodesEqual($boskele['previous'],$this->object2,'"Previous" link should be set');
  }

  public function testSyncEntries() {
    //Update search index
    search_cron();
    $client = 'test';
    $num_indexed = db_result(db_query('SELECT COUNT(*) FROM search_dataset WHERE reindex=0'));
    $this->assertEquals(5, $num_indexed, 
                       'Must be equal to number of nodes created in setUp()');
    //Update a single node
    $this->node1->type = 'testtype';
    $this->node1->title = 'Changed node1 title';
    node_save($this->node1);
    $num_to_sync = _kentry_num_to_sync($client);
    
    $this->assertEquals(1, $num_to_sync,
                        'Expecting 1 node to be synced');
    //Now sync the unsynced node
    $synced_nodes = _kentry_sync($client, 1);
    $this->assertEquals(1, count($synced_nodes),
                        'Only 1 synced entry to be returned');
    $num_to_sync = _kentry_num_to_sync($client);
    $this->assertEquals(0, $num_to_sync, 'All kentries should be synced');
    $this->assertEquals($this->node1->nid, $synced_nodes[0]->nid,
                        'Synced node should be the same as inserted node');
    
    //Add a new node to be synced
    $this->node2->type = 'kentry_test';
    kentry_insert($this->node2);  //Resave node2 as a kentry
    $num_to_sync = _kentry_num_to_sync($client);
    $this->assertEquals(1, $num_to_sync, '1 new kentry expected to sync');
    $synced_nodes = _kentry_sync($client, 1);
    $this->assertEquals(1, count($synced_nodes),
                        'Only 1 synced entry to be returned');
    $num_to_sync = _kentry_num_to_sync($client);
    $this->assertEquals(0, $num_to_sync, 'All kentries should be synced');
    $this->assertEquals($this->node2->nid, $synced_nodes[0]->nid,
                        'Synced node should be the same as inserted node');
    
    //Update node 1
    $this->node1->changed =  variable_get('kentry_time_posn_'.$client, 0)
                              + 86400;
    kentry_update($this->node1);
    $num_to_sync = _kentry_num_to_sync($client);
    $this->assertEquals(1, $num_to_sync, '1 updated kentry expected to sync');
    $synced_nodes = _kentry_sync($client, 1);
    $this->assertEquals(1, count($synced_nodes),
                        'Only 1 synced entry to be returned');
    $this->assertEquals($this->node1->nid, $synced_nodes[0]->nid,
                        'Synced node should be the same as updated node');
    
  }

  public function testAncestor() {
    $a = _get_ancestors($this->node1->vid);
    $this->assertEquals($this->node2->vid, $a[0]->svid, 'Ancestor is node2');
  }

  public function testDescendant() {
    $d = _get_descendants($this->node1->vid);
    $this->assertEquals($this->node3->vid, $d[0]->vid, 'Descendant is node3');
  }
  public function testDeriveLinks() {
    $relid = $this->relid;
    $none = _derive_links($this->node1, 0);
    $this->assertEquals(0, count($none), 'No links expected when links are turned off.');
    $deg1 = _derive_links($this->node1, 1);
    $this->assertEquals(2, count($deg1[$relid]), '1 parent and 1 child expected.');
    $deg2 = _derive_links($this->node1, 2);
    $this->assertTrue(2 <= count($deg2[$relid]), 'At least 1 parent and 1 child expected.');
    $this->assertEquals(4, count($deg2[$relid]));
  }

  public function testLoadRelations() {
    $this->node1->relations = _derive_links($this->node1, 1);
    $rels = _kentry_load_relations($this->node1, 0);
    $this->assertEquals(2, count($this->node1->relations[$this->relid]), '1 parent and 1 child expected.');
    $this->assertEquals(3, $rels[$this->relid][3]['nid'], 'nid should be set');
  }

  public function teardown() {
    node_delete($this->node1->nid);
    db_query('TRUNCATE {kdictionary}');
    db_query('TRUNCATE {kentry}');
  }

  function testNodeField() {
    $field = _kentry_upload_field($this->node);
    $this->assertEquals(sprintf('node-%s', $this->node->nid), $field->name);
    $this->assertTrue(5 >= strlen($field->iso), 'ISO must be less than 6 chars long');
    $this->assertEquals('_auto', $field->iso, 'Must be autogenerated field');
    $this->assertEquals('urlattachments', $field->ftype);
  }
}

