<?php

    include_once("framework/class/object/qobject.class.php");
    include_once("framework/class/net/qhttp.class.php");

    class qRequestParser extends qObject {

        var $_function;
        var $_pathInfo;

        function qRequestParser($function, $pathInfo = null)
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
            throw(new qException("RequestParser::parse: This method must be implemented by child classes."));
            die();
        }
    }
?>
