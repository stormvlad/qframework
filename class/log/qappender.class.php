<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Nos permite añadir los mensajes de log en cualquier lugar
     *
     * qAppender es una clase abstracta que representa un agregador de mensajes de log.
     * Existe una clase derivada para cada tipo de medio de grabación de mensajes.
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:10
     * @version 1.0
     * @ingroup log
     */

    class qAppender extends qObject
    {
        /**
         * La plantilla a usar por esta classe
         */
        var $_layout;

        /**
         * Constructor
         *
         * @param layout qLayout Instáncia de la plantilla a usar.
         */
        function qAppender(&$layout)
        {
            $this->qObject();
            $this->_layout = &$layout;
        }

        /**
         * Recupera la plantilla que usa este agregador
         *
         * @return qLayout Instancia de plantilla
         */
        function &getLayout()
        {
            return $this->_layout;
        }

        /**
         * Establece la plantilla a usar por este agregador
         *
         * @param layout Layout Instancia de la plantilla
         */
        function setLayout(&$layout)
        {
            $this->_layout = &$layout;
        }

        /**
         * Escribir en el agregador
         *
         * @param message El mensaje a escribir
         * @note No débe usarse manualmente
         */
        function write($message)
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
            return;
        }
    }

?>