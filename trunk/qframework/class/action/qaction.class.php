<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/security/qfilterschain.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qvalidationslist.class.php");

    /**
     * Abstract class representing an qAction.
     */
    class qAction extends qObject
    {
        var $_filtersChain;
        var $_validationsList;

        /**
         * Constructor.
         *
         * @param actionInfo An qActionInfo object contaning information about the action
         * @param httpRequest the HTTP request.
         */
        function qAction()
        {
            $this->qObject();
            $this->_filtersChain    = null;
            $this->_validationsList = null;
        }

        /**
        *    Add function info here
        **/
        function addValidation($name, &$validation)
        {
            if  (!is_object($this->_validationsList))
            {
                $this->_validationsList = new qValidationsList();
            }

            $this->_validationsList->addValidation($name, $validation);
        }

        /**
        *    Add function info here
        **/
        function addFilter(&$filter)
        {
            if  (!is_object($this->_filtersChain))
            {
                $this->_filtersChain = new qFiltersChain();
            }

            $this->_filtersChain->addFilter($filter);
        }

        /**
        *    Add function info here
        **/
        function getValidateErrors()
        {
            if  (!is_object($this->_validationsList))
            {
                $this->_validationsList = new qValidationsList();
            }

            return $this->_validationsList->getErrors();
        }

        /**
         * Add function info here
         */
        function validate(&$controller, &$httpRequest)
        {
            if (is_object($this->_validationsList))
            {
                return $this->_validationsList->validate($httpRequest->getAsArray());
            }

            return true;
        }

        /**
         * Add function info here
         */
        function handleValidateError(&$controller, &$httpRequest)
        {
            throw(new qException("qAction::handleValidateError: This method must be implemented by child classes."));
            die();
        }

        /**
        *    Add function info here
        **/
        function getFilterError()
        {
            if  (!is_object($this->_filtersChain))
            {
                $this->_filtersChain = new qFiltersChain();
            }

            return $this->_filtersChain->getError();
        }

        /**
        *    Add function info here
        **/
        function filter(&$controller, &$httpRequest)
        {
            if  (is_object($this->_filtersChain))
            {
                return $this->_filtersChain->filter($controller, $httpRequest);
            }

            return true;
        }

        /**
         * Add function info here
         */
        function handleFilterError(&$controller, &$httpRequest)
        {
            throw(new qException("qAction::handleFilterError: This method must be implemented by child classes."));
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
        function perform(&$controller, &$httpRequest)
        {
            throw(new qException("qAction::perform: This method must be implemented by child classes."));
            die();
        }
    }
?>
