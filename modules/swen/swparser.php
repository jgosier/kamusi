<?php
#These arrays define all the possible prefixs etc. The ones where I have added 'xxx' indicate repeated prefixes where by
#adding x's I have basically omitted the second and third occurances of the prefixes to reduce the number of solutions given by the 
#parser. These could simply be omitted in the final version, though any changes to the arrays has to be carefully considered since it 
#affects the verb tests.

$PartsOfSpeech = array
  (
   "abbreviation",
   "adj/adv",
   "adjective",
   "adverb",
   "conjunction",
   "infix",
   "interjection",
   "interrogative",
   "name",
   "noun",
   "phrase",
   "prefix",
   "preposition",
   "pronoun",
   "verb",
   "verb subject",
   "verb tense",
   "verb relative",
   "verb object",
   "verb suffix"
  );
$psCode = array_combine($PartsOfSpeech, array_keys($PartsOfSpeech));
$VERB = array_search('verb', $PartsOfSpeech);
$SubjectPrefix = array(
"","ni","u","a","yu","tu","m","wa","u","i",
"li","ya","ki","vi","i","zi","xxxxu","ku","pa","ku",
"m","mw","mw","","","","","","","",
"si","hu","ha","hatu","ham","hamw","hawa","hau","hai","hali",
"haya","haki","havi","hai","hazi","xxxhau","haku","hapa","haku","ham",
"hamw","","","","","","","","","",
"na","wa","a","yua","twa","mwa","wa","wa","ya","la",
"ya","cha","vya","xxxxya","za","xxxwa","kwa","pa","kwa","mwa"
);


$SubjectDescription = array(
"","I","you (singular)","he she or it (for people or animals)","he she or it (for people or animals)",
"we","you (plural)","they (for people or animals)","it (class 3 11 or 14)","they (class 4)",
"it (class 5)","they (class 6) or it (class 6 collective nouns)","it (class 7)","they (class 8)",
"it (class 9)","they (class 10)","xxxxu (not used)","it (class 15)","it (someplace)","it (someplace)",
"it (someplace inside)","it (someplace inside)","you (plural)","","","","","","","",
"I  negative","you (singular)  negative","he she or it (for people or animals)  negative",
"we  negative","you (plural)  negative","you (plural)  negative",
"they (for people or animals)  negative","it (class 3 11 or 14)  negative",
"they (class 4) negative","it (class 5)  negative",
"they (class 6) or it (class 6 collective nouns) negative","it (class 7) negative",
"they (class 8)  negative","it (class 9)  negative","they (class 10)  negative",
"xxxhau (not used)","it (class 15) negative","it (someplace)  negative","it (someplace)  negative",
"it (someplace inside)  negative",
"it (someplace  inside)  negative","","","","","","","","","",
"I","you (singular)","he she or it (for people or animals)","he she or it (for people or animals)",
"we","you (plural)","they (for people or animals)","it (class 3 11 or 14)","they (class 4 or 6)",
"it (class 5)",
"it (class 9 or class 5 collective nouns)","it (class 7)","they (class 8)","xxxxxxit (class 9)",
"they (class 10)","xxxxxxwa (not used)","it (class 15)","it (someplace)","it (someplace)",
"it (someplace inside)"
);

$ObjectInfix = array(
"","ni","ku","m","mw","mu","tu","wa(eni)","wa","wa",
"u","i","li","ya","ya","ki","vi","i","zi","xxxxu",
"ku","pa","ku","m","mw","ji"
);

$ObjectDescription = array(
"","me","you (singular)","him her or it (for people or animals)",
"him her or it (for people or animals)","him her or it (for people or animals)","us",
"you (plural)","them (people or animals)","you (plural)",

"it (class 3 11 or 14)","them (class 4)","it (class 5)","them (class 6)",
"it (class 6 collective nouns)","it (class 7)","them (class 8)","it (class 9)","them (class 10)",
"xxxxxxu (not used)",

"it (class 15)","it (someplace)","it (someplace)","it (someplace inside)","it (someplace inside)",
"herself/ himself/ itself/ myself/ oneself/ ourselves/ themselves/ yourself/ yourselves"
);

$RelativeInfix = array(
"","ye","ye","ye","ye","o","o","o","o","yo",
"lo","yo","cho","vyo","yo","zo","","","","",
"ye","o","yo","lo","cho","po","vyo","zo","ko","mo"
);

$RelativeDescription = array(
"","he or she or the animal who","he or she or the animal who","he or she or the animal who","he or she or the animal who","they who or the thing that (class 3, 11, or 14)","they who or the thing that (class 3, 11, or 14)","they who or the thing that (class 3, 11, or 14)","they who or the thing that (class 3, 11, or 14)","the things that (class 4)",
"the thing that (class 5)","the things that (class 6)","the thing that (class 7)","the things that (class 8)","the thing that (class 9)","the things that (class 10)","","","","",
"he or she or the animal who","they who, or the thing that (class 3, 11, or 14)","the things that (class 4 or 6) or the thing that (class 9)","the thing that (class 5)","the thing that (class 7)","the time or place that (class 16)","the things that (class 8)","the things that (class 10)","the place that (class 17)","the place inside that (class 18)"
);

