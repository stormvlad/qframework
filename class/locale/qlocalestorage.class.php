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
         * @brief Método de carga de las traducciones
         *
         * Este método débe implementarse para cada una de las classes que representan un tipo de almacenamiento.
         *
         * @param locale <em>qLocale</em> Referencia a la instancia global del objeto de traducción
         * @return boolean Devuelve si la operación se ha llevado a cabo con éxito
         */
        function load(&$locale)
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
            return;
        }

        /**
         * @brief Guarda una traducción
         *
         * Este método débe implementarse para cada una de las classes que representan un tipo de almacenamiento.
         *
         * @param locale <em>qLocale</em> Referencia a la instancia global del objeto de traducción
         * @param name <em>string</em> Identificador de la traducción
         * @param value <em>string</em> Cadena traducida
         * @return boolean Devuelve si la operación se ha llevado a cabo con éxito
         */
        function saveValue(&$locale, $name, $value)
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
            return;
        }

        /**
         * @brief Guarda todas las traducciones actuales en memória
         *
         * Este método débe implementarse para cada una de las classes que representan un tipo de almacenamiento.
         *
         * @param locale <em>qLocale</em> Referencia a la instancia global del objeto de traducción
         * @return boolean Devuelve si la operación se ha llevado a cabo con éxito
         */
        function save(&$locale)
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
            return;
        }
    }
?>
