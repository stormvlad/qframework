<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Encapsula la definición de un objeto que representa una URL
     * 
     * Proporciona métodos para establecer y recuperar todas las partes de una URL:
     * - url (the complete url)
     * - scheme
     * - host     
     * - user
     * - password
     * - path
     * - query
     * - fragment
     *
     * Cada vez que se efectua un cambio en uno de los campos la cadena URL
     * se recalcula y cuando llamamos a la función getUrl devuleve la correta.
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:04
     * @version 1.0
     * @ingroup net
     */
    class qUrl extends qObject
    {
        var $_url;
        var $_scheme;
        var $_host;
        var $_port;
        var $_user;
        var $_pass;
        var $_path;
        var $_query;
        var $_fragment;

        /**
        *    Add function info here
        */
        function qUrl($url = null)
        {
            $this->qObject();

            if (empty($url))
            {
                include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");
                
                $server = &qHttp::getServerVars();
                $url    = $server->getValue("REQUEST_URI");
            }

            $url = rawUrlDecode($url);
            
            if (!eregi("^http[s]?://", $url))
            {
                $protocol = "http";
                $server   = &qHttp::getServerVars();
                $uri      = $server->getValue("HTTP_HOST");

                if (substr($url, 0, 1) == "?" || substr($url, 0, 1) != "/")
                {
                    $uri .= dirname($server->getValue("PHP_SELF")) . "/";
                }
                
                if (substr($url, 0, 1) == "?")
                {
                    $fileName = basename($server->getValue("PHP_SELF"));
                    
                    if ($fileName !== "index.php")
                    {
                        $uri .= basename($server->getValue("PHP_SELF"));
                    }
                }

                if ($server->getValue("HTTPS") == "on" || eregi("^https", $server->getValue("HTTP_REFERER")))
                {
                    $protocol = "https";
                }

                $uri   .= $url;
                $uri    = preg_replace("|[/]+|s", "/", $uri);
                $result = $protocol . "://" . $uri;
            }
            else
            {
                $result = $url;
            }

            $this->_url = str_replace("&amp;", "&", $result);
            $this->_calculateFields();
            $this->_glueUrl();
        }

        /**
        *    Add function info here
        */
        function _calculateFields()
        {
            $parts           = parse_url($this->_url);
            $this->_scheme   = empty($parts["scheme"]) ? "" : $parts["scheme"];
            $this->_host     = empty($parts["host"]) ? "" : $parts["host"];
            $this->_port     = empty($parts["port"]) ? "" : $parts["port"];
            $this->_user     = empty($parts["user"]) ? "" : $parts["user"];
            $this->_pass     = empty($parts["pass"]) ? "" : $parts["pass"];
            $this->_path     = empty($parts["path"]) ? "" : $parts["path"];
            $this->_query    = empty($parts["query"]) ? "" : $parts["query"];
            $this->_fragment = empty($parts["fragment"]) ? "" : $parts["fragment"];
        }

        /**
         * Puts all the pieces back in place, and returns the resulting
         * url.
         *
         * This function is useful if we changed any of the parts individually and
         * want to get the resulting url
         *
         * Extracted from http://www.php.net/manual/en/function.parse-url.php
         */
        function _glueUrl()
        {
             $uri  = $this->_scheme ? $this->_scheme . ":" . ((strtolower($this->_scheme) == "mailto") ? "" : "//") : "";
             $uri .= $this->_user ? $this->_user . ($this->_pass ? ":" . $this->_pass : "") . "@" : "";
             $uri .= $this->_host ? $this->_host : "";
             $uri .= $this->_port ? ":" . $this->_port : "";
             $uri .= $this->_path ? $this->_path : "";
             $uri .= $this->_query ? "?" . $this->_query : "";
             $uri .= $this->_fragment ? "#" . $this->_fragment : "";

            $this->_url = $uri;
        }

        /**
        *    Add function info here
        */
        function getUrl($relative = false)
        {
            if (!empty($relative))
            {
                return baseName($this->_url);
            }

            return $this->_url;
        }

        /**
        *    Add function info here
        */
        function setUrl($url)
        {
            $this->_url = $url;
            $this->_calculateFields();
            $this->_glueUrl();
        }

        /**
        *    Add function info here
        */
        function getUri()
        {
            $uri  = $this->_path ? $this->_path : "";
            $uri .= $this->_query ? "?" . $this->_query : "";
            $uri .= $this->_fragment ? "#" . $this->_fragment : "";
             
            return $uri;
        }
        
        /**
        *    Add function info here
        */
        function getScheme()
        {
            return $this->_scheme;
        }

        /**
        *    Add function info here
        */
        function setScheme($scheme)
        {
            $this->_scheme = $scheme;
            $this->_glueUrl();
        }

        /**
        *    Add function info here
        */
        function getHost()
        {
            return $this->_host;
        }

        /**
        *    Add function info here
        */
        function setHost($host)
        {
            $this->_host = $host;
            $this->_glueUrl();
        }

        /**
        *    Add function info here
        */
        function getServer()
        {
            return preg_replace("/^(.*)[.]([^.]+[.][^.]+)$/", "\\1", $this->_host);
        }
        
        /**
        *    Add function info here
        */
        function getDomain()
        {
            return preg_replace("/^(.*[.])([^.]+[.][^.]+)$/", "\\2", $this->_host);
        }
        
        /**
        *    Add function info here
        */
        function getPort()
        {
            return $this->_port;
        }

        /**
        *    Add function info here
        */
        function setPort($port)
        {
            $this->_port = $port;
            $this->_glueUrl();
        }

        /**
        *    Add function info here
        */
        function getUser()
        {
            return $this->_user;
        }

        /**
        *    Add function info here
        */
        function setUser($user)
        {
            $this->_user = $user;
            $this->_glueUrl();
        }

        /**
        *    Add function info here
        */
        function getPass()
        {
            return $this->_pass;
        }

        /**
        *    Add function info here
        */
        function setPass($pass)
        {
            $this->_pass = $pass;
            $this->_glueUrl();
        }

        /**
        *    Add function info here
        */
        function getPath()
        {
            return $this->_path;
        }

        /**
        *    Add function info here
        */
        function setPath($path)
        {
            $this->_path = $path;
            $this->_glueUrl();
        }

        /**
        *    Add function info here
        */
        function getBaseName()
        {
            return basename($this->_path);
        }

        /**
        *    Add function info here
        */
        function getQuery()
        {
            return $this->_query;
        }

        /**
        *    Add function info here
        */
        function setQuery($query)
        {
            $this->_query = $query;
            $this->_glueUrl();
        }

        /**
        *    Add function info here
        */
        function getFragment()
        {
            return $this->_fragment;
        }

        /**
        *    Add function info here
        */
        function setFragment($fragment)
        {
            $this->_fragment = $fragment;
            $this->_glueUrl();
        }

        /**
         * Returns the query as an array of items
         *
         * @return An associative array where the keys are the name
         * of the parameters and the value is the value assigned to
         * the parameter.
         */
        function getQueryArray($query = null)
        {
            if (empty($query))
            {
                $query = $this->_query;
            }
            else
            {
                $query = preg_replace("/^[^?]+[?]/", "", $query);
            }
            
            $reqParams = preg_split("/&(amp;)?/", $query);
            $results   = array();

            foreach ($reqParams as $param)
            {
                $parts = explode("=", $param);
                $var   = $parts[0];
                
                if (empty($parts[1]))
                {
                    $value = "";
                }
                else
                {
                    $value = urldecode($parts[1]);
                }
                
                $results[$var] = $value;
            }

            return $results;
        }

        /**
        * Add function info here
        */
        function addQueryParam($param, $value)
        {
            if (!empty($this->_query))
            {
                $this->_query .= "&";
            }

            $this->_query .= $param ."=" . $value;
            $this->_glueUrl();
            
            return $this->_query;
        }

        /**
        * Add function info here
        */
        function setQueryParam($param, $value)
        {
            if (preg_match("/((&(amp;)?)|^)" . $param . "=[^&]+/", $this->_query))
            {
                $this->_query  = preg_replace("/(((&(amp;)?)|^)" . $param . ")=[^&]+/", "$1=" . $value, $this->_query);
                $this->_glueUrl();
            }
            else
            {
                $this->addQueryParam($param, $value);
            }
            
            return $this->_query;
        }
        
        /**
        * Add function info here
        */
        function removeQueryParam($param)
        {
            $this->_query = preg_replace("/((&(amp;)?)|^)" . $param . "(\\[[^\\]]+\\])?=[^&]+/", "", $this->_query);
            $this->_query = preg_replace("/^&/", "", $this->_query);
            $this->_glueUrl();
            
            return $this->_query;
        }
    }
?>