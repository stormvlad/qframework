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
        var $_curlSession;
        
        var $_url;
        var $_returnTransfer;
        var $_variables;
        var $_followLocation;
        
        var $_buffer;
        
        /**
        * Constructor
        *
        * @param url Establishes the url of a CURL session
        * @return void
        * @public
        */
        function qCurl($url = null)
        {
            $this->setUrl($url);
            $this->setFollowLocation(true);
            $this->setReturnTransfer(true);
        }
        
        /**
        * Add function info here
        */
        function setSession($session)
        {
            $this->_curlSession = $session;
        }
        
        /**
        * Add function info here
        */
        function getSession()
        {
            return $this->_curlSession;
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
        function setVariables($variables)
        {
            $this->_variables = $variables;
        }
        
        /**
        * Add function info here
        */
        function getVariables()
        {
            return $this->_variables;
        }
        
        /**
        * Add function info here
        */
        function setFollowLocation($follow = true)
        {
            $this->_followLocation = $follow;
        }
        
        /**
        * Add function info here
        */
        function getFollowLocation()
        {
            return $this->_followLocation;
        }
        
        /**
        * Add function info here
        */
        function setReturnTransfer($return = true)
        {
            $this->_returnTransfer = $return;
        }
        
        /**
        * Add function info here
        */
        function getReturnTransfer()
        {
            return $this->_returnTransfer;
        }
        
        /**
        * Add function info here
        */
        function execute()
        {
            $this->setSession(curl_init());
            
            curl_setopt($this->getSession(), CURLOPT_URL, $this->getUrl());
            curl_setopt($this->getSession(), CURLOPT_RETURNTRANSFER, $this->getReturnTransfer());
            if($this->getVariables())
            {
                curl_setopt($this->getSession(), CURLOPT_POST, 1);
                curl_setopt($this->getSession(), CURLOPT_POSTFIELDS, $this->getVariables());
            }
            curl_setopt($this->getSession(), CURLOPT_FOLLOWLOCATION, $this->getFollowLocation());
            
            $this->_buffer = curl_exec($this->getSession());
            curl_close($this->getSession());
            
            return $this->_buffer;
        }
        
    }
?>