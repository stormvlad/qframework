<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfilelister.class.php");

    /**
     * @brief Servicio para listar los archivos de un directorio remoto en FTP
     * 
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:58
     * @version 1.0
     * @ingroup file
     * @see qFileList
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
