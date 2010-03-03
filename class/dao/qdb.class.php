<?php
    
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qconfig.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/timer/qtimer.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qclient.class.php");

    /**
     * @brief Libreria de abstracción de la base de datos
     * 
     * Esta clase es un simple enmascaramiento para qFramework de la 
     * libreria ADOdb (Database Abstraction Library for PHP).
     *
     * Se ha elejido esta libreria por sus ventajas:
     * - Diseñada para ser veloz
     * - Proporciona portabilidad 
     * - Fácil de aprender
     * - Calidad del código fuente
     * - Su uso implantado y extenso
     * - Licencia LGPL
     *
     * Mas información:    
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
        var $_quoteName;
        
        /**
         * Constructor
         */
        function qDb(&$db)
        {
            $this->qObject();

            $this->_db         = &$db;
            $this->_queryCount = 0;
            $this->_quoteName  = $db->nameQuote;

            $this->registerEvent(1, "SQL_STATEMENT_EXECUTION");
        }

        /**
         * Devuelve la única instancia de qDb
         *
         * @note Basado en el patrón Singleton. El objectivo de este método es asegurar que exista sólo una instancia de esta clase y proveer de un punto global de accesso a ella.
         * @return qDb
         */
        function &getInstance()
        {
            trigger_error("This function must be implemented by child classes.", E_USER_ERROR);
            return;
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
                "sql"        => preg_replace("/[\t\r\n ]+/", " ", trim($sql)),
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
        
        /**
        * Add function info here
        */
        function getDataProvider()
        {
            return $this->_db->dataProvider;
        }
        
        /**
        * Add function info here
        */
        function getQuoteName()
        {
            return $this->_quoteName;
        }
        
        /**
        * Add function info here
        */
        function setQuoteName($name)
        {
            $this->_quoteName = $name;
        }
        
        /**
        * Add function info here
        */
        function getAffectedRows()
        {
            return $this->_db->Affected_Rows();
        }
        
        /**
        * Add function info here
        */
        function hasTable($tableName)
        {
            $tables = $this->MetaTables();
            return in_array($tableName, $tables);
        }
        
        /**
        * Add function info here
        */
        function truncateTable($table, $resetAutoIncrement = false)
        {
            $result = $this->Execute("DELETE FROM " . $this->_quoteName . $table . $this->_quoteName);
            
            if (empty($result))
            {
                return false;
            }
            
            if (!empty($resetAutoIncrement))
            {
                return $this->resetTableAutoIncrement($table);
            }
            
            return true;
        }
        
        /**
        * Add function info here
        */
        function resetTableAutoIncrement($table)
        {
            return $this->Execute("ALTER TABLE " . $this->_quoteName . $table . $this->_quoteName . " AUTO_INCREMENT=1");
        }
        
        /**
        * Add function info here
        */
        function dropTable($table)
        {
            return $this->Execute("DROP TABLE " . $this->_quoteName . $table . $this->_quoteName);
        }
        
        /**
        * Add function info here
        */
        function renameTable($src, $dst)
        {
            return $this->Execute("RENAME TABLE " . $this->_quoteName . $src . $this->_quoteName . " TO " . $this->_quoteName . $dst . $this->_quoteName);
        }
        
        /**
        * Add function info here
        */
        function replicateTable($src, $dst, $cloneData = true)
        {
            if ($this->Execute("CREATE TABLE " . $this->_quoteName . $dst . $this->_quoteName . " LIKE " . $this->_quoteName . $src . $this->_quoteName))
            {
                if (empty($cloneData))
                {
                    return true;
                }
                
                return $this->Execute("INSERT INTO " . $this->_quoteName . $dst . $this->_quoteName . " SELECT * FROM " . $this->_quoteName . $src . $this->_quoteName);
            }
            
            return false;
        }
        
        /**
        * Add function info here
        */
        function optimizeTable($table)
        {
            $tables = "";
            
            if (is_array($table))
            {
                foreach ($table as $item)
                {
                    $tables .= $this->_quoteName . $item . $this->_quoteName . ", ";
                }
                
                $tables = substr($tables, 0, -2);
            }
            else
            {
                $tables = $this->_quoteName . $table . $this->_quoteName;
            }
            
            return $this->Execute("OPTIMIZE TABLE " . $tables);
        }
    }
?>