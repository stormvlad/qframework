<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpRequestVars extends qHttpVars
    {
        function qHttpRequestVars()
        {
            $this->qHttpVars($_REQUEST);
            $this->setValue("__method__", qHttp::getRequestMethod());
        }

        /**
        *    Add function info here
        */
        function getRequestMethod()
        {
            return $this->getValue("__method__");
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_REQUEST, $this->getAsArray());
        }
    }
?>