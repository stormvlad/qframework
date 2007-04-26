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
        var $_nonPersistent;

        /**
         * @brief Constructor.
         */
        function qAction()
        {
            $this->qObject();
            $this->_errors        = array();
            $this->_formName      = $this->getClassName();
            $this->_nonPersistent = array();
        }

        /**
         * @brief Devuelve el nombre del formulario
         *
         * @return <code>string</code>
         */
        function getFormName()
        {
            return $this->_formName;
        }

        /**
         * @brief Establece el nombre del formulario
         *
         * @param name <code>string</code>
         */
        function setFormName($name)
        {
            $this->_formName = $name;
        }
        
        /**
         * @brief Devuelve un booleano indicando si la acción tiene errores.
         *
         * @return <code>boolean</code>
         */
        function hasErrors()
        {
            return count($this->_errors) > 0;
        }

        /**
         * @brief Devuelve un booleano indicando si la acción tiene un error en el campo especificado.
         *
         * @param key <code>string</code> Identificador del error
         *
         * @return <code>boolean</code>
         */
        function hasError($key)
        {
            return !empty($this->_errors[$key]);
        }
        
        /**
         * @brief Devuelve un array asociativo con los errores producidos por la validación del formulario.
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
         * @brief Establece un array asociativo de errores para la acción.
         *
         * @param errors <code>array</code> Array asociativo de errores 
         */
        function setErrors(&$errors)
        {
            $this->_errors = &$errors;
        }
        
        /**
         * @brief Añade un error en la lista de errores
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
         * @brief Borra todos los errores almacenados previamente
         */
        function resetErrors()
        {
            $this->_errors = array();
        }

        /**
         * @brief Devuelve el método establecido en el que se validan los parámetros de la petición.
         *
         * @returns <code>integer</code>
         */
        function getValidationMethod()
        {
            return REQUEST_METHOD_NONE;
        }

        /**
         * @brief Método para validar manualmente ficheros y parámetros.
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
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
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
         * @brief Registra una lista de filtros para preprocesar la petición y postprocesar la respuesta.
         *
         * @param filtersChain <code>array</code>
         * @see qFilter
         */
        function registerFilters(&$filtersChain)
        {
        }

        /**
         * @brief Devuelve si la acción necesita autentificación.
         *
         * @return <code>boolean</code>
         */
        function isSecure()
        {
            return false;
        }

        /**
         * @brief Devuelve los permisos que hacen falta para ejecutar esta acción.
         *
         * @return <code>array</code>
         */
        function getPermissions()
        {
            return false;
        }

        /**
         * @brief Acción a ejecutar en caso de fallo de seguridad
         *
         * Este método débe de implementarse en todas las clases derivadas.
         * Ejectura las operaciones indicadas en caso de un fallo en la autentificación
         * del usuario o en la validación de los filtros de seguridad.
         *
         * @return qView Devuelve la vista a mostrar en caso de fallo de seguridad
         */
        function handleSecureError()
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
        }

        /**
         * @brief Acción principal
         *
         * Este método débe de implementarse en todas las clases derivadas.
         * Especificar las operaciones a ejecutar por la acción.
         * Se ejecuta en el caso que no llebemos a cabo ninguna validación, según
         * el método de validación definido en getValidationMethod.
         *
         * @see getValidationMethod
         * @see performAfterValidation
         */
        function perform()
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
        }

        /**
         * @brief Acción principal si se ha validado la petición
         *
         * Este método débe de implementarse en todas las clases derivadas
         * que tengan algún método con validación. Este método se define en
         * implementando el método getValidationMethod.
         *
         * Especificar las operaciones a ejecutar por la acción cuando el
         * proceso de validación se concluya sin errores.
         *
         * @see getValidationMethod
         * @see perform
         */
        function performAfterValidation()
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
        }

        /**
         * @brief Devuelve si existe un parámetro en el formulario
         *
         * @param name <code>string</code> Nombre del valor
         * @param [step] <code>integer</code> Número de paso en formulario, en caso de que sea un formulario por pasos
         * @return boolean
         */
        function formValueExists($name, $step = null)
        {
            if (qObject::isStaticCall())
            {
                $formName = qObject::getClassName();
            }
            else
            {
                $formName = $this->getFormName();
            }

            $user = &User::getInstance();
            return $user->formValueExists($formName, $name, $step);
        }

        /**
         * @brief Devuelve un valor del formulario
         *
         * @param name <code>string</code> Nombre del valor
         * @param [step] <code>integer</code>Número de paso en formulario, en caso de que sea un formulario por pasos
         * @return object
         */
        function getFormValue($name, $step = null)
        {
            if (qObject::isStaticCall())
            {
                $formName = qObject::getClassName();
            }
            else
            {
                $formName = $this->getFormName();
            }

            $user = &User::getInstance();
            return $user->getFormValue($formName, $name, $step);
        }

        /**
         * @brief Devuelve un array associativo con todos los valores del formulario
         *
         * @param [step] <code>integer</code> Número de paso en formulario, en caso de que sea un formulario por pasos
         * @return array
         */
        function &getFormValues($step = null)
        {
            if (qObject::isStaticCall())
            {
                $formName = qObject::getClassName();
            }
            else
            {
                $formName = $this->getFormName();
            }

            $user = &User::getInstance();
            return $user->getFormValues($formName, $step);
        }

        /**
         * @brief Establece y salva en la sessión un valor de formulario.
         *
         * @param name <code>string</code> Nombre del valor
         * @param value <code>mixed</code> Valor
         * @param [step] <code>integer</code> Número de paso en formulario, en caso de que sea un formulario por pasos
         */
        function setFormValue($name, $value, $step = null)
        {
            if (qObject::isStaticCall())
            {
                $formName = qObject::getClassName();
            }
            else
            {
                $formName = $this->getFormName();
            }
            
            $user = &User::getInstance();
            $user->setFormValue($formName, $name, $value, $step);
        }

        /**
         * @brief Salva los valores del formulario
         *
         * @param values <code>object</code> Valor
         * @param [step] <code>integer</code> Número de paso en formulario, en caso de que sea un formulario por pasos
         */
        function setFormValues($values, $step = null)
        {
            if (qObject::isStaticCall())
            {
                $formName = qObject::getClassName();
            }
            else
            {
                $formName = $this->getFormName();
            }

            $user = &User::getInstance();
            $user->setFormValues($formName, $values, $step);
        }

        /**
         * @brief Borra un valor del formulario
         *
         * @param name <code>string</code> Nombre del valor
         * @param [step] <code>integer</code> Número de paso en formulario, en caso de que sea un formulario por pasos
         */
        function removeFormValue($name, $step = null)
        {
            if (qObject::isStaticCall())
            {
                $formName = qObject::getClassName();
            }
            else
            {
                $formName = $this->getFormName();
            }

            $user = &User::getInstance();
            $user->removeFormValue($formName, $name, $step);
        }

        /**
         * @brief Borra todos los valores de un formulario
         */
        function resetFormValues()
        {
            if (qObject::isStaticCall())
            {
                $formName = qObject::getClassName();
            }
            else
            {
                $formName = $this->getFormName();
            }

            $user = &User::getInstance();
            $user->resetFormValues($formName);
        }

        /**
         * @brief Devuelve si una valor del formulario es persistente (por defecto lo son todos)
         *
         * @return <code>boolean</code>
         */
        function getFormValuePersistency($name)
        {
            return empty($this->_nonPersistent[$name]);
        }

        /**
         * @brief Establece si un valor del formulario es o no persistente
         *
         * @param name <code>string</code> Nombre del valor
         * @param [persistent] <code>boolean</code> Indica si es o no persistente
         */
        function setFormValuePersistency($name, $persistent = true)
        {
            if (empty($persistent))
            {
                $this->_nonPersistent[$name] = true;
            }
            else
            {
                unset($this->_nonPersistent[$name]);
            }
        }
        
        /**
         * @brief Salva los valores de la petición como valores del formulario
         */
        function save()
        {
            $controller = &Controller::getInstance();
            $request    = &Request::getInstance();
            $method     = $request->getMethod();

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
                $varsObj = &Request::getInstance();
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
                    if (!$user->formValueExists($formName, $key, $step) && $this->getFormValuePersistency($key))
                    {
                        $user->setFormValue($formName, $key, $value, $step);
                    }
                }
            }
        }

        /**
         * @brief Llama al controlador para establecer la siguiente acción a ejecutarse
         *
         * @param actionName <code>string</code> Nombre de la acción a ejecutar
         */
        function forward($actionName)
        {
            $controller = &Controller::getInstance();
            $controller->forward($actionName);
        }

        /**
         * @brief Llama al controlador para redireccionar la petición a otra URL
         *
         * @param url <code>string</code> Una URL existente
         */
        function redirect($url)
        {
            $controller = &Controller::getInstance();
            $controller->redirect($url);
            return;
        }

        /**
         * @brief Llama al controlador para redireccionar la petición a la URL anterior
         *
         * @param index <code>zero o entero negativo</code> Índice de la URL anterior
         */
        function redirectBack($index = -1)
        {
            $controller = &Controller::getInstance();
            $controller->redirectBack($index);
            return;
        }
    }
?>