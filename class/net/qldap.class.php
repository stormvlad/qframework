<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Encapsula el acceso a una base de datos LDAP
     *
     * Esta clase es un simple enmascaramiento para qFramework de las 
     * funciones de LDAP incluidas en PHP.
     * 
     * LDAP es el protocolo de acceso a directorios ligero (Lightweight Directory Access Protocol), 
     * un protocolo usado para acceder a "Servidores de Directorio". El directorio es una clase especial
     * de base de datos que contiene información estructurada en forma de árbol.
     *
     * Mas inormación sobre las funciones LDAP:     
     * - http://es.php.net/ldap
     *
     * Mas información sobre LDAP se puede encontrar en:
     * - <a href="http://developer.netscape.com/tech/directory/" target="_blank">Netscape</a>
     * - <a href="http://www.openldap.org/" target="_blank">OpenLDAP Project</a>
     * - <a href="http://www.innosoft.com/ldapworld/" target="_blank">LDAP World</a>
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 18:00
     * @version 1.0
     * @ingroup net
     */
    class qLdap extends qObject
    {
        var $_host;
        var $_port;
        var $_baseDn;

        var $_curIndex;
        var $_entries;

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
            $this->_host         = $host;
            $this->_port         = $port;
            $this->_baseDn       = null;

            $this->_curIndex     = 0;
            $this->_entries      = array();

            $this->_fp           = null;
        }

        /**
        * Add function info here
        */
        function getHost()
        {
            return $this->_host;
        }

        /**
        * Add function info here
        */
        function getPort()
        {
            return $this->_port;
        }

        /**
        * Add function info here
        */
        function getBaseDn()
        {
            return $this->_baseDn;
        }

        /**
        * Add function info here
        */
        function getEntries()
        {
            return $this->_entries;
        }

        /**
        * Add function info here
        */
        function getEntriesCount()
        {
            return $this->_entries["count"];
        }

        /**
        * Add function info here
        */
        function getCurIndex()
        {
            return $this->_curIndex;
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
        function setPort($port)
        {
            $this->_port = $port;
        }

        /**
        * Specifies the base DN for the directory.
        */
        function setBaseDn($baseDn)
        {
            $this->_baseDn = $baseDn;
        }

        /**
        * Connect to an LDAP server
        * Establishes a connection to a LDAP server on a specified hostname and port.
        */
        function connect()
        {
            return $this->_fp = ldap_connect($this->_host, $this->_port);
        }

        /**
        * Close link to LDAP server
        */
        function close()
        {
            if (!$this->_fp)
            {
                return false;
            }

            return ldap_close($this->_fp);
        }

        /*
        * Binds to the LDAP directory with specified RDN and password.
        *
        * @param username user RDN
        * @param password user password
        * @return boolean Returns TRUE on success or FALSE on failure.
        * @public
        */
        function bind($userName = null, $password = null)
        {
            if (!$this->_fp)
            {
                return false;
            }

            return ldap_bind($this->_fp, $userName, $password);
        }

        /**
        *  Search the active directory and pull the group names
        *    $search = "userPrincipalName=*".$username."*";
        */
        function search($search, $baseDn = null)
        {
            if (!$this->_fp)
            {
                return false;
            }

            if (empty($baseDn))
            {
                $baseDn = $this->_baseDn;
            }

            if ($result = ldap_search($this->_fp, $baseDn, $search))
            {
                $this->_current      = 0;
                $this->_entries      = ldap_get_entries($this->_fp, $result);
            }

            return $result;
        }

        /**
        * Add function info here
        */
        function fetch()
        {
            if (!$this->_fp || $this->_curIndex >= $this->_entries["count"])
            {
                return false;
            }

            $result = array();

            foreach ($this->_entries[$this->_curIndex] as $key => $value)
            {
                if (is_array($value))
                {
                    $values = array();

                    for ($i = 0; $i < $value["count"]; $i++)
                    {
                        $values[$i] = $value[$i];
                    }

                    if (count($values) == 1)
                    {
                        $values = $values[0];
                    }

                    $value = $values;
                }

                if (!is_int($key))
                {
                    $result[$key] = $value;
                }
            }

            unset($result["count"]);
            $this->_curIndex++;

            return $result;
        }

        /**
        * Add function info here
        */
        function delete($dn)
        {
            return ldap_delete($this->_fp, $dn);
        }

        /**
        * Add function info here
        */
        function update($dn, $values)
        {
            return ldap_modify($this->_fp, $dn, $values);
        }

        /**
        * Add function info here
        */
        function add($dn, $values)
        {
            return ldap_add($this->_fp, $dn, $values);
        }

        /**
        * Add function info here
        */
        function rename($oldDn, $newRdn, $newParent = null, $deleteOldRdn = true)
        {
            if (empty($newParent))
            {
                $newParent = $this->_baseDn;
            }

            return ldap_rename($this->_fp, $oldDn, $newRdn, $newParent, $deleteOldRdn);
        }
    }
?>