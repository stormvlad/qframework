 <?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurl.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurldownloaderfilewrapper.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurldownloaderbysocket.class.php");

    /**
     * @brief Descarga de ficheros remotos según una URL
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:04
     * @version 1.0
     * @ingroup net
     */
    class qUrlDownload extends qUrl
    {
        var $_outputFileName;
        var $_urlDownloader;

        /**
        * Constructor.
        */
        function qUrlDownload($url, $outputFileName = false, $urlDownloader = null)
        {
            $this->qUrl($url);

            if (empty($outputFileName))
            {
                $outputFileName = "./tmp/" . $this->getBaseName();
            }

            if (empty($urlDownloader))
            {
                if (ini_get("allow_url_fopen"))
                {
                    $urlDownloader = new qUrlDownloaderFileWrapper();
                }
                else
                {
                    $urlDownloader = new qUrlDownloaderBySocket();
                }
            }

            $this->_outputFileName = $outputFileName;
            $this->_urlDownloader  = $urlDownloader;
        }

        /**
        * Add function info here.
        */
        function getOutputFileName()
        {
            return $this->_outputFileName;
        }

        /**
        * Add function info here.
        */
        function setOutputFileName($name)
        {
            $this->_outputFileName = $name;
        }

        /**
        * Add function info here.
        */
        function &getUrlDownloader()
        {
            return $this->_urlDownloader;
        }

        /**
        * Add function info here.
        */
        function setUrlDownloader(&$downloader)
        {
            $this->_urlDownloader = &$downloader;
        }

        /**
        * Add function info here.
        */
        function download()
        {
            return $this->_urlDownloader->download($this);
        }
    }
?>