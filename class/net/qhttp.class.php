<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpcookievars.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpfilesvars.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpgetvars.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttppostvars.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttprequestvars.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpservervars.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpsessionvars.class.php");

    define("REQUEST_METHOD_NONE", 0);
    define("REQUEST_METHOD_GET",  2);
    define("REQUEST_METHOD_POST", 4);
    define("REQUEST_METHOD_ANY",  REQUEST_METHOD_GET | REQUEST_METHOD_POST);

    /**
     * @brief Acceso único a las variables predefinidas 
     *
     * Esta clase proporciona métodos basados en el patrón Singleton para
     * asegurarnos el acceso global a la misma instancia de las variables 
     * con un punto global de accesso a ellas.
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:40
     * @version 1.0
     * @ingroup net
     * @see qHttpVars
     */
    class qHttp extends qObject
    {

        /**
        *  Add function info here
        */
        function &getGetVars()
        {
            static $getVarsInstance;

            if (!isset($getVarsInstance))
            {
                $getVarsInstance = new qHttpGetVars();
            }

            return $getVarsInstance;
        }

        /**
        *  Add function info here
        */
        function &getPostVars()
        {
            static $postVarsInstance;

            if (!isset($postVarsInstance))
            {
                $postVarsInstance = new qHttpPostVars();
            }

            return $postVarsInstance;
        }

        /**
        *  Add function info here
        */
        function &getSessionVars()
        {
            static $sessionVarsInstance;

            if (!isset($sessionVarsInstance))
            {
                $sessionVarsInstance = new qHttpSessionVars();
            }

            return $sessionVarsInstance;
        }

        /**
        *  Add function info here
        */
        function &getCookieVars()
        {
            static $cookieVarsInstance;

            if (!isset($cookieVarsInstance))
            {
                $cookieVarsInstance = new qHttpCookieVars();
            }

            return $cookieVarsInstance;
        }

        /**
        *  Add function info here
        */
        function &getRequestVars()
        {
            static $requestVarsInstance;

            if (!isset($requestVarsInstance))
            {
                $requestVarsInstance = new qHttpRequestVars();
            }

            return $requestVarsInstance;
        }

        /**
        *  Add function info here
        */
        function &getServerVars()
        {
            static $serverVarsInstance;

            if (!isset($serverVarsInstance))
            {
                $serverVarsInstance = new qHttpServerVars();
            }

            return $serverVarsInstance;
        }

        /**
        *  Add function info here
        */
        function &getFilesVars()
        {
            static $filesVarsInstance;

            if (!isset($filesVarsInstance))
            {
                $filesVarsInstance = new qHttpFilesVars();
            }

            return $filesVarsInstance;
        }

        /**
        *  Add function info here
        */
        function getRequestMethod()
        {
            $server = &qHttp::getServerVars();

            if ($server->getValue("REQUEST_METHOD") == "POST")
            {
                return REQUEST_METHOD_POST;
            }
            else
            {
                return REQUEST_METHOD_GET;
            }
        }
    }
?>
