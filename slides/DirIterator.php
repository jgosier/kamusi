<?php
    class DirIterator extends RecursiveIteratorIterator
    {
        public function __construct( $path ) {
            $path = realpath( $path );

            if( !file_exists( $path ) ) {
                throw new Exception( "Path $path could not be found." );
            } elseif( !is_dir( $path ) ) {
                throw new Exception( "Path $path is not a directory." );
            }

            parent::__construct( new RecursiveDirectoryIterator( $path ) );
        }
        
    }
?>
