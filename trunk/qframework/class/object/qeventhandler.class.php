<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * This is the highest class on the top of our hierarchy. Provides some common methods
     * useful to deal with objects, an also some commodity methods for debugging such as
     * toString, which will dump the names and the values of the attributes of the object.
     * All the objects should inherit from this one and call this constructor manually, due
     * to PHP not automatically calling the parent's class constructor when inheriting.
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