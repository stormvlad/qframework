<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/libs/adodblite/adodb.inc.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qconfig.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/timer/qtimer.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qclient.class.php");

    /**
     * @brief Libreria de abstracci�n de la base de datos
     * 
     * Esta clase es un simple enmascaramiento para qFramework de la 
     * libreria ADOdb (Database Abstraction Library for PHP).
     *
     * Se ha elejido esta libreria por sus ventajas:
     * - Dise�ada para ser veloz
     * - Proporciona portabilidad 
     * - F�cil de aprender
     * - Calidad del c�digo fuente
     * - Su uso implantado y extenso
     * - Licencia LGPL
     *
     * Mas informaci�n:    
     * http://adodb.sourceforge.net/
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:36
     * @version 1.0
     * @ingroup dao
     * @see qDao Se usa conjuntamnte con los objetos de acceso a datos
     */
    class qDb extends qObject
    {
        var $_db;
        var $_queryCount;

        /**
         * Constructor
         */
        function qDb(&$db)
        {
            $this->qObject();

            $this->_db         = &$db;
            $this->_queryCount = 0;

            $this->registerEvent(1, "SQL_STATEMENT_EXECUTION");
        }

        /**
         * Devuelve la �nica instancia de qDb
         *
         * @note Basado en el patr�n Singleton. El objectivo de este m�todo es asegurar que exista s�lo una instancia de esta clase y proveer de un punto global de accesso a ella.
         * @return qDb
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
                "sql"        => ereg_replace("[\t\r\n ]+", " ", trim($sql)),
                "time"       => $seconds);

            $this->sendEvent(1, $params);
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
        * Add function info here
        */
        function ErrorNo()
        {
            return $this->_db->ErrorNo();
        }

        /**
        * Add function info here
        */
        function ErrorMsg()
        {
            return $this->_db->ErrorMsg();
        }

        /**
        * Add function info here
        */
        function Insert_ID()
        {
            return $this->_db->Insert_ID();
        }

        /**
        * Add function info here
        */
        function GetOne($sql, $inputarr = false)
        {
            $prevMode = $this->_db->SetFetchMode(ADODB_FETCH_NUM);
            $timer    = new qTimer();
            $result   = $this->_db->Execute($sql, $inputarr);
            $seconds  = $timer->get();

            $this->sendQueryEvent($sql, $seconds);

            $this->_db->SetFetchMode($prevMode);
            return $result->fields[0];
        }

        /**
        * Add function info here
        */
        function &GetRow($sql, $inputarr = false)
        {
            $timer   = new qTimer();
            $result  = $this->_db->Execute($sql, $inputarr);
            $seconds = $timer->get();

            $this->sendQueryEvent($sql, $seconds);

            return $result->fields;
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
        * @public
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