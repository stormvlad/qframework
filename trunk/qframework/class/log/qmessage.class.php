<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Contine la informaci�n sobre un mensaje de log.
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:10
     * @version 1.0
     * @ingroup log
     */

    class qMessage extends qObject
    {
        /**
         * Un vector unidimensional associativo de par�metros de mensaje
         */
        var $_params;

        /**
         * Constructor
         *
         * @param params Un array associativo de par�metros
         */
        function qMessage($params = null)
        {
            $this->qObject();
            $this->_params = array();
            
            if (is_array($params))
            {
                $this->_params = $params;
            }
        }

        /**
         * Recupera un par�metro
         *
         * @param name Nombre de par�metro
         *
         * @return string El valor del par�metro, si un par�metro con este nombre existe,
         *                en otro caso <b>NULL</b>.
         */
        function getParameter($name)
        {
            if (isset($this->_params[$name]))
            {
                return $this->_params[$name];
            }

            return null;
        }

        /**
         * Determina si un par�metro est� definido
         *
         * @param name string Nombre del par�metro
         *
         * @return bool <b>TRUE</b>, si el par�metro esta definido,
         *              en otro caso <b>FALSE</b>.
         */
        function hasParameter($name)
        {
            return isset($this->_params[$name]);
        }

        /**
         * Establece un par�metro
         *
         * @param name string Nombre del par�metro
         * @param value string Valor del par�metro
         */
        function setParameter($name, $value)
        {
            $this->_params[$name] = $value;
        }

        /**
         * Establece un par�metro por referencia
         *
         * @param name string Nombre del par�metro
         * @param value string Referencia al valor del par�metro
         */
        function setParameterByRef($name, &$value)
        {
            $this->_params[$name] = &$value;
        }
    }

?>