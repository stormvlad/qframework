<?php

    include_once("framework/class/net/qhttpvars.class.php" );
    include_once("framework/class/net/qhttp.class.php" );

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpSessionVars extends qHttpVars {

        function qHttpSessionVars($params = null)
        {
            $this->qProperties($params);
        }

        function save()
        {
            qHttp::setSession($this->getAsArray());
        }
    }
?>