$TenseInfix = array(
"","na","li","me","ta","ki","nge","nga","japo","ngali",

"ngeli","sipo","ka","mesha","mekwisha","lisha","kiisha","kisha","singe","singali",

"singeli","kito","xxxxsivyo","","","","","","","",

"ku","ja","ta","","","","","","","",

"","","","","","","","","ka","",

"","si","ka","","si","","","","","",

"","","hu","ka"
);

## these descriptions correspond with the Tense arrays above
$TenseDescription = array(
"","do/ does or is doing/ are doing/ am doing (present continuous)","in the past (past simple)",
"recently or beginning in the past (past perfect)","will/ in the future (future)",
"if or when (conditional)","were/ in the unlikely event (suppositional)","although","although",
"were it to have occurred/ had in the past (past suppositional)",

"were it to have occurred/ had in the past (past suppositional)",
"if not or when not (negative conditional)","and then (narrative)","have already","have already",
"did already","once it has occured","once it has occured","negative conditional",
"negative past conditional",

"negative past conditional","if not","xxxxsivyo (not used)","","","","","","","",

"negative past","incomplete past","negative future","","","","","","","",

"present negative","present indefinite","simple command","simple command plural","polite command",
"polite command plural","command with object","command plural with object","command","",

"subjunctive","negative subjunctive","expeditious","general relative","negative relative","","","","","",

"infinitive","negative infinitive","habitual","headline"
);


$lookupURL = "?q=lookup";

$toSwacols =
array  (
   "Id",
   "SwahiliWord",
   "SwahiliSortBy",
   "SwahiliPlural",

   "EnglishWord",
   "EnglishSortBy",
   "EnglishPlural",

   "PartOfSpeech",
   "Class",

   "SwahiliExample",
   "EnglishExample",
   "Derived",
   "DerivedLang",
   "DialectNote",
   "Dialect",
   "Terminology",
   "RelatedWords",
   "Taxonomy",
   "SwahiliDefinition",
   "EnglishDef",
   "EngAlt",
   "SwaAlt",
   "EngPluralAlt",
   "SwaPluralAlt"
  );

//This ensures that even when this file is 'include_once'd the globals are still
//available
$GLOBALS['VERB'] = $VERB;
$GLOBALS['psCode'] = $psCode;
$GLOBALS['lookupURL'] = $lookupURL;
$GLOBALS['TenseInfix'] = $TenseInfix;
$GLOBALS['ObjectInfix'] = $ObjectInfix;
$GLOBALS['dictSolnNum'] = $dictSolnNum;
$GLOBALS['SubjectPrefix'] = $SubjectPrefix;
$GLOBALS['RelativeInfix'] = $RelativeInfix;
$GLOBALS['TenseDescription'] = $TenseDescription;
$GLOBALS['ObjectDescription'] = $ObjectDescription;
$GLOBALS['SubjectDescription'] = $SubjectDescription;


function checkWord($word) {


    $word = strtolower($word);
    if (preg_match("/^\s*$/", $word, $match)){
       return 1;
    }

    $solnsArr = CheckTenses($word);

    $solnsArr2 = array();
    if (preg_match("/^(.+)je$/i", $word, $match2)) {
        #// do another test without the -je postfix
	$subWord = $match2[1];
	$solnsArr2 = CheckTenses($subWord);
        # // although somehow we should remember that we have removed the je in order to explain this to the user
    }

    $rv = array($solnsArr,$solnsArr2);
    return ($rv);

}

#********************************************************************
# this is the main function which takes the word testword and sees if it could be a verb
# if it finds a possible solution it calls function SetSolution(...) with the root verb and details 
# of tense, subject, object etc.
#n is the number of solutions so far
#in the final version, it is assumed that the solutions will be held in an array of some sort to 
#allow them to be ranked, processed etc, but a 2D array is a bit beyond javascript.

