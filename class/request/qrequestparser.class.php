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

        function getFunction()
        {
            return $this->_function;
        }

        function getPathInfo()
        {
            return $this->_pathInfo;
        }

        function setPathInfo()
        {
            return $this->_pathInfo;
        }
        
        function parse(&$request)
        {
            throw(new qException("RequestParser::parse: This method must be implemented by child classes."));
            die();
        }
    }
?>
