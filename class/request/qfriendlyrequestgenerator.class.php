<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/request/qrequestgenerator.class.php");

    /**
     * @brief Generador mejorado de cadenas con una petición HTTP GET
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 18:21
     * @version 1.0
     * @ingroup request     
     */
    class qFriendlyRequestGenerator extends qRequestGenerator
    {
        /**
         * Constructor.
         */
        function qFriendlyRequestGenerator()
        {
            $this->qRequestGenerator();
        }

        function getBaseUrl($abs = DEFAULT_ABSOLUTE_URL)
        {
            if ($abs)
            {
                $ret = $this->_baseUrl . "/";
            }
            else
            {
                $ret = $this->_dirName . "/";
            }

            return ereg_replace("^/+", "/", $ret);
        }
        
        /**
         * Returns a string representing the request
         *
         * @return A String object representing the request
         */
        function getRequest()
        {
            $file    = APP_ROOT_PATH . "config/controllermap.properties.php";

            if (!is_file($file) || !is_readable($file))
            {
                die();
            }
            
            include($file);

            $op = $this->_params["op"];
            
            if (isset($urlpattern[$op]))
            {   
                $patterns = $urlpattern[$op];
                
                if (is_array($patterns))
                {
                    $ret = $urlpattern[$op][0];
                    
                    foreach($patterns as $pattern)
                    {
                        preg_match_all("/\{([_0-9a-zA-Z-]+)\}/", $pattern, $matches);
                        if (count($matches[1]) == count($this->extract($matches[1])))
                        {
                            $ret = $pattern;
                        }
                    }
                }         
                else
                {
                    $ret = $patterns;
                }

                preg_match_all("/\{([_0-9a-zA-Z-]+)\}/", $ret, $matches);
                $values   = $this->extract($matches[1]);
                $pathinfo = str_replace($matches[0], $values, $ret);
            }
            else
            {
                $pathinfo = "";
                
                foreach ($this->_params as $name => $value)
                {
                    if ($pathinfo != "")
                    {
                        $pathinfo .= "/";
                    }
    
                    $pathinfo .= urlencode($value);
                }
            }
            
            return $pathinfo;
        }
    }
?>
