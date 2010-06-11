<?php
require_once('verbs.inc');
function paldo_keditmultiple_form(&$form){
	$output = '<div style="width:530px;overflow:scroll; padding:10px;">';
    $header = array('', t('Entry'), t(' '), t('Definition'), t('Notes'),t('Examples'), t('Priority'), t('Group'));
    $rows = array();
 /*   $groups = array_keys(array_fill(0, count(array_filter(element_children($form), 'is_numeric'))-1, 0));
    foreach ($groups as $group){
        drupal_add_tabledrag('kgrouping', 'match', 'sibling', 'group-weight-'.$group, NULL);
        drupal_add_tabledrag('kgrouping', 'order', 'sibling', 'priority-in-group', 'group-weight-'.$group);
    }*/
    drupal_add_tabledrag('keditmultiple', 'order', 'sibling', 'priority-in-group');
    foreach (element_children($form) as $i=>$key) {
        if (isset($form[$key]['pos'])){
            $row = $grow = array('');
            $row[] = drupal_render($form[$key]['title']);
            $row[] = drupal_render($form[$key]['pos']);
            $row[] = drupal_render($form[$key]['defn']);
            $row[] = drupal_render( $form[$key]['notes']);
            $row[] = drupal_render( $form[$key]['example']);
             // Add class to priority weight fields for drag and drop.
            if(isset($form[$key]['priority'])){ 
                $form[$key]['priority']['#attributes']['class'] = 'priority-in-group';
                $row[] = drupal_render($form[$key]['priority']);
            }
            
            if(isset($form[$key]['grouping'])){ 
                $form[$key]['grouping']['#attributes']['class'] = 'group-weight-'.$form[$key]['grouping']['#value'];
                $grow[] = $row[] = array('data'=>drupal_render($form[$key]['grouping']), 'colspan'=>4);
            }
            
            $rows[] = array('data' => $row, 'class' => 'draggable');
            if($i < count($groups)){
              //  $rows[] = array('data' => $grow, 'class' => 'tabledrag-root draggable');
            }
        }
  }
  $output .= theme('table', $header, $rows, array('id' => 'keditmultiple', 'width'=>'100%'));
  $output .= drupal_render($form);
  $output .='</div>';
  return $output;
}
	
// this codes was lifted from 
//http://google.com/codesearch?hl=en&q=grammar+lang:php+show:9-0GUgl4kQI:lkPepZHybyE:E4V7PDYmKqA&sa=N&cd=3&ct=rc&cs_p=http://freshmeat.net/redir/achievo/12449/url_zip/achievo-stable-1.2.1.zip&cs_f=achievo-1.2.1/atk/meta/grammar/class.atkmetagrammar.inc
/**
  * Returns the list of plural rules.
  *
  * @return list of plural rules
  */
//TODO make the rules come from a settings
function _get_plural_rules($lang='en') {
    switch($lang){
        case 'en':
            return array(
                    '/(fish)$/i' => '\1\2',                  // fish
                    '/(x|ch|ss|sh)$/i' => '\1es',            // search, switch, fix, box, process, address
                    '/(series)$/i' => '\1\2',
                    '/([^aeiouy]|qu)ies$/i' => '\1y',
                    '/([^aeiouy]|qu)y$/i' => '\1ies',        // query, ability, agency
                    '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves', // half, safe, wife
                    '/sis$/i' => 'ses',                      // basis, diagnosis
                    '/([ti])um$/i' => '\1a',                 // datum, medium
                    '/(p)erson$/i' => '\1\2eople',           // person, salesperson
                    '/(m)an$/i' => '\1\2en',                 // man, woman, spokesman
                    '/(c)hild$/i' => '\1\2hildren',          // child
                    '/s$/i' => 's',                          // no change (compatibility)
                    '/$/' => 's'
            );
            break;
        default:
            return array();
            break;
    }
}


function _transform($word, $rules) {
    foreach ($rules as $rule => $replacement)
            if ( preg_match( $rule, $word ) )
                    return preg_replace( $rule, $replacement, $word );
    return $word;
}

/**
 * NOUNS
 * Pluralize the given noun using the plural rules.
 * @param $word noun to be pluralized
 *
 * @return pluralized word
 */
function _pluralize( $word, $lang='en') {
    if( strlen( $word ) == 1 || strlen( $word ) == 2 ) { 
            return "";
    } else { 
            $rules = _get_plural_rules($lang);
    return _transform( $word, $rules );
    }
}

