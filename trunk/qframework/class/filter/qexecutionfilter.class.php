<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qfilter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qvalidationslist.class.php");

    /**
    * Add function info here
    */
    class qExecutionFilter extends qFilter
    {
        var $_actionsChain;

        /**
         * Add function info here
         */
        function qExecutionFilter(&$controllerParams)
        {
            $this->qFilter($controllerParams);
            $this->_actionsChain = array();
        }

        /**
         *    Add function info here
         */
        function addAction($action)
        {
            $this->_actionsChain[] = $action;
        }

        /**
         *    Add function info here
         */
        function checkSecurityAction(&$action)
        {
            $result = true;
            $user   = &$this->_controllerParams->getUser();

            if ($action->isSecure())
            {
                if (!$user->isAuthenticated())
                {
                    $result = false;
                }
                else
                {
                    $perm = $action->getPermissions();

                    if (is_string($perm))
                    {
                        $result = $user->hasPermission($perm);
                    }
                    elseif (is_array($perm))
                    {
                        if (count($perm) == 1)
                        {
                            $result = $user->hasPermission($perm[0]);
                        }
                        elseif (count($perm) == 2)
                        {
                            $result = $user->hasPermission($perm[0], $perm[1]);
                        }
                    }
                }
            }

            return $result;
        }

        /**
         *    Add function info here
         */
        function &executeAction(&$action)
        {
            $action->save();

            if (!$this->checkSecurityAction($action))
            {
                $view = $action->handleSecureError();
                $view->setValue("formValues", $action->getFormValues());
                return $view;
            }

            $httpRequest = &$this->_controllerParams->getHttpRequest();
            $method      = $httpRequest->getValue("__method__");

            if (($action->getValidationMethod() & $method) != $method)
            {
                $view = $action->perform();
                $view->setValue("formValues", $action->getFormValues());
                return $view;
            }

            $validations = new qValidationsList();
            $action->registerValidations($validations);

            if (!$validations->validate($httpRequest->getAsArray()))
            {
                $view = $action->handleValidateError($validations->getErrors());
                $view->setValue("formValues", $action->getFormValues());
                return $view;
            }

            if (!$action->validate())
            {
                $view = $action->handleValidateError($action->getErrors());
                $view->setValue("formValues", $action->getFormValues());
                return $view;
            }

            $view = $action->performAfterValidation();
            $view->setValue("formValues", $action->getFormValues());
            return $view;
        }

        /**
         * Add function info here
         */
        function run(&$filtersChain)
        {
            while (count($this->_actionsChain) > 0)
            {
                $action = array_pop($this->_actionsChain);
                $view   = &$this->executeAction($action);
            }

            if (empty($view))
            {
                //throw(new qException("qExecutionFilter::run: '" . $action->getClassName() . "' class should return a view after executing perform method."));
            }
            else
            {
                print $view->render();
            }
        }
    }
?>
