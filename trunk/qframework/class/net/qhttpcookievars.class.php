<?php

    include_once("framework/class/net/qhttpvars.class.php" );
    include_once("framework/class/net/qhttp.class.php" );

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpCookieVars extends qHttpVars {

        function qHttpCookieVars($params = null)
        {
            $this->qHttpVars($params);
        }

        function save()
        {
            qHttp::setCookie($this->getAsArray());
        }
    }
?>
