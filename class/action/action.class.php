<?php

    include_once("framework/class/net/request.class.php" );
    include_once("framework/class/object/observable.class.php" );

    /**
     * Abstract class representing an Action.
     */
    class Action extends Observable {

        // this is the pointer to the view associated with this action
        var $_view;

        /**
         * Constructor.
         *
         * @param actionInfo An ActionInfo object contaning information about the action
         * @param httpRequest the HTTP request.
         */
        function Action()
        {
            $this->Observable();
            $this->_view = null;
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
            throw(new Exception("Action::perform: This method must be implemented by child classes."));
            die();
        }

        /**
        /**
         * This function does not need to be reimplemented by the childs of this class.
         * It just returns the resulting view of the operation.
         */
        function &getView()
        {
            return $this->_view;
        }

        /**
         * Add function info here
         */
        function setView(&$view)
        {
            $this->_view = &$view;
        }

        /**
         * Add function info here
         */
        function validate()
        {
            return true;
        }
    }
?>
