<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qsmartyview.class.php");

    define(DEFAULT_DAO_GENERATOR_OUTPUT_DIR, "tmp/");
    define(DEFAULT_DAO_GENERATOR_TEMPLATE_DAO, "dao");
    define(DEFAULT_DAO_GENERATOR_TEMPLATE_DB_OBJECT, "db_object");
    define(DEFAULT_DAO_GENERATOR_TEMPLATES_DIR, "templates/dao_generator/");

    /**
    *  Base class for data access objects
    */
    class qDaoGenerator extends qObject
    {
        var $_db;
        var $_outputDir;
        var $_daoTemplate;
        var $_dbObjectTemplate;
        var $_templatesDir;

        /**
        * Add function info here
        */
        function qDaoGenerator(&$db, $outputDir = DEFAULT_DAO_GENERATOR_OUTPUT_DIR, $daoTemplate = DEFAULT_DAO_GENERATOR_TEMPLATE_DAO, $dbObjectTemplate = DEFAULT_DAO_GENERATOR_TEMPLATE_DB_OBJECT)
        {
            $this->qObject();

            $this->_db               = &$db;
            $this->_outputDir        = $outputDir;
            $this->_daoTemplate      = $daoTemplate;
            $this->_dbObjectTemplate = $dbObjectTemplate;
            $this->_templatesDir     = DEFAULT_DAO_GENERATOR_TEMPLATES_DIR;
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
        function getOutputDir()
        {
            return $this->_outputDir;
        }

        /**
        * Add function info here
        */
        function getDaoTemplate()
        {
            return $this->_daoTemplate;
        }

        /**
        * Add function info here
        */
        function getDbObjectTemplate()
        {
            return $this->_dbObjectTemplate;
        }

        /**
        * Add function info here
        */
        function getTemplatesDir()
        {
            return $this->_templatesDir;
        }

        /**
        * Add function info here
        */
        function setDb(&$db)
        {
            $this->_db = &$db;
        }

        /**
        * Add function info here
        */
        function setOutputDir($dir)
        {
            $this->_outputDir = $dir;
        }

        /**
        * Add function info here
        */
        function setDaoTemplate($template)
        {
            $this->_daoTemplate = $template;
        }

        /**
        * Add function info here
        */
        function setDbObjectTemplate($template)
        {
            $this->_dbObjectTemplate = $template;
        }

        /**
        * Add function info here
        */
        function setTemplatesDir($dir)
        {
            $this->_templatesDir = $dir;
        }

        /**
        * Add function here
        */
        function _getTableNameToClassName($tableName)
        {
            $className = str_replace("_", " ", $tableName);
            $className = ucwords($className);

            return str_replace(" ", "", $className);
        }

        /**
        * Add function here
        */
        function _outputDbObjectTemplate($tableName)
        {
            $fields     = $this->_db->MetaColumnNames($tableName, true);
            $idFields   = $this->_db->MetaPrimaryKeys($tableName);

            $smartyView = new qSmartyView($this->_dbObjectTemplate, "");
            $renderer   = &$smartyView->getRenderer();

            $renderer->setTemplatesDir($this->_templatesDir);

            $className  = $this->_getTableNameToClassName($tableName . "DbObject");

            $smartyView->setValue("className", $className);
            $smartyView->setValue("tableName", $tableName);
            $smartyView->setValue("fields", $fields);
            $smartyView->setValue("idFields", $idFields);

            $output = $smartyView->render();

            $f = new qFile("tmp/" . strtolower($className) . ".class.php");
            $f->open("w");
            $f->write($output);
            $f->close();
        }

        /**
        * Add function here
        */
        function _outputDaoTemplate($tableName)
        {
            $smartyView = new qSmartyView($this->_daoTemplate, "");
            $renderer   = &$smartyView->getRenderer();

            $renderer->setTemplatesDir($this->_templatesDir);

            $className  = $this->_getTableNameToClassName($tableName . "Dao");

            $smartyView->setValue("className", $className);
            $smartyView->setValue("tableName", $tableName);

            $output = $smartyView->render();

            $f = new qFile("tmp/" . strtolower($className) . ".class.php");
            $f->open("w");
            $f->write($output);
            $f->close();
        }

        /**
        * Add function here
        */
        function _outputTable($tableName)
        {
            $this->_outputDaoTemplate($tableName);
            $this->_outputDbObjectTemplate($tableName);
        }

        /**
        * Add function here
        */
        function output($tableName = null)
        {
            if (empty($tableName))
            {
                $tables = $this->_db->MetaTables();

                foreach ($tables as $table)
                {
                    $this->_outputTable($table);
                }
            }
            else
            {
                $this->_outputTable($tableName);
            }
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
                $sql .= "'" . $value . "', ";
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

            $sql = "UPDATE " . $this->_tableName . " SET ";

            foreach ($fields as $field => $value)
            {
                $sql .= $field . "='" . $value . "', ";
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
            $result = $this->_db->Execute($sql);

            if (!$result)
            {
                return false;
            }

            return true;
        }
    }

?>