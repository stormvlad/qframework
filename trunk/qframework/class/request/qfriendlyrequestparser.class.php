<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/request/qrequestparser.class.php");
    
    define("FRIENDLY_PATHINFO_PARAM", "q");

    /**
     * @brief Analizador fácil de cadenas de petición HTTP GET
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 18:19
     * @version 1.0
     * @ingroup request
     */
    class qFriendlyRequestParser extends qRequestParser
    {
        var $_pathInfo;
        var $_action;
        var $_paramNames;
        var $_paramValues;
              
        function qFriendlyRequestParser()
        {            
            $this->qRequestParser("");
            
            $httpVars        = &qHttp::getRequestVars();
            $this->_pathInfo = $httpVars->getValue(FRIENDLY_PATHINFO_PARAM);  
             
             if (substr($this->_pathInfo, 0, 1) == "/")
            {
                $this->_pathInfo = substr($this->_pathInfo, 1);
            }         
        }

        function parse(&$request)
        {
            $httpVars = &qHttp::getRequestVars();
            $request->setValuesByRef($httpVars->getAsArray());
            
            $file = APP_ROOT_PATH . "config/controllermap.properties.php";

            if (!is_file($file) || !is_readable($file))
            {
                die();
            }
            
            include($file);

            if (!$this->getParameters($urlpattern))
            {
                if (!$request->keyExists("op"))
                {
                    $request->setValue("op", $this->_pathInfo);
                }
                return;
            }

            $request->setValue("op", $this->_action);

            for ($i = 0; $i < count($this->_paramNames); $i++)
            {
                $request->setValue($this->_paramNames[$i], (isset($this->_paramValues[$i]) ? $this->_paramValues[$i] : ""));
            }
        }
        
        function checkPatterns($urlPatterns)
        {
            foreach($urlPatterns as $action => $patterns)
            {
                if (is_array($patterns))
                {
                    foreach($patterns as $number => $pattern)
                    {
                        if ($this->checkPattern($pattern))
                        {
                            return array($action, $number);
                        }
                    }
                }
                else
                {
                    if ($this->checkPattern($patterns))
                    {
                        return array($name, null);
                    }
                }
            }
            
            return array(null, null);
        }

        function checkPattern($pattern)
        {
            $er = preg_replace("/\{[_0-9a-zA-Z-]+\}/", "([_0-9a-zA-Z-]+)", $pattern);

            return preg_match("#^$er$#", $this->_pathInfo);
        }
        
        function getParameters($urlPatterns)
        {
            list($action, $number) = $this->checkPatterns($urlPatterns);

            if ($action === null)
            {
                return false;
            }
            elseif ($number !== null)
            {
                $pattern = $urlPatterns[$action][$number];            
            }
            else
            {
                $pattern = $urlPatterns[$action];
            }

            $er = preg_replace("/\{[_0-9a-zA-Z-]+\}/", "([_0-9a-zA-Z-]+)", $pattern);
            preg_match("#^$er$#", $this->_pathInfo, $values);
            array_shift($values);

            preg_match_all("/\{([_0-9a-zA-Z-]+)?\}/", $pattern, $matches);
            $names = $matches[1];
            
            $this->_action      = $action;
            $this->_paramNames  = $names;
            $this->_paramValues = $values;
            
            return true;
        }
    }
?>
