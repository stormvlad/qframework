<?php

    include_once("framework/class/net/httpvars.class.php" );
    include_once("framework/class/net/http.class.php" );

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class HttpFilesVars extends HttpVars {

        function HttpFilesVars($params = null)
        {
            $this->HttpVars($params);
        }
    }
?>
