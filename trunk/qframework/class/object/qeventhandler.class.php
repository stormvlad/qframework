<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Manejador de eventos
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:22
     * @version 1.0
     * @ingroup core event
     */

    class qEventHandler extends qObject
    {
        var $_eventName;
        var $_objHandler;
        var $_objMethodName;

        /**
         * Constructor
         */
        function qEventHandler($event, &$obj, $method)
        {
            $this->qObject();

            $this->_eventName     = $event;
            $this->_objHandler    = &$obj;
            $this->_objMethodName = $method;
        }

        /**
         * Add function info here
         */
        function &getObjHandler()
        {
            return $this->_objHandler;
        }

        /**
         * Add function info here
         */
        function getObjMethodName()
        {
            return $this->_objMethodName;
        }

        /**
         * Add function info here
         */
        function getEventName()
        {
            return $this->_eventName;
        }

        /**
         * Add function info here
         */
        function setEventName($event)
        {
            $this->_eventName = $name;
        }

        /**
         * Add function info here
         */
        function setObjHandler(&$obj)
        {
            $this->_objHandler = &$obj;
        }

        /**
         * Add function info here
         */
        function setObjMethodName($method)
        {
            $this->_objMethodName = $method;
        }

        /**
         * Add function info here
         */
        function perform(&$sender, $eventArgs)
        {
            call_user_func(array(&$this->_objHandler, $this->_objMethodName), $sender, $eventArgs);
        }
    }
?>