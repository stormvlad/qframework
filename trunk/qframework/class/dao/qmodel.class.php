<?php

    include_once("qframework/class/object/qobject.class.php" );

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

        //! An accessor
        /**
        * For SELECT queries
        * @param $sql the query string
        * @return mixed either false if error or object DataAccessResult
        */
        function & retrieve ($sql) {
            $result=& $this->da->fetch($sql);
            if ($error=$result->isError()) {
                trigger_error($error);
                return false;
            } else {
                return $result;
            }
        }

        //! An accessor
        /**
        * For INSERT, UPDATE and DELETE queries
        * @param $sql the query string
        * @return boolean true if success
        */
        function update ($sql) {
            $result=$this->da->fetch($sql);
            if ($error=$result->isError()) {
                trigger_error($error);
                return false;
            } else {
                return true;
            }
        }
    }

?>