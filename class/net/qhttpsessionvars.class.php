<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");

    /**
     * @brief  Variables registradas en la sesión del script
     *
     * Análoga a la matriz superglobal $_SESSION
     *
     * Mas información:
     * http://es.php.net/manual/es/reserved.variables.php#reserved.variables.session
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:25
     * @version 1.0
     * @ingroup net http
     * @see qHttp
     */
    class qHttpSessionVars extends qHttpVars
    {
        function qHttpSessionVars()
        {
            $this->qHttpVars($_SESSION);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_SESSION, $this->getAsArray());
        }
    }
?>
