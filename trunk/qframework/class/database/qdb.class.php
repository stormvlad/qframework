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

        /**
        * Add function info here
        */
        function qDb(&$db)
        {
            $this->qObject();
            $this->_db = &$db;

            overload("qdb");

            if ($this->getClassName() != "qdb")
            {
                overload($this->getClassName());
            }

            $this->registerEvent("DB_SQL_QUERY_EVENT");
        }

        /**
        * Add function info here
        */
        function &getDb()
        {
            throw(new qException("qDb::getDb: This function must be implemented by child classes."));
            die();
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

        /**
        * Add function info here
        */
        function __call($method, $args, &$return)
        {
            if ($method == "execute" || $method == "selectlimit")
            {
                $t = new qTimer();
                $return = call_user_func_array(array(&$this->_db, $method), $args);
                $t->stop();

                $params = array(
                    "ip"    => qClient::getIp(),
                    "class" => $this->getClassName(),
                    "sql"   => $args[0],
                    "time"  => $t->get());

                $this->sendEvent("DB_SQL_QUERY_EVENT", $params);
            }
            else
            {
                $return = call_user_func_array(array(&$this->_db, $method), $args);
            }

            return true;
        }
    }
?>