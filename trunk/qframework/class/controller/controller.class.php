<?php

    include_once("framework/class/object/object.class.php" );
    include_once("framework/class/action/action.class.php" );
    include_once("framework/class/object/exception.class.php" );
    include_once("framework/class/net/http.class.php");
    include_once("framework/class/security/pipeline.class.php");

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

    class Controller extends Object {

        var $_actionMap;
        var $_actionParam;

        var $_sessionEnabled;

        var $_forwardAction;

        var $_pipeline;

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
        function Controller($actionMap = null, $actionParam = DEFAULT_ACTION_PARAM)
        {
            $this->Object();

            $this->_actionMap       = $actionMap;
            $this->_actionParam     = $actionParam;
            $this->_sessionEnabled  = false;
            $this->_forwardAction   = null;
            $this->_pipeline        = null;
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
        function setPipeline(&$pipeline)
        {
            $this->_pipeline = &$pipeline;
        }

        /**
         * Add function info here
         */
        function &getPipeline()
        {
            return $this->_pipeline;
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
        function forward($actionName)
        {
            $this->_forwardAction = $actionName;
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
                $httpRequest = &Http::getRequestVars();
            }

            if (!empty($this->_pipeline))
            {
                $result = $this->_pipeline->process();

                if (!$result->isValid())
                {
                    die("www.qdevel.com");
                }
            }

            $i = 0;
            $performed = false;

            while (!$performed)
            {
                if ($i == 0)
                {
                    $actionClass = $this->_getActionClassName($httpRequest->getValue($this->_actionParam));
                }
                elseif (!empty($this->_forwardAction))
                {
                    $actionClass = $this->_getActionClassName($this->_forwardAction);
                    $this->_forwardAction = null;
                }
                else
                {
                    $performed = true;
                }

                if (!$performed)
                {
                    include_once("class/action/" . strtolower($actionClass) . ".class.php");
                    $actionObject = new $actionClass();

                    if ($actionObject->validate())
                    {
                        $actionObject->perform($this, $httpRequest);
                    }

                    $i++;
                }
            }

            $view = $actionObject->getView();

            if ($this->_sessionEnabled)
            {
                $session->save();
            }

            if (empty($view))
            {
                $e = new Exception("Controller::process: The view is empty after calling the perform method.");
                throw($e);
            }
            else
            {
                $view->render();
            }
        }
    }
?>
