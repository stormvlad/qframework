<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qappender.class.php");

    /**
     * @brief Añade el mensaje directamente a la respuesta para el cliente
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:32
     * @version 1.0
     * @ingroup log
     */
    class qStdoutAppender extends qAppender
    {
        /**
         * Constructor
         *
         * @param layout Una instancia de qLayout (la plantilla)
         */
        function qStdoutAppender($layout)
        {
            $this->qAppender($layout);
        }

        /**
         * Escribe el mensaje directamente a la respuesta para el cliente
         *
         * @param message string El mensaje a escribir
         * @note No debe llamarse manualmente
         */
        function write($message)
        {
            print $message . "<br/>\n";
        }

        /**
         * Hace un volcado de la pila de llamadas a funciones.
         */
        function writeStackTrace()
        {
            if (function_exists("debug_backtrace"))
            {
                $info = debug_backtrace();
                print "-- Backtrace --<br/><i>";
    
                foreach ($info as $trace)
                {
                    if (($trace["function"] != "standard")                     &&
                        (!empty($trace["file"]))                               &&
                        (basename($trace["file"]) != "qerrorlogger.class.php") &&
                        (basename($trace["file"]) != "qlogger.class.php")      &&
                        ($trace["file"] != __FILE__ ))
                    {
                        print $trace["file"] . "(" . $trace["line"] . "): ";
    
                        if (!empty($trace["class"]))
                        {
                            print $trace["class"] . ".";
                        }
    
                        print $trace["function"] . "<br />";
                    }
                }
    
                print "</i>";
            }
            else
            {
                print "<i>Stack trace is not available</i><br />";
            }
        }
    }

?>