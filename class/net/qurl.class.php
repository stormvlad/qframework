<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Encapsulates a definition of an object representing a URL
     *
     * Provides getters and setters for all the parts of the url:
     * <ul>
     * <li>url (the complete url)</li>
     * <li>scheme</li>
     * <li>host</li>
     * <li>user</li>
     * <li>password</li>
     * <li>path</li>
     * <li>query</li>
     * <li>fragment</li>
     *
     * </ul>
     * Every time a change is made in one of the fields the
     * url string is recalculated so that any call to getUrl
     * will return the right one.
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
        function qUrl($url)
        {
            $this->qObject();

            $this->_url = $url;
            $this->_calculateFields();
        }

        /**
        *    Add function info here
        */
        function _calculateFields()
        {
            $parts           = parse_url($this->_url);
            $this->_url      = $parts["url"];
            $this->_scheme   = $parts["scheme"];
            $this->_host     = $parts["host"];
            $this->_port     = $parts["port"];
            $this->_user     = $parts["user"];
            $this->_pass     = $parts["pass"];
            $this->_path     = $parts["path"];
            $this->_query    = $parts["query"];
            $this->_fragment = $parts["fragment"];
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
        function getUrl()
        {
            return $this->_url;
        }

        /**
        *    Add function info here
        */
        function setUrl($url)
        {
            $this->_url = $url;
            $this->_calculateFields();
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
        function getQueryArray()
        {
            $reqParams = explode("&", $this->_query);
            $results   = array();

            foreach ($reqParams as $param)
            {
                $parts         = explode("=", $param);
                $var           = $parts[0];
                $value         = urldecode($parts[1]);
                $results[$var] = $value;
            }

            return $results;
        }
    }
?>
