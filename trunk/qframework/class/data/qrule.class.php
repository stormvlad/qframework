<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * This is an implementation of the 'Strategy' pattern as it can be seen
     * http://www.phppatterns.com/index.php/article/articleview/13/1/1/. Here we use
     * this pattern to validate data received from forms. Its is useful since for example
     * we check in many places if a 'postId' is valid or not. We can put the
     * checkings inside the class and simply reuse this class wherever we want. If we ever
     *`change the format of the postId parameter, we only have to change the code of the
     * class that validates it and it will be automatically used everywhere.
     */
    class qRule extends qObject
    {
        var $_error;

        /**
         * The constructor does nothing.
         */
        function qRule()
        {
            $this->qObject();
            $this->_error = false;
        }

        /**
         * Returns the error message set by the validate() method.
         *
         * @return The error message set by the validate() method.
         */
        function getError()
        {
            return $this->_error;
        }

        /**
         * Sets the error message that will be returned in case there is an error.
         *
         * @param message The error message.
         */
        function setError($error)
        {
            $this->_error = $error;
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function check()
        {
            throw(new Exception("qRule::check: This method must be implemented by child classes."));
            die();
        }
    }
?>