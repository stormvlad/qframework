<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
    * qLdap class to a LDAP server database.
    *
    * LDAP is the Lightweight Directory Access Protocol, and is a protocol used to access "Directory Servers".
    * The Directory is a special kind of database that holds information in a tree structure.
    *
    * LDAP support in PHP is not enabled by default. You will need to compile PHP with LDAP support.
    */
    class qLdap extends qObject
    {
        var $_host;
        var $_port;
        var $_fp;

        /**
        * Constructor
        *
        * @param host Establishes the hostname of a LDAP server
        * @param port Establishes the port of a LDAP server defaults to 389
        * @return  void
        */
        function qLdap($host = "localhost", $port = 389)
        {
            $this->setHost($host);
            $this->setPort($port);
        }

        /**
        * Establishes the hostname of a LDAP server.
        * To use LDAP with SSL, compile OpenLDAP 2.x.x with SSL support, configure PHP with SSL,
        * and use ldaps://hostname/ as host parameter.
        *
        * @param host string with hostname of a LDAP server
        */
        function setHost($host)
        {
            $this->_host = $host;
        }

        /**
        * Establishes the port of a LDAP server defaults to 389.
        *
        * @param port integer with port of a LDAP server
        */
        function setPort($port = 389)
        {
            $this->_port = $port;
        }

        /**
        * Specifies the base DN for the directory.
        */
        function setBaseDn($basedn)
        {
            $this->_basedn = $basedn;
        }

        /**
        * Connect to an LDAP server
        * Establishes a connection to a LDAP server on a specified hostname and port.
        */
        function connect()
        {
            // open the connection
            $this->_fp = ldap_connect($this->_host, $this->_port);

            // if no connection, return false
            if (!$this->_fp)
            {
                return false;
            }

            return true;
        }

        /**
        * Close link to LDAP server
        */
        function close()
        {
            if ($this->_fp)
            {
                ldap_close($this->_fp);
            }
        }

        /*
        * Binds to the LDAP directory with specified RDN and password.
        *
        * @param username user RDN
        * @param password user password
        * @return boolean Returns TRUE on success or FALSE on failure.
        * @access public
        */
        function bind($username, $password)
        {
            $bind = @ldap_bind($this->_fp, $username, $password);

            return $bind;
        }

        /**
        *  Search the active directory and pull the group names
        *    $search = "userPrincipalName=*".$username."*";
        */
        function search ($search, $basedn = "")
        {
            if (!$this->_fp)
            {
                return false;
            }

            if (!$basedn)
            {
                $basedn = $this->_basedn;
            }

            // Perform Search
            $sr = ldap_search($this->_fp, $basedn, $search);

            // no matches, bad bad bad
            if (!ldap_count_entries($this->_fp, $sr))
            {
                return false;
            }

            // get the info
            $info = ldap_get_entries($this->_fp, $sr);

            // make it a bit cleaner
            $info = $info[0];

            // if its a Person category, set that as their full name
            if (preg_match('/CN=Person/i', $info['objectcategory'][0]))
            {
                $loginname = $info['name'][0];
            }

            // loop through and pull out all the groups, recursive
            if (is_array($info['memberof']))
            {
                foreach ($info['memberof'] as $var)
                {
                    if (preg_match('/CN=([^,]*?),/i', $var, $m))
                    {
                        $groups[]   = $m[1];
                        $moregroups = $this->search('name=*'.$m[1].'*', $basedn);

                        if (is_array($moregroups))
                        {
                            foreach ($moregroups as $v)
                            {
                                $groups[] = $v;
                            }
                        }
                    }
                }
            }

            // nice stuff.
            if (isset($loginname))
            {
                $groups["loginname"] = $loginname;
            }

            return $groups;
        }
    }
?>
