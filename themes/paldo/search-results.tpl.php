<?php
// $Id: search-results.tpl.php,v 1.1 2007/10/31 18:06:38 dries Exp $

/**
 * @file search-results.tpl.php
 * Default theme implementation for displaying search results.
 *
 * This template collects each invocation of theme_search_result(). This and
 * the child template are dependant to one another sharing the markup for
 * definition lists.
 *
 * Note that modules may implement their own search type and theme function
 * completely bypassing this template.
 *
 * Available variables:
 * - $search_results: All results as it is rendered through
 *   search-result.tpl.php
 * - $type: The type of search, e.g., "node" or "user".
 *
 *
 * @see template_preprocess_search_results()
 */
$dbs = variable_get('kclient_all_dbs', array());
//get search term
$keys = arg(2);
$link = search_query_extract($keys, 'link');
list($from, $to) = split('\|', $link); 
$query = split("link:$from\|$to", $keys);
$searchterm = trim($query[0]);
if(trim($link)){
    drupal_set_title(t('!query - !link', array(
                        '!link'=> $dbs[$link],
                        '!query'=>$searchterm)
                  ).' - '.drupal_get_title());
}else{
    drupal_set_title(t('!query', array(
                        '!query'=>$searchterm)
                  ).' - '.drupal_get_title());
}
?>
<div class="search-results <?php print $type; ?>-results" width="98%" cellpadding="5">
   <?php print $search_results; ?> 
</div>
<?php print $pager; ?>
