<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
    *  Base class for data access objects
    */
    class qDao extends qObject
    {
        var $_db;

        /**
        * Add function info here
        */
        function qDao(&$db)
        {
            $this->_db= &$db;
        }

        /**
        * Add function info here
        */
        function retrieve($sql)
        {
            $result = $this->_db->Execute($sql);

            if (!$result)
            {
                return false;
            }

            return $result;
        }

        /**
        * Add function info here
        */
        function update($sql)
        {
            $result = $this->_db->Execute($sql);

            if (!$result)
            {
                return false;
            }

            return true;
        }
    }

?>