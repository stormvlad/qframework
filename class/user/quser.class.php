<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/user/qusersessionstorage.class.php");

    define("DEFAULT_USER_PERMISSIONS_LEVEL", "__all__");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qUser extends qObject
    {
        var $_sid;
        var $_loaded;
        var $_storage;
        var $_authenticated;
        var $_loginName;
        var $_lastActionTime;
        var $_attributes;
        var $_formValues;
        var $_permissions;

        /**
        * Add function info here
        */
        function qUser($sid, &$storage)
        {
            $this->qObject();

            $this->_sid            = $sid;
            $this->_loaded         = false;
            $this->_storage        = &$storage;
            $this->_authenticated  = false;
            $this->_loginName      = null;
            $this->_lastActionTime = null;
            $this->_attributes     = new qProperties();
            $this->_formValues     = array();
            $this->_permissions    = array();
        }

        /**
        * Add function info here
        */
        function &getInstance()
        {
            static $user;

            if (!isset($user))
            {
                if (!session_id())
                {
                    session_start();
                }

                $user = new qUser(session_id(), new qUserSessionStorage());
            }

            return $user;
        }

        /**
        * Add function info here
        */
        function getSid()
        {
            return $this->_sid;
        }

        /**
        * Add function info here
        */
        function setSid($sid)
        {
            $this->_sid = $sid;
        }

        /**
        * Add function info here
        */
        function isLoaded()
        {
            return $this->_loaded;
        }

        /**
        * Add function info here
        */
        function isAuthenticated()
        {
            return $this->_authenticated;
        }

        /**
        * Add function info here
        */
        function setAuthenticated($auth = true)
        {
            $this->_authenticated = $auth;
        }

        /**
        * Add function info here
        */
        function getLoginName()
        {
            return $this->_loginName;
        }

        /**
        * Add function info here
        */
        function setLoginName($name)
        {
            $this->_loginName = $name;
        }

        /**
        * Add function info here
        */
        function getLastActionTime()
        {
            return $this->_lastActionTime;
        }

        /**
        * Add function info here
        */
        function setLastActionTime($time)
        {
            $this->_lastActionTime = $time;
        }

        /**
        * Add function info here
        */
        function &getAttributes()
        {
            return $this->_attributes->getAsArray();
        }

        /**
        * Add function info here
        */
        function getAttribute($name)
        {
            return $this->_attributes->getValue($name);
        }

        /**
        * Add function info here
        */
        function &getAttributeRef($name)
        {
            return $this->_attributes->getValueRef($name);
        }

        /**
        * Add function info here
        */
        function setAttributes($attributes)
        {
            foreach ($attributes as $name => $value)
            {
                $this->_attributes->setValue($name, $value);
            }
        }

        /**
        * Add function info here
        */
        function setAttribute($name, $value)
        {
            $this->_attributes->setValue($name, $value);
        }

        /**
        * Add function info here
        */
        function removeAttribute($name)
        {
            $this->_attributes->removeValue($name);
        }

        /**
        * Add function info here
        */
        function hasAttribute($name)
        {
            return $this->_attributes->keyExists($name);
        }

        /**
        * Add function info here
        */
        function &getAllFormValues()
        {
            return $this->_formValues;
        }

        /**
        * Add function info here
        */
        function getNormalizedStep($formName, $step)
        {
            if ($step === null)
            {
                $step = count($this->_formValues[$formName]) - 1;
            }
            else if ($step < 0)
            {
                $step = count($this->_formValues[$formName]) -1 + $step;
            }

            if ($step <  0)
            {
                $step = 0;
            }

            return $step;
        }

        /**
        * Add function info here
        */
        function getNextStep($formName)
        {
            return count($this->_formValues[$formName]);
        }

        /**
        * Add function info here
        */
        function formValueExists($formName, $name, $step = null)
        {
            $step = $this->getNormalizedStep($formName, $step);
            return array_key_exists($name, $this->_formValues[$formName][$step]);
        }

        /**
        * Add function info here
        */
        function getFormValue($formName, $name, $step = null)
        {
            $step = $this->getNormalizedStep($formName, $step);
            return $this->_formValues[$formName][$step][$name];
        }

        /**
        * Add function info here
        */
        function &getFormValues($formName = null, $step = null)
        {
            if (empty($formName))
            {
                return $this->_formValues;
            }
            else
            {
                $step = $this->getNormalizedStep($formName, $step);

                if (empty($this->_formValues[$formName][$step]))
                {
                    return false;
                }

                return $this->_formValues[$formName][$step];
            }
        }

        /**
        * Add function info here
        */
        function setFormValue($formName, $name, $value, $step = null)
        {
            $step = $this->getNormalizedStep($formName, $step);
            $this->_formValues[$formName][$step][$name] = $value;
        }

        /**
        * Add function info here
        */
        function setFormValues($formName = null, $values = null, $step = null)
        {
            if (empty($formName))
            {
                $this->_formValues = $values;
            }
            else
            {
                foreach ($values as $key => $value)
                {
                    $this->setFormValue($formName, $key, $value, $step);
                }
            }
        }

        /**
        * Add function info here
        */
        function removeFormValue($formName, $name, $step = null)
        {
            $step = $this->getNormalizedStep($formName, $step);
            unset($this->_formValues[$formName][$step][$name]);
        }

        /**
        * Add function info here
        */
        function resetFormValues($formName = null)
        {
            if (empty($formName))
            {
                $this->_formValues = array();
            }
            else
            {
                $this->_formValues[$formName] = array();
            }
        }

        /**
        * Add function info here
        */
        function &getPermissions()
        {
            return $this->_permissions;
        }

        /**
        * Add function info here
        */
        function setPermissions(&$permissions)
        {
            $this->_permissions = &$permissions;
        }

        /**
        * Add function info here
        */
        function setPermission($name, $level = DEFAULT_USER_PERMISSIONS_LEVEL)
        {
            $this->_permissions[$level][$name] = true;
        }

        /**
        * Add function info here
        */
        function resetPermissions()
        {
            $this->_permissions = array();
        }

        /**
        * Add function info here
        */
        function removePermission($name, $level = DEFAULT_USER_PERMISSIONS_LEVEL)
        {
            unset($this->_permissions[$level][$name]);
        }

        /**
        * Add function info here
        */
        function hasPermission($name, $level = DEFAULT_USER_PERMISSIONS_LEVEL)
        {
            return is_array($this->_permissions[$level]) && array_key_exists($name, $this->_permissions[$level]);
        }

        /**
        * Add function info here
        */
        function load()
        {
            $this->_loaded = true;
            $this->_storage->load($this);
        }

        /**
        * Add function info here
        */
        function store()
        {
            if (!$this->isLoaded())
            {
                $this->load();
            }

            $this->_storage->store($this);
        }

        /**
        * Add function info here
        */
        function destroy()
        {
            $cookieInfo = session_get_cookie_params();

            if ((empty($cookieInfo["domain"])) && (empty($cookieInfo["secure"])))
            {
                setcookie(session_name(), "", time() - 3600, $cookieInfo["path"]);
            }
            else if (empty($cookieInfo["secure"]))
            {
                setcookie(session_name(), "", time() - 3600, $cookieInfo["path"], $cookieInfo["domain"]);
            }
            else
            {
                setcookie(session_name(), "", time() - 3600, $cookieInfo["path"], $cookieInfo["domain"], $cookieInfo["secure"]);
            }

            unset($_COOKIE[session_name()]);
            session_destroy();
        }
    }
?>
