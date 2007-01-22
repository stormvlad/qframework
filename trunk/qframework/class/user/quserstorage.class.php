<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Servicio de almacenaje abstracto para los datos de usuario
     *
     * Esta clase es la base para definir el servicio de almacenaje para los
     * datos de qUser, sessión del usuario o visitante.
     *
     * @author  qDevel - info@qdevel.com
     * @date    18/03/2005 20:42
     * @version 1.0
     * @ingroup core
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
            trigger_error("This function must be implemented by child classes.", E_USER_ERROR);
            return;
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
            trigger_error("This function must be implemented by child classes.", E_USER_ERROR);
            return;
        }
    }

?>