<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qrequestparser.class.php");

    class qPrettyRequestParser extends qRequestParser
    {
        var $_paramsMap;
        var $_firstParamFromFunction;

        function qPrettyRequestParser($function, $path_info = null)
        {
            $this->qRequestParser($function, $path_info);
            $this->_paramsMap = null;
            $this->_firstParamFromScriptName = false;
        }

        function setParamsMap($map)
        {
            $this->_paramsMap = $map;
        }

        function getParamsMap()
        {
            return $this->_paramsMap;
        }

        function setFirstParamFromFunction($paramName)
        {
            $this->_firstParamFromFunction = $paramName;
        }

        function getFirstParamFromFunction()
        {
            return $this->_firstParamFromFunction;
        }

        function parse()
        {
            $result = Array();

            if ($paramName = $this->getFirstParamFromFunction())
            {
                $result[$paramName] = $this->getFunction();
            }

            if (!empty($this->_paramsMap))
            {
                if (substr($this->_pathInfo, 0, 1) == "/")
                {
                    $this->_pathInfo = substr($this->_pathInfo, 1);
                }

                $paramValues  = explode("/", $this->_pathInfo);
                $paramNames   = $this->_paramsMap[$this->_function];
                $totalNames   = count($paramNames);

                for ($i = 0; $i < $totalNames; $i++)
                {
                    $paramName          = $paramNames[$i];
                    $result[$paramName] = isset($paramValues[$i]) ? $paramValues[$i] : "";
                }
            }

            return $result;
        }
    }
?>
