<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qparams.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qControllerParams extends qObject
    {
        var $_controller;
        var $_httpRequest;
        var $_user;

        /**
         * Add function info here
         */
        function qControllerParams(&$controller, &$httpRequest, &$user)
        {
            $this->qObject();

            $this->_controller  = &$controller;
            $this->_httpRequest = &$httpRequest;
            $this->_user        = &$user;
        }

        /**
         * Add function info here
         */
        function &getController()
        {
            return $this->_controller;
        }

        /**
         * Add function info here
         */
        function setController(&$controller)
        {
            $this->_controller = &$controller;
        }

        /**
         * Add function info here
         */
        function &getHttpRequest()
        {
            return $this->_httpRequest;
        }

        /**
         * Add function info here
         */
        function setHttpRequest(&$request)
        {
            $this->_httpRequest = &$request;
        }

        /**
         * Add function info here
         */
        function &getUser()
        {
            return $this->_user;
        }

        /**
         * Add function info here
         */
        function setUser(&$user)
        {
            $this->_user = &$user;
        }
    }

?>