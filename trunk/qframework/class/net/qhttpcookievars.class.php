<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");

    /**
     * @brief   Variables proporcionadas al script por medio de HTTP cookies
     *
     * Análoga a la matriz superglobal $_COOKIE
     *
     * Mas información:
     * http://es.php.net/manual/es/reserved.variables.php#reserved.variables.cookies
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:25
     * @version 1.0
     * @ingroup net http
     * @see qHttp
     */
    class qHttpCookieVars extends qHttpVars
    {
        function qHttpCookieVars()
        {
            $this->qHttpVars($_COOKIE);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_COOKIE, $this->getAsArray());
        }
    }
?>
