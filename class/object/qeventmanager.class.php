<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qeventhandler.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * This is the highest class on the top of our hierarchy. Provides some common methods
     * useful to deal with objects, an also some commodity methods for debugging such as
     * toString, which will dump the names and the values of the attributes of the object.
     * All the objects should inherit from this one and call this constructor manually, due
     * to PHP not automatically calling the parent's class constructor when inheriting.
     */
    class qEventManager extends qObject
    {
        var $_registeredEvents;
        var $_events;

        /**
         * Constructor
         */
        function qEventManager()
        {
            // cannot call qObject constructor for ciclic reasons :-)
            $this->_registeredEvents = array();
            $this->_events           = array();
        }

        /**
         * Add function info here
         */
        function &getEventManager()
        {
            static $eventManagerInstance;

            if (!isset($eventManagerInstance))
            {
                $eventManagerInstance = new qEventManager();
            }

            return $eventManagerInstance;
        }

        /**
         * Add function info here
         */
        function registerEvent(&$obj, $event)
        {
            if (array_key_exists($event, $this->_registeredEvents))
            {
                throw(new qException("qEventManager::registerEvent: '" . $obj->getClassName() . "' class cannot register the event with code '" . $event . "' because it's already registered to '" . $this->_registeredEvents[$event] . "' class."));
                return false;
            }

            $this->_registeredEvents[$event] = $obj->getClassName();
            return true;
        }

        /**
         * Add function info here
         */
        function addEventHandler($event, &$obj, $method)
        {
            $this->_events[$event][] = new qEventHandler($event, $obj, $method);
            return true;
        }

        /**
         * Add function info here
         */
        function sendEvent(&$sender, $event, $eventArgs = array())
        {
            if (!array_key_exists($event, $this->_registeredEvents))
            {
                throw(new qException("qEventManager::sendEvent: '" . $sender->getClassName() . "' class cannot send the event with code '" . $event . "' because there are no events with this code. First time you must register the event with registerEvent method."));
                return false;
            }
            else
            {
                $registeredClass = $this->_registeredEvents[$event];

                if ($sender->getClassName() != $registeredClass && !$sender->isSubclass($registeredClass))
                {
                    throw(new qException("qEventManager::sendEvent: '" . $sender->getClassName() . "' class cannot send the event with code '" . $event . "' because it's already registered to '" . $registeredClass . "' class."));
                    return false;
                }
            }

            if (is_array($this->_events[$event]))
            {
                foreach ($this->_events[$event] as $eventHandler)
                {
                    $eventHandler->perform($sender, $eventArgs);
                }
            }

            return true;
        }
    }
?>
