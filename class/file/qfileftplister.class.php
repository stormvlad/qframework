<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfilelister.class.php");

    /**
    * Encapsulation of a class to manage files. It is basically a wrapper
    * to some of the php functions.
    * http://www.php.net/manual/en/ref.filesystem.php
    */
    class qFileFtpLister extends qFileLister
    {
        var $_ftp;

        /**
        *    Add function info here
        */
        function qFileFtpLister(&$ftp)
        {
            $this->qFileLister();
            $this->_ftp = &$ftp;
        }

        /**
        *  Add function info here
        */
        function ls($dir = null)
        {
            if (!$this->_ftp->isConnected())
            {
                throw(new qException("qFileFtpLister::ls: qFtp object passed in constructor must been connected and logged in."));
                return false;
            }

            return $this->_ftp->ls($dir);
        }
    }
?>
