<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php" );

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpServerVars extends qHttpVars
    {
        /**
        *    Add function info here
        */
        function qHttpServerVars()
        {
            $this->qHttpVars($_SERVER);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_SERVER, $this->getAsArray());
        }
    }
?>
