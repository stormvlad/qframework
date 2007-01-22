<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Conversión para cadenas con patrones
     *
     * Reemplaza las ocurrencias de simbolos predefinidos por valores.
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:07
     * @version 1.0
     * @ingroup misc
     */
    class qConversionPattern extends qObject
    {
        /**
         * Cadena con el nombre de la función que debe llamarse
         * cuando se ha de convertir un símbolo
         */
        var $_func;

        /**
         * El objeto que contiene la función que debe llamarse cuando se ha de convertir
         * un símbolo
         */
        var $_obj;

        /**
         * Caena con el patron de conversión que contiene los símbolos
         */
        var $_pattern;

        /**
         * Constructor
         *
         * @param pattern string Patrón de conversión que contiene los símbolos predefinidos
         */
        function qConversionPattern($pattern = null)
        {
            $this->qObject();

            $this->_func    = null;
            $this->_obj     = null;
            $this->_pattern = $pattern;
        }

        /**
         * Devuelve un parámetro para un símbolo de conversión.
         *
         * @param index int El índice del patrón en el que estamos trabajando.
         *
         * @return string Un símbolo de conversión si el parámetro existe,
         *                sino <b>NULL</b>.
         */
        function getParameter(&$index)
        {
            $length = strlen($this->_pattern);
            $param  = null;

            // avanzamos hata el parámetro
            $index += 2;

            if ($index < $length)
            {
                // recorremos a traves del símbolo del parámetro
                while ($this->_pattern{$index} != "}" && $index < $length)
                {
                    $param .= $this->_pattern{$index};
                    $index++;
                }

                if ($this->_pattern{$index} == "}")
                {
                    return $param;
                }

                // se ha encontrado un parámetro pero no el final }
            }

            // oops, no hay suficiente texto para continuar
            return null;
        }

        /**
         * Devuelve el patrón de conversión
         *
         * @return string Patrón de conversión
         */
        function getPattern()
        {
            return $this->_pattern;
        }

        /**
         * Parsea el patrón de conversión
         *
         * @return string Una cadena con los símbolos convertidos a sus valores respectivos.
         */
        function &parse()
        {
            if ($this->_pattern == null)
            {
                trigger_error("A conversion pattern has not been specified.", E_USER_ERROR);
                return;
            }

            $length  = strlen($this->_pattern);
            $pattern = null;

            for ($i = 0; $i < $length; $i++)
            {
                if ($this->_pattern{$i} == "%" && ($i + 1) < $length)
                {
                    if ($this->_pattern{$i + 1} == "%")
                    {
                        $data = "%";
                        $i++;
                    }
                    else
                    {
                        // grab conversion char
                        $char  = $this->_pattern{++$i};
                        $param = null;

                        // does a parameter exist?
                        if (($i + 1) < $length && $this->_pattern{$i + 1} == "{")
                        {
                            // retrieve parameter
                            $param = $this->getParameter($i);
                        }

                        if ($this->_obj == null)
                        {
                            $data = $function($char, $param);
                        }
                        else
                        {
                            $object   = &$this->_obj;
                            $function = &$this->_func;

                            $data = $object->$function($char, $param);
                        }
                    }

                    $pattern .= $data;
                }
                else
                {
                    $pattern .= $this->_pattern{$i};
                }
            }

            return $pattern;
        }

        /**
         * Establece la función que debe llamarse para convertir
         *
         * @param function string Nombre de la función
         */
        function setCallbackFunction($function)
        {
            $this->_func = $function;
        }

        /**
         * Establece el objeto y método que debe llamarse para convertir
         *
         * @param object El objeto que tiene el método para llamarlo
         * @param function string Nombre del método
         */
        function setCallbackObject(&$object, $function)
        {
            $this->_func = $function;
            $this->_obj  = &$object;
        }

        /**
         * Establece el patrón de conversión
         *
         * @param pattern string Un patrón que consiste en uno o más símbolos predefinidos
         */
        function setPattern($pattern)
        {
            $this->_pattern = $pattern;
        }
    }
?>