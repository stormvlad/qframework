<?php

    // PEAR include 
    include_once("SOAP/Client.php");

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("SOAP_CLIENT_DEFAULT_TIMEOUT", 120);
    
    /**
    * Add class info here
    */
    class qSoapClient extends qObject
    {
        var $_soapClient;
        var $_nameSpace;

        /**
        * Constructor
        */
        function qSoapClient($serverUrl, $nameSpace, $encoding = null, $timeOut = SOAP_CLIENT_DEFAULT_TIMEOUT)
        {
            $this->qObject();

            if (empty($encoding))
            {
                include_once(APP_ROOT_PATH . "class/locale/locale.class.php");
                $locale = &Locale::getInstance();
                $encoding = $locale->getCharset();
            }
            
            // SOAP client initialization
            $this->_nameSpace  = $nameSpace;

            $this->_soapClient = new SOAP_Client($serverUrl);
            $this->_soapClient->_encoding = $encoding;

            $this->setOption("timeout", $timeOut);

            if( !function_exists("curl_init") )
            {
                trigger_error("CURL Library is missing.", E_USER_ERROR);
                die;
            }

            if (strtolower(substr($serverUrl, 0, 5)) == "https")
            {
                $this->setOption("curl", CURLOPT_VERBOSE, 1);
                $this->setOption("curl", CURLOPT_SSL_VERIFYPEER, false);
                $this->setOption("curl", CURLOPT_SSL_VERIFYHOST, false);
            }
        }

        /**
        * Add function info here
        */
        function getServerUrl()
        {
            return $this->_soapClient->_endpoint;
        }

        /**
        * Add function info here
        */
        function setServerUrl($url)
        {
            $this->_soapClient->_endpoint = $url;
        }
        
        /**
        * Add function info here
        */
        function getNameSpace()
        {
            return $this->_nameSpace;
        }

        /**
        * Add function info here
        */
        function setNameSpace($name)
        {
            $this->_nameSpace = $space;
        }
        
        /**
        * Add function info here
        */
        function getEncoding()
        {
            return $this->_soapClient->_encoding;
        }

        /**
        * Add function info here
        */
        function setEncoding($encoding)
        {
            $this->_soapClient->_encoding = $encoding;
        }

        /**
        * Add function info here
        */
        function getTimeout()
        {
            return $this->_soapClient->_options["timeout"];
        }

        /**
        * Add function info here
        */
        function setTimeout()
        {
            $this->_soapClient->setOpt("timeout", $timeOut);
        }

        /**
        * Add function info here
        */
        function getOption($category, $option = null)
        {
            if (empty($option))
            {
                return $this->_soapClient->_options[$category];
            }
            
            return $this->_soapClient->_options[$category][$option];
        }

        /**
        * Add function info here
        */
        function setOption($category, $option, $value = null)
        {
            $this->_soapClient->setOpt($category, $option, $value);
        }
        
        /**
        * Add function info here
        */
        function &getInstance()
        {
            static $soapClient;

            if (!isset($soapClient))
            {
                $soapClient = new qSoapClient();
            }

            return $soapClient;
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
        function getErrorMessage($obj)
        {
            if ($this->isError($obj))
            {
                if (preg_match("/^([^\\[]+ +\\[[^\\]]+ +\\[[^\\]]+ +)?(.+)/", strip_tags($obj->message), $regs))
                {
                    return $regs[2];
                }
            }

            return false;
        }

        /**
        * Add function info here
        */
        function call($method, $params)
        {
            return $this->_soapClient->call($method, $params, $this->_nameSpace);
        }

        /**
        * Add function info here
        */
        function callAutoMethod($args)
        {
            $callers = debug_backtrace();
            $method  = $callers[1]["function"];
            $options = array("namespace" => $this->getNameSpace(), "trace" => 1, "debug" => _DEBUG_);
            $params  = array_merge($args, array($options));
            
            return $this->call($method, $params);
        }
    }

?>
