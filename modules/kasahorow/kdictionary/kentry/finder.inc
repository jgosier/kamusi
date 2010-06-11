<?php

/**
 * The filtering uses a negative logic. To filter, it compiles a list of
 * all node->vids that do NOT obey the filter. Then it looks at a list of
 * all node->nids whose title matches the search terms. It then removes any
 * node->nids which are in the negative filter.
 * TODO(paa.kwesi): This is becomes inefficient as the database size grows
 */
function _kentry_finder($db=NULL, $title='', $approved=NULL) {
  if(!$db){
    return _available_dicts();
  }
  drupal_set_breadcrumb(array(l(t('Home'),''), l($pt = t('Find !dbname',
                                                   array('!dbname' =>
                                                         _name($db))),
                                                 "find/$db")));
  $field_filters = _kdatabase_field_filtertypes();
  unset($field_filters[0]); //Remove the no-filter field
  $field_filters = is_array($field_filters)?array_keys($field_filters):array();
  $fields = _kdatabase_get_field_filters($db, $field_filters);
  drupal_set_title($pt);
  $header[] = '';
  $conditions = array();
  foreach($fields as $kfid => $field){
    $header[t($field->label)] = array('data' => t($field->label),
                                      'field'=>'v.value',
                                      'kfid' => $field->kfid);
    $fields_by_name[$field->name] = $field;
  }
  if(is_numeric($header[($_GET['order'])]['kfid'])) {
    $sort_by_kfid = ' f.kfid='.$header[($_GET['order'])]['kfid'].' ';
  }else{
    $sort_by_kfid = ' 1 ';
  }
  
  $query_filters[] = '1';
  $vids[] = -1;
  $valid_vids = array();
  $processed_params = array();
  foreach($_GET as $param => $value){
    if(in_array($param, $processed_params)){
      continue;
    }
    $processed_params[] = $param;
    $is_min = substr($param, 0, 5)=='_min_';
    $is_max = substr($param, 0, 5)=='_max_';
    if($param == 's') {
      $query_filters[] = sprintf("n.title LIKE '%%%s%%'", $value);      
    }else if($is_min) { //Check for max
      $label = substr($param, 5);
      if($max_value = $_GET['_max_'.$label]){
        $processed_params[] = '_max_'.$label; //Avoid reprocessing
        $result = db_query("SELECT vid FROM kentry_values
                                    WHERE kfid=%d AND
                                      value BETWEEN %d AND %d",
                                    $fields_by_name[$label]->kfid,
                                    $value, $max_value
                                    );
        
      }else{
        $result = db_query("SELECT vid FROM kentry_values
                                    WHERE value>=%d AND kfid=%d",
                                    $value, $fields_by_name[$label]->kfid);  
      }
    }else if($is_max) {//Check for min
      $label = substr($param, 5);
      if($min_value = $_GET['_min_'.$label]){
        $processed_params[] = '_min_'.$label; //Avoid reprocessing
        $result = db_query("SELECT vid FROM kentry_values
                                    WHERE kfid=%d AND
                                      value NOT BETWEEN %d AND %d",
                                    $fields_by_name[$label]->kfid,
                                    $min_value, $value
                                    );
        
      }else{
        $result = db_query("SELECT vid FROM kentry_values
                                    WHERE value<=%d AND kfid=%d",
                                    $value, $fields_by_name[$label]->kfid);
      }
    }else{
      //default--assume it's a textfield
      if($fields_by_name[$param]->kfid) { //A valid field
        $result = db_queryd("SELECT vid FROM kentry_values
                                      WHERE kfid=%d AND
                                        value LIKE '%s%%'",
                                      $fields_by_name[$param]->kfid,
                                      $value
                                      );
      }
    }
    if($result){
      while($r = db_fetch_object($result)){
        $vids[] = $r->vid;
        $conditions[$label][$r->vid] = $r->vid;
      }        
    }
  }
  $num_conditions = count($conditions);
  foreach(array_count_values($vids) as $vid => $count) {
    if($count == $num_conditions){
      $valid_vids[$vid] = $vid;
    }
  }
  if(!$num_conditions){
    //no conditions. Let's get some random listings by default;
    $valid_vids = array_keys(kentry_page($title, $approved, $db, 1));
  }else{
    
  }
  $query_filters[] = sprintf("n.vid IN (%s)", join(",", $valid_vids));
  
  $nids = array();
  $rows = array();
  $lats = array();
  $longs = array();
  
  $header[] = array('data' => t('Date'), 'field' => 'n.changed');
  $q = pager_query("SELECT n.nid FROM {node} n
                INNER JOIN {kentry_values} v ON v.vid=n.vid
                INNER JOIN {kentry_fields} f ON f.kfid=v.kfid
                WHERE $sort_by_kfid $sort_by_title
                AND ".join(' AND ', $query_filters).' GROUP BY n.nid '.
                tablesort_sql($header),
                variable_get('kasahorow_entries_per_page', 10));
  while($r = db_fetch_object($q)){
    $nids[] = $r->nid;
    $nodes[$r->nid] = node_load(array('nid'=>$r->nid));
    if(function_exists('kuser_link')){
      $rows[$r->nid]['node'] = theme('links',
                                     kuser_link('node', $nodes[$r->nid])).' ';
    }
    
    $rows[$r->nid]['node'].= l($nodes[$r->nid]->title, "node/$r->nid");
    foreach($fields as $kfid => $field){
      $rows[$r->nid][] = $nodes[$r->nid]->chn[$kfid]->value;
    }
    $rows[$r->nid][] = format_date($nodes[$r->nid]->changed);
    $lats[] = $nodes[$r->nid]->latitude;
    $longs[] = $nodes[$r->nid]->longitude;
  }

  $mapcenter = (array_sum($lats)/2).','.(array_sum($longs)/2);
  
  if($_GET['format']=='rss_200'){
    node_feed($nids);
    exit;
  }
  $results = theme('table', $header, $rows, '38%').theme('pager');
  drupal_add_js(drupal_get_path('module', 'kdictionary').'/jquery.jmap.js');
  $localhostkey = _kfield_google_maps_key();
  $instructions = t('Markers show only approximate location');
  $output.= drupal_get_form('_kentry_finder_form', $db, $fields);
  $output.= '<hr/>';
  $html = <<<HTML
    <div id='results' style='float:left'>$results</div>
    <div id="map1" class="jmap" style="float:right; display:block; border:1px solid gray; height: 500px;width: 58%; margin:0 auto;"></div>
    <br style='clear:both;'/>
    <p align="center">$instructions</p>
    
    <!-- Localhost Key-->
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=$localhostkey" type="text/javascript"></script>
    
    <!-- map.ifies.org -->
    <!--<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAApCYnM8S-TSRBhQk16Ynr4BRMFDArna7TyLvN-BP2UPnpVPTTThTJtWBJwhvgDdYt37zadgzAjoYHSQ" type="text/javascript"></script>-->

    
    <script type="text/javascript">
    var search_feed = location.href+'&format=rss_200';
    
        jQuery(document).ready(function(){
					jQuery('#map1').jmap('init', {'mapType':'hybrid',
                    'mapCenter':[$mapcenter],
                    'mapEnableGoogleBar':'true',
                    'mapZoom':7, //TODO: Calculate with GMap2.getBoundsZoomLevel( latlngbounds ) instead
                    'mapShowjMapsIcon':false,
                    });
					
					//jQuery('.add-feed').click(function(){
						jQuery('#map1').jmap('AddFeed', {							
                            'feedUrl': search_feed
						}, function(feed, options){
							
						});
					//});
        });
    </script>
HTML;
  $output.=$html;
  return $output;
}

