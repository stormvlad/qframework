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

        /**
        * Add function info here
        */
        function qDao(&$db, $tableName)
        {
            $this->qObject();

            $this->_db        = &$db;
            $this->_tableName = $tableName;
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
            $sql = "SELECT * FROM " . $this->_tableName;

            if (!empty($whereClause))
            {
                $sql .= " WHERE " . $whereClause;
            }

            if (!empty($orderClause))
            {
                $sql .= " ORDER BY " . $orderClause;
            }

            return $this->_retrieve($sql, $offset, $numRows);
        }

        /**
        * Add function here
        */
        function selectCount($whereClause = null)
        {
            $sql = "SELECT COUNT(*) AS totalregs FROM " . $this->_tableName;

            if (!empty($whereClause))
            {
                $sql .= " WHERE " . $whereClause;
            }

            if (!($result = $this->_retrieve($sql)))
            {
                return false;
            }

            if (!($row = $result->FetchRow()))
            {
                return false;
            }

            return $row["totalregs"];
        }

        /**
        * Add function here
        */
        function selectFromId($id)
        {
            $sql = "SELECT * FROM " . $this->_tableName . " WHERE id='" . $id . "'";

            return $this->_retrieve($sql);
        }

        /**
        * Add function here
        */
        function insert($obj)
        {
            $fields = $obj->getFields();

            $sql = "INSERT INTO " . $this->_tableName . " (";

            foreach ($fields as $field => $value)
            {
                $sql .= $field . ", ";
            }

            $sql = substr($sql, 0, -2) . ") VALUES (";

            foreach ($fields as $field => $value)
            {
                $value = qDb::qstr($value);

                if (!empty($value))
                {
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
            $sql    = "UPDATE " . $this->_tableName . " SET ";

            foreach ($fields as $field => $value)
            {
                $value = qDb::qstr($value);

                if (!empty($value))
                {
                    $sql .= $field . "='" . $value . "', ";
                }
                else if ($obj->hasNullValue($field))
                {
                    if ($value === 0 || $value === "0")
                    {
                        $sql .= $field . "='0', ";
                    }
                    else if ($value === "")
                    {
                        $sql .= $field . "='', ";
                    }
                    else if ($value === null)
                    {
                        $sql .= $field . "=NULL, ";
                    }
                }
                else
                {
                    $sql .= $field . "=" . $field . ", ";
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
            $sql = "DELETE FROM " . $this->_tableName . " WHERE " . $this->_getWhereClause($obj);

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
                $sql .= $idField . "='" . $fields[$idField] . "' AND ";
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
            if ($this->isDebug())
            {
                $this->_printSqlQueryDebug($sql);
            }

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
            if ($this->isDebug())
            {
                $this->_printSqlQueryDebug($sql);
            }

            return $result = $this->_db->Execute($sql);
        }

        /**
        * Add function info here
        */
        function execute($sql)
        {
            if ($this->isDebug())
            {
                $this->_printSqlQueryDebug($sql);
            }

            return $this->_db->Execute($sql);
        }

        /**
        * Add function info here
        */
        function getDbObjects($whereClause = null, $orderClause = null, $offset = null, $numRows = null)
        {
            $objClassName = str_replace("dao", "dbobject", $this->getClassName());

            if (!($result = $this->select($whereClause, $orderClause, $offset, $numRows)))
            {
                return false;
            }

            $items = array();

            while ($row = $result->FetchRow())
            {
                $obj = new $objClassName();
                $obj->map($row);
                $items[] = $obj;
            }

            return $items;
        }

        /**
        * Add function info here
        */
        function getDbObject($whereClause = null)
        {
            if (!($items = $this->getDbObjects($whereClause)))
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
            return $this->getDbObjects("id='" . $id . "'");
        }
    }

?>