<?php

    include_once("framework/class/object/qobject.class.php" );

    define("DEFAULT_TRIM", true);

    /**
     * This is an implementation of the 'Strategy' pattern as it can be seen
     * http://www.phppatterns.com/index.php/article/articleview/13/1/1/. Here we use
     * this pattern to validate data received from forms. Its is useful since for example
     * we check in many places if a 'postId' is valid or not. We can put the
     * checkings inside the class and simply reuse this class wherever we want. If we ever
     *`change the format of the postId parameter, we only have to change the code of the
     * class that validates it and it will be automatically used everywhere.
     */
    class qValidator extends qObject {

        var $value;
        var $_valid;
        var $_message;

        /**
         * The constructor does nothing.
         */
        function qValidator($value = "", $trim = DEFAULT_TRIM)
        {
            $this->qObject();

            if ($trim)
            {
                $this->_value   = trim($value);
            }
            else
            {
                $this->_value   = $value;
            }

            $this->_valid   = false;
            $this->_message = "";
        }

        function getValue()
        {
            return $this->_value;
        }

        function setValue($value)
        {
            $this->_value = $value;
        }

        function isEmpty()
        {
            return empty($this->_value);
        }

        /**
         * Returns true if the condition imposed on the parameters is true. This method
         * must be called after calling the validate() method.
         *
         * @return True if the parameter is correct or false otherwhise.
         */
        function isValid()
        {
            return $this->_valid;
        }

        /**
         * Returns the error message set by the validate() method.
         *
         * @return The error message set by the validate() method.
         */
        function getMessage()
        {
            return $this->_message;
        }

        /**
         * Sets the error message that will be returned in case there is an error.
         *
         * @param message The error message.
         */
        function setMessage( $message )
        {
            $this->_message = $message;
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate()
        {
            throw(new qException("qValidator::validate: This method must be implemented by child classes."));
            die();
        }
    }
?>