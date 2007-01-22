<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Registro de sucesos
     *
     * Proporciona una interfície para dar salida a un tipo de registro de sucesos
     * en uno o varios medios.
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:23
     * @version 1.0
     * @ingroup log
     */
    class qLogger extends qObject
    {
        /**
         * Un array asociativo de agregadores
         */
        var $_appenders;

        /**
         * Número entero con el nivel de prioridad de salida. Debe coincidir o superar
         * para ordenar a qFramework que abandone la ejecución después de registrar
         * el mensage del suceso
         */
        var $_exitPriority;

        /**
         * Número entero con el nivel de prioridad. Debe coincidir o superar
         * para ordenar a esta classe que registre el mensaje
         */
        var $_priority;

        /**
         * Constructor
         */
        function qLogger()
        {
            $this->qObject();

            // establece las prioridades mínimas por defecto
            if ($this->isDebug())
            {
                $this->_priority     = 0; // Muestra todos los errores
                $this->_exitPriority = 1; // Paramos la ejecución en cualquier error, aun siendo un NOTICE
            }
            else
            {
                $this->_priority     = 0;    // Muestra todos los errores
                $this->_exitPriority = 2000; // Paramos la ejecución en los warnings
            }
        }

        /**
         * Añade un agregador (una tipo salida para los mensajes)
         *
         * @param name string Nombre del agregador
         * @param appender qAppender Una instancia de qAppender
         * @note Si ya existe un agregador con este nombre, se informa de un error.
         */
        function addAppender($name, &$appender)
        {
            if (isset($this->_appenders[$name]))
            {
                throw(new qException("qLogger::addAppender: qLogger already has appender " . $name));
                die();
            }

            $this->_appenders[$name] =& $appender;
        }

        /**
         * Devuelve un agregador
         *
         * @param name string Nombre del agregador
         *
         * @return qAppender Referencia a la instancia de un agregador, si el nombrado existe,
         *                  en otro caso <b>NULL</b>.
         */
        function &getAppender($name)
        {
            if (isset($this->_appenders[$name]))
            {
                return $this->_appenders[$name];
            }
        }

        /**
         * Devuelve el nivel de prioridad que debe coincidir o sobrepasar para ordenar
         * a qFramework de salir después de registrar el mensage del suceso
         *
         * @return int Nivel de prioridad
         */
        function getExitPriority()
        {
            return $this->_exitPriority;
        }

        /**
         * Devuelve el nivel de prioridad que debe coincidir o superar para ordenar
         * a esta classe que registre el mensaje
         *
         * @return int Nivel de prioridad
         */
        function getPriority()
        {
            return $this->_priority;
        }

        /**
         * Mensaje del suceso
         *
         * @param message Una instancia de qMessage
         */
        function log(&$message)
        {
            // recupera la prioridad del mensaje
            $msgPriority = $message->getParameter("p");

            if ($this->_priority == 0 || $msgPriority >= $this->_priority)
            {
                // pasa por todos los appenders y escribe en cada uno
                $keys  = array_keys($this->_appenders);
                $count = sizeof($keys);

                for ($i = 0; $i < $count; $i++)
                {
                    $appender = &$this->_appenders[$keys[$i]];
                    $layout   = &$appender->getLayout();
                    $result   = &$layout->format($message);

                    $appender->write($result);
                }
            }

            // debe salir de la ejecución?
            if ($this->_exitPriority > 0 && $msgPriority >= $this->_exitPriority)
            {
                // pasa por todos los appenders y si tienen implementada la función writeStackTrace la ejecuta
                // para hacer un volcado de pila
                $keys  = array_keys($this->_appenders);
                $count = sizeof($keys);

                for ($i = 0; $i < $count; $i++)
                {
                    $appender = &$this->_appenders[$keys[$i]];

                    if ($appender->hasMethod("writeStackTrace"))
                    {
                        $appender->writeStackTrace();
                    }
                }

                // Y finalmente paramos la ejecución del script
                exit;
            }
        }

        /**
         * Quita un agregador
         *
         * @param name string Nombre del agregador
         */
        function removeAppender($name)
        {
            if (isset($this->_appenders[$name]))
            {
                $appender = &$this->_appenders[$name];
                unset($this->_appenders[$name]);
            }
        }

        /**
         * Establece el nivel de prioridad que debe coincidir o sobrepasar para ordenar
         * a qFramework de salir después de registrar el mensage del suceso
         *
         * @param priority int Nivel de prioridad
         * @note Con un nivel de prioridad 0 no saldra nunca
         */
        function setExitPriority($priority)
        {
            $this->_exitPriority = $priority;
        }

        /**
         * Establece el nivel de prioridad que debe coincidir o superar para ordenar
         * a esta classe que registre el mensaje
         *
         * @param priority int Nivel de prioridad
         * @note Un nivel de prioridad 0 registrara qualquier mensaje
         */
        function setPriority($priority)
        {
            $this->_priority = $priority;
        }
    }

?>
