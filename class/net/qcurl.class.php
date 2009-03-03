<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Encapsula el soporte CURL en PHP
     *
     * PHP soporta libcurl, una biblioteca creada por Danile Stenberg, que permite conexión y comunicación
     * con varios tipos de servidores diferentes con varios tipos de protocolos diferentes
     *
     * Mas información:
     * - http://es.php.net/manual/es/ref.curl.php
     * - http://curl.haxx.se/
     *
     * @author  Isaac
     * @date    22/03/2005 14:08
     * @version 1.0
     * @ingroup net
     */
    class qCurl extends qObject
    {
        var $_url;
        var $_options;
        var $_error;
        
        /**
        * Constructor
        *
        * @param url Establishes the url of a CURL session
        * @return void
        * @public
        */
        function qCurl($url = null)
        {
            $this->qObject();
            
            $this->_url     = $url;
            $this->_options = array();
            $this->_error   = false;
            
            $this->setOption(CURLOPT_HTTPGET, true);
            $this->setOption(CURLOPT_RETURNTRANSFER, true);
            $this->setOption(CURLOPT_FOLLOWLOCATION, true);
        }

        /**
        * getUrl
        *
        * @return url
        * @public
        */
        function getUrl()
        {
            return $this->_url;
        }
        
        /**
        * setUrl
        *
        * @param url
        * @return void
        * @public
        */
        function setUrl($url)
        {
            $this->_url = $url;
        }
        
        /**
        * Add function info here
        */
        function setOptions($options)
        {
            $this->_options = $options;
        }
        
        /**
        * Add function info here
        */
        function getOptions()
        {
            return $this->_options;
        }
        
        /**
        * Add function info here
        */
        function setOption($name, $value)
        {
            $this->_options[$name] = $value;
        }
        
        /**
        * Add function info here
        */
        function getOption($name)
        {
            return $this->_options[$name];
        }
        
        /**
        * Add function info here
        */
        function getLastError()
        {
            return $this->_error;
        }
        
        /**
        * Add function info here
        */
        function execute($url = null)
        {
            $this->_error = false;
            
            if (empty($url))
            {
                $url = $this->_url;
            }
            
            $curl = curl_init();
            
            curl_setopt($curl, CURLOPT_URL, $url);
            
            foreach ($this->_options as $key => $value)
            {
                curl_setopt($curl, $key, $value);
            }
            
            $result = curl_exec($curl);
            
            if (empty($result))
            {
                $this->_error = "cURL Error " . curl_errno($curl) . ": " . curl_error() . ".";
            }
            
            curl_close($curl);
            return $result;
        }
    }
?>