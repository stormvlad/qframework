<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpFilesVars extends qHttpVars
    {
        function qHttpFilesVars()
        {
            $this->qHttpVars($_FILES);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_FILES, $this->getAsArray());
        }
    }
?>
