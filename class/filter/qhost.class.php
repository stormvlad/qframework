<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Representa una dirección IP o rango de direcciones.
     *
     * @author  qDevel - info@qdevel.com
     * @date    07/03/2005 23:46
     * @version 1.0
     * @ingroup filter     
     */
    class qHost extends qObject
    {
        var $_host;
        var $_mask;

        /**
        * Add function info here
        */
        function qHost($host, $mask = 32)
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
