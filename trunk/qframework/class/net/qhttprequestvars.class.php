<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");

    /**
     * @brief  Variables proporcionadas al script por medio de HTTP POST, GET y Cookies.
     *     
     * Variables proporcionadas al script por medio de cuaquier mecanismo de entrada del usuario 
     * y por lo tanto no se puede confiar en ellas. La presencia y el orden en que aparecen las variables
     * en esta matriz es definido por la directiva de configuración 
     * <a href="ini.core.php#ini.variables-order" target="_blank">variables_order</a>.     
     *
     * Análoga a la matriz superglobal $_REQUEST
     *
     * Mas información:
     * http://es.php.net/manual/es/reserved.variables.php#reserved.variables.request
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:25
     * @version 1.0
     * @ingroup net http
     * @see qHttp
     */
    class qHttpRequestVars extends qHttpVars
    {
        function qHttpRequestVars()
        {
            $this->qHttpVars($_REQUEST);
            $this->setValue("__method__", qHttp::getRequestMethod());
        }

        /**
        *    Add function info here
        */
        function getRequestMethod()
        {
            return $this->getValue("__method__");
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_REQUEST, $this->getAsArray());
        }
    }
?>