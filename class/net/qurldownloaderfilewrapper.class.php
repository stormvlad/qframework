<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurldownloader.class.php");

    /**
     * @brief Servicio de descarga de ficheros con envolturas fopen
     *
     * Esta clase necesita de la siguiente configuración en <tt class="filename">php.ini</tt>:
     * - allow_url_fopen = On
     *
     * Mas información:
     * http://es.php.net/manual/es/ref.filesystem.php
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:04
     * @version 1.0
     * @ingroup net
     */
    class qUrlDownloaderFileWrapper extends qUrlDownloader
    {
        /**
        * Constructor.
        */
        function qUrlDownloaderFileWrapper()
        {
            $this->qUrlDownloader();
        }

        /**
        * Add function info here.
        */
        function download(&$urlDownload)
        {
            $url      = $urlDownload->getUrl();
            $fileName = $urlDownload->getOutputFileName();

            if (!($fIn = fopen($url, "r")))
            {
                return false;
            }

            if (!($fOut = fopen($fileName, "w")))
            {
                return false;
            }

            while ($data = fread($fIn, $this->_dataBlockSize))
            {
                fwrite($fOut, $data);
            }

            fclose($fIn);
            fclose($fOut);

            return true;
        }
    }
?>