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
        var $appenders;

        /**
         * Número entero con el nivel de prioridad de salida. Debe coincidir o superar
         * para ordenar a qFramework que abandone la ejecución después de registrar
         * el mensage del suceso
         */
        var $exitPriority;

        /**
         * Número entero con el nivel de prioridad. Debe coincidir o superar
         * para ordenar a esta classe que registre el mensaje
         */
        var $priority;

        /**
         * Constructor
         */
        function &qLogger()
        {
            parent::qObject();

            // establece las prioridades mínimas por defecto
            if (parent::isDebug())
            {
                $this->priority     = 0; // MUESTRA TODOS LOS ERRORES
                $this->exitPriority = 1; // PARA EN QUALQUIER ERROR, INCLUSO NOTICES
            }
            else
            {
                $this->priority     = 3000; // ERROR
                $this->exitPriority = 3000; // ERROR
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
            if (isset($this->appenders[$name]))
            {
                throw(new qException("qLogger::addAppender: qLogger already has appender " . $name));
                die();
            }

            $this->appenders[$name] =& $appender;
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
            if (isset($this->appenders[$name]))
            {
                return $this->appenders[$name];
            }

            return NULL;
        }

        /**
         * Devuelve el nivel de prioridad que debe coincidir o sobrepasar para ordenar
         * a qFramework de salir después de registrar el mensage del suceso
         *
         * @return int Nivel de prioridad
         */
        function getExitPriority()
        {
            return $this->exitPriority;
        }

        /**
         * Devuelve el nivel de prioridad que debe coincidir o superar para ordenar
         * a esta classe que registre el mensaje
         *
         * @return int Nivel de prioridad
         */
        function getPriority()
        {
            return $this->priority;
        }

        /**
         * Mensaje del suceso
         *
         * @param message Una instancia de qMessage
         */
        function log(&$message)
        {
            // recupera la prioridad del mensaje
            $msgPriority =& $message->getParameter("p");

            if ($this->priority == 0 || $msgPriority >= $this->priority)
            {
                // pasa por todos los agregadores y escribe en cada uno
                $keys  = array_keys($this->appenders);
                $count = sizeof($keys);

                for ($i = 0; $i < $count; $i++)
                {
                    $appender =& $this->appenders[$keys[$i]];
                    $layout   =& $appender->getLayout();
                    $result   =& $layout->format($message);

                    $appender->write($result);
                }
            }

            // debe salir de la ejecución?
            if ($this->exitPriority > 0 && $msgPriority >= $this->exitPriority)
            {
                throw(new qException($message->getParameter("m"), $message->getParameter("p")));
                // sayonara baby
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
            if (isset($this->appenders[$name]))
            {
                $appender =& $this->appenders[$name];
                $appender->cleanup();

                unset($this->appenders[$name]);
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
            $this->exitPriority = $priority;
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
            $this->priority = $priority;
        }
    }

?>