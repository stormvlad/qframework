<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpPostVars extends qHttpVars
    {
        function qHttpPostVars()
        {
            $this->qHttpVars($_POST);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_POST, $this->getAsArray());
        }
    }
?>
