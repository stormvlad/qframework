<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfilelistentry.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/ftp/FTP.php");

    define(DEFAULT_FTP_PORT,           21);
    define(DEFAULT_FTP_OVERWRITE,      false);
    define(DEFAULT_FTP_CASE_SENSITIVE, true);
    define(FTP_MODE_ASCII,             FTP_ASCII);
    define(FTP_MODE_BINARY,            FTP_BINARY);

    /**
     * HttpVars compatibility package, which allows to fetch some of php's basic
     * global variables without having to worry about which version of php we're using.
     * The problem here is that since PHP 4.1.0 things like $_REQUEST, $_POST, $_GET, etc
     * are available, and before that their equivalents were $HTTP_GET_VARS,
     * $HTTP_POST_VARS and so on. By using this package and calling the functions
     * getPostVars, getGetVars, getSessionVars/setSessionVars we will get rid of any
     * incompatibility with the version of php we are running while having access to the
     * variables we most need.
     */
    class qFtp extends qObject
    {
        var $_ftp;

        /**
        *  Add function info here
        */
        function qFtp($server, $port = DEFAULT_FTP_PORT)
        {
            $this->qObject();
            $this->_ftp = new Net_FTP($server, $port);
        }

        /**
        *  Add function info here
        */
        function connect()
        {
            return ($this->_ftp->connect() === true);
        }

        /**
        *  Add function info here
        */
        function disconnect()
        {
            $this->_ftp->disconnect();
        }

        /**
        *  Add function info here
        */
        function login($userName, $pass)
        {
            return ($this->_ftp->login($userName, $pass) === true);
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
            $oldErrorHandler = set_error_handler("_internalErrorHandlerDummy");
            $result          = ($this->_ftp->cd($dir) === true);
            set_error_handler($oldErrorHandler);

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
            $oldErrorHandler = set_error_handler("_internalErrorHandlerDummy");

            if (substr($localName, -1) == "/")
            {
                $result = ($this->_ftp->putRecursive($localName, $remoteName, $overwrite) === true);
            }
            else
            {
                $result = ($this->_ftp->put($localName, $remoteName, $overwrite) === true);
            }

            set_error_handler($oldErrorHandler);
            return $result;
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
                    if ((ereg($pattern, $entry["name"]) && $caseSensitive) || (eregi($pattern, $entry["name"]) && !$caseSensitive))
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
