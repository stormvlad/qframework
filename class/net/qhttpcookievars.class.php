<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpCookieVars extends qHttpVars
    {
        function qHttpCookieVars()
        {
            $this->qHttpVars($_COOKIE);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_COOKIE, $this->getAsArray());
        }
    }
?>
