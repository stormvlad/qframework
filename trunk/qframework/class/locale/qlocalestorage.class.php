<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Servicio de almacenaje de ficheros de idioma
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 17:49
     * @version 1.0
     * @ingroup i18n
     */
    class qLocaleStorage extends qObject
    {
        /**
         * @brief Constructor
         */
        function qLocaleStorage()
        {
            $this->qObject();
        }

        /**
         * @brief M�todo de carga de las traducciones
         *
         * Este m�todo d�be implementarse para cada una de las classes que representan un tipo de almacenamiento.
         *
         * @param locale <em>qLocale</em> Referencia a la instancia global del objeto de traducci�n
         * @return boolean Devuelve si la operaci�n se ha llevado a cabo con �xito
         */
        function load(&$locale)
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
            return;
        }

        /**
         * @brief Guarda una traducci�n
         *
         * Este m�todo d�be implementarse para cada una de las classes que representan un tipo de almacenamiento.
         *
         * @param locale <em>qLocale</em> Referencia a la instancia global del objeto de traducci�n
         * @param name <em>string</em> Identificador de la traducci�n
         * @param value <em>string</em> Cadena traducida
         * @return boolean Devuelve si la operaci�n se ha llevado a cabo con �xito
         */
        function saveValue(&$locale, $name, $value)
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
            return;
        }

        /**
         * @brief Guarda todas las traducciones actuales en mem�ria
         *
         * Este m�todo d�be implementarse para cada una de las classes que representan un tipo de almacenamiento.
         *
         * @param locale <em>qLocale</em> Referencia a la instancia global del objeto de traducci�n
         * @return boolean Devuelve si la operaci�n se ha llevado a cabo con �xito
         */
        function save(&$locale)
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
            return;
        }
    }
?>
