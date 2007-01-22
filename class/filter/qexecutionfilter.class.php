<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qfilter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qvalidationslist.class.php");

    /**
     * @brief Filtro de ejecución
     *
     * Implemente el mecanismo de autentificación propio, valida la petición,
     * ejecuta la acción principal y muestra el resultado.
     *
     * @author  qDevel - info@qdevel.com
     * @date    08/03/2005 00:30
     * @version 1.0
     * @ingroup filter
     * @see     qUser
     * @see     qAction
     */
    class qExecutionFilter extends qFilter
    {
        var $_actionsChain;

        /**
         * Add function info here
         */
        function qExecutionFilter()
        {
            $this->qFilter();
            $this->_actionsChain = array();
        }

        /**
         *    Add function info here
         */
        function addAction(&$action)
        {
            $this->_actionsChain[] = &$action;
        }

        /**
         *    Add function info here
         */
        function checkSecurityAction(&$action)
        {
            $result = true;
            $user   = &User::getInstance();

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
                if ($view = $action->handleSecureError())
                {
                    $view->setValue("formValues", $action->getFormValues());
                }

                return $view;
            }

            $httpRequest = &Request::getInstance();
            $files       = &qHttp::getFilesVars();
            $method      = $httpRequest->getMethod();

            if (($action->getValidationMethod() & $method) != $method)
            {
                if ($view = $action->perform())
                {
                    $view->setValue("formValues", $action->getFormValues());
                }

                return $view;
            }

            $validations = new qValidationsList();
            $action->registerValidations($validations);

            $validate = $validations->validate(array_merge($httpRequest->getAsArray(), $files->getAsArray()));
            $validate = $action->validate() && $validate;

            if (!$validate)
            {
                $errors = array_merge($validations->getErrors(), $action->getErrors());

                if ($view = $action->handleValidateError($errors))
                {
                    $view->setValue("formValues", $action->getFormValues());
                }

                return $view;
            }

            if ($view = $action->performAfterValidation())
            {
                $view->setValue("formValues", $action->getFormValues());
            }

            return $view;
        }

        /**
         * Add function info here
         */
        function run(&$filtersChain)
        {
            $count = count($this->_actionsChain);
            
            while ($count > 0)
            {
                $action = &$this->_actionsChain[$count - 1];
                $view   = &$this->executeAction($action);

                unset($this->_actionsChain[$count - 1]);
                $count = count($this->_actionsChain);
            }

            if (empty($view))
            {
                //trigger_error("'" . $action->getClassName() . "' class should return a view after executing perform / peformAfterValidation method.", E_USER_WARNING);
                return;
            }

            print $view->render();
        }
    }
?>