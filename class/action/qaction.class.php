<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Classe abstracta que representa una acción.
     *
     * qAction permite separar la aplicación y la lógica de negocio de
     * la presentación (base de datos generalmente).
     * Proveendo de un conjunto de métodos núcleo usados por el framework y
     * automatización en los formularios en seguridad y validación.
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 21:21
     * @version 1.0
     * @ingroup core
     */
    class qAction extends qObject
    {
        var $_errors;
        var $_formName;

        /**
         * Constructor.
         */
        function qAction()
        {
            $this->qObject();
            $this->_errors           = array();
            $this->_formName         = $this->getClassName();
        }

        /**
         * Devuelve el nombre del formulario
         *
         * @return <code>string</code>
         */
        function getFormName()
        {
            return $this->_formName;
        }

        /**
         * Establece el nombre del formulario
         *
         * @param name <code>string</code>
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
         * @return <code>array()</code>
         */
        function &getErrors()
        {
            return $this->_errors;
        }

        /**
         * Añade un error en la lista de errores
         *
         * @param error <code>string</code> Mensaje de error
         * @param key <code>string</code> Identificador del error
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
         * Devuelve el método establecido en el que se validan los parámetros de la petición.
         *
         * @returns <code>integer</code>
         */
        function getValidationMethod()
        {
            return REQUEST_METHOD_NONE;
        }

        /**
         * Método para validar manualmente ficheros y parámetros.
         *
         * @return <code>boolean</code>
         */
        function validate()
        {
            return true;
        }

        /**
         * Ejecución en caso de encontrar errores en la validación
         *
         * @param errors <code>array</code>
         */
        function handleValidateError($errors)
        {
            throw(new qException("qAction::handleValidateError: This method must be implemented by child classes."));
            die();
        }

        /**
         * @brief Registra una lista de validadores para los parámetros de la petición
         *
         * Añadir a la lista de validadores qValidator o qRule
         *
         * @param validationsList qValidationsList Referencia a la lista de validadores
         */
        function registerValidations(&$validationsList)
        {
        }

        /**
         * Add function info here
         *
         * @param filtersChain <code>array</code>
         */
        function registerFilters(&$filtersChain)
        {
        }

        /**
         * Devuelve si la acción necesita autentificación.
         *
         * @return <code>boolean</code>
         */
        function isSecure()
        {
            return false;
        }

        /**
         * Devuelve los permisos que hacen falta para ejecutar esta acción.
         *
         * @return <code>array</code>
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
         * @param name <code>string</code> Nombre del valor
         * @param [step] <code>integer</code> Número de paso en formulario, en caso de que sea un formulario por pasos
         * @return boolean
         */
        function formValueExists($name, $step = null)
        {
            $user     = &User::getInstance();
            $formName = $this->getFormName();

            return $user->formValueExists($formName, $name, $step);
        }

        /**
         * Devuelve un valor del formulario
         *
         * @param name <code>string</code> Nombre del valor
         * @param [step] <code>integer</code>Número de paso en formulario, en caso de que sea un formulario por pasos
         * @return object
         */
        function getFormValue($name, $step = null)
        {
            $user     = &User::getInstance();
            $formName = $this->getFormName();

            return $user->getFormValue($formName, $name, $step);
        }

        /**
         * Devuelve un array associativo con todos los valores del formulario
         *
         * @param [step] <code>integer</code> Número de paso en formulario, en caso de que sea un formulario por pasos
         * @return array
         */
        function &getFormValues($step = null)
        {
            $user     = &User::getInstance();
            $formName = $this->getFormName();

            return $user->getFormValues($formName, $step);
        }

        /**
         * Establece y salva en la sessión un valor de formulario.
         *
         * @param name <code>string</code> Nombre del valor
         * @param value <code>mixed</code> Valor
         * @param [step] <code>integer</code> Número de paso en formulario, en caso de que sea un formulario por pasos
         */
        function setFormValue($name, $value, $step = null)
        {
            $user     = &User::getInstance();
            $formName = $this->getFormName();

            $user->setFormValue($formName, $name, $value, $step);
        }

        /**
         * Salva los valores del formulario
         *
         * @param values <code>object</code> Valor
         * @param [step] <code>integer</code> Número de paso en formulario, en caso de que sea un formulario por pasos
         */
        function setFormValues($values, $step = null)
        {
            $user     = &User::getInstance();
            $formName = $this->getFormName();

            $user->setFormValues($formName, $values, $step);
        }

        /**
         * Borra un valor del formulario
         *
         * @param name <code>string</code> Nombre del valor
         * @param [step] <code>integer</code> Número de paso en formulario, en caso de que sea un formulario por pasos
         */
        function removeFormValue($name, $step = null)
        {
            $user     = &User::getInstance();
            $formName = $this->getFormName();

            $user->removeFormValue($formName, $name, $step);
        }

        /**
         * Borra todos los valores de un formulario
         */
        function resetFormValues()
        {
            $user     = &User::getInstance();
            $formName = $this->getFormName();

            $user->resetFormValues($formName);
        }

        /**
         * Salva los valores de la petición como valores del formulario
         */
        function save()
        {
            $controller = &Controller::getInstance();
            $request    = &qHttp::getRequestVars();
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

            $user     = &User::getInstance();
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
         * @param actionName <code>string</code> Nombre de la acción a ejecutar
         */
        function forward($actionName)
        {
            $controller = &Controller::getInstance();
            $controller->forward($actionName);
        }

        /**
         * Redirecciona la petición a otra URL
         *
         * @param url <code>string</code> Una URL existente
         */
        function redirect($url)
        {
            $controller = &Controller::getInstance();
            $controller->redirect($url);
        }
    }
?>