<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qipformatrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurl.class.php");

    /**
    * qUrlCheckRule class
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:22
     * @version 1.0
     * @ingroup validation rule
     */
    class qUrlCheckRule extends qRule
    {
        /**
         * The constructor does nothing.
         */
        function qUrlCheckRule()
        {
            $this->qRule();
        }

        /**
        * Validates the data. Does nothing here and it must be reimplemented by
        * every child class.
        */
        function validate($value)
        {
            $ipValidator = new qValidator();
            $ipValidator->addRule(new qIpFormatRule());

            $url  = new qUrl($value);
            $port = $url->getPort();

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

            $request = "GET " . $url->getPath() . " HTTP/1.1\r\nAccept: */*\r\nHost: " . $url->getHost() . "\r\nConnection: Keep-Alive\r\n\r\n";

            fputs($fp, $request);
            $response = trim(fgets($fp));
            fclose($fp);

            return ereg(" 200 OK$", $response);
        }
    }
?>