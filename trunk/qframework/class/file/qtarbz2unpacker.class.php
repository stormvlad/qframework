<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qbaseunpacker.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qconfig.class.php");

    // default paths where 'tar' and 'gzip' can be found... this should be true
    // for at least linux-based machines (this is where they are located in
    // my gentoo box)
    define(DEFAULT_TAR_PATH, "/bin/tar" );
    define(DEFAULT_BZIP2_PATH, "/bin/bzip2" );

    /**
     * Unpacks .tar.gz files.
     */
    class qTarBz2Unpacker extends qBaseUnpacker
    {
        function qTarBz2Unpacker()
        {
            $this->qBaseUnpacker();
        }

        function unpack( $file, $destFolder )
        {
            // get the paths where tar and gz are
            $config =& qConfig::getConfig();
            $tarPath = $config->getValue( "path_to_tar" );
            if( $tarPath == "" )
                $tarPath = DEFAULT_TAR_PATH;

            $bzip2Path = $config->getValue( "path_to_bzip2" );
            if( $bzip2Path == "" )
                $bzip2Path = DEFAULT_BZIP2_PATH;

            // and now build the command
            //$file = escapeshellarg($file);
            //$destFolder = escapeshellarg($destFolder);

            //
            // :DANGER:
            // what if the user voluntarily sets the path of gzip and tar
            // to something else? we are doing no checks here to make sure that
            // the user is giving us a valid commnand so... how could we make
            // sure that it'll work?
            //
            $cmd = "$bzip2Path -dc $file | $tarPath xv -C $destFolder";

            $result = exec( $cmd, $output, $retval );

            //
            // :KLUDGE:
            // apparently, we should get something in $retval but there's nothing
            // to the only way I've found to check if the command finished
            // successfully was checking if the $output array is full or empty
            //

            return ($retval==0);
        }
    }
?>
