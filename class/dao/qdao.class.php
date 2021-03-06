<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/dao/qdb.class.php");

    /**
     * @defgroup dao Objetos de acceso a datos
     *
     * qFramework dispone de una capa de persist�ncia de objetos i herramientas de consulta,
     * la capa est� formada por el grupo de clases <b>Dao</b>.
     *
     * Esto significa que qFramework permite tratar la base de dados como un conjunto de objetos,
     * disponiendo de una API senzilla para consultar y guardar los datos. Este tipo de herramientas
     * se conocen como Object Relational Mapping (ORM) y Data Access Objects (DAO).
     *
     * Mas informaci�n:
     * - Data Access Object Pattern - http://www.phppatterns.com/index.php/article/articleview/25/1/1/
     * - Catalog of Patterns of Enterprise Application Architecture - http://www.martinfowler.com/eaaCatalog/
     * - Core J2EE Patterns - Data Access Object - http://java.sun.com/blueprints/corej2eepatterns/Patterns/DataAccessObject.html
     *
     */

    /**
     * @brief Clase base para objetos de acceso a datos
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:36
     * @version 1.0
     * @ingroup dao
     * @see qDaoGenerator Estas clases pueden generarse autom�ticamente
     */
     class qDao extends qObject
     {
        /**
         * qDb instancia a la base de datos
         */
        var $_db;

        /**
         * Cadena con el nombre de la tabla en la base de datos
         */
        var $_tableName;

        /**
         * Caracter para escapar los nombres de campo
         */
        var $_quoteName;

        /**
         * Array asociativo con las condiciones de consulta
         */
        var $_clauses;

        /**
         * Constructor
         *
         * @param $db        qDb    Instancia a la base de datos
         * @param $tableName string Nombre de la tabla en la base de datos
         */
        function qDao(&$db, $tableName)
        {
            $this->qObject();

            $this->_db        = &$db;
            $this->_tableName = $tableName;
            $this->_clauses   = array();
            $this->_quoteName = $db->_db->nameQuote;

            $this->setClause("SELECT", "*");
            $this->setClause("FROM", $this->getQuotedTableName());
        }

        /**
        * Add function info here
        */
        function isMsSql()
        {
            return $this->_db->isMsSql();
        }
        
        /**
        * Add function info here
        */
        function isMySql()
        {
            return $this->_db->isMySql();
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
         * Devuelve el valor actual de una cl�usula
         *
         * @param $name Nombre de la cl�usula
         * @return Cadena con el valor de la cl�usula, si existe,
         *         en otro caso, NULL
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
         * Establece una cl�usula para las siguientes sentencias
         *
         * @param $name Nombre de la cl�usula.
         *              Valores possibles: SELECT, FROM, WHERE, GROUP BY, ORDER BY, HAVING
         * @param $value Cadena con el contenido de la cl�usula (condici�n)
         */
        function setClause($name, $value)
        {
            $this->_clauses[strtoupper($name)] = $value;
        }

        /**
         * Borra todas las cl�usula establecidas
         */
        function resetClauses()
        {
            $this->_clauses = array();
        }

        /**
         * Devuelve el nombre de la tabla actual en la base de datos
         *
         * @return string Nombre de la tabla
         */
        function getQuotedTableName()
        {
            if (strpos($this->_tableName, ".") !== false && $this->isMySql())
            {
                return str_replace(".", "." . $this->_quoteName, $this->_tableName) . $this->_quoteName;
            }
            
            return $this->_quoteName . $this->_tableName . $this->_quoteName;
        }
        
        /**
         * Devuelve el nombre de la tabla actual en la base de datos
         *
         * @return string Nombre de la tabla
         */
        function getTableName()
        {
            return $this->_tableName;
        }

        /**
         * Devuelve la instancia de la base de datos
         *
         * @return qDb Referencia a la base de datos
         */
        function &getDb()
        {
            return $this->_db;
        }

        /**
         * Establece el nombre de la tabla en la base de datos
         *
         * @param $name string Nombre de la tabla
         */
        function setTableName($name)
        {
            $this->_tableName = $name;
        }

        /**
         * Establece la instancia de la base de datos
         *
         * @param $db qDb Referencia a la base de datos
         */
        function setDb(&$db)
        {
            $this->_db = &$db;
        }

        /**
         * Devuelve los registros para una sentencia de consulta
         *
         * @param $whereClause string  Cl�usula con la condici�n de b�squeda
         * @param $orderClause string  Cl�usula con la expressi�n de ordenaci�n
         * @param $offset      integer Desplazamiento inicial en los registros resultantes
         * @param $numRows     integer N�mero m�ximo de filas a devolver
         *
         * @return ADOdb::ResultSet Filas consultadas
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
         * Devuelve el n�mero de registros para una sentencia de consulta
         *
         * @param $whereClause string  Cl�usula con la condici�n de b�squeda
         *
         * @return integer N�mero de filas
         */
        function selectCount($whereClause = null)
        {
            if (!($result = $this->select($whereClause)))
            {
                return false;
            }

            $val = $result->RecordCount();
            $result->Close();
            
            return $val;
        }

        /**
         * Devuelve la primera parte de una SQL query para insertar el objecto
         * (parte que contiene el nombre de los campos y hasta el 'VALUES')
         *
         * @param $obj qDbObject Objeto del tipo de la tabla
         *
         * @return string parte de una SQL query de inserci�n
         */
        function getInsertSqlQueryPart1($obj)
        {
            $fields = $obj->getFields();
            $sql    = "INSERT INTO " . $this->getQuotedTableName() . " (";

            foreach ($fields as $field => $value)
            {
                if($value !== null || !$this->isPrimaryKey($field))
                {
                    $sql .= $this->_quoteName . $field . $this->_quoteName . ", ";
                }
            }

            $sql = substr($sql, 0, -2) . ") VALUES";
            return $sql;
        }
        
        /**
         * Devuelve la segunda parte de una SQL query para insertar el objecto
         * (parte que contiene los valores de los campos)
         *
         * @param $obj qDbObject Objeto del tipo de la tabla
         *
         * @return string parte de una SQL query de inserci�n
         */
        function getInsertSqlQueryPart2($obj)
        {
            $fields = $obj->getFields();
            $sql    = "(";

            foreach ($fields as $field => $value)
            {
                if (!empty($value))
                {
                    $value = qDb::qstr($value);
                    $sql .= "'" . $value . "', ";
                }
                else
                {
                    if ($value === 0 || $value === "0" || $value === 0.0)
                    {
                        $sql .= "'0', ";
                    }
                    else if ($value === "")
                    {
                        $sql .= "'', ";
                    }
                    else if ($value === null && !$this->isPrimaryKey($field))
                    {
                        $sql .= "NULL, ";
                    }
                    else if( !$this->isPrimaryKey($field))
                    {
                        $sql .= "'', ";
                    }
                }
            }

            $sql = substr($sql, 0, -2) . ")";
            return $sql;
        }
        
        /**
         * Devuelve la SQL query para insertar el objecto
         *
         * @param $obj qDbObject Objeto del tipo de la tabla
         *
         * @return string SQL query de inserci�n
         */
        function getInsertSqlQuery($obj)
        {
            return $this->getInsertSqlQueryPart1($obj) . " " . $this->getInsertSqlQueryPart2($obj);
        }
        
        /**
         * Inserta un objeto en la base de datos
         *
         * @param $obj qDbObject Objeto del tipo de la tabla
         *
         * @return integer Devuelve el identificador del nuevo registro, si se
         *                 ha insertado con �xito, sino devuelve FALSE
         */
        function insert($obj)
        {
            $sql = $this->getInsertSqlQuery($obj);

            if ($this->execute($sql))
            {
                return $this->_db->Insert_ID();
            }

            return false;
        }

        /**
         * Modifica un objeto en la base de datos
         *
         * Los campos identificadores se usaran para la sentencia de modificaci�n.
         *
         * @param $obj qDbObject Objeto del tipo de la tabla
         *
         * @return integer Devuelve el n�mero de filas modificadas, si se
         *                 han modificado con �xito, sino devuelve FALSE
         */
        function update($obj)
        {
            $fields   = $obj->getFields();
            $idFields = $obj->getIdFields();
            $sql      = "UPDATE " . $this->getQuotedTableName() . " SET ";

            foreach ($fields as $field => $value)
            {
                if (!in_array($field, $idFields))
                {
                    if (!empty($value))
                    {
                        $value = qDb::qstr($value);
                        $sql  .= $this->_quoteName . $field . $this->_quoteName . "='" . $value . "', ";
                    }
                    else if ($obj->hasNullValue($field))
                    {
                        if ($value === 0 || $value === "0")
                        {
                            $sql .= $this->_quoteName . $field . $this->_quoteName . "='0', ";
                        }
                        else if ($value === "")
                        {
                            $sql .= $this->_quoteName . $field . $this->_quoteName . "='', ";
                        }
                        else if ($value === null)
                        {
                            $sql .= $this->_quoteName . $field . $this->_quoteName . "=NULL, ";
                        }
                    }
                    else
                    {
                        //$sql .= $this->_quoteName . $field . $this->_quoteName . "='" . $value . "', ";
                    }
                }
            }

            $sql = substr($sql, 0, -2) . " WHERE " . $this->_getWhereClause($obj);

            return $this->execute($sql);
        }

        /**
         * Borra un objeto de la base de datos
         *
         * @param $obj qDbObject Objeto del tipo de la tabla
         *
         * @return integer Devuelve el n�mero de filas borradas, si
         *                 ha habido alguna, sino devuelve FALSE
         */
        function delete($obj)
        {
            $sql = "DELETE FROM " . $this->getQuotedTableName() . " WHERE " . $this->_getWhereClause($obj);
            return $this->execute($sql);
        }

        /**
         * Devuelve la cl�usula de con la condici�n de b�squeda
         *
         * @param $obj qDbObject Objeto del tipo de la tabla
         * @return string Cadena con la condici�n de b�squeda
         * @private
         */
        function _getWhereClause($obj)
        {
            $fields   = $obj->getFields();
            $idFields = $obj->getIdFields();
            $sql      = "";

            foreach ($idFields as $idField)
            {
                $sql .= $this->_quoteName . $idField . $this->_quoteName . "='" . $fields[$idField] . "' AND ";
            }

            return substr($sql, 0, -5);
        }

        /**
         * Escribe en la salida estandard una sentencia SQL.
         *
         * @param $sql cadena Sentencia SQL
         * @private
         */
        function _printSqlQueryDebug($sql)
        {
            print "<pre>"; print $sql; print "</pre>";
        }

        /**
         * Devuelve los registros para una sentencia SQL
         *
         * @param $sql     string  Sentencia SQL con un comando de selecci�n
         * @param $offset  integer Desplazamiento inicial en los registros resultantes
         * @param $numRows integer N�mero m�ximo de filas a devolver
         *
         * @return ADOdb::ResultSet Filas consultadas
         * @private
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

            $result->Close();
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
         * @deprecated Esta funci�n obliga a llamar id al campo identificador de la tabla
         * @see retrieveByPK Usar esta funci�n en su lugar
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
            $result->Close();
            
            if (!isset($row["total"]))
            {
                return false;
            }

            return $row["total"];
        }

        /**
        * Add function here
        */
        function doDelete($whereClause = null)
        {
            $sql = "DELETE FROM " . $this->getQuotedTableName();

            if (!empty($whereClause))
            {
                $sql .= " WHERE " . $whereClause;
            }
            else if ($this->getClause("WHERE"))
            {
                $sql .= " WHERE " . $this->getClause("WHERE");
            }

            return $this->execute($sql);
        }

        /**
         * Devuelve un objeto para una clave primaria
         *
         * @param $pk mixed
         */
        function retrieveByPK($pk)
        {
            $obj      = $this->getDbObjectClass();
            $pkFields = $obj->getPrimaryKeyFields();

            return $this->getDbObject($pkFields[0] . "='" . $pk . "'");
        }

        /**
         * Devuelve un objeto para varias claves primarias
         *
         * @param $pks mixed
         */
        function retrieveByPKs($pks)
        {
            $obj      = $this->getDbObjectClass();
            $pkFields = $obj->getPrimaryKeyFields();
            $clause   = "1=1";
            
            foreach ($pks as $field => $value)
            {
                if (in_array($field, $pkFields))
                {
                    $clause = $clause . " AND " . $field . "='" . $value . "' ";
                }
            }

            return $this->getDbObject($clause);
        }

        /**
        * Add function info here
        */
        function isPrimaryKey($fieldName)
        {
            $obj = $this->getDbObjectClass();

            return in_array($fieldName, $obj->getPrimaryKeyFields());
        }
        
        /**
        * Add function info here
        */
        function getDataProvider()
        {
            return $this->_db->getDataProvider();
        }
        
        /**
        * Add function info here
        */
        function getAffectedRows()
        {
            return $this->_db->getAffectedRows();
        }
        
        /**
        * Add function info here
        */
        function truncate($resetAutoIncrement = false)
        {
            return $this->_db->truncateTable($this->_tableName, $resetAutoIncrement);
        }
        
        /**
        * Add function info here
        */
        function resetAutoIncrement()
        {
            return $this->_db->resetTableAutoIncrement($this->_tableName);
        }
        
        /**
        * Add function info here
        */
        function drop()
        {
            return $this->_db->dropTable($this->_tableName);
        }
        
        /**
        * Add function info here
        */
        function rename($dst)
        {
            return $this->_db->renameTable($this->_tableName, $dst);
        }
        
        /**
        * Add function info here
        */
        function replicate($dst, $cloneData = true)
        {
            return $this->_db->replicateTable($this->_tableName, $dst, $cloneData);
        }
    }

?>