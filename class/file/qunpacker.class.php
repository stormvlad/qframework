<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qbaseunpacker.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qtargzunpacker.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qzipunpacker.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qtarbz2unpacker.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qrarunpacker.class.php");

    define(UNPACKER_AUTODETECT, "detect");
    define(UNPACKER_TAR_GZ, "tar.gz");
    define(UNPACKER_TAR_BZ2, "tar.bz2");
    define(UNPACKER_ZIP, "zip");
    define(UNPACKER_RAR, "rar");
    define(UNPACKER_UNSUPPORTED, false);

    /**
     * Class that implements an object capable of unpacking several different
     * kinds of compressed files.
     */
    class qUnpacker extends qObject {

        var $_methods = Array( "tar.gz"  => "TarGzUnpacker",
                           "zip"     => "ZipUnpacker",
                           "tar.bz2" => "TarBz2Unpacker",
               "rar" => "RarUnpacker"
                         );

        var $_method;

        var $_unpackerObj;

        /**
         * Creates an object of this class. The first parameter is the
         * name of the file while the second parameter is the method
         * we'd like to use. The class is able to to auto-detect
         * the file we're using, but we can still force one specific
         * unpacking method.
         */
        function qUnpacker( $method = UNPACKER_AUTODETECT )
        {
            $this->qObject();

            $this->_method = $method;
        }

        function _findUnpacker()
        {
            if( $this->_method == UNPACKER_AUTODETECT ) {
                $extArray = explode( ".", $this->_file );

                $ext = $extArray[count($extArray)-1];
                $ext2 = $extArray[count($extArray)-2].".".$ext;

                if( isset($this->_methods[$ext]))
                    $this->_method = $ext;
                elseif( isset($this->_methods[$ext2]))
                    $this->_method = $ext2;
                else
                    $this->_method = UNPACKER_UNSUPPORTED;
            }

            // create the object
            if( $this->_method != UNPACKER_UNSUPPORTED ) {
                $this->_unpacker = new $this->_methods[$this->_method]();
                $result = true;
            }
            else
                $result = false;

            return $result;
        }

        /**
         * Unpacks the file using the selected method to the destination
         * folder.
         */
        function unpack( $file, $destFolder = "./" )
        {
            // find the most suitable unpacker mechanism
            $this->_file = $file;

            if( !$this->_findUnpacker())
                return UNPACKER_UNSUPPORTED;

            // and then just do it
            return $this->_unpacker->unpack( $file, $destFolder );
        }
    }
?>
