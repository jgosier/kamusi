<?php
// $Id: search-result.tpl.php,v 1.1 2007/10/31 18:06:38 dries Exp $

/**
 * @file search-result.tpl.php
 * Default theme implementation for displaying a single search result.
 *
 * This template renders a single search result and is collected into
 * search-results.tpl.php. This and the parent template are
 * dependent to one another sharing the markup for definition lists.
 *
 * Available variables:
 * - $url: URL of the result.
 * - $title: Title of the result.
 * - $snippet: A small preview of the result. Does not apply to user searches.
 * - $info: String of all the meta information ready for print. Does not apply
 *   to user searches.
 * - $info_split: Contains same data as $info split into a keyed array.
 * - $type: The type of search, e.g., "node" or "user".
 *
 * Default keys within $info_split:
 * - $info_split['type']: Node type.
 * - $info_split['user']: Author of the node linked to users profile. Depends
 *   on permission.
 * - $info_split['date']: Last update of the node. Short formatted.
 * - $info_split['comment']: Number of comments output as "% comments", %
 *   being the count. Depends on comment.module.
 * - $info_split['upload']: Number of attachments output as "% attachments", %
 *   being the count. Depends on upload.module.
 *
 * Since $info_split is keyed, a direct print of the item is possible.
 * This array does not apply to user searches so it is recommended to check
 * for their existance before printing. The default keys of 'type', 'user' and
 * 'date' always exist for node searches. Modules may provide other data.
 *
 *   <?php if (isset($info_split['comment'])) : ?>
 *     <span class="info-comment">
 *       <?php print $info_split['comment']; ?>
 *     </span>
 *   <?php endif; ?>
 *
 * To check for all available data within $info_split, use the code below.
 *
 *   <?php print '<pre>'. check_plain(print_r($info, 1)) .'</pre>'; ?>
 *
 * @see template_preprocess_search_result()
 */

global $base_url;
//If it's a remotely fetched result it will be entirely an array so we need to
//convert the appropriate components to an object so that our theming can work.
//This is not the only way to do it though so if you think of something better
//feel free to change this implementation
if ($base_url != $result['domain']){
    $result['node'] = (object)$result['node'];
    if(is_array($result['node']->taxonomy)){
        foreach($result['node']->taxonomy as $tid=>$term){
            $result['node']->taxonomy[$tid] = (object)$result['node']->taxonomy[$tid];
        }
    }
}
$node = $result['node'];
$node->searchresult = 1;
$showborder = abs($node->grouping)%2; //will give 0 or 1. Alternate groups *hopefully* are consecutive
if($showborder){
    print "<dl title='".strip_tags(_kentry_status($node->approved))."' class='$zebra' style='border-left:10px #A50000 solid; margin-right:25px;' id='cluster$node->grouping'>";
}else{
    print "<dl title='".strip_tags(_kentry_status($node->approved))."' class='$zebra' style='border-right:10px #A50000 solid; margin-right:15px;' id='cluster$node->grouping'>";
}
//Now add the links
print str_replace("<##snippet##>", $snippet, theme('kentry', $node, 1, 0));

$domain = $result['domain']; 

if($node->comment_count){
    $links[] = l("($node->comment_count)",
      "$domain/node/$node->nid", array('fragment'=>'comments',
        'attributes'=>array('title'=>t('Read all comments'))));
}
$links[] = l(".",
        "$domain/node/$node->nid",
             array('attributes'=>array(
                    'title'=>t('Full view, including other languages'),
                    'class'=>'readmore')));

if(user_access('review entry')){
    $links[] = l('.', "$domain/node/$node->nid/edit",
        array('attributes'=>array('title'=>t('Edit'),
            'class'=>'edit')));
    $links[] = l('.', "$domain/node/$node->nid/relations",
        array('attributes'=>array('title'=>t('Create links to other entries'),
            'class'=>'link')));
}
$links[] = l('.', "$domain/comment/reply/$node->nid",
        array('fragment'=>'comment_form',
              'attributes'=>array('class'=>'commentlink',
                'title'=>t('Add new comment'))));


