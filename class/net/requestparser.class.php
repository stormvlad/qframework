<?php

    include_once("framework/class/object/object.class.php");
    include_once("framework/class/net/http.class.php");

    class RequestParser extends Object {

        var $_function;
        var $_pathInfo;

        function RequestParser($function, $pathInfo = null)
        {
            if ($pathInfo === null)
            {
                $server   = &Http::getServerVars();
                $pathInfo = $server->getValue("PATH_INFO");
            }

            $this->Object();
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

        function parse()
        {
            throw(new Exception("RequestParser::parse: This method must be implemented by child classes."));
            die();
        }
    }
?>
