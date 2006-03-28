<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Nos permite a�adir los mensajes de log en cualquier lugar
     *
     * qAppender es una clase abstracta que representa un agregador de mensajes de log.
     * Existe una clase derivada para cada tipo de medio de grabaci�n de mensajes.
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
        var $layout;

        /**
         * Constructor
         *
         * @param layout qLayout Inst�ncia de la plantilla a usar.
         */
        function &qAppender(&$layout)
        {
            parent::qObject();

            $this->layout =& $layout;
        }

        /**
         * Recupera la plantilla que usa este agregador
         *
         * @return qLayout Instancia de plantilla
         */
        function & getLayout()
        {
            return $this->layout;
        }

        /**
         * Establece la plantilla a usar por este agregador
         *
         * @param layout Layout Instancia de la plantilla
         */
        function setLayout(&$layout)
        {
            $this->layout =& $layout;
        }

        /**
         * Escribir en el agregador
         *
         * @param message El mensaje a escribir
         * @note No d�be usarse manualmente
         */
        function write ($message)
        {
            throw(new qException("qAppender::write: This method must be implemented by child classes."));
            die();
        }
    }

?>