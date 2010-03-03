<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfilelistentry.class.php");

    require_once 'Net/FTP.php'; // PEAR include

    define("DEFAULT_FTP_PORT",           21);
    define("DEFAULT_FTP_OVERWRITE",      false);
    define("DEFAULT_FTP_CASE_SENSITIVE", true);
    define("FTP_MODE_ASCII",             FTP_ASCII);
    define("FTP_MODE_BINARY",            FTP_BINARY);

    /**
     * @brief Encapsula el acceso a FTP
     *
     * Esta clase es un simple enmascaramiento para qFramework de la 
     * libreria <a href="http://pear.php.net/package/Net_FTP/">Net_FTP</a> 
     * de <a href="http://pear.php.net/">PEAR</a>.
     * 
     * qFtp nos permite comunicarnos con servidores FTP de una forma más comoda
     * que con las funciones nativas en PHP y añade características como recursividad
     * en subida y bajada, creacion de directorios y cambio de permisos. 
     * 
     * Mas información:
     * - http://pear.php.net/package/Net_FTP/
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 16:19
     * @version 1.0
     * @ingroup net
     * @note También implementa el patrón de diseño Observer para permitir por ejemplo 
     *       una barra de progreso. (caracteristica aún sin adaptar)
     */
    class qFtp extends qObject
    {
        var $_ftp;
        var $_isConnected;
        var $_isLogged;

        /**
        *  Add function info here
        */
        function qFtp($server, $port = DEFAULT_FTP_PORT)
        {
            $this->qObject();
            $this->_ftp         = new Net_FTP($server, $port);
            $this->_isConnected = false;
            $this->_isLogged    = false;
        }

        /**
        *  Add function info here
        */
        function connect()
        {
            $this->_isConnected = ($this->_ftp->connect() === true);
            return $this->_isConnected;
        }

        /**
        *  Add function info here
        */
        function disconnect()
        {
            $this->_isConnected = false;
            $this->_isLogged    = false;

            $this->_ftp->disconnect();
        }

        /**
        *  Add function info here
        */
        function isConnected()
        {
            return $this->_isConnected;
        }

        /**
        *  Add function info here
        */
        function login($userName, $pass)
        {
            $this->_isLogged = ($this->_ftp->login($userName, $pass) === true);
            return $this->_isLogged;
        }

        /**
        *  Add function info here
        */
        function getMode()
        {
            return $this->_ftp->getMode();
        }

        /**
        *  Add function info here
        */
        function setMode($mode)
        {
            return ($this->_ftp->setMode($mode) === true);
        }

        /**
        *  Add function info here
        */
        function isPassive()
        {
            return $this->_ftp->isPassive();
        }

        /**
        *  Add function info here
        */
        function setPassive($passive)
        {
            if ($passive)
            {
                $this->_ftp->setPassive();
            }
            else
            {
                $this->_ftp->setActive();
            }
        }

        /**
        *  Add function info here
        */
        function cd($dir)
        {
            $result = ($this->_ftp->cd($dir) === true);

            return $result;
        }

        /**
        *  Add function info here
        */
        function get($remoteName, $localName, $overwrite = DEFAULT_FTP_OVERWRITE)
        {
            if (substr($remoteName, -1) == "/")
            {
                return ($this->_ftp->getRecursive($remoteName, $localName, $overwrite) === true);
            }
            else
            {
                return ($this->_ftp->get($remoteName, $localName, $overwrite) === true);
            }
        }

        /**
        *  Add function info here
        */
        function put($localName, $remoteName, $overwrite = DEFAULT_FTP_OVERWRITE)
        {
            if (substr($localName, -1) == "/")
            {
                $result = ($this->_ftp->putRecursive($localName, $remoteName, $overwrite) === true);
            }
            else
            {
                $result = ($this->_ftp->put($localName, $remoteName, $overwrite) === true);
            }

            return $result;
        }

        /**
        *  Add function info here
        */
        function chmod($remoteName, $permissions)
        {
            if (substr($remoteName, -1) == "/")
            {
                return ($this->_ftp->chmodRecursive($remoteName, $permissions) === true);
            }
            else
            {
                return ($this->_ftp->chmod($remoteName, $permissions) === true);
            }
        }

        /**
        *  Add function info here
        */
        function mkdir($dir)
        {
            return ($this->_ftp->mkdir($dir, false) === true);
        }

        /**
        *  Add function info here
        */
        function pwd()
        {
            return ($this->_ftp->pwd() === true);
        }

        /**
        *  Add function info here
        */
        function rename($oldName, $newName)
        {
            return ($this->_ftp->rename($oldName, $newName) === true);
        }

        /**
        *  Add function info here
        */
        function rm($name)
        {
            if (substr($name, -1) == "/")
            {
                return ($this->_ftp->rm($name, true) === true);
            }
            else
            {
                return ($this->_ftp->rm($name) === true);
            }
        }

        /**
        *  Add function info here
        */
        function execute($command)
        {
            return ($this->_ftp->execute($command) === true);
        }

        /**
        *  Add function info here
        */
        function ls($dir = null, $pattern = null, $caseSensitive = DEFAULT_FTP_CASE_SENSITIVE)
        {
            $entries = $this->_ftp->ls($dir);

            if (is_object($entries))
            {
                return false;
            }

            $result = array();

            foreach ($entries as $entry)
            {
                $add = false;

                if (!empty($pattern))
                {
                    $pattern = "/" . $pattern . "/";
                    
                    if ((preg_match($pattern, $entry["name"]) && $caseSensitive) || (preg_match($pattern . "i", $entry["name"]) && !$caseSensitive))
                    {
                        $add = true;
                    }
                }
                else
                {
                    $add = true;
                }

                if ($add)
                {
                    array_push($result, new qFileListEntry($entry["name"], $entry["size"], $entry["rights"], $entry["user"], $entry["group"], $entry["stamp"], $entry["is_dir"]));
                }
            }

            return $result;
        }
    }
?>
