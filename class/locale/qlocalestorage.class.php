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
        * Add function info here
        */
        function qLocaleStorage()
        {
            $this->qObject();
        }

        /**
        * Add function info here
        */
        function load(&$config)
        {
            throw(new qException("qLocaleStorage::load: This method must be implemented by child classes."));
            die();
        }

        /**
        * Add function info here
        */
        function saveValue(&$config, $name, $value)
        {
            throw(new qException("qLocaleStorage::saveValue: This method must be implemented by child classes."));
            die();
        }

        /**
        * Add function info here
        */
        function save(&$config)
        {
            throw(new qException("qLocaleStorage::save: This method must be implemented by child classes."));
            die();
        }
    }
?>
