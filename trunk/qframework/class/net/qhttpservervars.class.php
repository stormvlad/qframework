<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php" );

    /**
     * @brief  Variables definidas por el servidor web o relacionadas
     *
     * Contienen información tal como cabeceras, rutas y ubicaciones de
     * scripts. Las entradas de esta matriz son creadas por el servidor
     * web. No existen garantías de que cada servidor vaya a
     * proveer alguno de estos valores; puede que los servidores omitan
     * algunos, o provean otros que no se listan aquí. Hecha esta
     * aclaración, un gran número de estas variables hacen
     * parte de la <a href="http://hoohoo.ncsa.uiuc.edu/cgi/env.html" target="_blank">especificación CGI 1.1</a>, 
     * así que puede esperar que sean definidas por el servidor.
     *
     * Análoga a la matriz superglobal $_SERVER
     *
     * Mas información:
     * http://es.php.net/manual/es/reserved.variables.php#reserved.variables.server
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:25
     * @version 1.0
     * @ingroup net http
     * @see qHttp
     */
    class qHttpServerVars extends qHttpVars
    {
        /**
        *    Add function info here
        */
        function qHttpServerVars()
        {
            $this->qHttpVars($_SERVER);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_SERVER, $this->getAsArray());
        }
    }
?>
