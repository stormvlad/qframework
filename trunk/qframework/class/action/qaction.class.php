<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Abstract class representing an qAction.
     */
    class qAction extends qObject
    {
        var $_controllerParams;

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
    }
?>
