<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/request/qrequestgenerator.class.php");

    define("DEFAULT_INDEX_FILE", "index.php");

    /**
     * @brief Generador simple de cadenas con una petición HTTP GET
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 18:21
     * @version 1.0
     * @ingroup request     
     */
    class qRawRequestGenerator extends qRequestGenerator
    {
        /**
         * Constructor.
         */
        function qRawRequestGenerator()
        {
            $this->qRequestGenerator();
        }

        function getBaseUrl($abs = DEFAULT_ABSOLUTE_URL)
        {
            if ($abs)
            {
                $ret = $this->_baseUrl . "/";
            }
            else
            {
                $ret = $this->_dirName . "/";
            }

            return ereg_replace("^/+", "/", $ret);
        }

        function getIndexUrl($abs = DEFAULT_ABSOLUTE_URL)
        {
            return $this->getBaseUrl($abs) . DEFAULT_INDEX_FILE;
        }

        /**
         * Returns a string representing the request
         *
         * @return A String object representing the request
         */
        function getRequest()
        {
            $request = "";
            $amp = "&amp;";

            foreach ($this->_params as $name => $value)
            {
                if ($request == "")
                {
                    $request .= "?";
                }
                else
                {
                    $request .= $amp;
                }

                $request .= urlencode($name) . "=" . urlencode($value);
            }

            return $request;
        }
    }
?>
