<?php

    include_once("framework/class/net/httpvars.class.php" );
    include_once("framework/class/net/http.class.php" );

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class HttpCookieVars extends HttpVars {

        function HttpCookieVars($params = null)
        {
            $this->HttpVars($params);
        }

        function save()
        {
            Http::setCookie($this->getAsArray());
        }
    }
?>
