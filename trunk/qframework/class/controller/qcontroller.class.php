<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/action/qaction.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/controller/qcontrollerparams.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qvalidationslist.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/security/qfilterschain.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/user/quser.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/user/qusersessionstorage.class.php");

    define(DEFAULT_ACTION_PARAM, "op");
    define(DEFAULT_ACTION_NAME, "default");
    define(DEFAULT_ACTIONS_CLASS_PATH, "class/action/");

    /**
     * This is how MVC works, using the pattern of the 'Front Controller'. With this pattern, we have
     * a single controller class that receives all the requests from the client, identifies the
     * action to be taken and the relays the execution of the action to the most suitable Action class.
     * The 'Action' class then will use the application business logic (the Model) to carry out the
     * operations necessary.
     *
     * (according to http://java.sun.com/blueprints/guidelines/designing_enterprise_applications_2e/web-tier/web-tier5.html)
     *
     * 1. The controller receives a POST from the client.
     * 2. The controller creates an Action corresponding to the requested operation (as described in the previous section).
      * 3. The controller calls the Action's perform method.
     * perform calls a model business method.
     * 4. The controller calls the screen flow manager to select the next view to display.
     * 5. The screen flow manager determines the next view and returns its name to the controller.
     * 6. The controller forwards the request to the templating service, which assembles and delivers the selected view to the client.
     *
     * In our particular case, we have two kinds of Actions: Action and ActionForm. The first is the
     * one that should be normally used, while the second should be used when receiving data from
     * forms, since it provides an additional method, validate() that will allow the developer
     * to validate the data that came from the form. If the result of validate() is 'false',
     * the controller will <b>not</b> call the perform() method and stop execution. However, the
     * validate method should also generate a valid view containing probably the error message.
     */

    class qController extends qObject
    {
        var $_actionMap;
        var $_actionParam;
        var $_actionsClassPath;
        var $_defaultAction;
        var $_sessionEnabled;
        var $_actionsChain;
        var $_forwarded;
        var $_user;

        /**
         * $ActionsMap is an associative array of the form:
         *
         * ($actionName, $actionClassName)
         *
         * Where for every different possible value of the 'action' parameter in the request,
         * there is an object inheriting form the Action class that will take care of
         * that requested action.
         *
         * @param actionMap is the associative array with the mappings
         * @param actionParam is the name of the parameter in the request that will be used
         */
        function qController()
        {
            $this->qObject();

            $this->_actionMap        = array();
            $this->_actionParam      = DEFAULT_ACTION_PARAM;
            $this->_actionsClassPath = DEFAULT_ACTIONS_CLASS_PATH;
            $this->_defaultAction    = DEFAULT_ACTION_NAME;
            $this->_sessionEnabled   = false;
            $this->_actionsChain     = array();
            $this->_forwarded        = 0;
            $this->_user             = null;
        }

        /**
         * Add function info here
         */
        function &getController()
        {
            static $controllerInstance;

            if (!isset($controllerInstance))
            {
                $controllerInstance = new qContoller();
            }

            return $controllerInstance;
        }

        /**
         * Add function info here
         */
        function &getUser()
        {
            return $this->_user;
        }

        /**
         * Add function info here
         */
        function setUser(&$user)
        {
            $this->_user = $user;
        }

        /**
         * Add function info here
         */
        function getActionParam()
        {
            return $this->_actionParam;
        }

        /**
         * Add function info here
         */
        function setActionParam($actionParam)
        {
            $this->_actionParam = $actionParam;
        }

        /**
         * Add function info here
         */
        function getActionsClassPath()
        {
            return $this->_actionsClassPath;
        }

        /**
         * Add function info here
         */
        function setActionsClassPath($path)
        {
            $this->_actionsClassPath = $path;
        }

        /**
         * Add function info here
         */
        function getDefaultAction()
        {
            return $this->_defaultAction;
        }

        /**
         * Add function info here
         */
        function setDefaultAction($actionClassName)
        {
            $this->_defaultAction = $actionClassName;
        }

        /**
         * Add function info here
         */
        function getSessionEnabled()
        {
            return $this->_sessionEnabled;
        }

        /**
         * Add function info here
         */
        function setSessionEnabled($enabled = true)
        {
            $this->_sessionEnabled = $enabled;
        }

        /**
         * Add function info here
         */
        function registerActions($actions)
        {
            $result = true;

            foreach ($actions as $actionKey => $actionClassName)
            {
                $result &= $this->registerAction($actionKey, $actionClassName);
            }

            return $result;
        }

        /**
         * Add function info here
         */
        function registerAction($actionKey, $actionClassName)
        {
            if (array_key_exists($actionKey, $this->_actionMap))
            {
                throw(new qException("qController::registerAction: '" . $actionClassName . "' class cannot register '" . $actionKey . "' action because it's already registered to '" . $this->_actionMap[$actionKey] . "' class."));
                return false;
            }

            $this->_actionMap[$actionKey] = $actionClassName;
            return true;
        }

        /**
         * Add function info here
         */
        function forward($actionName)
        {
            $actionClassName = $this->_getActionClassName($actionName);

            if  ($this->_forwarded == 0)
            {
                array_push($this->_actionsChain, $actionClassName);
            }
            else
            {
                $left  = array_slice($this->_actionsChain, 0, count($this->_actionsChain) - $this->_forwarded);
                $right = array_slice($this->_actionsChain, count($this->_actionsChain) - $this->_forwarded, $this->_forwarded);
                $this->_actionsChain = array_merge($left, array($actionClassName), $right);
            }

            $this->_forwarded++;
        }

        /**
         * Add function info here
         */
        function _getActionClassName($actionName)
        {
            if (empty($actionName))
            {
                $actionName = $this->_defaultAction;
            }

            if (array_key_exists($actionName, $this->_actionMap))
            {
                $actionClassName = $this->_actionMap[$actionName];
            }
            else
            {
                $actionClassName = ucfirst($actionName) . "Action";
            }

            return $actionClassName;
        }

        /**
         * Add function info here
         */
        function loadActionClass($actionClassName)
        {
            if (!class_exists($actionClassName))
            {
                include_once($this->_actionsClassPath . strtolower($actionClassName) . ".class.php");
            }
        }

        /**
         *    Add function info here
         */
        function _checkSecurityAction(&$action)
        {
            $result = true;

            if ($action->isSecure())
            {
                if (!$this->_user->isAuthenticated())
                {
                    $result = false;
                }
                else
                {
                    $perm = $action->getPermissions();

                    if (is_string($perm))
                    {
                        $result = $this->_user->hasPermission($perm);
                    }
                    elseif (is_array($perm))
                    {
                        if (count($perm) == 1)
                        {
                            $result = $this->_user->hasPermission($perm[0]);
                        }
                        elseif (count($perm) == 2)
                        {
                            $result = $this->_user->hasPermission($perm[0], $perm[1]);
                        }
                    }
                }
            }

            return $result;
        }

        /**
         * Processess the HTTP request sent by the client
         *
         * @param httpRequest HTTP request sent by the client
         */
        function &_execute(&$action, &$httpRequest, &$controllerParams)
        {
            $filters = new qFiltersChain($controllerParams);
            $action->registerFilters($filters);

            if (!$filters->filter())
            {
                $view = $action->handleFilterError($filters->getError());
                return $view;
            }

            if (!$this->_checkSecurityAction($action))
            {
                $view = $action->handleSecureError();
                return $view;
            }

            $method = $httpRequest->getValue("__method__");

            if (($action->getValidationMethod() & $method) != $method)
            {
                $view = $action->perform();
                return $view;
            }

            $validations = new qValidationsList();
            $action->registerValidations($validations);

            if ($validations->validate($httpRequest->getAsArray()) && $action->validate())
            {
                $view = $action->performAfterValidation();
                return $view;
            }

            $view = $action->handleValidateError($validations->getErrors());
            return $view;
        }

        /**
         * Processess the HTTP request sent by the client
         *
         * @param httpRequest HTTP request sent by the client
         */
        function process($httpRequest = null)
        {
            if ($this->_sessionEnabled)
            {
                session_start();

                if (empty($this->_user))
                {
                    $this->_user = new qUser(session_id(), new qUserSessionStorage());
                }

                $this->_user->setSid(session_id());
                $this->_user->load();
            }

            if (empty($httpRequest))
            {
                $httpRequest = &qHttp::getRequestVars();
            }

            $actionClassName = $this->_getActionClassName($httpRequest->getValue($this->_actionParam));
            array_push($this->_actionsChain, $actionClassName);

            while (count($this->_actionsChain) > 0)
            {
                $actionClassName  = array_pop($this->_actionsChain);
                $this->loadActionClass($actionClassName);
                $controllerParams = new qControllerParams($this, $httpRequest, $this->_user);
                $actionObject     = new $actionClassName($controllerParams);
                $this->_forwarded = 0;

                $view = &$this->_execute($actionObject, $httpRequest, $controllerParams);
            }

            if ($this->_sessionEnabled)
            {
                $this->_user->store();
            }

            if (empty($view))
            {
                throw(new qException("qController::process: '" . $actionObject->getClassName() . "' class should return a view after executing perform method."));
            }
            else
            {
                $view->render();
            }
        }
    }
?>