function _kentry_finder_form($state, $db=NULL, $fields) {
  $form = array();
  $form['db'] = array(
    '#type' => 'value',
    '#value' => $db
  );
  //The next 2 fields are shown inline
  $form['s'] = array(
    '#type' => 'textfield',
    '#default_value' => $_GET['s'],
    '#prefix' => '<div class="container-inline">'
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Search'),
    '#suffix' => '</div>'
  );
  if(count($fields)) {
    $form['filters'] = array(
      '#type' => 'fieldset',
      '#title' => t('Filters'),
      '#collapsible' => 1,
      '#tree' => 1,
      '#prefix' => '<div class="container-inline">'
    );
    
    foreach($fields as $kfid=>$field) {
      if($field->ffilter == 'map') {
        $form['filters'][$field->name] = array(
          '#type' => $field->ffilter,
          '#value' => array('min'=>$_GET['_min_'.$field->name],
                            'max'=>$_GET['_max_'.$field->name],
                           ),
          '#title' => $field->label,
          '#description' => $field->explanation,
          //'#default_value' => $_GET[$field->name]
        );
      }else{
        $form['filters'][$field->name] = array(
          '#type' => $field->ffilter,
          '#default_value' => $_GET[$field->name],
          '#field_prefix' => $field->label,
          '#description' => $field->explanation,
        );
      }
    }
    $form['div'] = array(
      '#suffix' => '</div>'
    );
  }
  return $form;
}

/**
 * Simply construct a $_GET query and redirect to that URL
 */
function _kentry_finder_form_submit($state, &$values){
  $v = $values['values'];
  $params = array();
  $db = $v['db'];
  $params[] = 's='.$v['s'];
  if(is_array($v['filters'])) {
    foreach($v['filters'] as $kfid => $value) {
      if(is_array($value)){
        foreach($value as $k => $val) {
          if($val){
            $params[] = '_'.$k.'_'.$kfid.'='.$val; 
          }          
        }
      }else{
        $params[] = "$kfid=$value"; 
      }
    }
  }
  drupal_goto("find/$db", join('&', $params));
}

/**
 *@args
 *$db - Database to return fields for
 *$filtertype - Whether to return only fields of a certain type
 * defined by _kdatabase_field_filtertypes()
 */
function _kdatabase_get_field_filters($db=NULL, $filtertype=array(0)) {
  $fields = array();
  if($db) {
    $q = db_query("SELECT * FROM {kentry_fields}
                  WHERE iso='%s' AND ffilter IN ('".join("','", $filtertype)."')",
                  $db
                  );
    while($r = db_fetch_object($q)){
      $fields[$r->kfid] = $r;
    }
  }
  return $fields;
}

