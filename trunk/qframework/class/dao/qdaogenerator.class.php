<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qsmartyview.class.php");

    define("DEFAULT_DAO_GENERATOR_OUTPUT_DIR", "tmp/");
    define("DEFAULT_DAO_GENERATOR_TEMPLATE_DAO", "dao");
    define("DEFAULT_DAO_GENERATOR_TEMPLATE_DB_OBJECT", "dbobject");
    define("DEFAULT_DAO_GENERATOR_TEMPLATES_DIR", "templates/dao_generator/");

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
        function output($tables = null)
        {
            if (empty($tables))
            {
                $tables = $this->_db->MetaTables();

                foreach ($tables as $table)
                {
                    $this->_outputTable($table);
                }
            }
            else if (is_array($tables))
            {
                foreach ($tables as $table)
                {
                    $this->_outputTable($table);
                }
            }
            else if (is_string($tables))
            {
                $this->_outputTable($tables);
            }
        }
    }

?>