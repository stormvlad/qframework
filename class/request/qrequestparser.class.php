<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");

    /**
     * @brief Base del analizador de cadenas de petición HTTP GET
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 18:19
     * @version 1.0
     * @ingroup request
     */
    class qRequestParser extends qObject
    {
        var $_function;
        var $_pathInfo;

        /**
         * Constructor
         */
        function qRequestParser($function, $pathInfo = null)
        {
            if ($pathInfo === null)
            {
                // $pathinfo = $_ENV["FILEPATH_INFO"] || $_SERVER["PATH_INFO"];
                $pathinfo = $_SERVER["PATH_INFO"];
            }

            $this->qObject();
            $this->_function = $function;
            $this->_pathInfo = $pathInfo;
        }

        /**
         * Add function info here
         */
        function getFunction()
        {
            return $this->_function;
        }

        /**
         * Add function info here
         */
        function getPathInfo()
        {
            return $this->_pathInfo;
        }

        /**
         * Add function info here
         */
        function setPathInfo()
        {
            return $this->_pathInfo;
        }

        /**
         * Add function info here
         */
        function parse(&$request)
        {
            trigger_error("This function must be implemented by child classes.", E_USER_ERROR);
            return;
        }
    }
?>
