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
     * HttpVars compatibility package, which allows to fetch some of php's basic
     * global variables without having to worry about which version of php we're using.
     * The problem here is that since PHP 4.1.0 things like $_REQUEST, $_POST, $_GET, etc
     * are available, and before that their equivalents were $HTTP_GET_VARS,
     * $HTTP_POST_VARS and so on. By using this package and calling the functions
     * getPostVars, getGetVars, getSessionVars/setSessionVars we will get rid of any
     * incompatibility with the version of php we are running while having access to the
     * variables we most need.
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
