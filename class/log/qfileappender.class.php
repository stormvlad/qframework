<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qconversionpattern.class.php");

    /**
     * @brief Permite guardar los mensajes de sucesos en un fichero.
     *
     * Clase que implementa la salida a fichero para registro de sucesos.
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:18
     * @version 1.0
     * @ingroup log
     */
    class qFileAppender extends qAppender
    {
        /**
         * Booleano que indica si debe o no abrirse el fichero para añadir
         */
        var $_append;

        /**
         * Cadena con la ruta absoluta al fichero de registro
         */
        var $_file;

        /**
         * Recurso con el puntero al fichero de registro
         */
        var $_fp;

        /**
         * Patrón de conversión (qConversionPattern) para usar con esta plantilla
         */
        var $_pattern;

        /**
         * Constructor
         *
         * Carácteres de conversion:
         *
         * <ul>
         *     <li><b>%C{constant}</b> - el valor de una constanthe value de PHP</li>
         *     <li><b>%d{format}</b>   - una fecha (usa el formato de la función date())</li>
         * </ul>
         *
         * @param layout qLayout Instancia de qLayout, plantilla a usar
         * @param file string   Ruta absoluta al fichero de registro de sucesos
         * @param append bool   Debe abrirse el fichero en modo de agregación
         *                      (sino todos los datos son reemplazados).
         */
        function qFileAppender($layout, $file, $append = true)
        {
            $this->qAppender($layout);

            $this->_append  = $append;
            $this->_file    = $file;
            $this->_pattern = new qConversionPattern($file);
        }

        /**
         * Método <i>callback</i> ConversionPattern
         *
         * @param char string Un caracter de conversión
         * @param param string Un parámetro de conversión
         *
         * @return string El reemplazo de los datos proporcionados
         * @note No debe llamarse manualmente
         */
        function &callback($char, $param)
        {
            switch ($char)
            {
                case "C":
                    $data = (defined($param)) ? constant($param) : "";
                    break;

                case "d":
                    // get the date
                    if ($param == null)
                    {
                        $param = "d_j_y";
                    }

                    $data = date($param);
            }

            return $data;
        }

        /**
         * Cierra el puntero al fichero
         *
         * @note No debe llamarse manualmente
         */
        function cleanup()
        {
            if (!empty($this->_fp))
            {
                fflush($this->_fp);
                fclose($this->_fp);

                $this->_fp = null;
            }
        }

        /**
         * Abre el puntero al fichero
         *
         * @note No debe llamarse manualmente
         */
        function openFP()
        {
            // register callback method
            // this cannot be done in the constructor
            $this->_pattern->setCallbackObject($this, "callback");

            $this->_file = $this->_pattern->parse();
            $this->_fp   = @fopen($this->_file, ($this->_append) ? "a" : "w");

            if ($this->_fp === false)
            {
                trigger_error("Failed to open log file " . $this->_file . " for writing", E_USER_WARNING);
            }

            return $this->_fp;
        }

        /**
         * Escribe un mensaje en el fichero de sucesos
         *
         * @param message string El mensaje a escribir
         * @note No debe llamarse manualmente
         */
        function write ($message)
        {
            if (empty($this->_fp))
            {
                $this->openFP();
            }
            
            fputs($this->_fp, $message);
            fflush($this->_fp);
        }

        /**
         * Hace un volcado de la pila de llamadas a funciones.
         */
        function writeStackTrace()
        {
            if (function_exists("debug_backtrace"))
            {
                $info = debug_backtrace();
                fputs($this->_fp, "-- Backtrace --" . PHP_EOL);
    
                foreach ($info as $trace)
                {
                    if (($trace["function"] != "standard")                     &&
                        (!empty($trace["file"]))                               &&
                        (basename($trace["file"]) != "qerrorlogger.class.php") &&
                        (basename($trace["file"]) != "qlogger.class.php")      &&
                        ($trace["file"] != __FILE__ ))
                    {
                        fputs($this->_fp, $trace["file"] . "(" . $trace["line"] . "): ");
    
                        if (!empty($trace["class"]))
                        {
                            fputs($this->_fp, $trace["class"] . ".");
                        }
    
                        fputs($this->_fp, $trace["function"] . PHP_EOL);
                    }
                }
            }
            else
            {
                fputs($this->_fp, "Stack trace is not available" . PHP_EOL);
            }

            fflush($this->_fp);
        }
    }

?>