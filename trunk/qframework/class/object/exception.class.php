<?php

    /**
     * Avisos en PHP5
     */     
    define ("E_STRICT", 2048);
    
    /**
     * @brief Representa una excepcion en la ejecuci�n 
     *
     * Clase base para el control de excepciones. 
     * Substituye para PHP4 a la clase Exception incluida en PHP5
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 18:19
     * @version 1.0
     * @ingroup core
     * @deprecated Incluida ya en el c�digo de PHP5
     */
    class Exception
    {
        var $_exceptionString;
        var $_exceptionCode;

        /**
         * Constructor
         *
         * @param exceptionString <em>string</em> Mensaje descriptivo de la excepci�n
         * @param exceptionCode <em>integer</em> Identificador num�rico asignado a esta excepci�n
         */
        function Exception($exceptionString, $exceptionCode = 0)
        {
            $this->_exceptionString = $exceptionString;
            $this->_exceptionCode   = $exceptionCode;
        }

        /**
         * Dispara una excepci�n y para la ejecuci�n, vuelca la informaci�n para depuraci�n.
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
     * Lanza una excepci�n
     *
     * @param exception <em>Exception</em> Objeto con la excepci�n
     */
    function throw($exception)
    {
        $exception->qthrow();
    }

    /**
     * Recoge una excepci�n
     *
     * @param exception <em>Exception</em> Objeto con la excepci�n
     */
    function catch($exception)
    {
        print("Exception catched!");
    }

?>
