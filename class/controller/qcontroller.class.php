<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/action/qaction.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/controller/qcontrollerparams.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qexecutionfilter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qfilterschain.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/user/quser.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/user/qusersessionstorage.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qdate.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/logging/qlogmanager.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qredirectview.class.php");

    define("DEFAULT_ACTION_PARAM", "op");
    define("DEFAULT_ACTION_NAME", "default");
    define("DEFAULT_ACTIONS_CLASS_PATH", "class/action/");

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
        var $_controllerParams;
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
            $this->_controllerParams = null;
            $this->_user             = null;

            $logManager = &qLogManager::getInstance();
            $logger     = &$logManager->getLogger("default");

            set_error_handler(array(&$logger, "standard"));
        }

        /**
         * Add function info here
         */
        function &getInstance()
        {
            static $controllerInstance;

            if (!isset($controllerInstance))
            {
                $controllerInstance = new qController();
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
            $this->_user = &$user;
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
        function isSessionEnabled()
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
        function getSessionName()
        {
            return session_name();
        }

        /**
         * Add function info here
         */
        function setSessionName($name)
        {
            session_name($name);
        }

        /**
         * Add function info here
         */
        function getSessionPath()
        {
            return session_save_path();
        }

        /**
         * Add function info here
         */
        function setSessionPath($path)
        {
            session_save_path($path);
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
        function actionClassFileNotFound($actionName)
        {
            return $this->getActionClassName($this->_defaultAction);
        }

        /**
         * Add function info here
         */
        function getActionClassName($actionName)
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
                $classFile = $this->_actionsClassPath . strtolower($actionName) . "action.class.php";

                if (is_file($classFile) && is_readable($classFile))
                {
                    $actionClassName = ucfirst($actionName) . "Action";
                }
                else
                {
                    return $this->actionClassFileNotFound($actionName);
                }
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
         * Add function info here
         */
        function forward($actionName)
        {
            $actionClassName  = $this->getActionClassName($actionName);
            $this->loadActionClass($actionClassName);

            $filtersChain     = new qFiltersChain();
            $executionFilter  = new qExecutionFilter($this->controllerParams);
            $action           = new $actionClassName($this->controllerParams);

            $action->registerFilters($filtersChain);
            $executionFilter->addAction($action);

            $filtersChain->addFilter($executionFilter);
            $filtersChain->run();
        }

        /**
        * Add function info here
        */
        function redirect($url)
        {
            $view = new qRedirectView($url);
            print $view->render();
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
                if (empty($this->_user))
                {
                    $this->_user = &qUser::getInstance();
                }

                $this->_user->setSid(session_id());
                $this->_user->load();
            }

            if (empty($httpRequest))
            {
                $httpRequest = &qHttp::getRequestVars();
            }

            $this->controllerParams = new qControllerParams($this, $httpRequest, $this->_user);
            $this->forward($httpRequest->getValue($this->_actionParam));

            if ($this->_sessionEnabled)
            {
                $d = new qDate();
                $this->_user->setLastActionTime($d->getDate(DATE_FORMAT_TIMESTAMP));
                $this->_user->store();
            }
        }
    }
?>