function CheckTenses($testword) {
    global $SubjectPrefix, $TenseInfix, $RelativeInfix, $ObjectInfix;
    $start=0;
    $n=0;
    $end=strlen($testword);
    $RelativeNotFound = 1;
    $iRelative;
    $iTense;
    $iTenseSing;
    $iTensePlural;
    $iSubject;
    $iTenseInf;
    $iTenseNegInf;

    $solns = array();

   ## just go through all the tense checks one after another
             
   ########  1) Regular tenses (1 to 22) with positive subject prefix  eg ninaenda ################ 
   for ($iSubject = 1; $iSubject <= 21; $iSubject++)   ## ni, u, a etc
    {
      $start = 0;
      if (substr($testword,$start,strlen($SubjectPrefix[$iSubject])) == $SubjectPrefix[$iSubject])
        {
            
         for ($iTense = 1; $iTense <= 22; $iTense++)   ## na, me, ta, etc 
           {
             $start = strlen($SubjectPrefix[$iSubject]);
             if (substr($testword,$start,strlen($TenseInfix[$iTense])) == $TenseInfix[$iTense])
                {
                  $start = strlen($SubjectPrefix[$iSubject]) + strlen($TenseInfix[$iTense]);
		  if (Monosyllabic(substr($testword,$start, $end - $start))) { $start += 2; }
                  SetSolution ($solns, (++$n),substr($testword,$start,$end - $start),$iTense,$iSubject,0,0);
                  $RelativeNotFound = 1;
                  if (($iTense == 1) || ($iTense == 2) || (($iTense == 4) && (substr($testword,$start,2) == "ka")))
                    {
                      $tempStart = $start;
                      if ($iTense == 4) { $tempStart += 2; }  ##ie ignore the ka in nitakachofanya
                      for ($iRelative = 20; ($iRelative <= 29)&&($RelativeNotFound); $iRelative++)
                        {
                          if (substr($testword,$tempStart,strlen($RelativeInfix[$iRelative])) == $RelativeInfix[$iRelative])
                            {
                              $RelativeNotFound = 0; ##ie break out of loop
                              $start = $tempStart + strlen($RelativeInfix[$iRelative]);
                              SetSolution($solns, (++$n),substr($testword,$start,$end - $start),$iTense,$iSubject,$iRelative,0);
			      last; #break out here
                            }
                        } 
                     }

                  if ($RelativeNotFound) { $iRelative = 0; }
                  for ($iObject = 1; $iObject <= 25; $iObject++)
		  {
			 if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
		       {
			   SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end - $start-strlen($ObjectInfix[$iObject])),$iTense,$iSubject,$iRelative,$iObject);
		       }
		   }                     
	      }
	 }
     }
  }


    # 2) Regular tenses with negative subject prefix  (30-32) eg 'sikuenda' ################
    for ($iSubject = 30; $iSubject <= 50; $iSubject++)   #si, hu, ha etc
      {
       $start = 0;
       if (substr($testword,$start,strlen($SubjectPrefix[$iSubject])) == $SubjectPrefix[$iSubject])
        {
         for ($iTense = 30; $iTense <= 32; $iTense++)   ## ku, ja, ta
           {
             $start = strlen($SubjectPrefix[$iSubject]);
             if (substr($testword,$start,strlen($TenseInfix[$iTense])) == $TenseInfix[$iTense])
                {
                  $start = strlen($SubjectPrefix[$iSubject]) + strlen($TenseInfix[$iTense]);
		  if (Monosyllabic(substr($testword,$start,$end - $start))) { $start += 2; }
                  SetSolution ($solns, (++$n),substr($testword,$start,$end - $start),$iTense,$iSubject,0,0);
                  for ($iObject = 1; $iObject <= 25; $iObject++)
                     {
                       if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                           {
			       SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end - $start-strlen($ObjectInfix[$iObject])),$iTense,$iSubject,$iRelative,$iObject);
			   }
		     }
        
                   }
              }
        }
   }
   
   
   ######/   3) Present -ve  (40)  eg 'siendi' ########################
   $iTense = 40;
   if ((substr($testword,$end-1,1) == "i")||(substr($testword,$end-1,1) == "u"))
      {
        for ($iSubject = 30; $iSubject <= 50; $iSubject++)   ## si, hu, ha etc
         {
           $start = 0;
           if (substr($testword,$start,strlen($SubjectPrefix[$iSubject])) == $SubjectPrefix[$iSubject])
            {
              ##no tense infix
              $start = strlen($SubjectPrefix[$iSubject]);
              ## if word ends in "i" then the root could either end in "i" or "a"
              SetSolution ($solns, (++$n),substr($testword,$start,$end - $start),$iTense,$iSubject,0,0);
              if (substr($testword,$end-1,1) == "i") {
                 SetSolution ($solns, (++$n),substr($testword,$start,$end - 1 - $start) . "a",$iTense,$iSubject,0,0);
	     }
              for ($iObject = 1; $iObject <= 25; $iObject++)
                 {
                   if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                      {
                        SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end - $start-strlen($ObjectInfix[$iObject])),$iTense,$iSubject,0,$iObject);
                        if (substr($testword,$end-1,1) == "i") {
                             SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end - 1 - $start-strlen($ObjectInfix[$iObject])) . "a",$iTense,$iSubject,0,$iObject);
			 }
                      }         
        
                   }
              }
        }
    }
   
   
   ####   4) Present indefinite (a tense)  (41) eg naenda  ####
   $iTense = 41;
   if ((substr($testword,$end-1,1) == "a")||(substr($testword,$end-1,1) == "e")||(substr($testword,$end-1,1) == "i")||(substr($testword,$end-1,1) == "u"))
      {
        for ($iSubject = 60; $iSubject <= 79; $iSubject++)   ## na, wa, twa etc
         {
           $start = 0;
           if (substr($testword,$start,strlen($SubjectPrefix[$iSubject])) == $SubjectPrefix[$iSubject])
            {
              ##no tense infix
              $start = strlen($SubjectPrefix[$iSubject]);
	      if (Monosyllabic(substr($testword,$start, $end - $start))) { $start += 2; }
              SetSolution ($solns, (++$n),substr($testword,$start,$end - $start),$iTense,$iSubject,0,0);
              for ($iObject = 1; $iObject <= 25; $iObject++)
                 {
                   if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                      {
                          SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end - $start-strlen($ObjectInfix[$iObject])),$iTense,$iSubject,0,$iObject);
                      }         
        
                   }
              }
        }
   }

   ####/ 5) Simple command  (42,43) piga pigeni ####################################
    $iTenseSing=42;
    $iTensePlural=43;  
           
         ##first deal with irregulars njoo, njooni, nenda, nendeni, lete, (leteni)
    if ($testword  ==  "njoo") { SetSolution($solns, ++$n,"ja",$iTenseSing,0,0,0); }
    else if ($testword  ==  "njooni") { SetSolution($solns, ++$n,"kuja",$iTensePlural,0,0,0); }
    else if ($testword  ==  "nenda") { SetSolution($solns, ++$n,"enda",$iTenseSing,0,0,0); }
    else if ($testword  ==  "nendeni") { SetSolution($solns, ++$n,"enda",$iTensePlural,0,0,0); }
    else if ($testword  ==  "lete") { SetSolution($solns, ++$n,"leta",$iTenseSing,0,0,0); }
    else if ($testword  ==  "leteni") { SetSolution($solns, ++$n,"leta",$iTensePlural,0,0,0); }
           
          else if (substr($testword,$end-3,3) == "eni")
            {
               ##two possible stems e or a/            
                SetSolution($solns, (++$n),substr($testword,0,$end-3) . "a",$iTensePlural,0,0,0);
                SetSolution($solns, (++$n),substr($testword,0,$end-3) . "e",$iTensePlural,0,0,0);   
            }

         else if (substr($testword,$end-2,2) == "ni")
             {
               ##just remove -ni           
              SetSolution($solns, (++$n),substr($testword,0,$end-2),$iTensePlural,0,0,0);
            }
       
	 ##this gives too many false readings, and would be picked up anyway by Kamusi        
    ##      else if ((substr($testword,$end-1,1) eq "a")||(substr($testword,$end-1,1) eq "e")||(substr($testword,$end-1,1) eq "i")||(substr($testword,$end-1,1) eq "u"))
     ##           SetSolution($solns, (++$n),substr($testword,0,$end),$iTenseSing,0,0,0);

  
   
   ###### 6) command in subjunctive (44,45) uende or mkuleni ####################/
      $iTenseSing=44;
      $iTensePlural=45;  
      for ($i = 0; $i <= 2; $i++) 
        {
         if ($i==0) { $iSubject = 2; } ##u
         else if ($i==1) { $iSubject = 6; } ##m
         else { $iSubject = 21; }##mw
         $start = 0;
         if (substr($testword,$start,strlen($SubjectPrefix[$iSubject])) == $SubjectPrefix[$iSubject]) {
	     $start = strlen($SubjectPrefix[$iSubject]);
             if (substr($testword,$end-3,3) == "eni") {
               ##two possible stems e or a/            
                SetSolution($solns, (++$n),substr($testword,$start,$end - 3 - $start) . "a",$iTensePlural,$iSubject,0,0);
                SetSolution($solns, (++$n),substr($testword,$start,$end - 3 - $start) . "e",$iTensePlural,$iSubject,0,0);   
	    }

             else if (substr($testword,$end-1,1) == "e")
             {        
                SetSolution($solns, (++$n),substr($testword,$start,$end - 1 - $start) . "a",$iTenseSing,$iSubject,0,0);
                SetSolution($solns, (++$n),substr($testword,$start,$end - 1 - $start) . "e",$iTenseSing,$iSubject,0,0);
             }
             
	     else if ((substr($testword,$end-1,1) == "i")||(substr($testword,$end-1,1) == "u")) {
                SetSolution($solns, (++$n),substr($testword,$start,$end - $start),$iTenseSing,$iSubject,0,0);
	    }
 
            for ($iObject = 1; $iObject <= 25; $iObject++)
                {
                   if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                       {
                          if (substr($testword,$end-3,3) == "eni")
                            {
                              ##two possible stems e or a/            
                              SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end - 3 - $start-strlen($ObjectInfix[$iObject])) . "a",$iTensePlural,$iSubject,0,$iObject);
                              SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end - 3 - $start-strlen($ObjectInfix[$iObject])) . "e",$iTensePlural,$iSubject,0,$iObject);   
                             }

                           else if (substr($testword,$end-1,1) == "e")
                             {        
                              SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end - 1 - $start-strlen($ObjectInfix[$iObject])) . "a",$iTenseSing,$iSubject,0,$iObject);
                              SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end - 1 - $start-strlen($ObjectInfix[$iObject])) . "e",$iTenseSing,$iSubject,0,$iObject);
                              }
             
			  else if ((substr($testword,$end-1,1) == "i")||(substr($testword,$end-1,1) == "u")) {
                             SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end - $start-strlen($ObjectInfix[$iObject])),$iTenseSing,$iSubject,0,$iObject);
			 }
                        }
                   }
              }
         } 
                          
                            
     ######    7) command with object nipe / nipeni   (give me) (46,47)  ######################/
     $iTenseSing=46;
     $iTensePlural=47;  
     $iSubject = 0;  ##no subject
     for ($iObject = 1; $iObject <= 25; $iObject++)
       {
         if (($iObject != 2) && (substr($testword,0,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject]))
          {
             if (substr($testword,$end-3,3) == "eni")
                {
                  ##two possible stems e or a/            
                  SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end - 3 - $start-strlen($ObjectInfix[$iObject])) . "a",$iTensePlural,$iSubject,0,$iObject);
                  SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-3-$start-strlen($ObjectInfix[$iObject])) . "e",$iTensePlural,$iSubject,0,$iObject);   
                }

             else if (substr($testword,$end-1,1) == "e")
                {        
                   SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-1-$start-strlen($ObjectInfix[$iObject])) . "a",$iTenseSing,$iSubject,0,$iObject);
                   SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-1-$start-strlen($ObjectInfix[$iObject])) . "e",$iTenseSing,$iSubject,0,$iObject);
                }
             
             else if ((substr($testword,$end-1,1) == "i")||(substr($testword,$end-1,1) == "u")) {
                   SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-$start-strlen($ObjectInfix[$iObject])),$iTenseSing,$iSubject,0,$iObject);
	       }
            
           }
        }
        
     ########/8) command with ka  kapige (48) ####################

     $iTenseSing = 48;
     $iTensePlural = 49;
     
      $start = 0;
      if (substr($testword,$start,strlen($TenseInfix[$iTenseSing])) == $TenseInfix[$iTenseSing])
         {
	     $start = strlen($TenseInfix[$iTenseSing]);
             if (substr($testword,$end-3,3) == "eni") ##not sure if kapigeni exists though
             {
               ##two possible stems e or a/            
                SetSolution($solns, (++$n),substr($testword,$start,$end-3-$start) . "a",$iTensePlural,0,0,0);
                SetSolution($solns, (++$n),substr($testword,$start,$end-3-$start) . "e",$iTensePlural,0,0,0);   
             }

             else if (substr($testword,$end-1,1) == "e")
             {        
                SetSolution($solns, (++$n),substr($testword,$start,$end-1-$start) . "a",$iTenseSing,0,0,0);
                SetSolution($solns, (++$n),substr($testword,$start,$end-1-$start) . "e",$iTenseSing,0,0,0);
             }
             
	     else if ((substr($testword,$end-1,1) == "i")||(substr($testword,$end-1,1) == "u")) {
                SetSolution($solns, ++$n,substr($testword,$start,$end),$iTenseSing,0,0,0);
	    }
 
            for ($iObject = 1; $iObject <= 25; $iObject++)
                {
                   if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                       {
                          if (substr($testword,$end-3,3) == "eni")
                            {
                              ##two possible stems e or a/  not sure if valid with ka tense           
                              SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-3-$start-strlen($ObjectInfix[$iObject])) . "a",$iTensePlural,0,0,$iObject);
                              SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-3-$start-strlen($ObjectInfix[$iObject])) . "e",$iTensePlural,0,0,$iObject);   
                             }

                           else if (substr($testword,$end-1,1) == "e")
                             {        
                              SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-1-$start-strlen($ObjectInfix[$iObject])) . "a",$iTenseSing,0,0,$iObject);
                              SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-1-$start-strlen($ObjectInfix[$iObject])) . "e",$iTenseSing,0,0,$iObject);
                              }
             
			  else if ((substr($testword,$end-1,1) == "i")||(substr($testword,$end-1,1) == "u")) {
                             SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-$start-strlen($ObjectInfix[$iObject])),$iTenseSing,0,0,$iObject);
			 }
                        }
                  }
              
          } 
     
    ####/9) subjunctive +ve and ve & expeditious (50,51,52) (nipende and nisipende and nikapige)##########/
   for ($iSubject = 1; $iSubject <= 22; $iSubject++)   ## ni, u, a etc NOTE 22 not 21
    {
      $start = 0;
      if (substr($testword,$start,strlen($SubjectPrefix[$iSubject])) == $SubjectPrefix[$iSubject])
        {
         for ($iTense = 50; $iTense <= 52; $iTense++)   ## null, si, ka
           {
             $start = strlen($SubjectPrefix[$iSubject]);
             if (substr($testword,$start,strlen($TenseInfix[$iTense])) == $TenseInfix[$iTense])
                {
                  $start = strlen($SubjectPrefix[$iSubject]) + strlen($TenseInfix[$iTense]);
                  if (substr($testword,$end-1,1) == "e")
                      {        
                         SetSolution($solns, (++$n),substr($testword,$start,$end-1-$start) . "a",$iTense,$iSubject,0,0);
                         SetSolution($solns, (++$n),substr($testword,$start,$end-1-$start) . "e",$iTense,$iSubject,0,0);
                      }
             
		  else if ((substr($testword,$end-1,1) == "i")||(substr($testword,$end-1,1) == "u")) {
                        SetSolution($solns, (++$n),substr($testword,$start,$end-$start),$iTense,$iSubject,0,0);
		    }
                  
                  
                  for ($iObject = 1; $iObject <= 25; $iObject++)
                    {
                      if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                         {
                           if (substr($testword,$end-1,1) == "e")
                             {        
                              SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-1-$start-strlen($ObjectInfix[$iObject])) . "a",$iTense,$iSubject,0,$iObject);
                              SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-1-$start-strlen($ObjectInfix[$iObject])) . "e",$iTense,$iSubject,0,$iObject);
                              }
             
                           else if ((substr($testword,$end-1,1) == "i")||(substr($testword,$end-1,1) == "u")) {
                             SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-$start-strlen($ObjectInfix[$iObject])),$iTense,$iSubject,0,$iObject);
			 }
                          }
                     }           
                 }
             }
        }
    }
  

    #////10) general relative (53) nipigaye  ///////////
    $iTense = 53;
    for ($iSubject = 1; $iSubject <= 15; $iSubject++)   #// ni, u, a etc
     {
       if (substr($testword,0,strlen($SubjectPrefix[$iSubject])) == $SubjectPrefix[$iSubject])
          {
            #// if end of word matches the relative for that subjec  eg ye for ni
            if (substr($testword,$end - strlen($RelativeInfix[$iSubject]), strlen($RelativeInfix[$iSubject])) == $RelativeInfix[$iSubject])
              {
		$start = strlen($SubjectPrefix[$iSubject]);
		if (Monosyllabic(substr($testword,$start,strlen($RelativeInfix[$iSubject])))) { $start += 2; }
                SetSolution($solns, (++$n),substr($testword,$start,$end-strlen($RelativeInfix[$iSubject]) - $start),$iTense,$iSubject,$iSubject,0);
                for ($iObject = 1; $iObject <= 25; $iObject++)
                  {
                   if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                      {
                          SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end - strlen($RelativeInfix[$iSubject]) - $start - strlen($ObjectInfix[$iObject])),$iTense,$iSubject,$iSubject,$iObject);
                      }      
                  }
              }
            #/// now test for general relative of time/manner  eg afikapo (still within the first if statement)
            for ($iRelative = 25; $iRelative <= 26; $iRelative++)  #// po and vyo 
               {
                  #// if end of word is "vyo" or "po"
                  if (substr($testword,$end - strlen($RelativeInfix[$iRelative]), strlen($RelativeInfix[$iRelative]))
		      == $RelativeInfix[$iRelative])
                 {
		   $start = strlen($SubjectPrefix[$iSubject]);
		   if (Monosyllabic(substr($testword,$start,$end - strlen($RelativeInfix[$iRelative]) - $start)))
		   { $start = $start +2; }

                   SetSolution($solns,(++$n),substr($testword,$start,$end - strlen($RelativeInfix[$iRelative]) - $start),
			       $iTense,$iSubject,$iRelative,0);
                   for ($iObject = 1; $iObject <= 25; $iObject++)
                     {
                       if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                        {
			    SetSolution($solns,(++$n),substr($testword,$start+strlen($ObjectInfix[$iObject])),$end - strlen($RelativeInfix[$iSubject]) - ($start+strlen($ObjectInfix[$iObject])),$iTense,$iSubject,$iRelative,$iObject);
                         }
                      }      
                  }
              }


          }
   }


    ####11) general negative relative (53) nisiyekula  ##########/
    $iTense = 54;
    for ($iSubject = 1; $iSubject <= 15; $iSubject++)   ## ni, u, a etc
     {
       if (substr($testword,0,strlen($SubjectPrefix[$iSubject])) == $SubjectPrefix[$iSubject])
         {
            $start = strlen($SubjectPrefix[$iSubject]);
            ## if next 2 letters match TenseInfix (si) and following are the relative for that subjec  eg ye for ni
            
            if ((substr($testword,$start,strlen($TenseInfix[$iTense])) == $TenseInfix[$iTense])
              && (substr($testword,$start+strlen($TenseInfix[$iTense]),strlen($RelativeInfix[$iSubject])) == $RelativeInfix[$iSubject]))
	    {
                $start = strlen($SubjectPrefix[$iSubject]) +strlen($TenseInfix[$iTense]) + strlen($RelativeInfix[$iSubject]);
		if (Monosyllabic(substr($testword,$start,$end-$start))) { $start += 2; }
                SetSolution($solns, (++$n),substr($testword,$start,$end - $start),$iTense,$iSubject,$iSubject,0);
                for ($iObject = 1; $iObject <= 25; $iObject++)
                  {
                   if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                      {
                          SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-$start-strlen($ObjectInfix[$iObject])),$iTense,$iSubject,$iSubject,$iObject);
                      }      
                  }
              }

	    #/// now test for negative relative of time/manner  eg asipofika (still within the first if statement)
            $start = strlen($SubjectPrefix[$iSubject]);
            for ($iRelative = 25; $iRelative <= 26; $iRelative++)  #// po and vyo 
               {
                 #// if "vyo" or "po" after the "si" eg nisipofanya
                 if ((substr($testword,$start,strlen($TenseInfix[$iTense])) == $TenseInfix[$iTense]) &&
		     (substr($testword,$start+strlen($TenseInfix[$iTense]), strlen($RelativeInfix[$iRelative]))
		      == $RelativeInfix[$iRelative]))
                 {
		     $start = strlen($SubjectPrefix[$iSubject]) + strlen($TenseInfix[$iTense]) +
			 strlen($RelativeInfix[$iRelative]);
		     if (Monosyllabic(substr($testword,$start,$end - $start))) {   $start = $start +2; }

		     SetSolution($solns,(++$n),substr($testword,$start,$end - $start),$iTense,$iSubject,$iRelative,0);
                   for ($iObject = 1; $iObject <= 25; $iObject++)
                     {
                       if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                        {
                           SetSolution($solns,(++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),
							     $end - ($start+strlen($ObjectInfix[$iObject]))),
				       $iTense,$iSubject,$iRelative,$iObject);            
                         }
                      }      
                  }
              }


          }
     }

     #### 12) infinitive positive and negative (60,61) eg kupiga ######################/
     $iTenseInf = 60;
     $iTenseNegInf = 61;
      
     if (substr($testword,0,4) == "kuto")
       { 
         $start = 4;
	 if (Monosyllabic(substr($testword,$start,$end-$start))) { $start += 2; }
         SetSolution($solns, (++$n),substr($testword,$start,$end-$start),$iTenseNegInf,0,0,0);
         for ($iObject = 1; $iObject <= 25; $iObject++)
            {
              if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                {
                      SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-$start-strlen($ObjectInfix[$iObject])),$iTenseNegInf,0,0,$iObject);
                 }      
             }
         }

     if (substr($testword,0,2) == "ku")
       { 
         $start = 2;
	 if (Monosyllabic(substr($testword,$start,$end-$start))) { $start += 2; }
         SetSolution($solns, (++$n),substr($testword,$start,$end-$start),$iTenseInf,0,0,0);
         for ($iObject = 1; $iObject <= 25; $iObject++)
            {
              if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                {
                      SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-$start-strlen($ObjectInfix[$iObject])),$iTenseInf,0,0,$iObject);
                 }      
             }
         }    
         
     else if (substr($testword,0,2) == "kw")
       { 
         $start = 2;
	 if (Monosyllabic(substr($testword,$start,$end-$start))) { $start += 2; }
         SetSolution($solns, (++$n),substr($testword,$start,$end-$start),$iTenseInf,0,0,0);
         for ($iObject = 1; $iObject <= 25; $iObject++)
            {
              if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                {
                      SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-$start-strlen($ObjectInfix[$iObject])),$iTenseInf,0,0,$iObject);
                 }      
             }
         }
 

                      
     ####/ 14 & 15) habitual 62 hupiga, and headline tense (63)    mtoto kapigwa na .. and ha##############
     if ((substr($testword,$end-1,1) == "a")||(substr($testword,$end-1,1) == "e")||(substr($testword,$end-1,1) == "i")||(substr($testword,$end-1,1) == "u"))
       {
         for ($iTense = 62; $iTense<=63; $iTense++)
           {
              ## if starts with ka or hu
              if (substr($testword,0,strlen($TenseInfix[$iTense])) == $TenseInfix[$iTense])
                 {
                    $start = strlen($TenseInfix[$iTense]);
		    if (Monosyllabic(substr($testword,$start,$end-$start))) { $start += 2; }
                    SetSolution($solns, (++$n),substr($testword,$start,$end-$start),$iTense,0,0,0);   ##no subject in these tenses
                    for ($iObject = 1; $iObject <= 25; $iObject++)
                      {
                       if (substr($testword,$start,strlen($ObjectInfix[$iObject])) == $ObjectInfix[$iObject])
                          {
                              SetSolution($solns, (++$n),substr($testword,$start+strlen($ObjectInfix[$iObject]),$end-$start-strlen($ObjectInfix[$iObject])),$iTense,0,0,$iObject);
                          }
                       }      
                  }
             }
         }


   #////////  16) Kuwa na - irregular present tense  nina, una, ana etc //////// 

    $iTense = 1;       #//present continuous    	
    $start = 0;

    for ($iSubject = 1; $iSubject <= 21; $iSubject++) { #// ni, u, a etc
	if ((substr($testword,$start,strlen($SubjectPrefix[$iSubject]))  == $SubjectPrefix[$iSubject])
	    && (substr($testword,strlen($SubjectPrefix[$iSubject]),$end - strlen($SubjectPrefix[$iSubject])) == "na")) {
            SetSolution($solns,(++$n),"wa na",$iTense,$iSubject,0,0);
	}
    }
    
   $iTense = 40; #//present negative    sina, huna, hakuna   etc   
   for ($iSubject = 30; $iSubject <= 50; $iSubject++) { #            // si, hu, ha etc
       if ((substr($testword,$start,strlen($SubjectPrefix[$iSubject])) == $SubjectPrefix[$iSubject]) 
	   && (substr($testword,strlen($SubjectPrefix[$iSubject]),$end - strlen($SubjectPrefix[$iSubject])) == "na")) {
              SetSolution($solns,(++$n),"wa na",$iTense,$iSubject,0,0);
	  }
   }
   return($solns);
}




