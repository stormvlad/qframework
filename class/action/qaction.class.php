<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Abstract class representing an qAction.
     */
    class qAction extends qObject
    {
        var $_controllerParams;
        var $_errors;
        var $_formName;

        /**
         * Constructor.
         *
         * @param actionInfo An qActionInfo object contaning information about the action
         * @param httpRequest the HTTP request.
         */
        function qAction(&$controllerParams)
        {
            $this->qObject();
            $this->_controllerParams = &$controllerParams;
            $this->_errors           = array();
            $this->_formName         = $this->getClassName();
        }

        /**
        *    Add function info here
        **/
        function getFormName()
        {
            return $this->_formName;
        }

        /**
        *    Add function info here
        **/
        function setFormName($name)
        {
            $this->_formName = $name;
        }

        /**
        *    Add function info here
        **/
        function &getErrors()
        {
            return $this->_errors;
        }

        /**
        *    Add function info here
        **/
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
        *    Add function info here
        **/
        function resetErrors()
        {
            $this->_errors = array();
        }

        /**
        *    Add function info here
        **/
        function &getControllerParams()
        {
            return $this->_controllerParams;
        }

        /**
        *    Add function info here
        **/
        function setControllerParams(&$controllerParams)
        {
            $this->_controllerParams = &$controllerParams;
        }

        /**
        *    Add function info here
        **/
        function getValidationMethod()
        {
            return REQUEST_METHOD_NONE;
        }

        /**
         * Add function info here
         */
        function validate()
        {
            return true;
        }

        /**
         * Add function info here
         */
        function handleValidateError($errors)
        {
            throw(new qException("qAction::handleValidateError: This method must be implemented by child classes."));
            die();
        }

        /**
         * Add function info here
         */
        function registerValidations(&$validationsList)
        {
        }

        /**
         * Add function info here
         */
        function registerFilters(&$filtersChain)
        {
        }

        /**
        *    Add function info here
        **/
        function isSecure()
        {
            return false;
        }

        /**
        *    Add function info here
        **/
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
        * Add function info here
        */
        function formValueExists($name, $step = null)
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            return $user->formValueExists($formName, $name, $step);
        }

        /**
        * Add function info here
        */
        function getFormValue($name, $step = null)
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            return $user->getFormValue($formName, $name, $step);
        }

        /**
        * Add function info here
        */
        function &getFormValues($step = null)
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            return $user->getFormValues($formName, $step);
        }

        /**
        * Add function info here
        */
        function setFormValue($name, $value, $step = null)
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            $user->setFormValue($formName, $name, $value, $step);
        }

        /**
        * Add function info here
        */
        function setFormValues($values, $step = null)
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            $user->setFormValues($formName, $values, $step);
        }

        /**
        * Add function info here
        */
        function removeFormValue($name, $step = null)
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            $user->removeFormValue($formName, $name, $step);
        }

        /**
        * Add function info here
        */
        function resetFormValues()
        {
            $user     = &$this->_controllerParams->getUser();
            $formName = $this->getFormName();

            $user->resetFormValues($formName);
        }

        /**
        * Add function info here
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
        * Add function info here
        */
        function forward($actionName)
        {
            $controller = &$this->_controllerParams->getController();
            $controller->forward($actionName);
        }

        /**
        * Add function info here
        */
        function redirect($url)
        {
            $controller = &$this->_controllerParams->getController();
            $controller->redirect($url);
        }
    }
?>