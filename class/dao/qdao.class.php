<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/dao/qdb.class.php");

    /**
     * @defgroup dao Objetos de acceso a datos
     *
     * qFramework dispone de una capa de persisténcia de objetos i herramientas de consulta,
     * la capa está formada por el grupo de clases <b>Dao</b>.
     *
     * Esto significa que qFramework permite tratar la base de dados como un conjunto de objetos,
     * disponiendo de una API senzilla para consultar y guardar los datos. Este tipo de herramientas
     * se conocen como Object Relational Mapping (ORM) y Data Access Objects (DAO).
     *
     * Mas información:
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
     * @see qDaoGenerator Estas clases pueden generarse automáticamente
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

            $this->setClause("SELECT", "*");
            $this->setClause("FROM", "`" . $this->_tableName . "`");
        }

        /**
         * Devuelve el valor actual de una cláusula
         *
         * @param $name Nombre de la cláusula
         * @return Cadena con el valor de la cláusula, si existe,
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
         * Establece una cláusula para las siguientes sentencias
         *
         * @param $name Nombre de la cláusula.
         *              Valores possibles: SELECT, FROM, WHERE, GROUP BY, ORDER BY, HAVING
         * @param $value Cadena con el contenido de la cláusula (condición)
         */
        function setClause($name, $value)
        {
            $this->_clauses[strtoupper($name)] = $value;
        }

        /**
         * Borra todas las cláusula establecidas
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
         * @param $whereClause string  Cláusula con la condición de búsqueda
         * @param $orderClause string  Cláusula con la expressión de ordenación
         * @param $offset      integer Desplazamiento inicial en los registros resultantes
         * @param $numRows     integer Número máximo de filas a devolver
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
         * Devuelve el número de registros para una sentencia de consulta
         *
         * @param $whereClause string  Cláusula con la condición de búsqueda
         *
         * @return integer Número de filas
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
         * Inserta un objeto en la base de datos
         *
         * @param $obj qDbObject Objeto del tipo de la tabla
         *
         * @return integer Devuelve el identificador del nuevo registro, si se
         *                 ha insertado con éxito, sino devuelve FALSE
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
                    else
                    {
                        $sql .= "'', ";
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
         * Modifica un objeto en la base de datos
         *
         * Los campos identificadores se usaran para la sentencia de modificación.
         *
         * @param $obj qDbObject Objeto del tipo de la tabla
         *
         * @return integer Devuelve el número de filas modificadas, si se
         *                 han modificado con éxito, sino devuelve FALSE
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
                    //$sql .= "`" . $field . "`='" . $value . "', ";
                }
            }

            $sql = substr($sql, 0, -2) . " WHERE " . $this->_getWhereClause($obj);

            return $this->_update($sql);
        }

        /**
         * Borra un objeto de la base de datos
         *
         * @param $obj qDbObject Objeto del tipo de la tabla
         *
         * @return integer Devuelve el número de filas borradas, si
         *                 ha habido alguna, sino devuelve FALSE
         */
        function delete($obj)
        {
            $sql = "DELETE FROM `" . $this->_tableName . "` WHERE " . $this->_getWhereClause($obj);
            return $this->_update($sql);
        }

        /**
         * Devuelve la cláusula de con la condición de búsqueda
         *
         * @param $obj qDbObject Objeto del tipo de la tabla
         * @return string Cadena con la condición de búsqueda
         * @private
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
         * @param $sql     string  Sentencia SQL con un comando de selección
         * @param $offset  integer Desplazamiento inicial en los registros resultantes
         * @param $numRows integer Número máximo de filas a devolver
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
         * @private
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
            if (!($items = $this->getDbObjects($whereClause, $orderClause, 0, 1)))
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
         * @deprecated Esta función obliga a llamar id al campo identificador de la tabla
         * @see retrieveByPK Usar esta función en su lugar
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

        /**
        * Add function here
        */
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