<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpCookieVars extends qHttpVars
    {
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
