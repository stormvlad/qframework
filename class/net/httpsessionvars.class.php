<?php

    include_once("framework/class/net/httpvars.class.php" );
    include_once("framework/class/net/http.class.php" );

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class HttpSessionVars extends HttpVars {

        function HttpSessionVars($params = null)
        {
            $this->Properties($params);
        }

        function save()
        {
            Http::setSession($this->getAsArray());
        }
    }
?>
