<?php
    require_once('DirIterator.php');
    include('kam.php');

	class Slideshow {
		private $host;
		private $path;
		private $url;
		
		function __construct( $host, $path ) {
			$this->host = $host;
			$this->path = $path;
		}
		
		/**
		 * resize image to 400 width and maintain proportionate.
		 * @param - the filename 
		 * @return - the new dimension
		 */
		function resize_image( $filename ) {
			$file_path = $this->path . $filename;
			$imageinfo = @getimagesize( $file_path );

            list( $org_width, $org_height ) = $imageinfo;
								
            //check if original image is greater or equal to 400
            $given_width = $org_width >= 400 ? 400 : $org_width;
			
            if( is_int( $org_width ) ) { 	
	        $aspect_ratio = ( $org_height / $org_width );

	        $new_height = ( $aspect_ratio * $given_width );
            }
        
            $dimension = array( 'width' => $given_width, 'height' => $new_height );
				
            return $dimension;
		}
		
		/**
		 * put the slideshow on the web
		 * @param  String filename 
		 * @param String path to the file
		 * @return String
		 */
		function setup_slideshow( $filename ) {
			$dimension = $this->resize_image( $filename );
			
			$slideshow = '
				<div style="border:3px solid #A50000; background-color:#F6F3EC;
					width:'.$dimension['width'].'px; height:'.
	    			$dimension['height'].'px; color:#422C1A;font-family:Arial;
					margin:0 auto;" >	

			        <div id="my_slideshow" class="slideshow">
			            <a href="'.$this->url.'" target="_blank">
				        <img border="0" src="http://kamusi.org/?q=system/files/dictimages/'.$filename.'" 
				            alt="community submitted photo" width="'.$dimension['width'].'" height="'.$dimension['height'].'" /> </a>
			        </div>
			    </div>';
			return $slideshow;
		}
		
		/**
		 * get a particular file name 
		 * 
		 * @return  file name.
		 */
		
		function get_filename() {
			$path = $this->path;
			foreach(new DirIterator( $path ) as $file ) {
		        if( eregi('.jpg|JPEG|PNG|png',$file) ) {
		            $filenames[] = $this->strip_out_filename( $file );
		        } 

		    }
			
			$size = sizeof( $filenames );
		    $id = ( @rand() % $size );
			return $filenames[$id];
		}

		/**
		 * Strips out the exact filename from the long file path
		 *
		 * @return the filename.
		 */
		function strip_out_filename( $file) {
		    $filename = substr( $file, strrpos( $file, '/' )+1,strlen( $file ) - 
		    strrpos( $file, '/' ) );
		    return $filename;
		}
		
		/**
		 * get node id from the filename
		 *
		 * @return node id
		 */
		function get_node_id( $filename ) {
			
			preg_match('/(?<digit>\d+)_/', $filename, $matches);
			return $matches[1];
			
		}
		
		/**
		 * setup the URL that when the image is click will open up the 
		 * corresponding word.
		 */ 
		function set_url( $id ) {
		    // google analytics parameters
	            
			$ga = "&utm_source=feature&utm_medium=homepage&utm_campaign=slideshow";
			
			$q = "SELECT SwahiliWord, EnglishWord FROM dict WHERE id=$id";

		    $result = mysql_query( $q ) or die("Couldn't fetch url detail ".mysql_error() );

		    while( $row = mysql_fetch_array( $result ) ) {
		    	$view_image .= "&EnglishWord=".$row['EnglishWord']."&SwahiliWord=".$row['SwahiliWord']."EngP=1";
	        }

		    $this->url =  $this->host."/?q=view_image/&id=".$id.$view_image.$ga;
		}
		
	}

?>
