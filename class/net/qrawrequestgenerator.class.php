<?php

    include_once("framework/class/net/qrequestgenerator.class.php" );

    /**
     * Generates HTTP GET requests in a transparent way:
     *
     * <i>
     * $g = new RequestGenerator()<br>
     * $g->addParameter( "op", "ViewArticle" );<br>
     * $g->addParameter( "articleId", 1 );<br>
     * $request = $g->getRequest();<br>
     * </i><br>
     * Doing so we can easily change the format of the urls in the future if necessary.
     */
    class qRawRequestGenerator extends qRequestGenerator {

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
            return $this->getBaseUrl($abs) . "index.php";
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
