<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurldownloader.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qipformatrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qvalidator.class.php");

    /**
     * @brief Servicio de descarga de ficheros con sockets
     *
     * Nota: No se necesitan bibliotecas externas para construir esta clase, son parte del núcleo de PHP.
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:04
     * @version 1.0
     * @ingroup net
     */
    class qUrlDownloaderBySocket extends qUrlDownloader
    {
        /**
        * Constructor.
        */
        function qUrlDownloaderBySocket()
        {
            $this->qUrlDownloader();
        }

        /**
        * Add function info here.
        */
        function download(&$urlDownload)
        {
            $ipValidator = new qValidator();
            $ipValidator->addRule(new qIpFormatRule());

            $url      = &$urlDownload;
            $port     = $url->getPort();
            $fileName = $urlDownload->getOutputFileName();

            if (empty($port))
            {
                $port = 80;
            }

            if ($ipValidator->validate($url->getHost()))
            {
                $ip = $url->getHost();
            }
            else
            {
                $ip = gethostbyname($url->getHost());
            }

            if (!($fp = fsockopen($ip, $port)))
            {
                return false;
            }

            if (!($fOut = fopen($fileName, "w")))
            {
                return false;
            }

            $request = "GET " . $url->getPath() . " HTTP/1.1\r\nAccept: */*\r\nHost: " . $url->getHost() . "\r\nConnection: Keep-Alive\r\n\r\n";

            fputs($fp, $request);
            $result = "";

            while ($data = fread($fp, $this->_dataBlockSize))
            {
                $result .= $data;
            }

            $result = substr($result, strpos($result, "\r\n\r\n") + 4);
            fwrite($fOut, $result);
            fclose($fp);
            fclose($fOut);

            return true;
        }
    }
?>