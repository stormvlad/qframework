<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php" );

    /**
     * @brief  Variables definidas por el servidor web o relacionadas
     *
     * Contienen informaci�n tal como cabeceras, rutas y ubicaciones de
     * scripts. Las entradas de esta matriz son creadas por el servidor
     * web. No existen garant�as de que cada servidor vaya a
     * proveer alguno de estos valores; puede que los servidores omitan
     * algunos, o provean otros que no se listan aqu�. Hecha esta
     * aclaraci�n, un gran n�mero de estas variables hacen
     * parte de la <a href="http://hoohoo.ncsa.uiuc.edu/cgi/env.html" target="_blank">especificaci�n CGI 1.1</a>, 
     * as� que puede esperar que sean definidas por el servidor.
     *
     * An�loga a la matriz superglobal $_SERVER
     *
     * Mas informaci�n:
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
