  <div class="paldobox">    
    <?php //Check if these are search results and change the no results message
          //to an option to use our translation service
    	/*if(module_exists('kentry')){
	    	if($title == t('Your search yielded no results')){
	    		$kwsearch.= "<h3>Try an exact match search in the following databases</h3>";
	    		$kwsearch.= "Clicking on each link will open a new window<ul>";
	    		foreach(_dictionary_languages(TRUE) as $iso=>$dict){
	    			$kwsearch.= "<li>".l($dict, "all/$iso/".(($p = strpos(arg(2), 'type:'))?trim(substr(arg(2), 0, $p)):arg(2)), array('attributes'=>array('target'=>$iso)))."</li>";
	    		}
	    		$kwsearch.= "</ul>";
	    	   
	    	}
    	}*/
        if(strstr($title, "results")){
          $title = t('!title for %query', array('!title'=>$title,
                                                '%query'=>arg(2)));
        }
    ?>
    <?php if ($title) { ?><h2 class="title"><?php print $title; ?></h2><?php } ?>
    <div class="content"><?php print $content.$kwsearch; ?></div>
 </div>

