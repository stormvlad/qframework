<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/user/quserstorage.class.php");

    /**
     * @brief Servicio de almacenaje para los datos de usuario en base de datos 
     *
     * @author  qDevel - info@qdevel.com
     * @date    18/03/2005 20:42
     * @version 1.0
     * @ingroup core
     */
    class qUserDbStorage extends qUserStorage
    {
        var $_db;

        /**
        * Add function info here
        */
        function qUserDbStorage(&$db)
        {
            $this->qUserStorage();
            $this->_db = &$db;
        }

        /**
         * Devuelve la sentencia SQL para cargar los datos de la sesión
         *
         * @private
         * @exception qUserDbStorage::_getSqlLoadStatement: This method must be implemented by child classes.
         */
        function _getSqlLoadStatement(&$user)
        {
            throw(new qException("qUserDbStorage::_getSqlLoadStatement: This method must be implemented by child classes."));
            die();
        }

        /**
         * Devuelve la sentencia SQL para salvar los datos de la sesión
         *
         * @private
         * @exception qUserDbStorage::_getSqlLoadStatement: This method must be implemented by child classes.
         */
        function _getSqlStoreStatement(&$user)
        {
            throw(new Exception("qUserDbStorage::_getSqlLoadStatement: This method must be implemented by child classes."));
            die();
        }

        /**
        * Add function info here
        */
        function load(&$user)
        {
            $sql    = $this->_getSqlLoadStatement($user);
            $result = $this->_db->Execute($sql);

            if (!$result)
            {
                return false;
            }

            $attributes = array();
            $row        = $result->FetchRow();

            foreach ($row as $name => $value)
            {
                $user->setAttribute($name, $value);
            }

            return true;
        }

        /**
        * Add function info here
        */
        function store(&$user)
        {
            $sql = $this->_getSqlStoreStatement($user);
            return $this->_db->Execute($sql);
        }
    }

?>