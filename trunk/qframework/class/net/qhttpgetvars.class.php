<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpGetVars extends qHttpVars
    {
        function qHttpGetVars()
        {
            $this->qHttpVars($_GET);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_GET, $this->getAsArray());
        }
    }
?>
