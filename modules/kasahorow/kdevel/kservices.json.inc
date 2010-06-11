<?php

function _kservices_json() {
  $output = $_GET['format'];
  $method = $_GET['method'];
  $args = explode(',', $_GET['args']);
  $methods = array();
  $rpc_methods = kservices_xmlrpc();
  foreach($rpc_methods as $key => $callback) {
    if(is_int($key)) {
      $methods[$callback[0]] = array(
          'callback' => $callback[1],
          'signature' => (count($args) == count($callback[2])-2)?$args:$callback[2],
          'help' => $callback[3]
                                     );
    }
    
  }
  $options = array('key'=> $_GET['kkey']);
  if(!empty($methods[$method])) {
    $jsondata = call_user_func_array($methods[$method]['callback'],
                                 array_merge($options,
                                             $methods[$method]['signature']));
  }else {
    $jsondata = new stdClass();
  }
  switch($output){
    case 'html':
      _kservices_iframe($jsondata);
      break;
    case 'js':
    default:
      _kjson_output('kasahorow_callback', $jsondata);
      break;
  }
  
}

function _kjson_output($callback, $data) {
  drupal_set_header('Content-Type: text/javascript; charset=utf-8');
  print $callback.'('.drupal_to_js($data).')';
}

function _kservices_iframe($data) {
  
  $h = <<<HTML
  
  
HTML;
  drupal_set_header('Content-Type: text/html; charset=utf-8');
  if (count($data['results'])) {
    foreach($data['results'] as $result) {
      $h.= theme_kentry_iframe($result);
    }
  } else {
    $h.= t('No results available. !suggest',
           array('!suggest' => l(t('Suggest that it be added'), 'contact',
                                 array('attributes' =>
                                       array('target' => '_blank')))));
  }
  print $h;
  exit;
}

function theme_kentry_iframe($result) {
  $output = '<div class="get-links-result">';
  $output.= '<u>'.l($result['type'].': '.$result['node']->title,
              $result['link'], array('attributes' =>
                                           array('target' => '_blank'),
                                           )).'</u>';
  $output.= '<p><img width=25px height=25px src="'.$result['node']->thumbnail.
            '" align="left"/>';
  $output.= $result['node']->defn.
  str_replace('<a', '<a target="_blank" ', $result['snippet']).'</p>';
  //$output.= '<small>'.l($result['link'], $result['link']).'</small>';
  return $output.'</div><hr/>';
  
}