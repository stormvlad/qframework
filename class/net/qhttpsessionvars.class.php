<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpSessionVars extends qHttpVars
    {
        function qHttpSessionVars()
        {
            $this->qHttpVars($_SESSION);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_SESSION, $this->getAsArray());
        }
    }
?>
