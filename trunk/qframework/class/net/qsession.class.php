<?php
    include_once("qframework/class/config/qproperties.class.php" );
    include_once("qframework/class/net/qhttpsessionvars.class.php" );
    include_once("qframework/class/net/qhttp.class.php" );

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qSession extends qObject {

        function qSession()
        {
            $this->qObject();
        }

        function &getSession()
        {
            static $sessionInstance;

            if (!isset($sessionInstance))
            {
                $sessionInstance = new qSessionInfo(Http::getSession());
            }

            return $sessionInstance;
        }
    }
?>
