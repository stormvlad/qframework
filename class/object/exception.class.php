<?php

    /**
     * Avisos en PHP5
     */     
    define ("E_STRICT", 2048);
    
    /**
     * @brief Representa una excepcion en la ejecución 
     *
     * Clase base para el control de excepciones. 
     * Substituye para PHP4 a la clase Exception incluida en PHP5
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 18:19
     * @version 1.0
     * @ingroup core
     * @deprecated Incluida ya en el código de PHP5
     */
    class Exception
    {
        var $_exceptionString;
        var $_exceptionCode;

        /**
         * Constructor
         *
         * @param exceptionString <em>string</em> Mensaje descriptivo de la excepción
         * @param exceptionCode <em>integer</em> Identificador numérico asignado a esta excepción
         */
        function Exception($exceptionString, $exceptionCode = 0)
        {
            $this->_exceptionString = $exceptionString;
            $this->_exceptionCode   = $exceptionCode;
        }

        /**
         * Dispara una excepción y para la ejecución, vuelca la información para depuración.
         */
        function qthrow()
        {
            print("<br/><b>Exception message</b>: " . $this->_exceptionString . "<br/><b>Error code</b>: " . $this->_exceptionCode."<br/>");
            $this->_printStackTrace();
        }

        /**
         * Hace un volcado de la pila de llamadas a funciones.
         *
         * @private
         */
        function _printStackTrace()
        {
            if (function_exists("debug_backtrace"))
            {
                $info = debug_backtrace();
                print("-- Backtrace --<br/><i>");

                foreach ($info as $trace)
                {
                    if (($trace["function"] != "standard") 
                         && (basename($trace["file"]) != "qerrorlogger.class.php" )
                         && (basename($trace["file"]) != "qlogger.class.php" )
                         && ($trace["file"] != __FILE__ ))
                    {
                        print($trace["file"] . "(" . $trace["line"] . "): ");

                        if (!empty($trace["class"]))
                        {
                            print($trace["class"]. ".");
                        }

                        print($trace["function"] . "<br/>");
                    }
                }

                print("</i>");
            }
            else
            {
                print("<i>Stack trace is not available</i><br/>");
            }
        }
    }

    /**
     * Lanza una excepción
     *
     * @param exception <em>Exception</em> Objeto con la excepción
     */
    function throw($exception)
    {
        $exception->qthrow();
    }

    /**
     * Recoge una excepción
     *
     * @param exception <em>Exception</em> Objeto con la excepción
     */
    function catch($exception)
    {
        print("Exception catched!");
    }

?>
