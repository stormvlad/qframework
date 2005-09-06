<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Conversi�n para cadenas con patrones
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
         * Cadena con el nombre de la funci�n que debe llamarse
         * cuando se ha de convertir un s�mbolo
         */
        var $func;

        /**
         * El objeto que contiene la funci�n que debe llamarse cuando se ha de convertir
         * un s�mbolo
         */
        var $obj;

        /**
         * Caena con el patron de conversi�n que contiene los s�mbolos
         */
        var $pattern;

        /**
         * Constructor
         *
         * @param pattern string Patr�n de conversi�n que contiene los s�mbolos predefinidos
         */
        function &qConversionPattern($pattern = NULL)
        {
            parent::qObject();

            $this->func    = NULL;
            $this->obj     = NULL;
            $this->pattern = $pattern;
        }

        /**
         * Devuelve un par�metro para un s�mbolo de conversi�n.
         *
         * @param index int El �ndice del patr�n en el que estamos trabajando.
         *
         * @return string Un s�mbolo de conversi�n si el par�metro existe,
         *                sino <b>NULL</b>.
         */
        function getParameter(&$index)
        {
            $length = strlen($this->pattern);
            $param  = '';

            // avanzamos hata el par�metro
            $index += 2;

            if ($index < $length)
            {
                // recorremos a traves del s�mbolo del par�metro
                while ($this->pattern{$index} != '}' && $index < $length)
                {
                    $param .= $this->pattern{$index};
                    $index++;
                }

                if ($this->pattern{$index} == '}')
                {
                    return $param;
                }

                // se ha encontrado un par�metro pero no el final }
            }

            // oops, no hay suficiente texto para continuar
            return NULL;
        }

        /**
         * Devuelve el patr�n de conversi�n
         *
         * @return string Patr�n de conversi�n
         */
        function getPattern()
        {
            return $this->pattern;
        }

        /**
         * Parsea el patr�n de conversi�n
         *
         * @return string Una cadena con los s�mbolos convertidos a sus valores respectivos.
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
         * Establece la funci�n que debe llamarse para convertir
         *
         * @param function string Nombre de la funci�n
         */
        function setCallbackFunction($function)
        {
            $this->func = $function;
        }

        /**
         * Establece el objeto y m�todo que debe llamarse para convertir
         *
         * @param object El objeto que tiene el m�todo para llamarlo
         * @param function string Nombre del m�todo
         */
        function setCallbackObject(&$object, $function)
        {
            $this->func =  $function;
            $this->obj  =& $object;
        }

        /**
         * Establece el patr�n de conversi�n
         *
         * @param pattern string Un patr�n que consiste en uno o m�s s�mbolos predefinidos
         */
        function setPattern($pattern)
        {
            $this->pattern = $pattern;
        }
    }
?>