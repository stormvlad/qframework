<?php

    include_once("framework/class/object/object.class.php" );

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
    class Url extends Object {

        var $_url;
        var $_scheme;
        var $_host;
        var $_port;
        var $_user;
        var $_pass;
        var $_path;
        var $_query;
        var $_fragment;

        function Url( $url )
        {
            $this->Object();

            $this->_url = $url;

            $this->_calculateFields();
        }

        function _calculateFields()
        {
            $parts = parse_url( $this->_url );

            $keys = Array( "scheme", "host", "port", "user", "pass",
                          "path", "query", "fragment" );

            // this saves us time ;)
            foreach( $keys as $key ) {
                $line = "\$this->_$key = \$parts[\"$key\"];";
                eval($line);
            }
        }

        function getUrl()
        {
            return $this->_url;
        }

        function setUrl( $url )
        {
            $this->_url = $url;

            $this->_calculateFields();
        }

        function getScheme()
        {
            return $this->_scheme;
        }

        function setScheme( $scheme )
        {
            $this->_scheme = $scheme;

            $this->glueUrl();
        }

        function getHost()
        {
            return $this->_host;
        }

        function setHost( $host )
        {
            $this->_host = $host;

            $this->glueUrl();
        }

        function getPort()
        {
            return $this->_port;
        }

        function setPort( $port )
        {
            $this->_port = $port;

            $this->glueUrl();
        }

        function getUser()
        {
            return $this->_user;
        }

        function setUser( $user )
        {
            $this->_user = $user;

            $this->glueUrl();
        }

        function getPass()
        {
            return $this->_pass;
        }

        function setPass( $pass )
        {
            $this->_pass = $pass;

            $this->glueUrl();
        }

        function getPath()
        {
            return $this->_path;
        }

        function setPath( $path )
        {
            $this->_path = $path;

            $this->glueUrl();
        }

        function getQuery()
        {
            return $this->_query;
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
            // first, separate all the different parameters
            $reqParams = explode( "&", $this->_query );

            $results = Array();
            foreach( $reqParams as $param ) {
                // now, for every parameter, get rid of the '='
                $parts = explode( "=", $param );
                $var = $parts[0];
                $value = urldecode($parts[1]);

                $results[$var] = $value;
            }

            return $results;
        }

        function setQuery( $query )
        {
            $this->_query = $query;

            $this->glueUrl();
        }

        function getFragment()
        {
            return $this->_fragment;
        }

        function setFragment( $fragment )
        {
            $this->_fragment = $fragment;

            $this->glueUrl();
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
        function glueUrl()
        {
             $uri = $this->_scheme ? $this->_scheme.':'.((strtolower($this->_scheme) == 'mailto') ? '':'//'): '';
             $uri .= $this->_user ? $this->_user.($this->_pass? ':'.$this->_pass:'').'@':'';
             $uri .= $this->_host ? $this->_host : '';
             $uri .= $this->_port ? ':'.$this->_port : '';
             $uri .= $this->_path ? $this->_path : '';
             $uri .= $this->_query ? '?'.$this->_query : '';
             $uri .= $this->_fragment ? '#'.$this->_fragment : '';

            $this->_url = $uri;

            return $uri;
        }
    }
?>
