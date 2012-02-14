<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qlogger.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qmessage.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qclient.class.php");
    
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
        function qErrorLogger ()
        {
            $this->qLogger();
        }

        /**
         * Registra un mensaje con la prioridad de información
         *
         * @param message  string Mensaje de error
         * @param class    string La classe donde se produce el error
         * @param function string La funcion donde se produce el error
         * @param file     string El fichero donde se produce el error
         * @param line     int    La linea del fichero donde se produce el error
         * @note Este tiene una prioridad de 1000.
         */
        function notice($message, $class = null, $function = null, $file = null, $line = null)
        {
            // Don't show these errors
            if ((substr($message, 0, 17) != "Undefined index: ") &&
                (substr($message, 0, 24) != "Only variable references"))
            {
                $message = new qMessage(
                    array(
                        "m"  => $message,
                        "c"  => $class,
                        "F"  => $function,
                        "f"  => $file,
                        "l"  => $line,
                        "N"  => "NOTICE",
                        "p"  => 2000,
                        "ip" => qClient::getIp()
                        )
                    );
                        
                $this->log($message);
            }
        }

        /**
         * Registra un mensaje con la prioridad de warning
         *
         * @param message  string Mensaje de error
         * @param class    string La classe donde se produce el error
         * @param function string La funcion donde se produce el error
         * @param file     string El fichero donde se produce el error
         * @param line     int    La linea del fichero donde se produce el error
         * @note Este tiene una prioridad de 2000.
         */
        function warning($message, $class = null, $function = null, $file = null, $line = null)
        {
            $message = new qMessage(
                array(
                    "m"  => $message,
                    "c"  => $class,
                    "F"  => $function,
                    "f"  => $file,
                    "l"  => $line,
                    "N"  => "WARNING",
                    "p"  => 2000,
                    "ip" => qClient::getIp()
                    )
                );
                
            $this->log($message);
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
        function error($message, $class = null, $function = null, $file = null, $line = null)
        {
            $message = new qMessage(
                array(
                    "m"  => $message,
                    "c"  => $class,
                    "F"  => $function,
                    "f"  => $file,
                    "l"  => $line,
                    "N"  => "ERROR",
                    "p"  => 3000,
                    "ip" => qClient::getIp()
                    )
                );
                
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
        function standard($level, $message, $file, $line)
        {
            // calculamos el nivel según la configuración de error_reporting
            $level = error_reporting() & $level;
            
            // Si el resultado no es el nivel del error capturado no hacemos nada
            if (empty($level))
            {
                return;
            }

            // Sino procesamos el error capturado
            switch ($level)
            {
                case E_STRICT:
                    // do nothing
                    break;

                case E_NOTICE:
                case E_USER_NOTICE:
                    $this->notice($message, null, null, $file, $line);
                    break;

                case E_WARNING:
                case E_CORE_WARNING:
                case E_COMPILE_WARNING:
                case E_USER_WARNING:
                    $this->warning($message, null, null, $file, $line);
                    break;

                case E_PARSE:
                case E_ERROR:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                default:
                    $this->error($message, null, null, $file, $line);
            }
        }
    }

?>