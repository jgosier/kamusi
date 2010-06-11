<?php
// $Id: node.tpl.php,v 1.5 2007/10/11 09:51:29 goba Exp $
?>
<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">
<?php if(substr($node->type, 0, 6)=='kentry'){
    print "<dl title='".strip_tags(_kentry_status($node->approved))."'>";
    print $content;
    //print str_replace("<##snippet##>", $snippet, theme('kentry', $node, 1, 0));
    $domain = $result['domain']; 
    if(!$page){
        $nlinks[] = l(".",
        "$domain/node/$node->nid",
             array('attributes'=>array(
                    'title'=>t('Full view, including other languages'),
                    'class'=>'readmore')));
    }
    if($node->comment_count){
        $nlinks[] = l("($node->comment_count)",
          "$domain/node/$node->nid", array('fragment'=>'comments',
            'attributes'=>array('title'=>t('Read all comments'))));
    }
    if($page){
        $nlinks[] = l('.', "$domain/comment/reply/$node->nid",
        array('fragment'=>'comment_form',
              'attributes'=>array('class'=>'commentlink',
                'title'=>t('Add new comment'))));
    }
    if(user_access('review entry')){
        $nlinks[] = l('.', "$domain/node/$node->nid/edit",
        array('attributes'=>array('title'=>t('Edit'),
            'class'=>'edit')));
	$nlinks[] = l('.', "$domain/node/$node->nid/relations",
        array('attributes'=>array('title'=>t('Create links to other entries'),
            'class'=>'link')));
    }
    print join(" ", $nlinks);
    print $links;
    print "</dl>";
}else{

?>

<?php print $picture ?>

<?php if ($page == 0): ?>
  <h1><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h1>
<?php endif; ?>

  <?php if ($submitted): ?>
    <span class="submitted"><?php print $submitted; ?></span>
  <?php endif; ?>

  <div class="content clear-block">
  	<?php print $content; ?> 
  </div>

  <div class="clear-block">
  	<div class="meta">
    	<?php if ($taxonomy): ?>
		<div class="terms"><?php print $terms ?></div>
    	<?php endif;?>
    </div>

    	<?php if ($links): ?>
      		<div class="links"><?php print $links; ?></div>
    	<?php endif; ?>
  </div>
<? } ?>
</div>