#### This function just prints out the possible solution on the screen, but in the final version would store the solution
##/ in an array for looking up in the Kamusi and ranking later

$dictSolnNum = 0;

function SetSolution(&$solnsArr,$num,$root,$iTense,$iSubject,$iRelative,$iObject) {
    global $VERB, $dictSolnNum, $lookupURL;
    if (strlen($root) < 2){
    return 1;
    }

    $stmt = "SELECT SwahiliWord, SwahiliSortBy FROM {dict} WHERE SwahiliWord = '-$root' AND PartOfSpeech = $VERB";
    if(0 == db_result(db_query("SELECT COUNT(id) FROM {dict} WHERE SwahiliWord = '-$root' AND PartOfSpeech = $VERB"))){
        return 1; //Nothing found
    }
    #$stmt = "SELECT SwahiliWord, SwahiliSortBy FROM dict WHERE SwahiliWord = '-$root' ";
    //print $stmt;

#    $stmt = <<EOF;
#SELECT SwahiliWord, SwahiliSortBy FROM dict WHERE SwahiliWord = "$root" OR SwahiliWord = "-$root"
#EOF
    $result = db_query($stmt);
    if (!$result) {
	getSingleSolutionTxt($num,$root,$iTense,$iSubject,$iRelative,$iObject,$root);
    } else {
	$uniqueHeadWords = array();
	while ($vals = mysql_fetch_array($result)) {
	    $sw = $vals['SwahiliWord'];
	    $ssb = $vals['SwahiliSortBy'];
	    $sw_hash = $uniqueHeadWords[$ssb];
	    if (empty($sw_hash)) {
		$uniqueHeadWords[$ssb] = array($sw=>1);
		$sw_hash = $uniqueHeadWords[$ssb];
	    }
	    $sw_hash[$sw] = 1;
	}

	foreach ($uniqueHeadWords as $ssb=>$sw_hash){
	    foreach(array_keys($sw_hash) as $sw) {
		$curNumMatch = 0;
		list($verbLookupHtml,$groupedFlag) = dictEntriesSwa($ssb,$matchx,$curNumMatch,$VERB,1,NULL,1);
		$subTxt = "<hr>Entry is <b>${ssb}</b> - ";
		$subGroupUrlTxt = "<a href=\"${groupingUrl}&Word=${ssb}&EngP=0&submitted=true\">grouped</a>";
		if ($groupedFlag) {
		    $subTxt .= "this headword has been ${subGroupUrlTxt}<hr>\n";
		} else {
		    $subTxt .= "this headword has not been ${subGroupUrlTxt}<hr>\n";
		}
		$lookupStr = "<b>Verb: <a href=\"$lookupURL&Word=${ssb}&EngP=0&pos_restrict=$VERB\" onclick=\"return toggleDisplay('verbdiv${divnum}')\">${sw}</a></b>";
		$curSoln = getSingleSolutionTxt(++$dictSolnNum,$root,$iTense,$iSubject,$iRelative,$iObject,$lookupStr);
#		$curSoln .= "</pre><div id=\"verbdiv${divnum}\" style=\"display:none\">${verbLookupHtml}${subTxt}</div><pre>";
		$curSoln .= "<div id=\"verbdiv${divnum}\" style=\"display:none\">${verbLookupHtml}${subTxt}</div>";
		$divnum++;
		array_push($solnsArr, $curSoln);
	    }
	}
    }
    return;
}

