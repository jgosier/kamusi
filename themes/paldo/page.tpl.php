<?php //$id: $ ?>
<?php global $base_url; $directory = $base_url.'/'.$directory;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>">
<head>
<link rel="search" type="application/opensearchdescription+xml"
            href="/kamusi1.xml"
            locale="en-US" 
            title="English to Swahili" />
<link rel="search" type="application/opensearchdescription+xml"
            href="/kamusi2.xml"
            locale="en-US" 
            title="Swahili to English" />

  <?php print $head; ?>
  <?php print $styles;?>
  <?php print $scripts;?>
<!--[if IE]>
	<style type="text/css" media="screen">	@import "<?php print $directory?>/ie.css"; </style>
 <![endif]-->
<title><?php print str_replace("| PALDO", "|", $head_title); ?></title> 
<head>
<body>

	<!-- center column -->
	<div id="c-block">

	<div id="c-col">
	<?php print $header ?>
        <?php if ($site_name) { ?><h1 class='site-name'><a href="<?php print $front_page ?>" title="<?php print t('Home') ?>"><?php print $site_name ?></a></h1><img src="<?php print "$directory/images/div_line_title.gif"; ?>" width="97%" /><?php } ?>
	
	<!-- <?php print $breadcrumb ?> -->
        
       	<h1 class="title"><?php print $title ?></h1>
        <div class="tabs"><?php print $tabs ?></div>
        <?php print $help ?>
        <?php print $messages ?>
	<?php print $content_top ?>
        <?php print $content; ?>
        <?php print $feed_icons; ?>
        
	
	</div>
	<!-- end of center column --></div>
	<!-- end c-block -->
	
	<!-- left column -->
	<div id="lh-col">
		<div id="header">
	    <?php if ($logo) { ?><a href="<?php print $front_page ?>" title="<?php print t('Home') ?>"><img src="<?php print $logo ?>" alt="<?php print $logo ?>" /> </a><br /><a href="/?q=en"><?php print t('Home | Nyumbani')?></a><?php } ?>
                    <?php if ($site_slogan) { ?><div class='site-slogan'><?php print $site_slogan ?></div><?php } ?>
                </div>
		<img src="<?php print $directory ?>/images/div_line4.jpg" width="194" border="0" style="vertical-align: bottom;" /> 
		<?php if ($lefttop) { ?>
		<div id="left_top">
			<?php print $lefttop ?>
		</div>
		<img src="<?php print $directory ?>/images/div_line4.jpg" width="194"  border="0" align="top"/>
		<?php } ?>
		<div id="left_middle">
			<?php if (isset($primary_links)) { ?>
                <?php print theme('links', $primary_links, array('class' =>'links', 'id' => 'prinavlist')) ?>	
		</div>
		<img src="<?php print $directory ?>/images/div_line4.jpg" width="194" border="0" align="top"/>
		<?php } ?>
                <div id="left"><?php print $left ?></div>

		<?php if( $leftbottom ) { ?>
		<div id="left_bottom">
			<?php print $leftbottom ?>
		</div>
		<img src="<?php print $directory ?>/images/div_line4.jpg" width="194" border="0" align="top"/>
		<?php } ?>

		<div id="left_footer">
			<?php print $footer_message.$footer ?>
		</div>
		
	</div>
	<!-- end of left column -->
	<!-- right column -->
	<div id="right_wrapper">
	<?php if( $right ) { ?>
	<div id="rh-col">
		<div id="right_content">
                    <table cellspacing="0" cellpadding="0" background="<?php print $directory ?>/images/kikoi1.jpg" width="100%">	
                        <tbody>
                            <tr>
                                <td width="100%">
                                <?php print $right ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
		</div>
		<?php } ?>
	</div>
	</div>
	<!-- end of right column -->
        <?php print $closure ?>
	</body>
	</html>
