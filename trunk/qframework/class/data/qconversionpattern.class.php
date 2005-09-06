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
        var $func;

        /**
         * El objeto que contiene la función que debe llamarse cuando se ha de convertir
         * un símbolo
         */
        var $obj;

        /**
         * Caena con el patron de conversión que contiene los símbolos
         */
        var $pattern;

        /**
         * Constructor
         *
         * @param pattern string Patrón de conversión que contiene los símbolos predefinidos
         */
        function &qConversionPattern($pattern = NULL)
        {
            parent::qObject();

            $this->func    = NULL;
            $this->obj     = NULL;
            $this->pattern = $pattern;
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
            $length = strlen($this->pattern);
            $param  = '';

            // avanzamos hata el parámetro
            $index += 2;

            if ($index < $length)
            {
                // recorremos a traves del símbolo del parámetro
                while ($this->pattern{$index} != '}' && $index < $length)
                {
                    $param .= $this->pattern{$index};
                    $index++;
                }

                if ($this->pattern{$index} == '}')
                {
                    return $param;
                }

                // se ha encontrado un parámetro pero no el final }
            }

            // oops, no hay suficiente texto para continuar
            return NULL;
        }

        /**
         * Devuelve el patrón de conversión
         *
         * @return string Patrón de conversión
         */
        function getPattern()
        {
            return $this->pattern;
        }

        /**
         * Parsea el patrón de conversión
         *
         * @return string Una cadena con los símbolos convertidos a sus valores respectivos.
         */
        function &parse()
        {
            if ($this->pattern == NULL)
            {
                throw(new qException("qConversionPattern::parse: A conversion pattern has not been specified."));
                return;
            }

            $length  = strlen($this->pattern);
            $pattern = '';

            for ($i = 0; $i < $length; $i++)
            {
                if ($this->pattern{$i} == '%' && ($i + 1) < $length)
                {
                    if ($this->pattern{$i + 1} == '%')
                    {
                        $data = '%';
                        $i++;
                    }
                    else
                    {
                        // grab conversion char
                        $char  = $this->pattern{++$i};
                        $param = NULL;

                        // does a parameter exist?
                        if (($i + 1) < $length && $this->pattern{$i + 1} == '{')
                        {
                            // retrieve parameter
                            $param = $this->getParameter($i);
                        }

                        if ($this->obj == NULL)
                        {
                            $data = $function($char, $param);
                        }
                        else
                        {
                            $object   =& $this->obj;
                            $function =& $this->func;

                            $data = $object->$function($char, $param);
                        }
                    }

                    $pattern .= $data;
                }
                else
                {
                    $pattern .= $this->pattern{$i};
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
            $this->func = $function;
        }

        /**
         * Establece el objeto y método que debe llamarse para convertir
         *
         * @param object El objeto que tiene el método para llamarlo
         * @param function string Nombre del método
         */
        function setCallbackObject(&$object, $function)
        {
            $this->func =  $function;
            $this->obj  =& $object;
        }

        /**
         * Establece el patrón de conversión
         *
         * @param pattern string Un patrón que consiste en uno o más símbolos predefinidos
         */
        function setPattern($pattern)
        {
            $this->pattern = $pattern;
        }
    }
?>