function getSingleSolutionTxt($num,$root,$iTense,$iSubject,$iRelative,$iObject,$lookupStr) {
    global $psCode, $TenseDescription, $TenseInfix, $RelativeInfix, $lookupURL,
        $ObjectInfix, $SubjectPrefix, $SubjectDescription, $ObjectDescription,
        $RelativeDescription;

    $tenseLink = $TenseInfix[$iTense];
    if ($tenseLink == "") {
	$tenseLink = $TenseDescription[$iTense];
    } else {
	$posRestrict = $psCode["verb tense"];
	$tenseLink = "<a href=\"$lookupURL&Word=${tenseLink}&EngP=0&pos_restrict=${posRestrict}\" target=_blank>${tenseLink}</a>";
	$tenseLink .= " - " . $TenseDescription[$iTense];
    }

#    $solution = "Solution " . $num . ":\n";
    $solution = "Solution " . $num . ":<br>\n";
#    $solution .= "   $lookupStr\n";
    $solution .= "&nbsp;&nbsp;&nbsp;$lookupStr<br>\n";
    if ($iSubject>0) {
	$subject = $SubjectDescription[$iSubject];
	$swaSubjectDesc = $subject;
	$swaSubjectPrefix = $SubjectPrefix[$iSubject];
	$posRestrict = $psCode["verb subject"];
	$swaSubjectLink = "<a href=\"$lookupURL&Word=${swaSubjectPrefix}&EngP=0&pos_restrict=${posRestrict}\" target=_blank>${swaSubjectPrefix}</a>" .
	    " - $swaSubjectDesc";
#	$solution .= "   Subject: " . $swaSubjectLink . "\n";
	$solution .= "&nbsp;&nbsp;&nbsp;Subject: " . $swaSubjectLink . "<br>\n";
    }
#    $solution .= "   Tense: $tenseLink\n";
    $solution .= "&nbsp;&nbsp;&nbsp;Tense: $tenseLink<br>\n";
    if ($iRelative>0) {
	$relLink = $RelativeInfix[$iRelative];
	$relLinkDescr = $RelativeDescription[$iRelative];
	$posRestrict = $psCode["verb relative"];
	$relLink = "<a href=\"$lookupURL&Word=${relLink}&EngP=0&pos_restrict=${posRestrict}\" target=_blank>${relLink}</a> - $relLinkDescr";
#	$solution .= "   Relative: " . $relLink . "\n";
	$solution .= "&nbsp;&nbsp;&nbsp;Relative: " . $relLink . "<br>\n";
    }
    if ($iObject>0) {
	$objInfix = $ObjectInfix[$iObject];
	$objLink = $ObjectDescription[$iObject];
	$posRestrict = $psCode["verb object"];
	$objLink = "<a href=\"$lookupURL&Word=${objInfix}&EngP=0&pos_restrict=${posRestrict}\" target=_blank>${objInfix}</a> - $objLink";
#	$solution .= "   Object: " . $objLink;
	$solution .= "&nbsp;&nbsp;&nbsp;Object: " . $objLink . "<p>\n";
    }

    return($solution);
    return;
 
}


## main use of this function is to spot monosyllabic verbs eg kula so as not to allow ku in nimekula  to 
##be interpreted as an object 

function NotMonosyllabic ($word) {

    if (($word == "fa") || ($word == "wa") || ($word == "ja") || ($word == "la") || ($word == "nywa")) {
	return (0);
    }

    return (1);
}

function Monosyllabic($word) {

   if (($word == "kufa") ||
       ($word == "kuwa") ||
       ($word == "kuja") ||
       ($word == "kula")   ||
       ($word == "kunywa") ||
       ($word == "kuwa na")) {
      return (1);
  }

   return (0);
}



function dictEntriesSwa ($Word,$maxMatch,$matchCtRef,$posRestrict,$onlyResultsFlag,$fh, $noGoogleAdSenseFlag){
    return array('result text', 0);
}

?>