<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
    * Add class info here
    */
    class qSoapError extends qObject
    {
        var $_msg;

        /**
        * Constructor
        */
        function qSoapError($msg)
        {
            $this->qObject();

            $this->_msg = $msg;
        }

        /**
        * Add function info here
        */
        function getMessage()
        {
            return $this->_msg;
        }

        /**
        * Add function info here
        */
        function setMessage($msg)
        {
            $this->_msg = $msg;
        }
    }

?>