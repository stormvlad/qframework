<?php

    include_once("qframework/class/object/qobject.class.php" );
    include_once("qframework/class/action/qaction.class.php" );
    include_once("qframework/class/object/qexception.class.php" );
    include_once("qframework/class/net/qhttp.class.php");
    include_once("qframework/class/security/qpipeline.class.php");


    define("DEFAULT_ACTION_PARAM", "op");
    define("DEFAULT_ACTION_NAME", "default");

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

    class qController extends qObject {

        var $_actionMap;
        var $_actionParam;

        var $_sessionEnabled;

        var $_actionsChain;

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
        function qController($actionMap = null, $actionParam = DEFAULT_ACTION_PARAM)
        {
            $this->qObject();

            $this->_actionMap       = $actionMap;
            $this->_actionParam     = $actionParam;
            $this->_sessionEnabled  = false;
            $this->_actionsChain    = array();
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
        function registerAction($actionKey, $actionClassName)
        {
            $this->_actionMap[$actionKey] = $actionClassName;
        }

        /**
         * Add function info here
         */
        function forward($actionName)
        {
            $actionClassName = $this->_getActionClassName($actionName);
            array_push($this->_actionsChain, $actionClassName);
        }

        /**
         * Add function info here
         */
        function _getActionClassName($actionName)
        {
            if (($actionName == "") || (!empty($this->_actionMap) && !array_key_exists($actionName, $this->_actionMap)))
            {
                $actionName = DEFAULT_ACTION_NAME;
            }

            if (!empty($this->_actionMap))
            {
                $actionClassName = $this->_actionMap[$actionName];
            }
            else
            {
                $actionClassName = $actionName . "Action";
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
                include_once("class/action/" . strtolower($actionClassName) . ".class.php");
            }
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
                $session = &Http::getSessionVars();
            }

            if ($httpRequest === null)
            {
                $httpRequest = &qHttp::getRequestVars();
            }

            $actionClassName = $this->_getActionClassName($httpRequest->getValue($this->_actionParam));
            array_push($this->_actionsChain, $actionClassName);

            while (count($this->_actionsChain) > 0)
            {
                $actionClassName = array_shift($this->_actionsChain);
                $this->loadActionClass($actionClassName);
                $actionObject = new $actionClassName();

                if ($actionObject->validate())
                {
                    $actionObject->perform($this, $httpRequest);
                }
            }

            $view = $actionObject->getView();

            if ($this->_sessionEnabled)
            {
                $session->save();
            }

            if (empty($view))
            {
                $e = new qException("qController::process: The view is empty after calling the perform method.");
                throw($e);
            }
            else
            {
                $view->render();
            }
        }
    }
?>
