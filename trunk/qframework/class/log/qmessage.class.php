<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Contine la información sobre un mensaje de log.
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:10
     * @version 1.0
     * @ingroup log
     */

    class qMessage extends qObject
    {
        /**
         * Un vector unidimensional associativo de parámetros de mensaje
         */
        var $params;

        /**
         * Constructor
         *
         * @param params Un array associativo de parámetros
         */
        function &qMessage ($params = NULL)
        {
            parent::qObject();

            $this->params = ($params == NULL) ? array() : $params;
        }

        /**
         * Recupera un parámetro
         *
         * @param name Nombre de parámetro
         *
         * @return string El valor del parámetro, si un parámetro con este nombre existe,
         *                en otro caso <b>NULL</b>.
         */
        function &getParameter ($name)
        {
            if (isset($this->params[$name]))
            {
                return $this->params[$name];
            }

            return NULL;
        }

        /**
         * Determina si un parámetro está definido
         *
         * @param name string Nombre del parámetro
         *
         * @return bool <b>TRUE</b>, si el parámetro esta definido,
         *              en otro caso <b>FALSE</b>.
         */
        function hasParameter ($name)
        {
            return isset($this->params[$name]);
        }

        /**
         * Establece un parámetro
         *
         * @param name string Nombre del parámetro
         * @param value string Valor del parámetro
         */
        function setParameter ($name, $value)
        {
            $this->params[$name] = $value;
        }

        /**
         * Establece un parámetro por referencia
         *
         * @param name string Nombre del parámetro
         * @param value string Referencia al valor del parámetro
         */
        function setParameterByRef ($name, &$value)
        {
            $this->params[$name] =& $value;
        }
    }

?>