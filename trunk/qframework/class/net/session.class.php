<?php
    include_once("framework/class/config/properties.class.php" );
    include_once("framework/class/net/httpsessionvars.class.php" );
    include_once("framework/class/net/http.class.php" );

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class Session extends Object {

        function Session()
        {
            $this->Object();
        }

        function &getSession()
        {
            static $sessionInstance;

            if (!isset($sessionInstance))
            {
                $sessionInstance = new SessionInfo(Http::getSession());
            }

            return $sessionInstance;
        }
    }
?>
