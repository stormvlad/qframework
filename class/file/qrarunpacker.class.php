<?php

    include_once("qframework/class/object/qobject.class.php" );
    include_once("qframework/class/file/qbaseunpacker.class.php" );

    define( "DEFAULT_UNRAR_PATH", "/usr/bin/unrar" );

    class qRarUnpacker extends qBaseUnpacker {

        function qRarUnpacker()
        {
            $this->qBaseUnpacker();
        }

        function unpack( $file, $destFolder )
        {
            // get the paths where tar and gz are
            $config =& qConfig::getConfig();
            $unrarPath = $config->getValue( "path_to_unrar" );
            if( $unrarPath == "" )
                $unrarPath = DEFAULT_UNRAR_PATH;

            $cmd = "$unrarPath x $file $destFolder";

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