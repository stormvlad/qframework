<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qbaseunpacker.class.php");

    define(DEFAULT_UNZIP_PATH, "/usr/bin/unzip");

    class qZipUnpacker extends qBaseUnpacker {

        function qZipUnpacker()
        {
            $this->qBaseUnpacker();
        }

        function unpack( $file, $destFolder )
        {
            // get the paths where tar and gz are
            $config =& qConfig::getConfig();
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
