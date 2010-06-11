<?php

function _swen_admin_home(){
  $item = menu_get_item();
  if ($content = system_admin_menu_block($item)) {
    $output = theme('admin_block_content', $content);
  }
  else {
    $output = t('You do not have any administrative items.');
  }
  return $output;
}

function _swen_reports($type='stats'){
  global $SWEN_FIELDS;
  $output = 'Swahili-English Dictionary Reports';
  $header = array();
  $rows = array();
  $properties = array('width'=>'95%', 'align'=>'center');
  switch($type){
    case 'frequency':{
      $sql = "SELECT COUNT(path) AS hits, path, MAX(title) AS title,
             AVG(timer) AS average_time, SUM(timer) AS total_time
             FROM {accesslog} WHERE path LIKE 'lookup%' GROUP BY title";
      $sql_cnt = "SELECT COUNT(*) FROM {accesslog}
                 WHERE path LIKE 'lookup%' GROUP BY title ";
    
      $header = array(
        array('data' => t('Tries'), 'field' => 'hits', 'sort' => 'desc'),
        array('data' => t('Search term'), 'field' => 'path'),
        //array('data' => t('Average page generation time'), 'field' => 'average_time'),
        //array('data' => t('Total page generation time'), 'field' => 'total_time')
      );
      $sql .= tablesort_sql($header);
      $result = pager_query($sql, 30, 2, $sql_cnt);

      $rows = array();
      while ($page = db_fetch_object($result)) {
        preg_match("/'(.*?)'/", ($page->title), $word);
        $rows[] = array(
          $page->hits,
          _statistics_format_item($word[1], $page->path."?Word=$word[1]"),
          //t('%time ms', array('%time' => round($page->average_time))),
          //format_interval(round($page->total_time / 1000))
          );
      }
    
      if (empty($rows)) {
        $rows[] = array(array('data' => t('No statistics available.'), 'colspan' => 4));
      }
    
      drupal_set_title(t('Top searches in the past %interval', array('%interval' => format_interval(variable_get('statistics_flush_accesslog_timer', 259200)))));
      break;
    }
    case 'failed':{
      include_once(drupal_get_path('module', 'dblog').'/dblog.admin.inc');
      $header = array(
        array('data' => t('Type')),
        '',
        array('data' => t('Search term'), 'field' => 'message'),
        array('data' => t('Tries'), 'field' => 'count', 'sort' => 'desc'),
        ''
      );
      $rows = array();
      foreach(array(0=>t('Swahili-English'), 1=>t('English-Swahili')) as $dxn=>$name){
        $type = $dxn==0?'sw|en':'en|sw';
        
        $result = db_query("SELECT COUNT(wid) AS count, message, variables FROM {watchdog}
                           WHERE type = '%s' AND `timestamp`>%d
                           GROUP BY message, variables ". tablesort_sql($header),
                           $type, $lastrun);
      
        
        while ($dblog = db_fetch_object($result)) {
          $term = truncate_utf8(_dblog_format_message($dblog), 56, TRUE, TRUE);
          $rows[] = array($name, '', $term, $dblog->count,
                          l(t('Create entry'), "node/add/kentry-$db/$term",
                                   array('absolute'=>1)));
        }
      }

      
      break;
    }
    case 'stats':
    default:{
      $header = array('Field', 'Raw Count', 'Unique Count');
      foreach($SWEN_FIELDS as $field){
          $row = db_fetch_object(
              db_query("SELECT COUNT(%s) as rawc,
                       COUNT(DISTINCT(%s)) AS distinctc FROM {dict} WHERE
                       TRIM(%s) NOT IN ('', '0')", $field, $field, $field));
          $rows[] = array($field, $row->rawc, $row->distinctc);
        }

      break;
    }
  }
  
  
  $output.= theme('table', $header, $rows, $properties);
  $output .= theme('pager', NULL, 30, 2);   
  
  
  return $output;
}

function _swen_settings(){
  $form = array();
  $form['swen_image_dir'] = array(
      '#type'=>'textfield',
      '#title'=>t('Image directory'),
      '#default_value' => variable_get('swen_image_dir', 'files/'),
      '#description'=>''
      );
  
  $form['swen_search_history'] = array(
      '#type'=>'textfield',
      '#title'=>t('Number of searches in search history'),
      '#default_value' => variable_get('swen_search_history', 10),
      '#description'=>''
      );
  $form['paldo_server'] = array(
      '#type'=>'textfield',
      '#title'=>t('PALDO root domain'),
      '#default_value' => variable_get('paldo_server', 'http://words.fienipa.com'),
      '#description'=>''
      );
  $form['swen_search'] = array(
      '#type'=>'select',
      '#title'=>t('Search database'),
      '#options' => array('swen' => 'Swahili/English', 'paldo' => 'PALDO'),
      '#default_value' => variable_get('swen_search', 'paldo'),
      '#description'=>''
      );
  $fields = variable_get('swen_paldo_fields', array());
  $form['swen_paldo_fields'] = array(
      '#type' => 'fieldset',
      '#tree' => 1,
      '#title' => t('Map PALDO fields to {dict} columns')
                                     );
  $form['swen_paldo_fields']['POS'] = array(
      '#type'=>'textfield',
      '#title'=>t('Part of Speech'),
      '#default_value' => $fields['POS'],
      '#description'=>'The Taxonomy Vocabulary ID on PALDO that corresponds '.
                      'to the PartOfSpeech column'
      );
  $form['swen_paldo_fields']['DL'] = array(
      '#type'=>'textfield',
      '#title'=>t('Derived Language'),
      '#default_value' => $fields['DL'],
      '#description'=>'The Taxonomy Vocabulary ID on PALDO that corresponds '.
                      'to the DerivedLang column'
      );
  $form['swen_paldo_fields']['TERM'] = array(
      '#type'=>'textfield',
      '#title'=>t('Terminology'),
      '#default_value' => $fields['TERM'],
      '#description'=>'The Taxonomy Vocabulary ID on PALDO that corresponds '.
                      'to the Terminology column'
      );
  return system_settings_form($form);
}