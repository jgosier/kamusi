<?php 

	require_once("slideshow_beta2.php");
	$slideshow = new Slideshow( "http://kamusi.org",
		"/mnt/wulomei/fienipa/files/paldo/dictimages/" );
		
	$filename = $slideshow->get_filename();	
	$id = $slideshow->get_node_id( $filename );
	$slideshow->set_url($id);
	echo $slideshow->setup_slideshow( $filename );
	
?>
