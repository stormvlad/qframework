<?php

    include_once("framework/class/object/object.class.php" );
    include_once("framework/class/file/baseunpacker.class.php" );

    define( "DEFAULT_UNZIP_PATH", "/usr/bin/unzip" );

    class ZipUnpacker extends BaseUnpacker {

        function ZipUnpacker()
        {
            $this->BaseUnpacker();
        }

        function unpack( $file, $destFolder )
        {
            // get the paths where tar and gz are
            $config =& Config::getConfig();
            $unzipPath = $config->getValue( "path_to_unzip" );
            if( $unzipPath == "" )
                $unzipPath = DEFAULT_UNZIP_PATH;

            $cmd = "$unzipPath -o $file -d $destFolder";

            $result = exec( $cmd, $output, $retval );

            //
            // :KLUDGE:
            // apparently, we should get something in $retval but there's nothing
            // to the only way I've found to check if the command finished
            // successfully was checking if the $output array is full or empty
            //

            return ( $retval == 0 );
        }
     }
?>
