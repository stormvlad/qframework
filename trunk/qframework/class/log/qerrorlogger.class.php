<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qlogger.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qmessage.class.php");

    /**
     * @brief Establece el registro de errores por defecto.
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:18
     * @version 1.0
     * @ingroup log
     */
    class qErrorLogger extends qLogger
    {
        /**
         * Constructor
         */
        function &qErrorLogger ()
        {
            parent::qLogger();
        }

        /**
         * Registra un mensaje con la prioridad de desarrollo (debug)
         *
         * @param message  string Mensaje de error
         * @param class    string La classe donde se produce el error
         * @param function string La funcion donde se produce el error
         * @param file     string El fichero donde se produce el error
         * @param line     int    La linea del fichero donde se produce el error
         * @note Este tiene una prioridad de 1000.
         */
        function debug($message, $class = NULL, $function = NULL, $file = NULL, $line = NULL)
        {
            $message =& new qMessage(array('m' => $message,
                                           'c' => $class,
                                           'F' => $function,
                                           'f' => $file,
                                           'l' => $line,
                                           'N' => 'DEBUG',
                                           'p' => 1000));

            $this->log($message);
        }

        /**
         * Registra un mensaje con la prioridad de información
         *
         * @param message  string Mensaje de error
         * @param class    string La classe donde se produce el error
         * @param function string La funcion donde se produce el error
         * @param file     string El fichero donde se produce el error
         * @param line     int    La linea del fichero donde se produce el error
         * @note Este tiene una prioridad de 2000.
         */
        function info ($message, $class = NULL, $function = NULL, $file = NULL, $line = NULL)
        {
            if ((substr($message, 0, 17) != "Undefined index: ") && (substr($message, 0, 24) != "Only variable references")) // Don't show these errors
            {
                $message =& new qMessage(array('m' => $message,
                                               'c' => $class,
                                               'F' => $function,
                                               'f' => $file,
                                               'l' => $line,
                                               'N' => 'NOTICE',
                                               'p' => 2000));
                $this->log($message);
            }
        }

        /**
         * Registra un mensaje con la prioridad de error
         *
         * @param message  string Mensaje de error
         * @param class    string La classe donde se produce el error
         * @param function string La funcion donde se produce el error
         * @param file     string El fichero donde se produce el error
         * @param line     int    La linea del fichero donde se produce el error
         * @note Este tiene una prioridad de 3000.
         */
        function error ($message, $class = NULL, $function = NULL, $file = NULL, $line = NULL)
        {
            $message =& new qMessage(array('m' => $message,
                                           'c' => $class,
                                           'F' => $function,
                                           'f' => $file,
                                           'l' => $line,
                                           'N' => 'ERROR',
                                           'p' => 3000));

            $this->log($message);
        }

        /**
         * Registra un mensaje con la prioridad de warning
         *
         * @param message  string Mensaje de error
         * @param class    string La classe donde se produce el error
         * @param function string La funcion donde se produce el error
         * @param file     string El fichero donde se produce el error
         * @param line     int    La linea del fichero donde se produce el error
         * @note Este tiene una prioridad de 4000.
         */
        function warning ($message, $class = NULL, $function = NULL, $file = NULL, $line = NULL)
        {
            $message =& new qMessage(array('m' => $message,
                                           'c' => $class,
                                           'F' => $function,
                                           'f' => $file,
                                           'l' => $line,
                                           'N' => 'WARNING',
                                           'p' => 4000));

            $this->log($message);
        }

        /**
         * Registra un mensaje con la prioridad de error fatal
         *
         * @param message  string Mensaje de error
         * @param class    string La classe donde se produce el error
         * @param function string La funcion donde se produce el error
         * @param file     string El fichero donde se produce el error
         * @param line     int    La linea del fichero donde se produce el error
         * @note Este tiene una prioridad de 5000.
         */
        function fatal ($message, $class = NULL, $function = NULL, $file = NULL, $line = NULL)
        {
            $message =& new qMessage(array('m' => $message,
                                           'c' => $class,
                                           'F' => $function,
                                           'f' => $file,
                                           'l' => $line,
                                           'N' => 'FATAL',
                                           'p' => 5000));

            $this->log($message);
        }

        /**
         * Registra un error de PHP
         *
         * @param level   int    Nivel de prioridad
         * @param message string Mensaje de error
         * @param file    string El fichero donde se produce el error
         * @param line    int    La linea del fichero donde se produce el error
         * @note No llamar a este metodo directamente. Llamar a la función standard de PHP
         *       <i>trigger_error()</i>.
         */
        function standard ($level, $message, $file, $line)
        {
            // no queremos escribir los mensajes suprimidos
            if (error_reporting() > 0)
            {
                switch ($level)
                {
                    case E_STRICT:
                        // do nothing
                        break;

                    case E_NOTICE:
                    case E_USER_NOTICE:
                        $this->info($message, NULL, NULL, $file, $line);
                        break;

                    case E_WARNING:
                        $this->warning($message, NULL, NULL, $file, $line);
                        break;

                    case E_USER_WARNING:
                    case E_USER_ERROR:
                    default:
                        $this->fatal($message, NULL, NULL, $file, $line);
                }
            }
        }
    }

?>