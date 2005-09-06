<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qerrorlogger.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qpatternlayout.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qstdoutappender.class.php");

    /**
     * @defgroup log Registros de sucesos
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:19
     * @version 1.0
     */

    /**
     * @brief Gestiona todos los registros de sucesos (<i>logs</i>) de una aplicación
     *
     * Esta clase se usa habitualmente con una sola instancia (Singleton).
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:24
     * @version 1.0
     * @ingroup log
     */
    class qLogManager extends qObject
    {
        /**
         * Array associativo de registros
         */
        var $loggers;

        /**
         * Constructor
         */
        function &qLogManager()
        {
            parent::qObject();

            $this->loggers = array();

            // crea el logger por defecto
            $logger   =& new qErrorLogger();
            $layout   =& new qPatternLayout("<b>%N</b> [%f{rel}:%l] %m%n");
            $appender =& new qStdoutAppender($layout);

            $logger->addAppender("stdout", $appender);
            $this->addLogger("default", $logger);
        }

        /**
         * Añade un nuevo registro de sucesos
         *
         * @param name string El nombre del registro de sucesos
         * @param logger Una instancia de qLogger
         * @note Si ya existe un registro con este nombre, se informará del error.
         */
        function addLogger ($name, &$logger)
        {
            if (isset($this->loggers[$name]))
            {
                throw(new qException("qLogManager::addLogger: qLogManager already contains logger " . $name));
                die();
            }

            $this->loggers[$name] =& $logger;
            return;
        }

        /**
         * Devuelve la única instancia de qLogManager
         *
         * @note Basado en el patrón Singleton. El objectivo de este método es asegurar
         *       que exista sólo una instancia de esta clase y proveer de un punto global de accesso a ella.
         * @return qLogManager
         */
        function &getInstance()
        {
            static $instance = NULL;

            if ($instance === NULL)
            {
                // can't use reference with static data
                $instance = new qLogManager;
            }


            return $instance;
        }

        /**
         * Devuelve un registro de sucesos
         *
         * @param name string Nombre del registro
         * @note Si no se especifica ningun nombre, devuelve el registro por defecto.
         *
         * @return qLogger Instancia de qLogger, si el nombre del registro existe, en otro caso
         *                <b>NULL</b>.
         */
        function &getLogger($name = "default")
        {
            if (isset($this->loggers[$name]))
            {
                return $this->loggers[$name];
            }

            return NULL;
        }

        /**
         * Devuelve un array associativo de registros de sucesos.
         *
         * @return array Vector de registros
         */
        function &getLoggers()
        {
            return $this->loggers;
        }

        /**
         * Quita un registro de sucesos
         *
         * @param name string Nombre del registro de sucesos
         *
         * @return qLogger Instancia de qLogger, si el nombre del registro existe i ha sido
         *                quitado, en otro caso <b>NULL</b>.
         * @note No puedes quitar el registro por defecto.
         */
        function &removeLogger ($name)
        {
            if ($name != "default" && isset($this->loggers[$name]))
            {
                $logger =& $this->loggers[$name];

                unset($this->loggers[$name]);

                return $logger;
            }

            return NULL;
        }
    }

?>