<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/request/qrequestparser.class.php");
    
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
        function qFriendlyRequestParser()
        {            
            $this->qRequestParser("");
        }

        function parse(&$request)
        {
            $httpVars  = &qHttp::getRequestVars();
            $path_info = $httpVars->getValue(FRIENDLY_PATHINFO_PARAM);           
            $file      = APP_ROOT_PATH . "config/controllermap.properties.php";

            if (!is_file($file) || !is_readable($file))
            {
                die();
            }
            
            include($file);

            $result = Array();
            $count  = 0;

            if (substr($path_info, 0, 1) == "/")
            {
                $path_info = substr($path_info, 1);
            }

            foreach ($urlpattern as $name => $pattern)
            {                
                $er    = preg_replace("/\{[_0-9a-zA-Z-]+\}/", "([_0-9a-zA-Z-]+)", $pattern);
                $count = preg_match("#$er#", $path_info, $paramValues);

                if ($count)
                {
                    array_shift($paramValues);
                    $op          = $name;
                    break;
                }                                
            }

            if ($count == 0)
            {
                $request->setValue("op", $path_info);
                return;
            }
            
            $request->setValue("op", $op);

            preg_match_all("/\{([_0-9a-zA-Z-]+)?\}/", $urlpattern[$op], $matches);
            $paramNames = $matches[1];

            for ($i = 0; $i < count($paramValues); $i++)
            {
                $request->setValue($paramNames[$i], (isset($paramValues[$i]) ? $paramValues[$i] : ""));
            }
            
            print_r($request->getAsArray());
        }
    }
?>
