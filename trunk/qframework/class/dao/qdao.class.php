<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/database/qdb.class.php");

    /**
    *  Base class for data access objects
    */
    class qDao extends qObject
    {
        var $_db;
        var $_tableName;
        var $_clauses;

        /**
        * Add function info here
        */
        function qDao(&$db, $tableName)
        {
            $this->qObject();

            $this->_db        = &$db;
            $this->_tableName = $tableName;
            $this->_clauses   = array();

            $this->setClause("SELECT", "*");
            $this->setClause("FROM", "`" . $this->_tableName . "`");
        }

        /**
        * Add function info here
        */
        function getClause($name)
        {
            $key = strtoupper($name);

            if (empty($this->_clauses[$key]))
            {
                return false;
            }

            return $this->_clauses[$key];
        }

        /**
        * Add function info here
        */
        function setClause($name, $value)
        {
            $this->_clauses[strtoupper($name)] = $value;
        }

        /**
        * Add function info here
        */
        function resetClauses()
        {
            $this->_clauses = array();
        }

        /**
        * Add function info here
        */
        function getTableName()
        {
            return $this->_tableName;
        }

        /**
        * Add function info here
        */
        function &getDb()
        {
            return $this->_db;
        }

        /**
        * Add function info here
        */
        function setTableName($name)
        {
            $this->_tableName = $name;
        }

        /**
        * Add function info here
        */
        function setDb(&$db)
        {
            $this->_db = &$db;
        }

        /**
        * Add function here
        */
        function select($whereClause = null, $orderClause = null, $offset = null, $numRows = null)
        {
            $sql = "SELECT " . $this->getClause("SELECT") . " FROM " . $this->getClause("FROM");

            if (!empty($whereClause))
            {
                $sql .= " WHERE " . $whereClause;
            }
            else if ($this->getClause("WHERE"))
            {
                $sql .= " WHERE " . $this->getClause("WHERE");
            }

            if ($this->getClause("GROUP BY"))
            {
                $sql .= " GROUP BY " . $this->getClause("GROUP BY");
            }

            if ($this->getClause("HAVING"))
            {
                $sql .= " HAVING " . $this->getClause("HAVING");
            }

            if (!empty($orderClause))
            {
                $sql .= " ORDER BY " . $orderClause;
            }
            else if ($this->getClause("ORDER BY"))
            {
                $sql .= " ORDER BY " . $this->getClause("ORDER BY");
            }

            return $this->_retrieve($sql, $offset, $numRows);
        }

        /**
        * Add function here
        */
        function selectCount($whereClause = null)
        {
            if (!($result = $this->select($whereClause)))
            {
                return false;
            }

            return $result->RecordCount();
        }

        /**
        * Add function here
        */
        function insert($obj)
        {
            $fields = $obj->getFields();

            $sql = "INSERT INTO `" . $this->_tableName . "` (";

            foreach ($fields as $field => $value)
            {
                $sql .= "`" . $field . "`, ";
            }

            $sql = substr($sql, 0, -2) . ") VALUES (";

            foreach ($fields as $field => $value)
            {
                if (!empty($value))
                {
                    $value = qDb::qstr($value);

                    $sql .= "'" . $value . "', ";
                }
                else
                {
                    if ($value === 0 || $value === "0")
                    {
                        $sql .= "'0', ";
                    }
                    else if ($value === "")
                    {
                        $sql .= "'', ";
                    }
                    else if ($value === null)
                    {
                        $sql .= "NULL, ";
                    }
                }
            }

            $sql = substr($sql, 0, -2) . ")";

            if ($this->_update($sql))
            {
                return $this->_db->Insert_ID();
            }

            return false;
        }

        /**
        * Add function here
        */
        function update($obj)
        {
            $fields = $obj->getFields();
            $sql    = "UPDATE `" . $this->_tableName . "` SET ";

            foreach ($fields as $field => $value)
            {
                if (!empty($value))
                {
                    $value = qDb::qstr($value);
                    $sql  .= "`" . $field . "`='" . $value . "', ";
                }
                else if ($obj->hasNullValue($field))
                {
                    if ($value === 0 || $value === "0")
                    {
                        $sql .= "`" . $field . "`='0', ";
                    }
                    else if ($value === "")
                    {
                        $sql .= "`" . $field . "`='', ";
                    }
                    else if ($value === null)
                    {
                        $sql .= "`" . $field . "`=NULL, ";
                    }
                }
                else
                {
                    $sql .= "`" . $field . "`='" . $value . "', ";
                }
            }

            $sql = substr($sql, 0, -2) . " WHERE " . $this->_getWhereClause($obj);

            return $this->_update($sql);
        }

        /**
        * Add function here
        */
        function delete($obj)
        {
            $sql = "DELETE FROM `" . $this->_tableName . "` WHERE " . $this->_getWhereClause($obj);
            return $this->_update($sql);
        }

        /**
        * Add function here
        */
        function _getWhereClause($obj)
        {
            $fields   = $obj->getFields();
            $idFields = $obj->getIdFields();
            $sql      = "";

            foreach ($idFields as $idField)
            {
                $sql .= "`" . $idField . "`='" . $fields[$idField] . "' AND ";
            }

            return substr($sql, 0, -5);
        }

        /**
        * Add function info here
        */
        function _printSqlQueryDebug($sql)
        {
            print "<pre>"; print $sql; print "</pre>";
        }

        /**
        * Add function info here
        */
        function _retrieve($sql, $offset = null, $numRows = null)
        {
            if (empty($offset))
            {
                $offset = -1;
            }

            if (empty($numRows))
            {
                $numRows = -1;
            }

            $result = $this->_db->SelectLimit($sql, $numRows, $offset);

            if (!$result)
            {
                return false;
            }

            return $result;
        }

        /**
        * Add function info here
        */
        function _update($sql)
        {
            return $this->_db->Execute($sql);
        }

        /**
        * Add function info here
        */
        function execute($sql)
        {
            return $this->_db->Execute($sql);
        }

        /**
        * Returns an instance of DbObject class
        *
        */
        function getDbObjectClass()
        {
            $objClassName = str_replace("dao", "dbobject", $this->getClassName()); // PHP4
            $objClassName = str_replace("Dao", "DbObject", $objClassName); // PHP5

            return new $objClassName();
        }

        /**
        * Add function info here
        */
        function getDbObjects($whereClause = null, $orderClause = null, $offset = null, $numRows = null)
        {
            if (!($result = $this->select($whereClause, $orderClause, $offset, $numRows)))
            {
                return false;
            }

            $items = array();

            while ($row = $result->FetchRow())
            {
                $obj = $this->getDbObjectClass();
                $obj->map($row);
                $items[] = $obj;
            }

            return $items;
        }

        /**
        * Add function info here
        */
        function getDbObject($whereClause = null, $orderClause = null)
        {
            if (!($items = $this->getDbObjects($whereClause, $orderClause)))
            {
                return false;
            }

            return $items[0];
        }

        /**
        * Add function info here
        */
        function getAll()
        {
            return $this->getDbObjects();
        }

        /**
        * Add function info here
        */
        function getFromId($id)
        {
            return $this->getDbObject("id='" . $id . "'");
        }

        /**
        * Add function here
        */
        function doCount($whereClause = null)
        {
            $sql = "SELECT COUNT(" . $this->getClause("SELECT") . ") AS total FROM " . $this->getClause("FROM");

            if (!empty($whereClause))
            {
                $sql .= " WHERE " . $whereClause;
            }
            else if ($this->getClause("WHERE"))
            {
                $sql .= " WHERE " . $this->getClause("WHERE");
            }

            if ($this->getClause("GROUP BY"))
            {
                $sql .= " GROUP BY " . $this->getClause("GROUP BY");
            }

            if ($this->getClause("HAVING"))
            {
                $sql .= " HAVING " . $this->getClause("HAVING");
            }

            if (!($result = $this->_retrieve($sql)))
            {
                return false;
            }

            $row = $result->FetchRow();

            if (!isset($row["total"]))
            {
                return false;
            }

            return $row["total"];
        }

        function doDelete($whereClause = null)
        {
            $sql = "DELETE FROM `" . $this->_tableName . "`";

            if (!empty($whereClause))
            {
                $sql .= " WHERE " . $whereClause;
            }
            else if ($this->getClause("WHERE"))
            {
                $sql .= " WHERE " . $this->getClause("WHERE");
            }

            return $this->_update($sql);
        }

        /*
         * Retrieve a single object by pkey.
         *
         * @param mixed $pk
         */
        function retrieveByPK($pk)
        {
            $obj      = $this->getDbObjectClass();
            $pkFields = $obj->getPrimaryKeyFields();

            return $this->getDbObject($pkFields[0] . "='" . $pk . "'");
        }

        /*
         * Retrieve a single object by primary keys.
         *
         * @param mixed $pk
         */
        function retrieveByPKs($pks)
        {
            $obj      = $this->getDbObjectClass();
            $pkFields = $obj->getPrimaryKeyFields();

            $clause   = "1=1";
            foreach($pks as $field => $value)
            {
                if (in_array($field, $pkFields))
                {
                    $clause = $clause . " AND " . $field . "='" . $value . "' ";
                }
            }

            return $this->getDbObject($clause);
        }
    }

?>