<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/action/qaction.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qclient.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qexecutionfilter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qfilterschain.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qdate.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qlogmanager.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qredirectview.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/timer/qtimer.class.php");

    define("DEFAULT_ACTION_PARAM", "op");
    define("DEFAULT_ACTION_NAME", "default");
    define("DEFAULT_ACTIONS_CLASS_PATH", "class/action/");

    /**
     * @brief Gestiona todas las peticiones del cliente
     *
     * As� es como MVC funciona, usando el patr�n 'Front Controller'. Este patr�n tiene una �nica classe
     * de controlador que recibe totas las peticiones del cliente, identifica la acci�n a tomar
     * y traspasa la ejecuci�n a la acci�n que mejor corresponde seg�n la petici�n.
     * La classe 'qAction' usa la logica de la aplicaci�n (qModel) para hacer las operaciones necesarias.
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
     *
     * @author  qDevel - info@qdevel.com
     * @date    26/02/2005 14:46
     * @version 1.0
     * @ingroup core
     **/

    class qController extends qObject
    {
        var $_actionMap;
        var $_actionParam;
        var $_actionsClassPath;
        var $_currentAction;
        var $_defaultAction;
        var $_sessionEnabled;

        /**
         * Constructor
         */
        function qController()
        {
            $this->qObject();

            $this->_actionMap        = array();
            $this->_actionParam      = DEFAULT_ACTION_PARAM;
            $this->_actionsClassPath = DEFAULT_ACTIONS_CLASS_PATH;
            $this->_currentAction    = null;
            $this->_defaultAction    = DEFAULT_ACTION_NAME;
            $this->_sessionEnabled   = false;

            $logManager = &qLogManager::getInstance();
            $logger     = &$logManager->getLogger("default");

            set_error_handler(array(&$logger, "standard"));

            $this->registerEvent(1, "PROCESS_METHOD_STARTS");
            $this->registerEvent(2, "PROCESS_METHOD_ENDS");
        }

        /**
         * Devuelve la �nica instancia de qController
         *
         * @note Basado en el patr�n Singleton. El objectivo de este m�todo es asegurar que exista s�lo una instancia de esta clase y proveer de un punto global de accesso a ella.
         * @return qController
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
         * Devuelve el nombre del par�metro (de la petici�n) que define la acci�n a ejecutar por el controlador.
         *
         * @return string
         */
        function getActionParam()
        {
            return $this->_actionParam;
        }

        /**
         * Establece el nombre del par�metro que define la acci�n a ejecutar por el controlador
         *
         * @param actionParam <em>string</em>
         */
        function setActionParam($actionParam)
        {
            $this->_actionParam = $actionParam;
        }

        /**
         * Devuelve la ruta d�nde se encuentran las classes de acci�n <i>qAction</i>
         *
         * @return string
         */
        function getActionsClassPath()
        {
            return $this->_actionsClassPath;
        }

        /**
        * Establece la ruta d�nde se encuentran la classes de acci�n <i>qAction</i>.
        * Por defecto en "class/action/"
        *
        * @param path string
        */
        function setActionsClassPath($path)
        {
            $this->_actionsClassPath = $path;
        }

        /**
         * Devuelve la acci�n que se est� ejecutando por el controlador
         *
         * @return qAction
         */
        function &getCurrentAction()
        {
            return $this->_currentAction;
        }

        /**
         * Devuelve el nombre de la acci�n que se ejecuta por defecto. Por ejemplo cuando no especificamos
         * ning�na acci�n en la petici�n.
         *
         * @return string
         */
        function getDefaultAction()
        {
            return $this->_defaultAction;
        }

        /**
         * Establece el nombre de la acci�n que se ejecuta por defecto.
         * Por defecto "default"
         *
         * @param actionClassName string
         */
        function setDefaultAction($actionClassName)
        {
            $this->_defaultAction = $actionClassName;
        }

        /**
         * Devuelve si la session est� habilitada.
         *
         * @return boolean
         */
        function isSessionEnabled()
        {
            return $this->_sessionEnabled;
        }

        /**
         * Establece si la session est� habilitada.
         *
         * @param [enabled] <em>boolean</em> Establece si la session est� habilitada (Opcional)
         */
        function setSessionEnabled($enabled = true)
        {
            $this->_sessionEnabled = $enabled;
        }

        /**
         * Devuelve el nombre de la sesi�n actual.
         *
         * El nombre de la sesi�n hace referencia al session id utilizado en las cookies y en las URLs.
         *
         * @return string
         */
        function getSessionName()
        {
            return session_name();
        }

        /**
         * Establece el nombre de la sesi�n actual.
         *
         * Deber�a contener �nicamente caracteres alfanum�ricos; tambi�n deber�a ser corto y descriptivo
         * (p.ej. para usuarios con los avisos de las cookies activados). El nombre de la sesi�n se restaura
         * al valor guardado por defecto en session.name al comenzar la petici�n.
         * As�, pues, es necesario llamar a session_name() en cada petici�n (y antes de llamar a session_start() o a session_register()).
         *
         * @param name string
         */
        function setSessionName($name)
        {
            session_name($name);
        }

        /**
         * Devuelve la ruta donde se guardan los datos de la sesi�n actual
         *
         * Devuelve la ruta del directorio usado actualmente para guardar los datos de la sesi�n.
         *
         * @return string
         */
        function getSessionPath()
        {
            return session_save_path();
        }

        /**
         * Cambia la ruta donde se guardan los datos de la sesi�n actual
         *
         * Nota: En algunos sistemas operativos, puede que quiera especificar una ruta en un sistema de archivos que maneja muchos archivos peque�os de forma eficiente. Por ejemplo, en Linux, reiserfs puede dar un rendimiento mejor que ext2fs.
         *
         * @param path string
         */
        function setSessionPath($path)
        {
            session_save_path($path);
        }

        /**
         * Registra las acciones en el controlador
         *
         * $ActionsMap is an associative array of the form:
         *
         * ($actionName, $actionClassName)
         *
         * Where for every different possible value of the 'action' parameter in the request,
         * there is an object inheriting form the Action class that will take care of
         * that requested action.
         *
         * A�ade un mapa de acciones a ejecutar por el controlador. Se especifican en una matriz associativa
         * tal como el ejemplo siguiente:
         *
         * @verbatim
           $actions = Array(
             "default" => "DefaultAction",
             "login"   => "LoginAction",
             "error"   => "ErrorAction",
           );
           @endverbatim
         *
         * @param actions array tabla asociativa con el nombre de las acciones y classes
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
         * Registra una acci�n en el controlador
         *
         * @param actionKey string Nombre associado a una acci�n
         * @param actionClassName string Nombre de la classe a ejecutar por esta acci�n
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
         * Devuelve el nombre de la classe de acci�n a ejecutar en caso de no encontrarse
         * el archivo con la acci�n esperada.
         * Por defecto se usa la acci�n por defecto, en otro caso redefinir esta funci�n y
         * usar el par�metro $actionName.
         *
         * @param actionName string Nombre de la acci�n esperada
         */
        function actionClassFileNotFound($actionName)
        {
            return $this->getActionClassName($this->_defaultAction);
        }

        /**
         * Devuelve el nombre de la classe de la acci�n associada a un nombre
         *
         * Si no se especifica ning�n nombre se devuelve la acci�n por defecto.
         * Si tenemos acciones registradas se consulta el mapa de acciones y se devuelve
         * el nombre de la classe associado a este nombre.
         * En otro caso se busca si existe un archivo con este nombre seguido por "action.class.php"
         * en el directorio de acciones y se devuelve el nombre de la acci�n.
         *
         * @param actionName string Nombre associado a una acci�n
         * @return string Nombre de la classe de la acci�n
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
         * Carga el fichero associado al nombre de la classe de acci�n. Se busca el archivo en el
         * directorio especificado con setActionsClassPath
         *
         * @param actionClassName string Nombre de la classe de la acci�n
         * @see setActionsClassPath
         */
        function loadActionClass($actionClassName)
        {
            if (!class_exists($actionClassName))
            {
                include_once($this->_actionsClassPath . strtolower($actionClassName) . ".class.php");
            }
        }

        /**
         * Segueix la execuci� de la petici� a una altra acci�.
         *
         * @param actionName string
         */
        function forward($actionName)
        {
            $actionClassName  = $this->getActionClassName($actionName);
            $this->loadActionClass($actionClassName);

            $filtersChain     = new qFiltersChain();
            $executionFilter  = new qExecutionFilter();
            $action           = new $actionClassName();

            $this->_currentAction = &$action;

            $action->registerFilters($filtersChain);
            $executionFilter->addAction($action);

            $filtersChain->addFilter($executionFilter);
            $filtersChain->run();
        }

        /**
         * Redirecciona la petici�n a otra URL
         *
         * @param url string Una URL existente
         */
        function redirect($url)
        {
            if ($this->_sessionEnabled)
            {
                $user = &User::getInstance();
                $user->store();
            }

            $view = new qRedirectView($url);
            print $view->render();
        }

        /**
         * Se procesa la petici�n HTTP enviada por el cliente
         *
         * @param httpRequest petici�n HTTP personalizada, por defecto se recupera autom�ticamente <i>(opcional)</i>
         */
        function process($httpRequest = null)
        {
            $timer  = new qTimer();
            $server = &qHttp::getServerVars();
            $params = array(
                "ip"         => qClient::getIp(),
                "class"      => $this->getClassName(),
                "script"     => basename($server->getValue("PHP_SELF")),
                "uri"        => $server->getValue("REQUEST_URI")
                );

            $this->sendEvent(1, $params);

            if ($this->_sessionEnabled)
            {
                $user = &User::getInstance();
            }

            if (empty($httpRequest))
            {
                $httpRequest = &qHttp::getRequestVars();
            }

            $this->forward($httpRequest->getValue($this->_actionParam));

            if ($this->_sessionEnabled)
            {
                $d = new qDate();
                $user->setLastActionTime($d->getDate(DATE_FORMAT_TIMESTAMP));
                $user->store();
            }

            $params["seconds"] = $timer->get();
            $this->sendEvent(2, $params);
        }
    }

?>