function paldo_kentry($node, $teaser = FALSE, $page = FALSE){
    global $user, $base_url; 
    $pcat = is_array($node->taxonomy)? l(($rawpos=$node->taxonomy[$node->partofspeech]->name), 
    "taxonomy/term/".$node->partofspeech) : "";
    $pcat = empty($rawpos)?'':"( $rawpos )";
    $domain = "http://".variable_get('kclient_domain', ''); //TODO: don't assume 'http://'
    $dbnames = variable_get('kclient_db_names', array());
    $dbname = l($dbnames[$node->iso], "$domain/all/$node->iso");
    $process = '';
    $nodisplay = (variable_get('kentry_show', array()));
    drupal_add_css(drupal_get_path('module', 'kdictionary').'/kdictionary.css');
    
    $output = $word_classes = $variants = $semantic_relations = $partofspeech = '';
    
    if(!empty($node->example)){	
        $examples = "<dd class='examples'>";
        if(is_array($node->example)){
            foreach($node->example as $source=>$example){	
                if(trim($example)!=""){			
                    $examples .= "<div class='example'>".'<label>'.
                        t('!source example',
                          array('!source'=>$dbnames[$node->iso])).
                        '</label> '.
                        check_markup(preg_replace("/~/",
                            theme('placeholder', $node->word), $example),
                                     $node->filter, 0)."</div>";
                }
            }
        }
        $examples .="</dd>";				
    }
    
    
    if(module_exists('taxonomy') && is_array($node->taxonomy) && empty($node->in_preview) ){
        foreach($node->taxonomy as $tid=>$term_obj) {
            $word_classes.= '<div class="pos">'.t($term_obj->name).                
                l('&#151;', "$domain/taxonomy/term/$tid",
                   array('html'=>1,
                         'attributes'=>array(
                    'title'=>preg_replace("/~/", $node->word,$term_obj->description)))).                
                '&nbsp;&nbsp;&nbsp;</div>'; 
        }
        $word_classes.='<br style="clear:both"/>';
    }
    $reviewstatus = $nodisplay['rstatus']?'':_kentry_status($node->approved);
    
    //$output.= "<dl class='$zebra'>";
    $output.= "<dt><strong title='$dbname'>$node->title</strong> $pcat </dt>";
    //TODO: if($node->iso != 'sw'){ do not use theme_kentry_synonyms. Simply use the 'English Word' field values}
    $output.= ($page OR !$node->searchresult)?((!$nodisplay['links'])?
        theme('kentry_synonyms', $node, 0):''):"<##snippet##>";
    //$output.= "<dd>".$word_classes."</dd>";
    $output.= "<dd>".preg_replace("/<span.*?View.*?span>/", "", $node->children_html)."</dd>";
    //$output.= "<dd>".$node->children_html."</dd>";
    $output.= "<dd>".$node->body."</dd>"; //When node->defn is evaluated it becomes node->body
    $output.= (strip_tags($examples)&&!$nodisplay['example'])?$examples:'';
    $output.= "<dd>";
    //$output.= join(" ", $attribution);
    $output.= "</dd>";
    $output.= "<dd>".$word_classes."</dd>";
    //$output.="</dl>";
    //$output.= $node->teaser?"<span class='teaser'>".check_markup($node->teaser, $node->format, 0)."</span>":'';
    if($page){        
        if(variable_get('kentry_showcitation', FALSE)){
            $output.= "<div class='citation'><span class='reviewstatus'>"._kentry_status($node->approved)."</span><b>".t('Cite')."</b><br/>".$node->title.'. '.$pcat.'. '._kentry_cite($node)." ".t("from !url", array('!url'=>l(url(drupal_get_path_alias("node/$node->nid"), array('absolute'=>TRUE)), "node/$node->nid")))."</div>";
        }
        
        if(variable_get('show_linksgroupingtool', FALSE)){
            foreach($node->relations as $relid=>$rels){
                if(user_access('review entry') && count($rels)){//TODO: make 'review entry' perms database-specific e.g. 'review akan entries only'
                    $output.= "<div class='title'>".t('modify display order of %dbase %relid entries linked with %group', array('%dbase'=>'', '%relid'=>variable_get('ksynset_'.$relid.'_name',''), '%group'=>$node->title))."</div>";
                    $output.= "<div>".t('If you have JavaScript enabled you can use the <span style="cursor: crosshair;">cross-hair handle at the beginning of each row</span> to move entries up or down to reorder them')."</div>"; 
                    $output.= l(t('Return to %group', array('%group'=>$start)), "all/$iso/$start", array('html'=>TRUE));
                    $output.= drupal_get_form('kgrouping_form', $node->vid, '', 'links');
                    $output .= theme('pager', NULL, $page_increment);
                }
            }
        }
    }
    return $output;
}


