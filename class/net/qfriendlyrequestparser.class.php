<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qrequestparser.class.php");
    
    define("FRIENDLY_PATHINFO_PARAM", "q");

    /**
     * @brief Analizador fácil de cadenas de petición HTTP GET
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 18:19
     * @version 1.0
     * @ingroup net
     */
    class qFriendlyRequestParser extends qRequestParser
    {
        function qFriendlyRequestParser($function, $path_info = null)
        {            
            $request   = &qHttp::getRequestVars();
            $path_info = $request->getValue(FRIENDLY_PATHINFO_PARAM);
            
            $this->qRequestParser($function, $path_info);
        }

        function parse()
        {
            $file = APP_ROOT_PATH . "config/controllermap.properties.php";

            if (is_file($file) && is_readable($file))
            {
                include($file);
            }
            
            $result = Array();
            $count  = 0;

            if (substr($this->_pathInfo, 0, 1) == "/")
            {
                $this->_pathInfo = substr($this->_pathInfo, 1);
            }

            $pathactionparams = array_values($pathactionparams);
            for($i = 0; $count == 0 && $i < count($pathactionparams); $i++)
            {
                $er         = ereg_replace("\{(.*)\}", "([_0-9a-zA-Z-]+)?", $pathactionparams[$i]);

                // Extract the key values from the uri:
                $count      = preg_match("#$er#", $this->_pathInfo, $paramValues);
            }           
            
            if ($count == 0)
            {
                return false;
            }

            preg_match("/\{([_0-9a-zA-Z-]+)?\}/", $pathactionparams[$i], $paramNames);

            for ($i = 1; $i <= $count; $i++)
            {
                $paramName          = $paramNames[$i];
                $result[$paramName] = isset($paramValues[$i]) ? $paramValues[$i] : "";
            }

            return $result;
        }
    }
?>
