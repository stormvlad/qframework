<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Abstract class representing an qAction.
     */
    class qAction extends qObject
    {
        var $_validationMethod;

        /**
         * Constructor.
         *
         * @param actionInfo An qActionInfo object contaning information about the action
         * @param httpRequest the HTTP request.
         */
        function qAction($validationMethod = VALIDATION_METHOD_NONE)
        {
            $this->qObject();
            $this->_validationMethod = $validationMethod;
        }

        /**
        *    Add function info here
        **/
        function getValidationMethod()
        {
            return $this->_validationMethod;
        }

        /**
        *    Add function info here
        **/
        function setValidationMethod($method)
        {
            $this->_validationMethod = $method;
        }

        /**
         * Add function info here
         */
        function validate(&$controller, &$httpRequest)
        {
            return true;
        }

        /**
         * Add function info here
         */
        function handleValidateError(&$controller, &$httpRequest, &$errors)
        {
            throw(new qException("qAction::handleValidateError: This method must be implemented by child classes."));
            die();
        }

        /**
         * Add function info here
         */
        function registerValidations(&$controller, &$httpRequest, &$validationsList)
        {
        }

        /**
         * Add function info here
         */
        function handleFilterError(&$controller, &$httpRequest, $error)
        {
            throw(new qException("qAction::handleFilterError: This method must be implemented by child classes."));
            die();
        }

        /**
         * Add function info here
         */
        function registerFilters(&$controller, &$httpRequest, &$filtersChain)
        {
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
        function perform(&$controller, &$httpRequest)
        {
            throw(new qException("qAction::perform: This method must be implemented by child classes."));
            die();
        }

        /**
         * Add function info here
         */
        function performAfterValidation(&$controller, &$httpRequest)
        {
            throw(new qException("qAction::performAfterValidation: This method must be implemented by child classes."));
            die();
        }
    }
?>
