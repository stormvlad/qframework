<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Classe abstracta que representa una acción. 
     *
     * qAction permite separar la aplicación y la lógica de negocio de la presentación.
     * Proveendo de un conjunto de métodos núcleo usados por el framework,
     * automatización en los formularios en seguridad y validación.
     */
    class qAction extends qObject
    {
        var $_controllerParams;
        var $_errors;
        var $_formName;

        /**
         * Constructor.
         */
        function qAction(&$controllerParams)
        {
            $this->qObject();
            $this->_controllerParams = &$controllerParams;
            $this->_errors           = array();
            $this->_formName         = $this->getClassName();
        }

        /**
         * Devuelve el nombre del formulario
         *
         * @return string
         */
        function getFormName()
        {
            return $this->_formName;
        }

        /**
         * Establece el nombre del formulario
         *
         * @param name string
         */
        function setFormName($name)
        {
            $this->_formName = $name;
        }

        /**
         * Devuelve un array asociativo con los errores producidos por la validación del formulario.
         *
         * El array devuelto contiene como claves los campos en que se ha producido el error de validación
         * y como valores los mensajes de error.
         *
         * @return array()
         */
        function &getErrors()
        {
            return $this->_errors;
        }

        /**
         * Añade un error en la lista de errores
         *
         * @param error string Mensaje de error
         * @param key string Identificador del error
         */
        function addError($error, $key = null)
        {
            if (empty($key))
            {
                $this->_errors[] = $error;
            }
            else
            {
                $this->_errors[$key] = $error;
            }
        }

        /**
         * Borra todos los errores almacenados previamente
         */
        function resetErrors()
        {
            $this->_errors = array();
        }

        /**
         * Devuelve los parámetros del controlador.
         */
        function &getControllerParams()
        {
            return $this->_controllerParams;
        }

        /**
         * Establece los parámetros del controlador.
         */
        function setControllerParams(&$controllerParams)
        {
            $this->_controllerParams = &$controllerParams;
        }

        /**
         * Devuelve el método establecido en el que se validan los parámetros de la petición.
         *
         * @returns integer
         */
        function getValidationMethod()
        {
            return REQUEST_METHOD_NONE;
        }

        /**
         * Método para validar manualmente ficheros y parámetros.
         *
         * @return boolean
         */
        function validate()
        {
            return true;
        }

        /**
         * 
         *
         * @param errors array
         */
        function handleValidateError($errors)
        {
            throw(new qException("qAction::handleValidateError: This method must be implemented by child classes."));
            die();
        }

        /**
         * Registra una lista de validadores para 
         * 
         * @param validationsList array
         */
        function registerValidations(&$validationsList)
        {
        }

        /**
         
         * Add function info here
         *
         * @param filtersChain array
         */
        function registerFilters(&$filtersChain)
        {
        }

        /**
         * Devuelve si la acción necesita autentificación.
         *
         * @return boolean
         */
        function isSecure()
        {
            return false;
        }

        /**
         * Devuelve los permisos que hacen falta para ejecutar esta acción.
         *
         * @return array
         */
        function getPermissions()
        {
            return false;
        }

        /**
         * Add function info here
         */
        function handleSecureError()
        {
            throw(new qException("qAction::handleSecureError: This method must be implemented by child classes."));
            die();
        }

        /**
         * Receives the HTTP request from the client as parameter, so that we can
         * extract the parameters we need and carry out the operation.
         *
         * The result of this will be a view, which will normally be the output of the
         * processing we just did or for example an error view showing an error message.
         * Once we have completed processing, the controller will call the getView() method
         * to get the resulting view and send it back to the customer.
         *
         * @return Returns nothing
         */
        function perform()
        {
            throw(new qException("qAction::perform: This method must be implemented by child classes."));
            die();
        }

        /**
         * Add function info here
         */
        function performAfterValidation()
        {
            throw(new qException("qAction::performAfterValidation: This method must be implemented by child classes."));
            die();
        }

        /**
         * Devuelve si existe un parámetro en el formulario
         *
         * @param name string Nombre del valor
         * @param step integer opcional - Número de paso en formulario, en caso de que sea un formulario por pasos
         * @return boolean
         */
        function formValueExists($name, $step = null)
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            return $user->formValueExists($formName, $name, $step);
        }

        /**
         * Devuelve un valor del formulario
         *
         * @param name string Nombre del valor
         * @param step integer opcional - Número de paso en formulario, en caso de que sea un formulario por pasos
         * @return object 
         */
        function getFormValue($name, $step = null)
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            return $user->getFormValue($formName, $name, $step);
        }

        /**
         * Devuelve un array associativo con todos los valores del formulario
         *
         * @param step integer opcional - Número de paso en formulario, en caso de que sea un formulario por pasos
         * @return array
         */
        function &getFormValues($step = null)
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            return $user->getFormValues($formName, $step);
        }

        /**
         * Establece y salva en la sessión un valor de formulario.
         *
         * @param name string Nombre del valor
         * @param value object Valor
         * @param step integer opcional - Número de paso en formulario, en caso de que sea un formulario por pasos
         */
        function setFormValue($name, $value, $step = null)
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            $user->setFormValue($formName, $name, $value, $step);
        }

        /**
         * Salva los valores del formulario
         *
         * @param values object Valor
         * @param step integer opcional - Número de paso en formulario, en caso de que sea un formulario por pasos
         */
        function setFormValues($values, $step = null)
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            $user->setFormValues($formName, $values, $step);
        }

        /**
         * Borra un valor del formulario
         *
         * @param name string Nombre del valor
         * @param step integer opcional - Número de paso en formulario, en caso de que sea un formulario por pasos
         */
        function removeFormValue($name, $step = null)
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            $user->removeFormValue($formName, $name, $step);
        }

        /**
         * Borra todos los valores de un formulario
         */
        function resetFormValues()
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            $user->resetFormValues($formName);
        }

        /**
         * Desa els valors de la petició com a valors del formulari
         */
        function save()
        {
            $controller = &$this->_controllerParams->getController();
            $request    = &$this->_controllerParams->getHttpRequest();
            $method     = $request->getRequestMethod();

            if (!$controller->_sessionEnabled || (($this->getValidationMethod() & $method) != $method))
            {
                return;
            }

            if ($this->getValidationMethod() == REQUEST_METHOD_GET)
            {
                $varsObj = &qHttp::getGetVars();
            }
            else if ($this->getValidationMethod() == REQUEST_METHOD_POST)
            {
                $varsObj = &qHttp::getPostVars();
            }
            else if ($this->getValidationMethod() == REQUEST_METHOD_ANY)
            {
                $varsObj = &qHttp::getRequestVars();
            }

            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();
            $step     = $user->getNextStep($formName);
            $vars     = $varsObj->getAsArray();

            foreach ($vars as $key => $value)
            {
                $user->setFormValue($formName, $key, $value, $step);
            }

            $prevStep = $step - 1;

            if ($prevStep >= 0)
            {
                $prevValues = &$user->getFormValues($formName, $prevStep);

                foreach ($prevValues as $key => $value)
                {
                    if (!$user->formValueExists($formName, $key, $step))
                    {
                        $user->setFormValue($formName, $key, $value, $step);
                    }
                }
            }
        }

        /**
         * Establece la siguiente acción a ejecutarse por el controlador actual
         *
         * @param actionName string Nombre de la acción a ejecutar
         */
        function forward($actionName)
        {
            $controller = &$this->_controllerParams->getController();
            $controller->forward($actionName);
        }

        /**
         * Redirecciona la petición a otra URL
         *
         * @param url string Una URL existente
         */
        function redirect($url)
        {
            $controller = &$this->_controllerParams->getController();
            $controller->redirect($url);
        }
    }
?>