print "<div align='right'>".join(" ", $links)."</div>";

print "</dl>";


/*
$transformation = "";
if($page){
    switch($rawpos){
            case 'noun':
                    $plural = _pluralize($node->word, $node->iso);
                    $transformation = $plural !="" ? 
                    "<strong>".str_replace($node->word, $plural, $node->title)."</strong>".t(' plural') : "" ;
                    break;
            case 'verb':
                    $transformation = _conjugate($node->word, $node->title,
                                                 array('SimplePresent'),
                                                 $node->iso);                
                    break;
    }
}

//plural
$trans = "";
	switch( $result['node']->taxonomy[$result['node']->partofspeech]->name ) { 
		case 'noun':
		$plural = _pluralize( $result['node']->word, $result['node']->iso );
		$trans = t(_name($result['node']->iso).' plural: ').'<strong>'.$plural.'</strong><br />';
		break;
		case 'verb':
		
		$conjugated = _conjugate( $result['node']->word, $result['node']->title,
        	array('SimplePresent'), $result['node']->iso );

		$trans = t(_name($result['node']->iso).' verb: ').'<strong>'.$conjugated.'</strong><br />';
		break;
	}
	
	$pos = $result['node']->taxonomy[$result['node']->partofspeech]->name != ""?
		t('Part of speech: ').'<strong>'.$result['node']->taxonomy[$result['node']->partofspeech]->name.'
			</strong>
		<br />' : '';
	
	$noun_class = $result['node']->taxonomy[$result['node']->partofspeech]->name !="" ?  t('Noun class: ').'<strong>'.$result['node']->taxonomy[$result['node']->partofspeech]->name .'</strong><br />' :'';
	
	$related_word = $result['node']->title != ""? t(_name($result['node']->iso).' word: ').'<strong>'.$result['node']->title.'</strong><br />':'';
	
	$related_word_pl = $result['node']->title != "" ? t(_name($result['node']->iso).' word: ').'<strong>'.$result['node']->title.'</strong><br />':'';
	
	$examples = $result['node']->example['Editor'] != "" ? t(_name($result['node']->iso).' example: ').'<strong>'.$result['node']->example['Editor'].'</strong><br />': '';
	
	$related_word_examples = $result['node']->example['Editor'] != "" ? t(_name($result['node']->iso).' example: ').'<strong>'.$result['node']->example['Editor'].'</strong><br />': '';

foreach( $result['node']->relations as $val=>$v ) {
	
	foreach( $v as $vals=>$vs ) {
		$rel_wrd .= $vs['title'] !="" ? _name( $vs['type'] )." word = ".$vs['title']."<br />" : "";
		$rel_eg .= $vs['example'] != "" ? _name( $vs['type'] )." example =".$vs['example']."<br />" : "";
	}
}	
?>

<tr style="border-bottom:1px gray dotted;">
	
    <td class="search-snippet">
	
	<?php print t(_name($result['node']->iso).' word: ').'<strong>'.$result['node']->title; ?></strong><br />
	<?php print $trans; ?>
	<?php print $pos; ?>
	<?php print $noun_class; ?>
	<?php print $rel_wrd; ?>
	<?php print $related_word_pl; ?>
	<?php print $examples; ?>
	<?php print $rel_eg; ?>
  <ul id="navlist2">
	 <li>	<?php print l(t("Edit "), $result['domain']."/node/".$result['node']->nid."/edit",
		              array('attributes'=>array('rel'=>'lightframe', 'title'=>t('edit this entry')))); ?> </li>
					<li>
					<?php print l(t("Discuss"),
			                      $result['domain']."/comment/reply/".$result['node']->nid,
			                      array('fragment'=>'comment-form',
			                            'attributes'=>array('rel'=>'lightframe'))); ?>
					</li>
    <li> <?php print l(t('More detail...'), $result['link']) ?></li>
	</ul>
    </td>
</tr>
<? */ ?>