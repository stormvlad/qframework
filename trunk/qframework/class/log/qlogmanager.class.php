<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qerrorlogger.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qpatternlayout.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qstdoutappender.class.php");

    /** 
     * @defgroup log Logs
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:19
     * @version 1.0
     */

    /**
     * @brief Gestiiona todos los logs de la aplicaci�n.
     *
     * Esta clase se usa habitualmente con una sola instancia (singleton).
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:24
     * @version 1.0
     * @ingroup log
     */
    class qLogManager extends qObject
    {
        /**
         * An associative array of loggers.
         *
         * @private
         * @since  1.0
         * @type   array
         */
        var $loggers;

        /**
         * Constructor
         *
         * @note This should never be called manually.
         * @private
         * @since  1.0
         */
        function &qLogManager()
        {
            parent::qObject();

            $this->loggers = array();

            // create default logger
            $logger   =& new qErrorLogger();
            $layout   =& new qPatternLayout("<b>%N</b> [%f{rel}:%l] %m%n");
            $appender =& new qStdoutAppender($layout);

            $logger->addAppender("stdout", $appender);
            $this->addLogger("default", $logger);
        }

        /**
         * Add a logger.
         *
         * @note If a logger with the given name already exists, an error will be reported.
         * @param name string A logger name.
         * @param logger A Logger instance.
         *
         * @public
         * @since  1.0
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
         * Cleanup all loggers.
         *
         * @note If a logger with the given name already exists, an error will be reported.
         * @public
         * @since  1.0
         */
        function cleanup ()
        {
            $keys  = array_keys($this->loggers);
            $count = sizeof($keys);

            for ($i = 0; $i < $count; $i++)
            {
                $this->loggers[$keys[$i]]->cleanup();
            }
        }

        /**
         * Devuelve la �nica instancia de qLogManager
         *
         * @note Basado en el patr�n Singleton. El objectivo de este m�todo es asegurar que exista s�lo una instancia de esta clase y proveer de un punto global de accesso a ella.
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
         * Retrieve a logger.
         *
         * @note If a name is not specified, the default logger is returned.
         * @param name string A logger name.
         *
         * @return Logger A Logger instance, if the given Logger exists, otherwise
         *                <b>NULL</b>.
         *
         * @public
         * @since  1.0
         */
        function &getLogger ($name = "default")
        {
            if (isset($this->loggers[$name]))
            {
                return $this->loggers[$name];
            }

            return NULL;
        }

        /**
         * Retrieve an associative array of loggers.
         *
         * @return array An array of loggers.
         *
         * @public
         * @since  1.0
         */
        function &getLoggers ()
        {
            return $this->loggers;
        }

        /**
         * Remove a logger.
         *
         * @note You cannot remove the default logger.
         * @param name string A logger name.
         *
         * @return Logger A Logger instance, if the given logger exists and has been
         *                removed, otherwise <b>NULL</b>.
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