<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qexception.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qeventhandler.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qeventmanager.class.php");


    /**
     * This is the highest class on the top of our hierarchy. Provides some common methods
     * useful to deal with objects, an also some commodity methods for debugging such as
     * toString, which will dump the names and the values of the attributes of the object.
     * All the objects should inherit from this one and call this constructor manually, due
     * to PHP not automatically calling the parent's class constructor when inheriting.
     */
    class qObject
    {
        var $_debug;
        var $_eventMap;

        /**
         * Constructor
         */
        function qObject()
        {
            if (defined("_DEBUG_"))
            {
                $this->_debug = _DEBUG_;
            }
            else
            {
                $this->_debug = false;
            }

            // Not initialized to an array for memory amount reasons. Initialized on demand
            $this->_eventMap = null;
        }

        /**
        * Add function info here
        */
        function getEventCode($index)
        {
            if (empty($this->_eventMap) || empty($this->_eventMap[$index]))
            {
                return strtoupper($this->getClassName()) . "_EVENT" . $index;
            }

            return $this->_eventMap[$index];
        }

        /**
        * Add function info here
        */
        function setEventCode($index, $code)
        {
            if (empty($this->_eventMap))
            {
                $this->_eventMap = array();
            }

            $this->_eventMap[$index] = $code;
        }

        /**
        * Add function info here
        */
        function isDebug()
        {
            return $this->_debug;
        }

        /**
        * Add function info here
        */
        function setDebug($debug = true)
        {
            $this->_debug = $debug;
        }

        /**
         * Returns a string with a representation of the class
         * @return The string representing the object
         */
        function toString()
        {
            return get_class($this) . " " . $this->_dumpVars();
        }

        /**
         * Add function info here
         */
        function _dumpVars()
        {
            $vars = get_object_vars($this);
            $res  = "[";

            foreach ($vars as $key => $value)
            {
                $res .= " " . $key . "=" . $value;
            }

            return $res .= " ]";
        }

        /**
         * Returns the name of the class
         * @return String with the name of the class
         */
        function getClassName()
        {
            return get_class($this);
        }

        /**
         * Returns the name of the parent class
         * @return String containing the name of the parent class
         */
        function getParentClassName()
        {
            return get_parent_class($this);
        }

        /**
         * Returns true if the current class is a subclass of the given
         * class
         * @param $object The object.
         * @return True if the object is a subclass of the given object or false otherwise.
         */
        function isSubclass($mixed)
        {
            if (is_object($mixed))
            {
                return is_subclass_of($this, $mixed->getClassName());
            }
            else if (is_string($mixed))
            {
                return is_subclass_of($this, $mixed);
            }
            else
            {
                return false;
            }
        }

        /**
         * Returns an array containing the methods available in this class
         * @return Array containing all the methods available in the object.
         */
        function getMethods()
        {
            return get_class_methods($this);
        }

        /**
        * Add function info here
        */
        function hasMethod($method)
        {
            return in_array(strtolower($method), get_class_methods($this));
        }

        /**
         * Returns true if the class is of the given type.
         *
         * @param object Object
         * @return Returns true if they are of the same type or false otherwise.
         */
        function typeOf($mixed)
        {
            if (is_object($mixed))
            {
                return is_a($this, $mixed->getClassName());
            }
            else if (is_string($mixed))
            {
                return is_a($this, $mixed);
            }
            else
            {
                return false;
            }
        }

        /**
         * Add function info here
         */
        function unregisterEvent($event)
        {
            $eventCode = $event;

            if (is_int($eventCode))
            {
                $eventCode = $this->getEventCode($eventCode);
            }

            $eventManager = &qEventManager::getInstance();
            return $eventManager->unregisterEvent($this, $eventCode);
        }

        /**
         * Add function info here
         */
        function registerEvent($event, $eventCode = null)
        {
            if (empty($eventCode))
            {
                $eventCode = $this->getEventCode($event);
            }
            else
            {
                $this->setEventCode($event, $eventCode);
            }

            $eventManager = &qEventManager::getInstance();
            return $eventManager->registerEvent($this, $eventCode);
        }

        /**
         * Add function info here
         */
        function addEventHandler($event, &$obj, $method)
        {
            $eventCode = $event;

            if (is_int($eventCode))
            {
                $eventCode = $this->getEventCode($eventCode);
            }

            $eventManager = &qEventManager::getInstance();
            return $eventManager->addEventHandler($eventCode, $obj, $method);
        }

        /**
         * Add function info here
         */
        function sendEvent($event, $eventArgs = array())
        {
            $eventCode = $event;

            if (is_int($eventCode))
            {
                $eventCode = $this->getEventCode($eventCode);
            }

            $eventArgs    = array_merge(array("event" => $eventCode), $eventArgs);
            $eventManager = &qEventManager::getInstance();

            return $eventManager->sendEvent($this, $eventCode, $eventArgs);
        }
    }
?>