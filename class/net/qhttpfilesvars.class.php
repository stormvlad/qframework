<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpFilesVars extends qHttpVars
    {
        function qHttpFilesVars($params = null)
        {
            $this->qHttpVars($params);
        }
    }
?>