function paldo_kentry_synonyms($node, $teaser=TRUE, $type=''){
    $synonyms = '';
    $header = $rows = array();
    if($teaser){
            $node = kentry_load($node); //load synonyms as they're not loaded on non-node pages
    }
    if(isset($node->relations)){			
        $synonyms = "<dd class='synonyms'>";
        if(is_array($node->relations) && count($node->relations)){
            $header[] = t('Search by');
            foreach($node->relations as $relid=>$rels){
                $header[$relid] = t(' %lang', array('%lang'=>
                        variable_get('ksynset_'.$relid.'_name','')));
                if(is_array($rels)){	
                    $i=1;				
                    foreach($rels as $vid=>$rel) {
                        $class = ($rel['svid']==$node->vid)?'manual':'robot';
                        $title = ($rel['svid']==$node->vid)?
                            t('Decided by a human'):t('Pending final review');
                        $reltypes[] = $rel['type'];
                        $clean_w = strip_tags($rel['root']);
                        $rs[$rel['type']][$relid][$rel['title']] =
                          l($rel['title'],
                "search/kclient/$rel[title] link:$rel[type]|$node->iso",
                            array('attributes'=>array('class'=>$class,
                            'title'=>$title),
                            'html'=> TRUE));
                    }                               
                }
            }
        }
        $rowtypes = is_array($reltypes)?(array_unique($reltypes)):array();
        //Now restrict to $type
        if($type){
            $rowtypes = array_intersect($rowtypes, array($type));
        }
        $coltypes = is_array($node->relations)?array_keys($node->relations):array();
        
        $dbnames = variable_get('kclient_db_names', array());
        foreach($rowtypes as $rowtype){
            $rows[$rowtype][1] = $dbnames[$rowtype];
            foreach($coltypes as $coltype){
                $rows[$rowtype][$coltype] = is_array($rs[$rowtype][$coltype])?join(", ", $rs[$rowtype][$coltype]):"";
            }
        }
        if(count($rows)){
            foreach($coltypes as $a){//Ignore the first header column
                foreach($rows as $rk=>$value){
                    if($value[$a]){
                        $synonyms.= "<div id='$rk'><label>$value[1]
                            $header[$a]</label> $value[$a]</div>";
                    }
                }
            }
            //$synonyms.= theme('table', $header, $rows, array('width'=>'80%'));
        }
        $synonyms .="</dd>";				
    }
    return $synonyms;
}

/**
 * Given a node, display the teasers of all included nodes
 */
function paldo_display_node_includes($node, $teaser=FALSE){
    global $base_url;
    $canviewfiles = true;
    if(!user_access('view uploaded files')){
        watchdog('kgallery', 'You need to enable "view uploaded files"
                 permission', array(), WATCHDOG_ERROR);
        $canviewfiles = false;
    }
    $output = "";
    $path = drupal_get_path('module', 'kgallery');
    $default_thumb = theme('xspf_playlist_thumb_get', $node);
    $attributes = @getimagesize($default_thumb);
    $height = variable_get("kgallery_thumb_height", 200);
    $width = variable_get("kgallery_thumb_width", 200);
    $align = variable_get("kgallery_thumb_align", 'left');
    if(is_array($attributes)){
        $ratio = $attributes[1]/$attributes[0];
        if ($ratio < $height / $width) {
            $width = (int)min($width, $attributes[0]);
            $height = (int)round($width * $ratio);
        } else {
            $height = (int)min($height, $attributes[1]);
            $width = (int)round($height / $ratio);
        }
    }

    $thumb = theme('image', $default_thumb, $node->title, $node->title,
                   array('align'=>$align, 'height'=>$height, 'width'=>$width,
                         'class'=>'kthumb'), FALSE);
    $output.= "<div class='kgallery thumb$node->nid'>";
    if($teaser){
        
    }else{
        if((count(theme('xspf_playlist_create_item', $node)) > 0) && $canviewfiles){
            $mplayer = variable_get('mplayer', array('height'=>300, 'width'=>350));
            $fileJS = '<script type="text/javascript">';
            $fileJS.= 'var kw'.$node->nid.' = new SWFObject("'.$base_url.'/'.$path.'/mediaplayer.swf","playlist","'.$mplayer['width'].'","'.$mplayer['height'].'","7");';
            $fileJS.= 'kw'.$node->nid.'.addVariable("config", "'.url("kgallery/config/$node->nid").'"); ';
            #$fileJS.= 'kw'.$node->nid.'.addParam("allowscriptaccess", "always"); ';
            #$fileJS.= 'kw'.$node->nid.'.addParam("wmode", "transparent"); ';
            #$fileJS.= 'kw'.$node->nid.'.addVariable("enablejs", "true"); ';
            #$fileJS.= 'kw'.$node->nid.'.addVariable("midroll", "1471"); '; 
            $fileJS.= 'kw'.$node->nid.'.write("player'.$node->nid.'"); </script>';
            #$fileJS.= 'kw'.$node->nid.'.write("mediaspace"); </script>';
            $thumb = '<p id="player'.$node->nid.'">'.$thumb.'<br/>'.t('Enable !javascript and !flash in your browser  to browse the full media gallery', array('!javascript'=>'Javascript', '!flash'=>l(t('Adobe Flash player'), "http://www.macromedia.com/go/getflashplayer"))).'</p>'.$fileJS;
            #$thumb = '<p id="mediaspace">'.$default_thumb.'<br/>'.t('Enable !javascript and !flash in your browser  to browse the full media gallery', array('!javascript'=>'Javascript', '!flash'=>l(t('Adobe Flash player'), "http://www.macromedia.com/go/getflashplayer"))).'</p>'.$fileJS;   
        }
    }
    
    $output.= l($thumb, "node/$node->nid", array('html'=>TRUE,
                     'attributes'=>array()));   
    $output.= "</div><br style='clear:both'/>";
    
    return $output;
}

