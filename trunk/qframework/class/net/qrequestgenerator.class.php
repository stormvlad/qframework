<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");

    define("DEFAULT_ABSOLUTE_URL", false);

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
    class qRequestGenerator extends qObject
    {
        var $_params;
        var $_baseUrl;
        var $_dirName;
        var $_xhtmlEnabled;

        /**
         * Constructor.
         */
        function qRequestGenerator()
        {
            $this->qObject();

            $server              = &qHttp::getServerVars();

            $this->_params       = Array();
            $this->_dirName      = dirname($server->getValue("SCRIPT_NAME"));
            $this->_baseUrl      = "http://" . $server->getValue("SERVER_NAME") . $this->_dirName;
            $this->_xhtmlEnabled = true;
        }

        function getBaseUrl($abs = DEFAULT_ABSOLUTE_URL)
        {
            throw(new qException("RequestGenerator::getBaseUrl: This function must be implemented by child classes."));
            die();
        }

        function getIndexUrl($abs = DEFAULT_ABSOLUTE_URL)
        {
            throw(new qException("RequestGenerator::getIndexUrl: This function must be implemented by child classes."));
            die();
        }

        /**
         * Returns a string representing the request
         *
         * @return A String object representing the request
         */
        function getRequest()
        {
            throw(new qException("RequestGenerator::getRequest: This function must be implemented by child classes."));
            die();
        }

        function getUrl($res, $abs = DEFAULT_ABSOLUTE_URL)
        {
            return ereg_replace("^/+", "/", $this->getBaseUrl($abs) . $res);
        }

        /**
         * Adds a parameter to the request
         *
         * @param paramName Name of the parameter
         * @param paramValue Value given to the parameter
         */
        function addParameter($paramName, $paramValue)
        {
            $this->_params[$paramName] = $paramValue;
        }

        /**
         * Establece modo de generación de URLs compatible con XHTML
         *
         * @param enable Name of the parameter
         */
        function setXhtml($enable = true)
        {
            $this->_xhtmlEnabled = $enable;
        }

        /**
         * Devuelve si el generador está en modo de URLs compatible con XHTML
         *
         * @returns boolean Devuelve true si está en modo XHTML
         */
        function isXhtml()
        {
            return $this->_xhtmlEnabled;
        }
    }
?>
