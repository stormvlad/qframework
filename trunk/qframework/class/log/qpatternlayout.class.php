<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qlayout.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qconversionpattern.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");

    /**
     * @brief Permite una plantilla personalizable para usar una conversión con patrones.
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:22
     * @version 1.0
     * @ingroup log
     * @see     qConversionPattern
     */
    class qPatternLayout extends qLayout
    {
        /**
         * El objeto qMessage a formatear
         */
        var $_message;

        /**
         * El qConversionPattern (patrón de conversión) a usar con esta plantilla.
         */
        var $_pattern;

        /**
         * Constructor
         *
         * @param layout string Plantilla del mensaje
         *
         * @public
         * @since  1.0
         */
        function qPatternLayout($layout)
        {
            $this->qLayout();
            $this->_pattern = new qConversionPattern($layout);
        }

        /**
         * Método <i>callback</i> qConversionPattern
         *
         * @param char string Un caracter de conversion
         * @param param string Un parámetro de conversion
         * @note Esta función debe llamarse manualmente
         *
         * @return string El reemplazo para la información proporcionada
         */
        function &callback($char, $param)
        {
            switch ($char)
            {
                case "c":
                case "F":
                case "l":
                case "m":
                case "N":
                case "p":
                    $data = $this->_message->getParameter($char);
                    break;

                case "n":
                    $data = "\n";
                    break;

                case "r":
                    $data = "\r";
                    break;

                case "t":
                    $data = "\t";
                    break;

                case "T":
                    $data = time();
                    break;

                // conversion chars with a parameter
                case "C":
                    $data = (defined($param)) ? constant($param) : "";
                    break;

                case "d":
                    // get the date
                    if ($param == null)
                    {
                        $param = "%d/%m/%Y %H:%M:%S";
                    }

                    $data = strftime($param);
                    break;

                case "f":
                    // get the file
                    $data   = $this->_message->getParameter("f");
                    $server = &qHttp::getServerVars();

                    switch($param)
                    {
                        case "file":
                            $data = basename($data);
                            break;

                        case "dir":
                            $data = dirname($data);
                            break;

                        case "rel":
                            $data = substr(dirname($data), strlen($server->getValue("DOCUMENT_ROOT")) + 1) .  "/" . basename($data);
                    }
                    break;

                case "x":
                    // get a custom parameter
                    $data = $this->_message->getParameter($param);
            }

            return $data;
        }

        /**
         * Da formato a un mensaje de log
         *
         * <br/><br/>
         *
         * <b>Carácteres de conversión:</b>
         *
         * <ul>
         *     <li><b>%c</b>               - la classe donde se produce el log</li>
         *     <li><b>%C{constant}</b>     - el valor de la constante de PHP</li>
         *     <li><b>%d{format}</b>       - una fecha (usa el formato de date())</li>
         *     <li><b>%f{file|dir|rel}</b> - el fichero donde se produce el log</li>
         *     <li><b>%F</b>               - la función donde se produce el log</li>
         *     <li><b>%l</b>               - la linea donde se produce el log</li>
         *     <li><b>%m</b>               - el mensaje de log</li>
         *     <li><b>%n</b>               - un canvio de linea</li>
         *     <li><b>%N</b>               - el nombre del nivel</li>
         *     <li><b>%p</b>               - el nombre de la prioridad</li>
         *     <li><b>%r</b>               - un retorno de carro</li>
         *     <li><b>%t</b>               - una tabulación horizontal</li>
         *     <li><b>%T</b>               - un timestamp de unix (segundos desde el 1 de
         *                                   Enero de 1970)</li>
         *     <li><b>%x{param}</b>        - un nombre de parámetro personalizado</li>
         * </ul>
         *
         * @param message Una instancia de qMessage
         *
         * @return string El mensaje de log formateado.
         */
        function &format(&$message)
        {
            // registro del método callback
            // no puede realizarse en el constructor
            $this->_pattern->setCallbackObject($this, "callback");

            $this->_message = &$message;
            $parsed = $this->_pattern->parse();
            
            return $parsed;
        }
    }

?>