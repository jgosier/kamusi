<?php


function kentry_admin_settings(){
  $form['kdictionary_editor_roles'] = array(
            '#type' => 'checkboxes',
            '#title' => t('Editors'),
            '#default_value' => variable_get('kdictionary_editor_roles', array()),
            '#options' => user_roles(1),
            '#description' => t('Select at least one role.  Users with these 
  roles can edit entries of the types you specify.'),
            '#attributes' => NULL,
            '#required' => TRUE,
          );     
            
  $form['kdictionary_help'] = array(
                    '#type' => 'textfield',
                    '#title' => t('Node documentation'),
                    '#default_value' => variable_get('kdictionary_help', 
    'http://kasahorow.com/help'),
                    '#description' => t('Link to external documentation'),
                  );
  $form['kdictionary_mail'] = array(
                    '#type' => 'textfield',
                    '#title' => t('Content status email'),
                    '#default_value' => variable_get('kdictionary_mail',
                                                     'kasahorow@suuch.com'),
                    '#description' => t('Email address for receiving weekly '.
                                        'content status updates'),
                  );
  $form['kdictionary_cron_day'] = array(
      '#type' => 'textfield',
      '#title' => t('Day on which cron reports should run'),
      '#default_value' => variable_get('kdictionary_cron_day', 'Tue'),
      '#description' => t('Use the output of PHP function date("G"), e.g. '.
                          'Mon, or Tue, or Wed, or Sun'),
  );
  $lastrun = format_date(variable_get('kdictionary_cron_last', 0));
  $form['kdictionary_cron_last'] = array(
      '#type' => 'textfield',
      '#title' => t('Last timestamp of when cron reports were run'),
      '#default_value' => variable_get('kdictionary_cron_last', 0),
      '#description' => t('The last time cron reports were run was %date',
                          array('%date' => $lastrun)),
  );
  $form['speed'] = array('#type'=>'fieldset', '#title'=>t('Performance'));  
  $form['speed']['kasahorow_entries_per_page'] = array(
	  '#type' => 'textfield',
	  '#title' => t('Entries per index page'),
	  '#default_value' => variable_get('kasahorow_entries_per_page',10),
	  '#size' => 20,
	  '#maxlength' => '5',
	  '#description' => t('Number of entries on the !link page',
                              array('!link'=>l(t('index'),'all'))),
	);
  $form['speed']['kderive_links_deadline'] = array(
    '#type'=>'textfield',
    '#title'=>t('Link derivation deadline'),
    '#field_suffix' => 'milliseconds',
    '#default_value' => variable_get('kderive_links_deadline', 50),
    );
  $form['speed']['kentry_page_deadline'] = array(
    '#type'=>'textfield',
    '#title'=>t('Index page (all/&lt;db&gt;/&lt;term&gt;) generation deadline'),
    '#field_suffix' => 'milliseconds',
    '#default_value' => variable_get('kentry_page_deadline', 250),
    '#description' => t('This value should always be greater than the product of
                        the links derivation deadline (%a ms) and the number of
                        entries on the index page (%b) since the index page
                        always loads full nodes. Otherwise no entries will be
                        found since the index page will time out before the
                        links have been generated.',
                        array('%a'=>variable_get('kderive_links_deadline', 50),
                              '%b'=>variable_get('kasahorow_entries_per_page',1)
                              )
                        ),
    );
  
  $form['links'] = array('#type'=>'fieldset', '#title'=>t('Links'),
                         '#collapsible' => 1, '#collapsed' => 1);  
  $form['links']['krecursion_level'] = array(
	  '#type' => 'select',
          '#options'=> array(1=>t('1 level'), 0=>t('Off'), 2=>t('2 levels')),
	  '#title' => t('Ancestors are outgoing links. Descendants are incoming links'),
	  '#default_value' => variable_get('krecursion_level', 1),
	  '#description' => t('How deep to delve to automatically derive relations.
                Each database can also specify a different recursion level'),
	);
  foreach(_kdatabases(1) as $db=>$name){
    $form['links']["krecursion_level_$db"] = array(
	  '#type' => 'select',
          '#options'=> array(1=>t('1 level'), 0=>t('Off'), 2=>t('2 levels')),
	  '#title' => t('Default Level of Recursion/Degrees of separation for !db', array('!db'=>$name)),
	  '#default_value' => variable_get("krecursion_level_$db", variable_get('krecursion_level', 1)),
	  '#description' => t('How deep to delve to automatically derive relations.'),
	);
  }
  
  
  $form['entry'] = array('#type'=>'fieldset',
                         '#title'=>t('Default entry display'),
                         '#collapsible'=>TRUE,
                         '#description'=>t('Pick the elements of an entry that
                                           are NOT displayed by default'));
  
  $form['entry']['kentry_show'] = array(
	  '#type' => 'checkboxes',
          '#options'=>array('word'=>t('Group'),
                            'children'=>t('Fields'),
                            'defn'=>t('Definition'),
                            'example'=>t('Example'),
                            'notes'=>t('Notes'),
                            'links'=>t('Links'),
                            'search'=>t('Search box'),
                            'rstatus'=>t('Review status'),
                            'term_data' => t('Taxonomy term data'),
                            ),
	  '#title' => t('Do NOT display the following entry components'),
	  '#default_value' => variable_get('kentry_show',array()),
	);

  $form['entry']['kentry_showcitation'] = array(
	  '#type' => 'radios',
          '#options'=>array(TRUE=>t('Yes'), FALSE=>t('No')),
	  '#title' => t('Display Citation'),
	  '#default_value' => variable_get('kentry_showcitation',FALSE),
	  '#description' => t('Show the citation at the bottom of an entry?'),
	);
  
  $form['entry']['show_linksgroupingtool'] = array(
	  '#type' => 'radios',
          '#options'=>array(TRUE=>t('Yes'), FALSE=>t('No')),
	  '#title' => t('Display Grouping Tool for links'),
	  '#default_value' => variable_get('show_linksgroupingtool',FALSE),
	  '#description' => t('Show the links grouping tool on each entry?'),
	);
  
    $form['ksynsets'] = array('#type' => 'textfield',
      '#title' => t('Links'),
      '#description' => t('Short codes of the semantic relations.'),
      '#default_value'=>variable_get('ksynsets', ""),
    );
    
    $form['ksynset'] = array('#type' => 'fieldset',
      '#title' => t('Semantic relations'),
      '#description' => t(''),
      '#collapsible'=>TRUE,
    );
    $ksynsets = explode("," ,variable_get('ksynsets', ""));
    $ksynsets = ($ksynsets[0]=="")?array():$ksynsets;
    
    foreach($ksynsets as $ksynset){
        $form['ksynset']['ksynset_'.trim($ksynset).'_name'] = array('#type' => 'textfield',
          '#title' => t('Name of %this semantic relation', array('%this'=>$ksynset)),
          '#default_value' => variable_get('ksynset_'.trim($ksynset).'_name', ''),
          '#description' => t('The name of the semantic relation. e.g. Synonyms'),
          '#required' => TRUE,
        );
        $form['ksynset']['ksynset_'.trim($ksynset).'_derivation'] = array('#type' => 'textarea',
          '#title' => t('Provide a derivational sentence for %this relation', array('%this'=>$ksynset)),
          '#default_value' => variable_get('ksynset_'.trim($ksynset).'_derivation', ''),
          '#description' => t('e.g. for Hyponymy, a good derivational sentence is "~s and other #s". ~ will be replaced by the entry and # by the relation.'),
          
        );
    }
    return system_settings_form( $form );
}

/**
 * Display the form for adding/editing a database 
 */
function _add_edit_dict($state, $iso=NULL) { 
  global $user;
  $form = array();
  // get dictionary id
  if(($iso !=NULL) && (user_access('edit dictionary'))){
    $dict = db_fetch_object(db_query("SELECT * FROM {kdictionary} 
                                      WHERE iso='%s'", $iso));
  }	
    
  $form['basic']= array('#type'=>'fieldset', 
                        '#title'=>t('Basic settings'),
    			'#collapsible'=>TRUE,
    			'#description'=>t('Create a new content type')
  );

  $form['basic']['iso'] = array(
    '#type' => 'textfield',
    '#title' => t('Type'),
    '#size'=>5,
    '#default_value' => isset($dict->iso)?$dict->iso:'',
    '#disabled'=>isset($dict->iso),		  
    '#description' => t('Choose a unique code for this content type. '.
                        'This code '.
                        '<b>cannot be changed in the future</b>. '.
                        'Use a standard code, such as ISO codes, if '.
                        'possible.'),
		);
  //Only show the confirmation if creating a new database
  $form['basic']['confirmiso'] = array(
    '#type' => $dict->iso?'hidden':'textfield',
    '#title' => t('Confirm Type'),
    '#size'=>5,
    '#default_value' => isset($dict->iso)?$dict->iso:'',		  
    '#description' => t('Remember that you cannot change the unique code.'),
    '#required'=>TRUE,
  );
			
  $form['basic']['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Content type title'),
    '#default_value' => isset($dict->name)?$dict->name:'',
    '#size' => 50,
    '#maxlength' => 50,
    '#description' => t('This is usually the name you will give to 1 example '.
                        'of such a content type, e.g. kasahorow Akan Dictionary'),
    '#required' => TRUE,
  ); 
  $form['basic']['instruction'] = array(
    '#type' => 'textarea',
    '#title' => t('More details'),
    '#default_value' =>isset($dict->instruction)?$dict->instruction:'',
    '#cols' => 50,
    '#rows' => 5,
    '#description' => t('Extra information to display on the home page'),
  );
  $form['basic']['status'] = array(
    '#type' => 'select',
    '#title' => t('Status'),
    '#default_value' => (isset($dict->status) ? $dict->status:1),
    '#options' => array(1 => t('On'), 2=>t('On and featured'), 0 =>t('Off')),
    '#description' => t('Set status of dictionary to On (and featured) or Off'),
  );
			
  $form['basic']['editors'] = array(
    '#type' => 'select',
    '#multiple'=>TRUE,
    '#options'=>_getUsersId(),
    '#default_value' => isset($dict->editor)?unserialize($dict->editor):array(),
    '#description' =>t('Select editors assigned to this entry type. Editors are members who belong to the roles which are allowed to edit entries. See the settings page to modify this.')
  );	
    
  $letters = isset($dict->alphabets)?unserialize($dict->alphabets):array();
  $letters = join($letters,',');
  $form['search']= array(
    '#type'=>'fieldset', '#title'=>t('Browse settings'),
    '#collapsible'=>TRUE,
    '#collapsed'=>TRUE,
    '#description'=>t('The quick index shows up on /browse pages'),
  );
  $form['search']['alphabets'] = array(
    '#type' => 'textfield',
    '#title' => t('Quick  Index'),
    '#default_value' => $letters,
    '#size' => 50,
    '#maxlength' => 255,
    '#description' => t('Suggest indexes to your users. e.g. dictionaries '.
                        'often  provide an alphabetical index such as a, b, '.
                        'c, etc. Separate each option with a comma.')
			);
	
    if(module_exists('taxonomy')){
     $vocabs = taxonomy_get_vocabularies();
     $options = array();
     foreach($vocabs as $vid=>$vocab){
        $options[$vid] = $vocab->name;
     }
    $form['search']['vid'] = array(
            '#type' => 'select',
            '#title' => t('Primary Taxonomy'),
            '#description'=> t('Select the taxonomy under which entries of this type will be automatically classified. ').l(t('Modify this list'), 'admin/content/taxonomy'),
            '#options' => $options,
            '#default_value' => isset($dict->vid)?$dict->vid:'',
            );
         
    }
    
    $nchildren = array();
    $form['entry']['nchildren'] = array(
        '#type' => 'textarea',
        '#title' => t('Special properties'),
        '#default_value' => $children,
        '#size' => 50,
        '#description' => t('Create extra fields for entering extra
            information about the database entry. The following TYPEs are
            available:!types. Separate each field with a comma.',
            array('!types'=>join(', ',_kfield_types()))),
      );
    $children = isset($dict->children)?unserialize($dict->children):array();
    $children = join($children, ',');
    $form['entry']= array('#type'=>'fieldset', '#title'=>t('Field settings'),
    							 '#collapsible'=>TRUE,
    							 '#collapsed'=>TRUE,
    							 '#description'=>t('The following settings are useful for defining how entries in your database are structured')
    							 );
    
    $form['entry']['children'] = array(
        '#type' => 'hidden',
        '#title' => t('Special properties'),
        '#value' => $children,
        '#size' => 50,
        '#attributes' => array('readonly'=>1),
        '#description' => "DEPRECATED. The fields should
          have been migrated below. If you can't see the migrated fields
          run ".l("cron", 'admin/reports/status/run-cron',
                  array('query'=>drupal_get_destination())),
      );
    $form['entry']['chn'] = _kentry_fields_form($dict->iso);
    $labels = isset($dict->labels)?unserialize($dict->labels):array();
    $form['entry']['labels'] = array('#type'=>'fieldset', '#tree'=>TRUE, '#title'=>t('Default fields'), 
                                     '#collapsible'=>TRUE, '#collapsed'=>TRUE, 
                                     '#description' => t('Feel free to rename them'), '#weight' => -10);
        foreach(_default_fields() as $field=>$label){
            $form['entry']['labels'][$field] = array('#tree'=>TRUE);
            $form['entry']['labels'][$field]['title'] = array(
                              '#type' => 'textfield',
                              '#title' => t('%this', array('%this'=>$label['title'])),
                              '#default_value' => isset($labels[$field]['title'])?$labels[$field]['title']:$label['title'],
                              '#size' => 50,
                              '#maxlength' => 255,
                              '#description' => t('Specify an alternate name for the %this field', array('%this'=>$label['title'])),
                            );
            $form['entry']['labels'][$field]['desc'] = array(
                              '#type' => 'textfield',
                              '#title' => t('%this description', array('%this'=>$label['title'])),
                              '#default_value' => isset($labels[$field]['desc'])?$labels[$field]['desc']:$label['desc'],
                              '#size' => 50,
                              '#maxlength' => 255,
                              '#description' => t('Specify an alternate description for the %this field', array('%this'=>$label['title'])),
                            );
    }
    
  $form['indexsettings']= array(
    '#type'=>'fieldset', 
    '#title'=>t('Link settings'),
    '#collapsible'=>TRUE,
    '#collapsed'=>TRUE,
  );
    
    $default_value = isset($dict->index)?unserialize($dict->index):'';
    $form['indexsettings']['index'] = array(
		  '#type' => 'select',
		  '#multiple'=>TRUE,
		  '#size'=>5,
		  '#title' => t('Link your entries with entries from other databases'),
		  '#default_value' => is_array($default_value)?$default_value: array(),
		  '#options' => _kasahorow_entry_types(),
		  '#description' => t( 'You may choose to create cross-references to other databases. For example, for a dictionary database you can create multilingual dictionaries(e.g. Akan with Ewe and Hausa). If you create a dictionary for the cross-reference languages, they will list any words/phrases you added from this dictionary.'),
		);
    
    
    if($iso == NULL){
        $form['type'] = array('#type'=>'value', '#value'=>'add');
        $form['submit'] = array(
                      '#type' => 'submit',
                      '#value' => t( 'Add database' ),
                            );
    }else{		
        $form['iso'] = array('#type'=>'hidden', '#value'=>$iso);			
        $form['type'] = array('#type'=>'hidden', '#value'=>'update');		  
        $form['buttons']['submit'] = array(
                              '#type' => 'submit',
                              '#value' => t( 'Update' ),
              );
                    
        $form['buttons']['delete'] = array(
                              '#type' => 'submit',
                              '#value' => t( 'Delete' ),
                    );				
    }
    $form['#submit'][] = '_kentry_fields_form_submit';
    $form['#submit'][] = '_add_edit_dict_submit';
    return $form;
  }

  
  /*
   * Process forms
   */   
function _add_edit_dict_validate($state, &$values){
  if($values['values']['iso']!=$values['values']['confirmiso']){
      form_set_error('confirmiso', t('Both Short Codes must be the same'));
  }
}

function _add_edit_dict_submit($state, &$values) {
  $edit = $values['values'];
  if($thumb = trim($edit['thumbnail'])){
    variable_set('kentry_'.$edit['iso'].'_dthumb', $thumb);
  }

  $index = serialize($edit['index']);
      $word = preg_split("/[,|;]/",$edit['children'] );	    	
  $letters = preg_split("/[,|;]/",$edit['alphabets']);
  //Trim whitespaces, quotes. Check before foreach()
   if(!empty($word)) {	    	 
          foreach( $word as $value )	{
          $wrd[] = trim($value, "'\" ");
          }
   }	    		    	 
  $children = serialize($wrd);
              
  if( !empty($letters)) 	{	    		
      foreach( $letters as $value ){
          $letter[] = trim($value, "'\" ");
      }
  }
  $alphabets = serialize($letter);
  
      if(module_exists('taxonomy') && !empty($edit['vid'])){
          $vocab = taxonomy_vocabulary_load($edit['vid']);
          if(!in_array('kentry_'.$edit['iso'], $vocab->nodes)){
             $vocab->nodes = node_get_types('types');
             $save = (array)$vocab;
             taxonomy_save_vocabulary($save);                    
          }
       }else{
          //Save a new taxonomy named after this entry type
          /*TODO: Clean this up!
          $save = array();
          $save['name'] = $edit['name'];
          $save['description'] = isset($edit['instructions'])?$edit['instructions']:'';
          $save['nodes']['kentry_'.$edit['iso']] = 1;
          taxonomy_save_vocabulary($save);
          $edit['vid'] = $save['vid'];
          */
       }

  switch($edit['type']) {
      case 'add': //adding a new dictionary
      {    	
          if(db_query( "INSERT INTO {kdictionary}(name,`index`, editor,
               children,alphabets, status, iso, vid, instruction, created) VALUES('%s','%s','%s', '%s','%s','%s','%s', '%d', '%s', %d)",
               $edit['name'],$index, serialize($edit['editors']), $children,$alphabets, $edit['status'], $edit['iso'], $edit['vid'],$edit['instruction'], time() 
              ) ){	    	         	
             drupal_set_message( t("%dict was successfully added", array('%dict'=>$edit['name']))
                               );                   
             $values['redirect']  = 'admin/content/kw/kentry';  	
          }else{
              drupal_set_message( t("%dict could not be added
                                  due to error: !err", array('%dict'=>$edit['name'], '!err'=>db_error())), 'warning'
                                );
          }    
      }
  
      break;
      case 'update': 
      {		      
          /**
           * Updating the db to reflect the 
           * changes.
           */
          if( db_query("UPDATE {kdictionary} SET
                   name='%s', labels='%s', `index`='%s',children='%s',
                   alphabets='%s',status='%s',iso='%s', instruction='%s', editor='%s', vid=%d WHERE iso='%s'",
                   $edit['name'],serialize($edit['labels']), $index, 
                   $children,$alphabets,$edit['status'],
                    $edit['iso'], $edit['instruction'], serialize($edit['editors']), $edit['vid'], $edit['iso']
                  ) ) { 
              drupal_set_message( t("%type update was successful", array('%type'=>$edit['name'])));	    	    
             $values['redirect']  = 'admin/content/kw/kentry';	    	       	
           }
      
      }
      break;
      case t('Delete'): //TODO: Fix
      
      if($edit['confirmdelete']==1)
      {
          drupal_set_message($edit['dictname']." deleted");
          db_query("DELETE FROM {kdictionary} WHERE did=%d", $edit['did']);
          watchdog('dictionary', t(" %dict language dictionary deleted", array('%dict'=>$edit['dictname'])));
          drupal_goto('admin/content/kw/kentry');
      }  	else	{	
          $form = array();    	
          $form['dictname'] = array('#type'=>'hidden', '#value'=>$dict->dictname);
          $form['did'] = array('#type'=>'hidden', '#value'=>$dict->did);
          $form['confirmdelete'] = array('#type'=>'hidden', '#value'=>1);			
          $form['buttons']['delete'] = array(
                '#type' => 'submit',
                '#value' => t( 'Delete' ),
                  );	
          $form['buttons']['submit'] = array(
            '#type' => 'submit',
            '#value' => t( 'Cancel' ),
              );	
          
          unset($_POST);
          $output = drupal_get_form('_kdictionary_forms', $form );
          drupal_set_message( t("Do you really want to delete %dict?", array('%dict'=>$edit['dictname'])).$output);
          
      }	    
      break;
  }
      
  menu_rebuild();
}

function _kentry_fields_form($iso) {
   $form['field'] = array('#type' => 'fieldset', '#title' => t('Additional fields'), 
                 '#collapsible' => TRUE, '#tree'=> 1);
   $q = db_query("SELECT f.*, k.index FROM {kentry_fields} f
                 INNER JOIN {kdictionary} k ON k.iso=f.iso
                 WHERE f.iso='%s' ORDER BY f.category, f.weight, f.name", $iso);
   $n = db_result(db_query("SELECT COUNT(*) FROM {kentry_fields}
               WHERE iso='%s'", $iso));
   for($i=0; $i < $n + 1; $i++){        
       $r = $i<$n ? db_fetch_object($q): new stdClass();
       if(empty($r->kfid)){
           $r->kfid = 'new###';
           $r->label = t('Add new field');
       }
       $form['field'][$r->kfid] = array(
           '#type'=>'fieldset',
           '#title' => t('!field settings', array('!field'=>$r->label)),
           '#collapsible' => 1,
           '#collapsed' => 1,
           '#tree'=>1,
       );
       $form['field'][$r->kfid]['name'] = array(
           '#type' => 'textfield',
           '#title' => t('CSS class'),
           '#default_value' => $r->name,
           '#size' => 50,
           '#description' => 'CSS Class. Ideally use one from the '.l('Rich Snippets classes', 'http://www.google.com/support/webmasters/bin/topic.py?hl=en&topic=21997'),
       );
       $form['field'][$r->kfid]['label'] = array(
           '#type' => 'textfield',
           '#title' => t('Label'),
           '#default_value' => $r->label,
           '#size' => 50,
           '#description' => '',
       );
       $form['field'][$r->kfid]['explanation'] = array(
           '#type' => 'textarea',
           '#title' => t('Explanation'),
           '#default_value' => $r->explanation,
           '#size' => 50,
           '#description' => '',
       );
       $form['field'][$r->kfid]['ftype'] = array(
           '#type' => 'select',
           '#options' => _kfield_types(),
           '#title' => t('Field type'),
           '#default_value' => $r->ftype,
           '#description' => '',
       );
       $form['field'][$r->kfid]['category'] = array(
           '#type' => 'textfield',            
           '#title' => t('Category'),
           '#default_value' => $r->category,
           '#description' => '',
       );
       $form['field'][$r->kfid]['weight'] = array(
           '#type' => 'weight',
           '#title' => t('Weight'),
           '#default_value' => $r->weight,
           '#description' => '',
       );
       $form['field'][$r->kfid]['required'] = array(
           '#type' => 'checkbox',
           '#default_value' => $r->required,
           '#title' => t('The user must enter a value.'),
       );
       $form['field'][$r->kfid]['kteaser'] = array(
           '#type' => 'checkbox',
           '#default_value' => $r->kteaser,
           '#title' => 'Display in node teaser view',
       );
       $form['field'][$r->kfid]['ffilter'] = array(
           '#type' => 'select',
           '#options' => _kdatabase_field_filtertypes(),
           '#default_value' => $r->ffilter,
           '#title' => 'Allow search filtering on this value',
       );
       if(in_array($r->ftype, array('textfield', 'textarea', 'hidden'))){
           $form['field'][$r->kfid]['autocomplete'] = array(
               '#type' => 'checkbox',
               '#default_value' => $r->autocomplete,
               '#title'=>t('Form will auto-complete while user is typing.'),
           );
           $links = array();
           $ksynsets = explode("," ,variable_get('ksynsets', ""));
           $linkindex = isset($r->index)?unserialize($r->index):array();
           foreach($ksynsets as $ksynset){
              foreach($linkindex as $key=>$link){
                 $links[t(variable_get('ksynset_'.$ksynset.'_name', $ksynset))][$key.'|'.$ksynset] = _kasahorow_entry_types($key);
              }
              
           }
           if(!db_column_exists('kentry_fields', 'link')){//TODO: Add to .install file! - Done. Remove this in a cleanup
              @db_query("alter table {kentry_fields} add column `link` varchar(10)");   
           }            
           $form['field'][$r->kfid]['link'] = array(
               '#type' => 'select',
               '#options' => array_merge(array(NULL=>t('--No--')), $links),
               '#default_value' => $r->link,
               '#title'=>t('Link suggestions'),
               '#description'=> t('Delimiter (comma, semicolon, tab)
                                  separated entries will be used
                                  as link suggestions.')
           );
       }
       if(in_array($r->ftype, array('select', 'list'))){
           $form['field'][$r->kfid]['options'] = array(
               '#type' => 'textarea',
               '#title' => t('Selection options'),
               '#default_value' => $r->options,
               '#description'=>t('A list of all options.
                   Put each option on a separate line.
                   Example options are "red", "blue", "green", etc.'),
           );
       }
       
   }
   
   return $form;
}

function _kentry_fields_form_submit($state, $submitted){
    $vals = $submitted['values']['field'];
    foreach($vals as $kfid=>$info){
        $fields = array_keys($info);
        $values = array_values($info);
        if($kfid=='new###'){
            if(trim($info['name']!="")){
                $status = db_query("INSERT INTO {kentry_fields}(iso, ".
                    join(",", $fields).
                    ") VALUES('%s', ".
                    join(",", array_fill(0, count($fields), "'%s'"))
                    .")", 
                     array_merge(array($submitted['values']['iso']),$values));
            }
        }else{
            $pairs = array();
            foreach($info as $k=>$v){
                $pairs[] = "$k='%s'";
            }
            array_push($values, $kfid);
            $status = db_query("UPDATE {kentry_fields} SET ".join(',',$pairs).
                " WHERE kfid=%d", $values);
        }
    }
    if($status){
        drupal_set_message('Successfully updated children');
    }
}

/**
 * Display the list of databases
 */
function _check_stats() {
  $rows = array(); 
  $output = '<ul>';
  $output.= '<li>'.l(t('Design a new content type and publish it'),
      'admin/content/kw/kentry/add').'</li>';
  $output.= '</ul>';    
  $header = array(array('data'=>t('Content type'), 'field'=>'name'),
      t('Size'), t('Editors'), array('data'=>t('Status'), 'field'=>'status'),
      array('data'=>t('Featured'), 'field'=>'status'),
      array('data'=>t('Created'), 'field'=>'created'));
                 
  $result = db_query("SELECT name,`index`, editor,status, iso, created
                     FROM {kdictionary} ".tablesort_sql($header));
  $kentry = db_fetch_object(db_query("SHOW TABLES LIKE 'kentry'")); 
  while( $langs = db_fetch_object($result) ){
      $links = array(
          l(t('Properties'), 'admin/content/kw/kentry/props/'.$langs->iso),
          l(t('Edit'), 'admin/content/kw/kentry/edit/'.$langs->iso),
          module_exists("kdevel")?l(t('Quality'),
                                    'admin/content/kw/kentry/papa/'.$langs->iso
                                    ):l(t('Enable kasahorow Developer module
                                        to view Quality report'),
                                        'admin/build/modules')
          );
      $children= db_result(db_query("SELECT COUNT(*) FROM {kentry_fields}
          WHERE iso='%s'", $langs->iso));
      $rows[] = array(l($langs->name,'browse/'.$langs->iso )."
                      <br/><span id='help'>&nbsp;".join(" | ", $links)."</span>
                      <br /><i>&nbsp;Fields: ". ($children)."</i><br/>&nbsp;"._unserialize_to_string($langs->index)."",
                      $kentry?db_result(db_query("SELECT COUNT(*) FROM {kentry} WHERE iso='%s'", $langs->iso)):0,
                      getEditors($langs->iso),
                      ( $langs->status == 0 )?t('Off'):t('On'),
                      ($langs->status == 2)?t('Yes'):t('No'),
                      format_date($langs->created),
                                      
                      );
      
  }
  if(count($rows)) {
    $output.= theme('table', $header, $rows, array("width"=>"100%"));
    return $output;
  }else {
    return '';
  }
}

function _view_props($iso=NULL){
    global $user;
    include_once(drupal_get_path('module', 'node')."/node.pages.inc");
    $dict = $form = array();
    $output = "";
    if(($iso !=NULL) && (user_access('edit dictionary'))){
        $dict = db_fetch_array(db_query("SELECT * FROM {kdictionary} WHERE iso='%s'", $iso));
        drupal_set_title(t("Properties of %name", array('%name'=>$dict['name'])));
    }else{
        $dict['name'] = t('Sample database');
    }
    
    $output.="<div>p:iso=$iso</div>";
    $output.="<div>p:type=kentry_$iso</div>";
    $output.="<div>p:promote=0</div>";
    $output.="<div>p:uid=$user->uid</div>";
    $output.="<div>&nbsp;</div>";
    
    $node = new StdClass();
    $node->iso = $iso;
    $node->type='kentry_'.$iso;
    $form = node_form($form, $node);
    drupal_prepare_form($node->type."_node_form", $form, $form);
    foreach(element_children($form) as $name){
        switch($name){
            case 'basic':
                foreach(element_children($form[$name]) as $v){         
                    if(isset($form[$name][$v]['#default_value'])){//This is a form element
                        $output.="<div>k:$v=<i>".$form[$name][$v]['#title']."</i></div>";
                    }else{//This is a nested element
                        foreach(element_children($form[$name][$v]) as $props){
                            $output.="<div>k:$v:$props=<i>".$form[$name][$v][$props]['#title']."</i></div>";
                        }
                    }
                }
                break;
            case 'more':
                foreach(element_children($form[$name]) as $v){                 
                    if(isset($form[$name][$v]['#default_value'])){//This is a form element
                        $output.="<div>k:$v=<i>".$form[$name][$v]['#title']."</i></div>";
                    }else{//This is a nested element                     
                        foreach(element_children($form[$name][$v]) as $props){
                            $output.="<div>k:$v:$props=<i>".$form[$name][$v][$props]['#title']."</i></div>";
                        }
                    }
                }
                break;
            //Not needed in structure
            case 'form_token':
            case 'form_id':
            case 'attachments':
                break;
            case 'taxonomy':
                foreach(element_children($form[$name]) as $v){                    
                    if(isset($form[$name][$v]['#default_value'])){//This is a form element
                        $output.="<div>k:taxonomy:$v=<i>".$form[$name][$v]['#title']."</i></div>";
                    }else{//This is a nested element
                        foreach(element_children($form[$name][$v]) as $props){
                            $output.="<div>k:taxonomy:$v:$props=<i>".$form[$name][$v][$props]['#title']."</i></div>";
                        }
                    }
                }
                break;
            default:
                if(trim($form[$name]['#value'])){//This is a hidden field with a preset value
                    $output.="<div>k:$name=<i>".$form[$name]['#value']."</i></div>";
                }else{
                    $output.="<div>k:$name=<i>".$form[$name]['#title']."</i></div>";
                }
                break;
        }
    }    
    
    return $output;
}

function _kentry_form(&$node, $form_state) {
  $form = array();
  $chn = isset($node->children)?($node->children):array();
  $iso = substr($node->type, 7);
  $language = _name($iso);
  $langDetails = db_fetch_object(db_query("SELECT * FROM {kdictionary}
                                          WHERE status>0 AND iso='%s'", $iso));
  $labels = unserialize($langDetails->labels);
  $nodisplay = (variable_get('kentry_show', array()));
  
  $form['title'] = array('#type' => 'textfield',
          '#title' => t('%lang title', array('%lang'=>$language)),
          '#default_value' => $node->title?$node->title:arg(3),
          '#size' => 60,
          '#maxlength' => 128,
          '#required' => TRUE,
          '#weight'=>-10,
          '#autocomplete_path' => 'kentry/check/'.$iso,
          '#description'=>t('This most common (or correct standard) spelling of the name of the %lang.', array('%lang'=>$language)),
  );
  
  //entry component enabled
  if( !$nodisplay['word'] || !$nodisplay['defn'] || 
      !$nodisplay['example'] || !$nodisplay['notes'] ) { 
  
      //The form proper
      // Don't show when component has been turned off at the settings page.
      $form['basic'] = array('#type'=>'fieldset', '#title'=>t('Basic properties'), 
                          '#weight'=>-6,
                          '#description'=>t(''),
                          '#collapsible'=>TRUE);
      !$nodisplay['word'] ? 	    
  
      $form['basic']['lexeme'] = array('#type' => 'textfield',
          '#title' => isset($labels['word']['title'])?$labels['word']['title']:t('Group with'),
          '#default_value' => isset($node->word)?$node->word:'',
          '#size' => 60,
          '#maxlength' => 128,
          '#autocomplete_path' => 'kentry/check/'.$iso,
          '#description'=>isset($labels['word']['desc'])?$labels['word']['desc']:t('Put the common group that this entry belongs to here'),
       
       ) : '';
      
      !$nodisplay['defn'] ?
      
      $form['basic']['body'] = array(
          '#type' => 'textarea',
          '#title' => isset($labels['defn']['title'])?$labels['defn']['title']:t('Body'),
          '#default_value' => isset($node->body)?$node->body:'',
          '#cols' => 60,
          '#rows' => 15,
          '#description' => isset($labels['defn']['desc'])?$labels['defn']['desc']:t(''),			  
      ) : '' ;
      
      
      !$nodisplay['example'] ?
      
      $form['basic']['example'] = array(
            '#type' => 'textarea',
            '#title' => isset($labels['example']['title'])?$labels['example']['title']:t('Further details'),
            '#default_value' => isset($node->example['default'])?$node->example['default']:'',
            '#cols' => 60,
            '#rows' => 5,
            '#description' => isset($labels['example']['desc'])?$labels['example']['desc']:t('Use a ~(tilde) in place of the word.', array('%lang'=>t($language))),
            '#attributes' => NULL,
          ) : '' ;
          
      !$nodisplay['notes'] ?
                                          
      $form['basic']['notes'] = array(
            '#type' => 'textarea',
            '#title' => isset($labels['notes']['title'])?$labels['notes']['title']:t('Notes'),
            '#default_value' => isset($node->notes)?$node->notes:'',
            '#cols' => 60,
            '#rows' => 4,
            '#description' => isset($labels['notes']['desc'])?$labels['notes']['desc']:'',
            '#attributes' => NULL,
          ) : '';
      }
  //make this equivalent to published or not published or in moderation queue
   if(user_access('review entry')){
       $form['basic']['approved'] = array(
                 '#type' => 'select',
                 '#title' => t('Verify'),
                 '#default_value' => isset($node->approved)?$node->approved:0,
                 '#options' => array('-1'=>'Waitlist', '0'=>'No', '1'=>'Yes'),
                 '#description' => 'Is this entry fully reviewed?',
                 '#required' => FALSE,			  
               );
   }else{
       $form['approved'] = array(
         '#type' => 'hidden',
         '#value' => 0,
       );
   }
  
  $form['more'] = array('#type'=>'fieldset', '#title'=>t('More info'), 
                                              '#weight'=>-5,
                                            '#collapsible'=>TRUE,
                                            '#description'=> '');
  
  //Use new implementation by default
  $form['more'] += _kentry_values_form($node);    
  
  $form['more']['altmisspells'] = array(
            '#type' => 'textarea',
            '#title' => t('Alternate search queries for this entry'),
            '#default_value' => isset($node->altmisspells)?$node->altmisspells:'',
            '#cols' => 60,
            '#rows' => 1,
            '#description' => t('This includes common misspellings, non-standard spellings and other search terms that are highly relevant to this entry. Separate each alternative form with a comma. e.g. medaa,
             m\'daa. They are not shown as part of the entry, but help to improve the search for this entry.')
          );
  
  $form['more']['priority'] = array(
            '#type' => 'hidden',
            '#value'=>isset($node->priority)?$node->priority:0
                  );
                              
  $form['iso'] = array(
            '#type' => 'hidden',
            '#value' => $iso,
          );
  $form['eid'] = array(
            '#type' => 'hidden',
            '#value' => isset($node->eid)?$node->eid:'',
          );
  $form['posvid'] = array(
                    '#type' => 'hidden',
                    '#value' => $langDetails->vid,
                  );
  $form['format'] = filter_form($node->format);
  return $form;
}

/**
 * Create the form for adding relations to this entry 
 */
function _add_relations($state, $node){
  $relations = _get_relations($node->vid);
  //$node->relations = $relations;
  $form = array('rels'=>array(
   //'#title'=>$node->title,
   '#tree'=>TRUE,
   '#type'=>'fieldset',
   '#description'=>t('Links are connections between 
                     entries. There can be different types of links.
                     Create a new type of link !here.',
                     array('!here'=>l(t('here'),
                       'admin/content/kw/settings/kentry',
                       array('query'=>drupal_get_destination())))).
  node_view($node, 1, 1)));

  $indexlangs = unserialize(db_result(db_query("SELECT `index`
                                               FROM {kdictionary}
                                               WHERE iso='%s'", $node->iso))); 
  drupal_set_title(t('Create links for !word', array('!word'=>$node->title)));
  $ksynsets = explode("," ,variable_get('ksynsets', ""));
  $ksynsets = ($ksynsets[0]=="")?array():$ksynsets;

  if(is_array($node->chn)){
    $matches = array();
    $suggestions = array();
    foreach($node->chn as $kfid=>$child){
      list($linkdb, $linktype) = split('\|', $child->link);
      if(in_array($linkdb, $indexlangs)){
        $possmatches = array_map('trim', split("[,;\t/]", $child->value));
        foreach($possmatches as $possmatch){
		  if (trim($possmatch)) {
			$matches = array_merge($matches, kentry_page(trim($possmatch), '',
														 $linkdb, 1));
		  }
        }
        foreach($matches as $match){
          if(is_array($relations[$match->iso][$linktype])) {
            $existing_relations = $relations[$match->iso][$linktype];
          }else{
            $existing_relations = array();
          }
          if(!in_array($match->vid,
                       array_keys($existing_relations))){
            $pos = module_exists('taxonomy')?
              taxonomy_get_term($match->partofspeech):'';
            $output ='<b>'.$match->title.'</b> ('.$pos->name.'), '.
              l(t('View'), "node/$match->nid",
                array('attributes' => array('target'=>'autosuggest')))."\n\t".
                node_teaser($match->defn,FILTER_FORMAT_DEFAULT, 200);		
            $suggestions[$match->iso][$linktype][$match->vid]= $output;
          }
        }
      }        
    }
  }

 if(is_array($indexlangs)){
    foreach($indexlangs as $iso){
      $form['rels'][$iso] = array(
        '#type'=>'fieldset',
        '#title'=>t('Create confirmed links from !word to !db entries',
                    array('!word'=>$node->title,
                          '!db'=>_name($iso))),
        '#collapsible'=>TRUE,
        );
      foreach($ksynsets as $ksynset){
       $ksynset = trim($ksynset);             
       $form['rels'][$iso][$ksynset.'wrap'] = array(
             '#type' => 'markup',
           //  '#prefix'=>"<fieldset class=' collapsible'><legend>".t(variable_get('ksynset_'.$ksynset.'_name', ''))."</legend>",
             '#value'=>'<div><h3>'.t(variable_get('ksynset_'.$ksynset.'_name', $ksynset)).'</h3></div'
                                       );
           if(is_array($suggestions[$iso][$ksynset])){
             $form['rels'][$iso][$ksynset."_n#w"] = array(
              '#title' => t("Add suggested links", array('%rel'=>t(variable_get('ksynset_'.$ksynset.'_name', $ksynset)))),
              '#type'=>'checkboxes',
              '#options'=>$suggestions[$iso][$ksynset],
              '#default_value' => array(), //array_keys($suggestions[$iso][$ksynset]),
              '#description'=>t('Check to add these links'),
//                 '#prefix'=>"<fieldset class=' collapsible'><legend>".t(variable_get('ksynset_'.$ksynset.'_name', ''))."</legend>",
              );  
           }
           
          //List existing connections as checkboxes so they can be deleted separately.
          $form['rels'][$iso][$ksynset."_o#d"] = array(
              '#title' => t("Delete existing links", array('%rel'=>t(variable_get('ksynset_'.$ksynset.'_name', '')))),
              '#type'=>'checkboxes',
              '#options'=>$relations[$iso][$ksynset],
              '#description'=>t('Check to delete'),
//               '#prefix'=>"<fieldset class=' collapsible'><legend>".t(variable_get('ksynset_'.$ksynset.'_name', ''))."</legend>",
              );
          $form['rels'][$iso][$ksynset] = array(
                  '#type' => 'textfield',
                  '#size' => 70,
                  '#maxlength'=>255,
                  '#title' => t("Add new links", array('%rel'=>t(variable_get('ksynset_'.$ksynset.'_name', '')))),
                  '#default_value' => '',
                  '#description' => t(variable_get('ksynset_'.$ksynset.'_derivation', ''))." ".t("Can't find entry to link to? !add", array('!add'=>l(t('Add it and return'), "node/add/kentry-$iso", array('query'=>drupal_get_destination())))),
                  '#autocomplete_path' => 'kentry/synonyms/'.$iso.'/'.$node->nid,
                 '#suffix'=>'<hr/>'//"</fieldset>",
        );  
      }
    }
 }
 $form['links'] = array('#type'=>'fieldset', '#title'=>t('Clear all links'), '#collapsed'=>TRUE, '#collapsible'=>TRUE);
 $form['links']['delete'] = array('#type'=>'checkbox', '#title'=>t('Clear all links associated with %entry', array('%entry'=>$node->title)), '#description'=>t('Check this box and click the corresponding button below to delete all links for this entry. There will be no other confirmation step'));
 $form['nid'] = array('#type'=>'value', '#value'=>$node->nid);
 $form['vid'] = array('#type'=>'hidden', '#value'=>$node->vid);
 $form['iso'] = array('#type'=>'hidden', '#value'=>$node->iso);
 $form['save'] = array('#type'=>'submit', '#value'=>t('Save links'));
 $form['clear'] = array('#type'=>'submit', '#value'=>t('Clear all links'));
 return $form;
}

/**
 *
 */
function _add_relations_validate($state, &$values){
   if($values['values']['op'] == t('Clear all links') && !$values['values']['delete']){
        form_set_error('delete', t('Confirm links clearance by checking the box'));
    } 
}

/**
 * process node forms
 */
function _add_relations_submit($state, &$values) {
  global $user;//The current user
    if($values['values']['op'] == t('Clear all links') && $values['values']['delete']){
        kentry_delete_relations($values['values']['vid']);      //Delete all existing explicit relations
        drupal_set_message(t('Cleared all links for this entry'));
    }else{
     $create = $update = 1;
        foreach($values['values']['rels'] as $iso=>$rels){
            foreach($rels as $relid=>$rellist){
              if("_n#w"==substr($relid, -4, 4)&& count($rellist)){                
                foreach($rellist as $vid){
                  if($vid>0){
                    $create = db_query("INSERT IGNORE {kentry_synonyms}
                                       (vid, svid, iso, relid, uid, updated)
                                       VALUES (%d, %d, '%s', '%s', %d, %d);\n",
                                       $values['values']['vid'], $vid, $iso,
                                       substr($relid, 0, -4), $user->uid,
                                       time());
                  }
                }
                
              }else
                if("_o#d"==substr($relid, -4, 4)&& count($rellist)){
                    $todelete = array(-1);
                    foreach($rellist as $vid=>$delete){
                        if($delete){
                            $todelete[] = $vid;
                        }
                    }
                    $update = db_query("DELETE FROM {kentry_synonyms} WHERE relid='%s' AND vid=%d AND svid IN (%s)", substr($relid, 0, -4), $values['values']['vid'], join(",", $todelete));
                    
                }else{
                    $relations = split(",", $rellist);
                    foreach($relations as $rel){
                        list($svid, ) = split(":", $rel);
                        if(is_numeric($svid)){//Only create connections between existing entries to avoid the situation where bogus entries are created. Conservative first!
                            $create = db_query("INSERT IGNORE {kentry_synonyms}
                                               (vid, svid, iso, relid, uid, updated)
                                               VALUES (%d, %d, '%s', '%s', %d, %d);\n",
                                               $values['values']['vid'], $svid, $iso, $relid, $user->uid, time());
                        }
                    }
                }
            }
        }
        drupal_set_message(($create && $update)?t('Links updated successfully'):t('Link update failed'), ($create && $update)?'status':'error');
    }
    $kvid = $values['values']['vid'];
    $node = new stdClass();
    $node->vid = $kvid;
    _kentry_update_cache($node);
    $values['redirect'] = "node/".$values['values']['nid'];
}

function _kentry_report_searches() {
  $lastrun = variable_get('kentry_cron_last', 0);
  if((time() - $lastrun) > 86400) {
    variable_set('kentry_cron_last', time());
    include_once(drupal_get_path('module', 'dblog').'/dblog.admin.inc');
    $header = array(
      array('data' => t('Type')),
      array('data' => t('Search term'), 'field' => 'message'),
      array('data' => t('Tries'), 'field' => 'count', 'sort' => 'desc'),
      ''
       );
    $rows = array();
    foreach(array('searchfail' => 'Failed search',
      'searchsuccess' => 'Successful search') as $type=>$name){
     
      $result = db_query("SELECT COUNT(wid) AS count, message, variables
                          FROM {watchdog}
                          WHERE `type` = '%s' AND `timestamp`>%d
                          GROUP BY message, variables ". tablesort_sql($header),
                          $type, $lastrun);

      while ($dblog = db_fetch_object($result)) {
        $term = truncate_utf8(_dblog_format_message($dblog), 56, TRUE, TRUE);
        $rows[] = array($name, $term,
          $dblog->count,
          l(t('Add as entry'), "node/add", array('absolute' => 1)));
      }
    }
  }

  if(count($rows)) {
    $output  = theme('table', $header, $rows);
    $output .= theme('pager', NULL, 30, 0);
    $params = array(
      'subject'=>t('!day search report', array('!day'=>date('r'))),
      'message' => $output,
      'headers'=>array(
        'From' => variable_get('site_name', 'A kasahorow website').
        '<'.variable_get('site_mail', 'content@kasahorow.com').'>',
        'Content-Type' => 'text/html; charset=UTF-8; format=flowed',
       )
    );
    //TODO: Somehow send the report also to the language editors
    drupal_mail('kentry', 'alert', variable_get('kdictionary_mail', 'kasahorow@suuch.com'), language_default(), $params);
    watchdog('kentry', 'Alert sent:'.format_date(variable_get('kentry_cron_last', time())));
    $editors = _getUsersId();
    $editors = is_array($editors)?$editors:array();
    $expires = strtotime('next week');
    $macro = 'SEARCH_REPORT';
    $log = $output;
    $donotnotify = 0;
    $service = _fienipa_get_service();
    //Notify each editor
    foreach($editors as $uid=>$link){
      $server = variable_get("myfienipa_market", "http://fienipa.com");
      $result = xmlrpc($server."/xmlrpc.php",
        'flog.create.message',
        (int)$uid, (string)$macro, (string)$service,
        (int)$expires, (bool)$donotnotify,
        'kentry', (string)$log
      );
      if ($result === FALSE) {
        watchdog('fmonitor', 'Error %code: !message',
          array('%code' => xmlrpc_errno(),
                '!message' => xmlrpc_error_msg().' '.
                l(t('Notify the administrator'),
                'contact')), WATCHDOG_ERROR);
      }else {
        watchdog('fmonitor', 'Send alert at !url',
          array('!url'=>url("$server/fn/log/alert/$result")));
      }
    }
  }
}

function _kentry_report_content() {
  set_time_limit(0);
  # Release cron semaphore
  variable_del('cron_semaphore');
  if((time() - variable_get('kdictionary_cron_last', 0)) > 86400 &&
      date('D') == variable_get('kdictionary_cron_day', 'Tue')){
    kentry_fnapi('cron');
    # give a 1 hour window for this job to execute
    variable_set('kdictionary_cron_last', time());
    $stats = _check_stats();
    if($stats) {
      $params = array(
        'subject'=>t('!day Content Status Update', array('!day'=>date('r'))),
        'message' => $stats,
        'headers'=>array(
        'From' => variable_get('site_name', 'A kasahorow website').
          '<'.variable_get('site_mail', 'content@kasahorow.com').'>',
          'Content-Type' => 'text/html; charset=UTF-8; format=flowed',
        )
      );
      drupal_mail('kentry', 'alert', variable_get('kdictionary_mail', 'kasahorow@suuch.com'), language_default(), $params);
      watchdog('kdictionary', 'Alert sent:'.format_date(variable_get('kdictionary_cron_last', time())));
    }
  }else {
    watchdog('kentry-nostats', 'Alert not sent:'.format_date(variable_get('kdictionary_cron_last', time())));
  }
}


function _kfield_upload_form($state, $field) {  
  global $user;	
  # Wrapper for fieldset contents (used by ahah.js).
  $form = array(
    '#prefix' => '<div id="attach-wrapper">',
    '#suffix' => '</div>',
  );
  $form['#theme'] = 'upload_form_new';
  $form['#attributes']['enctype'] = 'multipart/form-data';  # required for file uploads
  if (is_array($field->files) && count($field->files)) {
    $form['files']['#theme'] = 'upload_form_current';
    $form['files']['#tree'] = TRUE;
    foreach ($field->files as $key => $file) {
      $file = (object)$file;  
      /*$description = url("g/dlfile/$file->fid/$file->field_id/$file->filename",
        array('absolute' => True));*/
      $description = file_create_url($file->filepath);
      $description = "<small>". check_plain($description) ."</small>";
      $form['files'][$key]['description'] = array('#type' => 'textfield', '#default_value' => trim($file->description) ? $file->description : $file->filename, '#maxlength' => 256, '#description' => $description );
      $form['files'][$key]['size'] = array('#value' => format_size($file->filesize));
      $form['files'][$key]['remove'] = array('#type' => 'checkbox', '#default_value' => !empty($file->remove));
      $form['files'][$key]['list'] = array('#type' => 'checkbox',  '#default_value' => $file->list);
      $form['files'][$key]['weight'] = array('#type' => 'weight', '#delta' => count($field->files), '#default_value' => $file->weight);
      $form['files'][$key]['filename'] = array('#type' => 'value',  '#value' => $file->filename);
      $form['files'][$key]['filepath'] = array('#type' => 'value',  '#value' => $file->filepath);
      $form['files'][$key]['filemime'] = array('#type' => 'value',  '#value' => $file->filemime);
      $form['files'][$key]['filesize'] = array('#type' => 'value',  '#value' => $file->filesize);
      $form['files'][$key]['fid'] = array('#type' => 'value',  '#value' => $file->fid);
      $form['files'][$key]['new'] = array('#type' => 'value', '#value' => FALSE);
    }
  }
  if (user_access('upload files')) {
    $limits = _upload_file_limits($user);

    $limit_description = t('The maximum size of file uploads is %filesize.', array('%filesize' => format_size($limits['file_size']))) . ' ';
    if (!empty($limits['resolution'])) {
      if (image_get_toolkit()) {
        $limit_description .= t('Images larger than %resolution will be resized.', array('%resolution' => $limits['resolution'])) . ' ';
      }
      else {
        $limit_description .= t('Images may not be larger than %resolution.', array('%resolution' => $limits['resolution'])) . ' ';
      }
    }
    $limit_description .= t('Only files with the following extensions may be uploaded: %extensions.', array('%extensions' => $limits['extensions'])) . ' ';
    
    $form['new']['#weight'] = 10;
    $form['new'][$field->kfid] = array(
      '#type' => 'file',
      '#title' => t('From your computer'),
      '#size' => 40,
      '#description' => $limit_description,
    );
    $form['field_id'] = array('#type' => 'hidden', '#value' => $field->kfid);
    $form['new']['attach'] = array(
      '#type' => 'submit',
      '#value' => t('Upload'),
      '#name' => 'attach',
      '#ahah' => array(
        'path' => 'kfield_upload/js',
        'wrapper' => 'attach-wrapper',
        'progress' => array('type' => 'bar', 'message' => t('Please wait...')),
      ),
      '#submit' => array('_kfield_upload_form_submit'),
    );
  }
  return $form;
}

function _kfield_upload_form_validate(&$form, &$form_state) {
}

function _kfield_upload_form_submit(&$form, &$form_state) {
  global $user;
  $upload_field = $form_state['values']['field_id'];  
  //Internet links
  db_query("UPDATE {kentry_fields} SET value='%s' WHERE kfid=%d",
            $form_state['values']['_auto'], $upload_field);


  // Local file uploads
  $limits = _upload_file_limits($user);
  $validators = array(
    'file_validate_extensions' => array($limits['extensions']),
    'file_validate_image_resolution' => array($limits['resolution']),
    'file_validate_size' => array($limits['file_size'], $limits['user_size']),
  );
  // Save new file uploads.  
  $dir_path = file_directory_path();
  $file = file_save_upload($upload_field, $validators, $dir_path);
  if ($file) {
    $file->list = variable_get('upload_list_default', 1);
    $file->description = $file->filename;
    $file->weight = 0;
    $file->new = TRUE;
    $file->field_id = $upload_field;
    $file->nid = $form_state['values']['nid'];
    $file->vid = $form_state['values']['vid'];
    $status = _kfield_upload_save($file);
    if($status) {

    }else {
      drupal_set_message(t('Failed to save upload to field'), 'error');
    }
    $form['#ga']->files[$file->fid] = $file;
    $form_state['values']['files'][$file->fid] = (array)$file;
  }else {
      drupal_set_message(t('File upload failed'), 'error');
  }

  if (isset($form_state['values']['files'])) {
    foreach ($form_state['values']['files'] as $fid => $file) {
      $form_state['values']['files'][$fid]['new'] = 
        !empty($form['#ga']->files[$fid]->new);
    }
  }

  // Order the form according to the set file weight values.
  if (!empty($form_state['values']['files'])) {
    $microweight = 0.001;
    foreach ($form_state['values']['files'] as $fid => $file) {
      if (is_numeric($fid)) {
        $form_state['values']['files'][$fid]['#weight'] = 
          $file['weight'] + $microweight;
        $microweight += 0.001;
      }
    }
    uasort($form_state['values']['files'], 'element_sort');
  }
}

/***
 * Save this file object to a grantapplication
 * Returns True on success, false otherwise
 */
function _kfield_upload_save($file) {
  $old_path = $file->filepath;
  $new_path = file_destination(str_replace($file->filename, 
    $file->uid.'_'.$file->field_id.'_'.$file->filename, $file->filepath),
    FILE_EXISTS_REPLACE);  
  /*TODO(paakwesi): Fix the copy operation--it doesn't seem to work
  if(file_copy($file, $new_path)) {  //Copy to the new location
    file_delete($old_path);  //And delete from old path
    $file->filepath = $new_path ? $new_path: $file->filepath;
  }*/
  file_set_status($file, FILE_STATUS_PERMANENT);
  # Add the file to the upload table
  // Create a new revision, or associate a new file needed.
    if ($file->new) {
      db_query("INSERT INTO {upload} (fid, nid, vid, list, description, weight) VALUES (%d, %d, %d, %d, '%s', %d)", $file->fid, $file->nid, $file->vid, $file->list, $file->description, $file->weight);
    }else {
    // Update existing revision.
      db_query("UPDATE {upload} SET list = %d, description = '%s', weight = %d WHERE fid = %d AND vid = %d", $file->list, $file->description, $file->weight, $file->fid, $file->vid);
    }

  return db_query("INSERT INTO {kentry_files} (fid, uid, field_id, 
    filename, filepath, filemime, filesize, list, description) VALUES 
    (%d, %d, %d, '%s', '%s', '%s', %d, %d, '%s')", $file->fid, $file->uid, 
    $file->field_id, $file->filename, $file->filepath, $file->filemime, 
    $file->filesize, $file->list, $file->description);
}

function _kfield_upload_js() {
  $cached_form_state = array();
  $files = array();
  $cached_form = form_get_cache($_POST['form_build_id'], $cached_form_state);
  // Load the form from the Form API cache.
  if (!$cached_form) {
    form_set_error('form_token', t('Validation error, please try again. If this error persists, please contact the site administrator.'));
    $output = theme('status_messages');
    print drupal_to_js(array('status' => TRUE, 'data' => $output));
    exit();
  }
  
  # Handle form upload
  $values = array('values' => $_POST);
  _kfield_upload_form_submit($cached_form, $values);
   

  # Render the form for output.
  $node = new stdClass();
  $node->nid = $values['values']['nid'];
  $node->iso = $values['values']['iso'];
  $node->files = $values['values']['files'];
  $field = _kentry_upload_field($node, $values['values']['field_type']);
  $form = _kfield_upload_form(array(), $field); 

  # Update the cached version of the form with changes the user may have made
  $cached_form['attachments']['wrapper'] = array_merge($cached_form['attachments']['wrapper'], $form);
  $cached_form['attachments']['#collapsed'] = FALSE;
  form_set_cache($_POST['form_build_id'], $cached_form, $cached_form_state);

  $form += array(
    '#post' => $_POST,
    '#programmed' => FALSE,
    '#tree' => FALSE,
    '#parents' => array(),
  );
  drupal_alter('form', $form, array(), '_kfield_upload_js');
  $form_state = array('submitted' => FALSE);
  $form = form_builder('_kfield_upload_js', $form, $form_state);
  $output.= theme('status_messages') . drupal_render($form);
  print drupal_to_js(array('status' => TRUE, 'data' => $output));
  exit;
}

function _kentry_upload_field($object, $type='node') {
  $db = new KDatabase('kentry_fields');
  switch($type) {
    case 'node':
      $node = $object;
      $field = $db->RetrieveRow(array('wheres' => array("name='node-$node->nid'")));
      if(!$field) {
        $field = array(
          'name' => "node-$node->nid",
          'iso' => '_auto', # autogenerated field
          'ftype' => 'urlattachments',
          'label' => "Media from around the web for $node->title"
        );
        $db->InsertRow($field);
      }
      $field->files = $node->files;
      break;
  }
  return $field;
}

