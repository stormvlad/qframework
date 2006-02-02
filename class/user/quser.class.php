<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/user/qusersessionstorage.class.php");

    define("DEFAULT_USER_PERMISSIONS_LEVEL", "__all__");
    define("DEFAULT_USER_HISTORY_SIZE", 10);

    /**
     * @brief Representa un cliente de la aplicación
     *
     * qUser es un objeto que representa un visitante del web usando el qFramework.
     * Cada visita única en el web es representada con una instancia de la clase qUser
     * y este objeto permanece persistente durante toda la sessión del cliente.
     * Esta es una grande ventaja de qFramework en la qual se mantiene toda la información
     * que pertenece a la sessión organizada de una forma lógica.
     *
     * El objeto qUser es especial ya que usa un objeto qUserStorage para obtener la persistencia.
     * Se pueden guardar datos especificos de cada usuario con el método setAttribute y 
     * guardar los datos allí.
     * 
     * Se puede guardar también la historia de un formulario de múltiples pasos.
     *
     * qUser también es usado para configurar la seguridad del web. Se pueden restringir
     * el acceso a usuarios registrados o incluso definir permisos concretos para secciones
     * del web.
     *
     * @author  qDevel - info@qdevel.com
     * @date    18/03/2005 20:42
     * @version 1.0
     * @ingroup core
     */
    class qUser extends qObject
    {
        var $_sid;
        var $_loaded;
        var $_storage;
        var $_authenticated;
        var $_loginName;
        var $_lastActionTime;
        var $_lastUri;
        var $_attributes;
        var $_attributesVolatile;
        var $_formValues;
        var $_permissions;
        var $_lifeTime;

        var $_history;
        var $_historyIndex;
        var $_historySize;

        var $_backed;

        /**
        * Add function info here
        */
        function qUser($sid, &$storage, $lifeTime = 0)
        {
            $this->qObject();

            $this->_sid      = $sid;
            $this->_storage  = &$storage;
            $this->_lifeTime = $lifeTime;
            
            $this->reset();
            $this->load();

            if ($this->isLifeTimeExpired())
            {
                $this->reset();
                $this->destroy();
            }
        }

        /**
         * Devuelve una instancia de la clase qUser
         *
         * @note Basado en el patrón Singleton. El objectivo de este método es asegurar que exista sólo una instancia de esta clase y proveer de un punto global de accesso a ella.
         * @return qUser
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
        function reset()
        {
            $this->_loaded             = false;
            $this->_authenticated      = false;
            $this->_loginName          = null;
            $this->_lastActionTime     = null;
            $this->_lastUri            = null;
            $this->_attributes         = new qProperties();
            $this->_attributesVolatile = array();
            $this->_formValues         = array();
            $this->_permissions        = array();
            
            $this->_history            = array();
            $this->_historyIndex       = 0;
            $this->_historySize        = DEFAULT_USER_HISTORY_SIZE;

            $this->_backed             = false;
        }

        /**
        * Add function info here
        */
        function isLifeTimeExpired()
        {
            if (empty($this->_lifeTime))
            {
                return false;
            }

            $time = $this->getLastActionTime();
            $d1   = new qDate($time);
            $sec1 = $d1->getDate(DATE_FORMAT_UNIXTIME);
            $d2   = new qDate();
            $sec2 = $d2->getDate(DATE_FORMAT_UNIXTIME);

            return ($sec2 - $sec1 >= $this->_lifeTime) && !empty($time);
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
        function getLifeTime()
        {
            return $this->_lifeTime;
        }

        /**
        * Add function info here
        */
        function setLifeTime($time)
        {
            $this->_lifeTime = $time;

            if ($this->isLifeTimeExpired())
            {
                $this->reset();
                $this->destroy();
            }
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
        function getHistoryIndex()
        {
            return $this->_historyIndex;
        }

        /**
        * Add function info here
        */
        function setHistoryIndex($index)
        {
            $this->_historyIndex = $index;
        }
        
        /**
        * Add function info here
        */
        function getHistorySize()
        {
            return $this->_historySize;
        }

        /**
        * Add function info here
        */
        function setHistorySize($size)
        {
            if (empty($size))
            {
                $size = DEFAULT_USER_HISTORY_SIZE;
            }
            
            $this->_historySize = $size;
        }

        /**
        * Add function info here
        */
        function &getHistory()
        {
            return $this->_history;
        }

        /**
        * Add function info here
        */
        function setHistory(&$history)
        {
            $this->_history = $history;
        }
        
        /**
        * Add function info here
        */
        function getHistoryUri($index = 0, $updateHistoryIndex = true)
        {
            $index = $this->_historyIndex + $index -1;

            if ($index < 0)
            {
                $index = $this->_historySize + $index;
            }

            if ($updateHistoryIndex)
            {
                $this->_historyIndex = $index;
            }
            
            return $this->_history[$index];
        }

        /**
        * Add function info here
        */
        function saveUriToHistory($uri = null)
        {
            if (empty($uri))
            {
                $server = &qHttp::getServerVars();
                $uri = $server->getValue("REQUEST_URI");
            }

            $prev = ($this->_historyIndex - 1) % $this->_historySize;
            
            if (!isset($this->_history[$prev]) || $this->_history[$prev] != $uri)
            {
                $this->_history[$this->_historyIndex] = $uri;
                $this->_historyIndex = ($this->_historyIndex + 1) % $this->_historySize;
            }
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
        function setAttributes($attributes, $volatile = false)
        {
            foreach ($attributes as $name => $value)
            {
                $this->setAttribute($name, $value, $volatile);
            }
        }

        /**
        * Add function info here
        */
        function setAttribute($name, $value, $volatile = false)
        {
            if ($volatile)
            {
                $this->setVolatile($name, $volatile);
            }

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
        function isVolatile($name)
        {
            return !empty($this->_attributesVolatile[$name]);
        }

        /**
        * Add function info here
        */
        function setVolatile($name, $volatile = true)
        {
            $this->_attributesVolatile[$name] = $volatile;
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
            if (empty($this->_formValues[$formName]))
            {
                return false;
            }
            
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

            if (empty($this->_formValues[$formName]))
            {
                return false;
            }
            
            return array_key_exists($name, $this->_formValues[$formName][$step]);
        }

        /**
        * Add function info here
        */
        function getFormValue($formName, $name, $step = null)
        {
            $step = $this->getNormalizedStep($formName, $step);

            if (empty($this->_formValues[$formName]))
            {
                return false;
            }
            
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

            if (empty($this->_formValues[$formName]))
            {
                return false;
            }
            
            $step = $this->getNormalizedStep($formName, $step);
            return $this->_formValues[$formName][$step];
        }

        /**
        * Add function info here
        */
        function setFormValue($formName, $name, $value, $step = null)
        {
            if (empty($this->_formValues[$formName]))
            {
                $this->_formValues[$formName] = array();
            }
            
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
            if (empty($this->_formValues[$formName]))
            {
                return;
            }
            
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

            foreach ($this->_attributesVolatile as $key => $value)
            {
                $this->removeAttribute($key);
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
            @session_destroy();
        }
    }
?>
