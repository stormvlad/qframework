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
        function _getNormalizedStep($step)
        {
            $user       = &$this->_controllerParams->getUser();
            $formValues = &$user->getAttributeRef("formValues");
            $formName   = $this->getFormName();

            if (empty($step))
            {
                $step = count($formValues[$formName]) - 1;
            }
            else if ($step < 0)
            {
                $step = count($formValues[$formName]) -1 + $step;
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
        function formValueExists($name, $step = null)
        {
            $user       = &$this->_controllerParams->getUser();
            $formValues = &$user->getAttributeRef("formValues");
            $formName   = $this->getFormName();
            $step       = $this->_getNormalizedStep($step);

            return array_key_exists($name, $formValues[$formName][$step]);
        }

        /**
        * Add function info here
        */
        function getFormValue($name, $step = null)
        {
            $user       = &$this->_controllerParams->getUser();
            $formValues = &$user->getAttributeRef("formValues");
            $formName   = $this->getFormName();
            $step       = $this->_getNormalizedStep($step);

            return $formValues[$formName][$step][$name];
        }

        /**
        * Add function info here
        */
        function &getFormValues($step = null)
        {
            $user       = &$this->_controllerParams->getUser();
            $formValues = &$user->getAttributeRef("formValues");
            $formName   = $this->getFormName();
            $step       = $this->_getNormalizedStep($step);

            if (empty($formValues[$formName][$step]))
            {
                return false;
            }

            return $formValues[$formName][$step];
        }

        /**
        * Add function info here
        */
        function setFormValue($name, $value, $step = null)
        {
            $user       = &$this->_controllerParams->getUser();
            $formValues = &$user->getAttributeRef("formValues");
            $formName   = $this->getFormName();
            $step       = $this->_getNormalizedStep($step);

            $formValues[$formName][$step][$name] = $value;

            $user->setAttribute("formValues", $formValues);
        }

        /**
        * Add function info here
        */
        function setFormValues($values, $step = null)
        {
            foreach ($values as $key => $value)
            {
                $this->setFormValue($key, $value, $step);
            }
        }

        /**
        * Add function info here
        */
        function resetFormValues()
        {
            $user       = &$this->_controllerParams->getUser();
            $formValues = &$user->getAttributeRef("formValues");
            $formName   = $this->getFormName();

            $formValues[$formName] = array();
        }

        /**
        * Add function info here
        */
        function save()
        {
            $controller = &$this->_controllerParams->getController();
            $request    = &$this->_controllerParams->getHttpRequest();

            if (!$controller->_sessionEnabled || $request->getRequestMethod() != $this->getValidationMethod())
            {
                return;
            }

            $user       = &$this->_controllerParams->getUser();
            $formValues = $user->getAttribute("formValues");
            $formName   = $this->getFormName();

            if (empty($formValues))
            {
                $formValues = array();
            }

            if (empty($formValues[$formName]))
            {
                $formValues[$formName] = array();
            }

            $step = count($formValues[$formName]);
            $formValues[$formName][$step] = array();

            if ($this->getValidationMethod() == REQUEST_METHOD_GET)
            {
                $varsObj = &qHttp::getGetVars();
            }
            else if ($this->getValidationMethod() == REQUEST_METHOD_POST)
            {
                $varsObj = &qHttp::getPostVars();
            }

            $vars = $varsObj->getAsArray();

            foreach ($vars as $key => $value)
            {
                $formValues[$formName][$step][$key] = $value;
            }

            $prevStep = $step - 1;

            if ($prevStep >= 0)
            {
                foreach ($formValues[$formName][$prevStep] as $key => $value)
                {
                    if (!isset($formValues[$formName][$step][$key]))
                    {
                        $formValues[$formName][$step][$key] = $value;
                    }
                }
            }

            $user->setAttribute("formValues", $formValues);
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