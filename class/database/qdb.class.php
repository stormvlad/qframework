<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/libs/adodb/adodb.inc.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qconfig.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/timer/qtimer.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qclient.class.php");

    /**
     * Provides a singleton for accessing the db.
     */
    class qDb extends qObject
    {
        var $_db;
        var $_queryCount;
        var $_eventName;

        /**
        * Add function info here
        */
        function qDb(&$db)
        {
            $this->qObject();
            $this->_db         = &$db;
            $this->_queryCount = 0;
            $this->_eventName  = strtoupper($this->getClassName()) . "_SQL_QUERY_EVENT";

            $this->registerEvent($this->_eventName);
        }

        /**
        * Add function info here
        */
        function &getInstance()
        {
            throw(new qException("qDb::getDb: This function must be implemented by child classes."));
            die();
        }

        /**
        * Add function info here
        */
        function sendQueryEvent($sql, $seconds)
        {
            $this->_queryCount++;

            $server = &qHttp::getServerVars();
            $params = array(
                "ip"         => qClient::getIp(),
                "class"      => $this->getClassName(),
                "script"     => basename($server->getValue("PHP_SELF")),
                "uri"        => $server->getValue("REQUEST_URI"),
                "queryCount" => $this->_queryCount,
                "sql"        => $sql,
                "time"       => $seconds);

            $this->sendEvent($this->_eventName, $params);
        }

        /**
        * Add function info here
        */
        function SelectLimit($sql, $numrows = -1, $offset = -1, $inputarr = false)
        {
            $timer   = new qTimer();
            $result  = $this->_db->SelectLimit($sql, $numrows, $offset, $inputarr);
            $seconds = $timer->get();

            $this->sendQueryEvent($sql, $seconds);

            return $result;
        }

        /**
        * Add function info here
        */
        function Execute($sql, $inputarr = false)
        {
            $timer   = new qTimer();
            $result  = $this->_db->Execute($sql, $inputarr);
            $seconds = $timer->get();

            $this->sendQueryEvent($sql, $seconds);

            return $result;
        }

        /**
        * Add function info here
        */
        function MetaTables($ttype = false, $showSchema = false, $mask = false)
        {
            return $this->_db->MetaTables($ttype, $showSchema, $mask);
        }

        /**
        * Add function info here
        */
        function MetaColumnNames($table, $numericIndex = false)
        {
            return $this->_db->MetaColumnNames($table, $numericIndex);
        }

        /**
        * Add function info here
        */
        function MetaPrimaryKeys($table, $owner = false)
        {
            return $this->_db->MetaPrimaryKeys($table, $owner);
        }

        /**
        * Prepares a string for an SQL query by escaping apostrophe
        * characters. If the PHP configuration setting 'magic_quotes_gpc'
        * is set to ON, it will first strip the added slashes. Apostrophe
        * characters are doubled, conforming with the ANSI SQL standard.
        * The SQL parser makes sure that the escape token is not entered
        * in the database so there is no need to modify the data when it
        * is read from the database.
        *
        * @param  string $string
        * @return string
        * @access public
        */
        function qstr($string)
        {
            if (get_magic_quotes_gpc())
            {
                $string = stripslashes($string);
            }

            return str_replace("'", "''", $string);
        }
    }
?>