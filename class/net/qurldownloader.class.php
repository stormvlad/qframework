<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("DEFAULT_URL_DOWNLOADER_DATA_BLOCK_SIZE", 8192);

    /**
     * @brief Servicio de descarga remota seg�n una URL
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:04
     * @version 1.0
     * @ingroup net
     */
    class qUrlDownloader extends qObject
    {
        var $_dataBlockSize;

        /**
        * Constructor.
        */
        function qUrlDownloader()
        {
            $this->qObject();
            $this->_dataBlockSize = DEFAULT_URL_DOWNLOADER_DATA_BLOCK_SIZE;
        }

        /**
        * Add function info here.
        */
        function getDataBlockSize()
        {
            return $this->_dataBlockSize;
        }

        /**
        * Add function info here.
        */
        function setDataBlockSize($size)
        {
            $this->_dataBlockSize = $size;
        }

        /**
        * Add function info here.
        */
        function download(&$urlDownload)
        {
            throw(new qException("qUrlDownloader::download: This method must be implemented by child classes."));
            die();
        }
    }
?>