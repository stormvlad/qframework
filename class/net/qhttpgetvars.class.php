<?php

    include_once("qframework/class/net/qhttpvars.class.php");
    include_once("qframework/class/net/qhttp.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpGetVars extends qHttpVars {

        function qHttpGetVars($params = null)
        {
            $this->qHttpVars($params);
        }

        function save()
        {
            Http::setGet($this->getAsArray());
        }
    }
?>
