<?php

    include_once("qframework/class/net/qrequestgenerator.class.php" );

    /**
     * Generates 'pretty' URLs
     */
    class qPrettyRequestGenerator extends qRequestGenerator {

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

            return ereg_replace("^/+", "/", $ret);
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
