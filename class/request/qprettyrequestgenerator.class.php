<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/request/qrequestgenerator.class.php");

    /**
     * @brief Generador mejorado de cadenas con una petición HTTP GET
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 18:21
     * @version 1.0
     * @ingroup request     
     */
    class qPrettyRequestGenerator extends qRequestGenerator
    {

        /*
         * Constructor.
         */
        function qPrettyRequestGenerator()
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

            return preg_replace("/^\\/+/", "\\/", $ret);
        }

        function getIndexUrl($abs = DEFAULT_ABSOLUTE_URL)
        {
            return $this->getBaseUrl();
        }

        /**
         * Returns a string representing the request
         *
         * @return A String object representing the request
         */
        function getRequest()
        {
            $request = "";

            foreach ($this->_params as $name => $value)
            {
                if ($request != "")
                {
                    $request .= "/";
                }

                $request .= urlencode($value);
            }

            return $request;
        }
    }
?>
