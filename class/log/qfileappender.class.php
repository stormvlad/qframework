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
        var $append;

        /**
         * Cadena con la ruta absoluta al fichero de registro
         */
        var $file;

        /**
         * Recurso con el puntero al fichero de registro
         */
        var $fp;

        /**
         * Patrón de conversión (qConversionPattern) para usar con esta plantilla
         */
        var $pattern;

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
        function &qFileAppender($layout, $file, $append = TRUE)
        {
            parent::qAppender($layout);

            $this->append  =  $append;
            $this->file    =  $file;
            $this->pattern =& new qConversionPattern($file);

            $this->openFP();
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
        function &callback ($char, $param)
        {
            switch ($char)
            {
                case 'C':
                    $data = (defined($param)) ? constant($param) : '';
                    break;

                case 'd':
                    // get the date
                    if ($param == NULL)
                    {
                        $param = 'd_j_y';
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
        function cleanup ()
        {
            if ($this->fp != NULL)
            {
                fflush($this->fp);
                fclose($this->fp);

                $this->fp = NULL;
            }
        }

        /**
         * Abre el puntero al fichero
         *
         * @note No debe llamarse manualmente
         */
        function openFP ()
        {
            // register callback method
            // this cannot be done in the constructor
            $this->pattern->setCallbackObject($this, 'callback');

            $this->file = $this->pattern->parse();
            $this->fp   = @fopen($this->file, ($this->append) ? 'a' : 'w');

            if ($this->fp === FALSE)
            {
                $error = 'Failed to open log file ' . $this->file . ' for writing';

                trigger_error($error, E_USER_WARNING);
            }
        }

        /**
         * Escribe un mensaje en el fichero de sucesos
         *
         * @param message string El mensaje a escribir
         * @note No debe llamarse manualmente
         */
        function write ($message)
        {
            fputs($this->fp, $message);
            fflush($this->fp);
        }
    }

?>