<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qUserStorage extends qObject
    {
        /**
         * Constructor
         */
        function qUserStorage()
        {
            $this->qObject();
        }

        /**
         * Carga los datos de la sesión del usuario
         *
         * @param user <em>qUser</em> Referencia al objeto usuario donde cargar los datos
         * @exception qUserStorage::load: This method must be implemented by child classes.
         * @private
         */
        function load(&$user)
        {
            throw(new qException("qUserStorage::load: This method must be implemented by child classes."));
            die();
        }

        /**
         * Salva los datos de la sesión del usuario
         *
         * @param user <em>qUser</em> Referencia al objeto usuario donde salvar los datos
         * @exception qUserStorage::store: This method must be implemented by child classes.
         * @private
         */
        function store(&$user)
        {
            throw(new qException("qUserStorage::store: This method must be implemented by child classes."));
            die();
        }
    }

?>