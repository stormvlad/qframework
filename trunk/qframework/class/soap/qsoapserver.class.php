<?php

    // PEAR include 
    include_once("SOAP/Server.php");
    
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
    * Add class info here
    */
    class qSoapServer extends qObject
    {
        var $_soapServer;

        /**
        * Constructor
        */
        function qSoapServer($encoding = null)
        {
            $this->qObject();

            if (empty($encoding))
            {
                include_once(APP_ROOT_PATH . "class/locale/locale.class.php");
                $locale = &Locale::getInstance();
                $encoding = $locale->getCharset();
            }

            // SOAP server initialization
            $this->_soapServer = new SOAP_Server();
            $this->_soapServer->xml_encoding      = $encoding;
            $this->_soapServer->response_encoding = $encoding;

            $this->_soapServer->addObjectMap($this, "urn:" . $this->getClassName());
        }

        /**
        * Add function info here
        */
        function isError($obj)
        {
            return PEAR::isError($obj);
        }
        
        /**
        * Add function info here
        */
        function run()
        {
            include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");
            $server = &qHttp::getServerVars();

            if ($server->getValue("REQUEST_METHOD") == "POST")
            {
                $this->_soapServer->service($GLOBALS["HTTP_RAW_POST_DATA"]);
            }
            else
            {
                throw(new qException("qSoapServer::run: Invalid request method. Only request POST method is available."));
                die();
            }
        }

        /**
        * Add function info here
        */
        function &getInstance()
        {
            static $soapServer;

            if (!isset($soapServer))
            {
                $soapServer = new qSoapServer();
            }

            return $soapServer;
        }
    }

?>