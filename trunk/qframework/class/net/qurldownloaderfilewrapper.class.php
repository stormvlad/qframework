<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurldownloader.class.php");

    /**
    * qUrlDownloader Base Class
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