<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Represents a record from the plog_blocked_hosts table.
     */
    class qBlackHost extends qObject
    {
        var $_host;
        var $_mask;

        /**
        * Add function info here
        */
        function qBlackHost($host, $mask = 32)
        {
            $this->qObject();

            $this->_host = $host;
            $this->_mask = $mask;
        }

        /**
        * Add function info here
        */
        function getHost()
        {
            return $this->_host;
        }

        /**
        * Add function info here
        */
        function getMask()
        {
            return $this->_mask;
        }

        /**
        * Add function info here
        */
        function getCidrAddress()
        {
            return $this->_host . "/" . $this->_mask;
        }

        /**
        * Add function info here
        */
        function setMask($mask)
        {
            $this->_mask = $mask;
        }

        /**
        * Add function info here
        */
        function setHost($host)
        {
            $this->_host = $host;
        }
    }
?>