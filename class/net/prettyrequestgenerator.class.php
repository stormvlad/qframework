<?php

    include_once("framework/class/net/requestgenerator.class.php" );

    /**
     * Generates 'pretty' URLs
     */
    class PrettyRequestGenerator extends RequestGenerator {

        /*
         * Constructor.
         */
        function PrettyRequestGenerator()
        {
            $this->RequestGenerator();
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
