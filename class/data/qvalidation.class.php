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
    class qValidation extends qObject
    {
        var $_error;

        /**
         * The constructor does nothing.
         */
        function qValidation()
        {
            $this->qObject();
            $this->_error = false;
        }

        /**
        *    Add function info here
        **/
        function setError($error)
        {
            $this->_error = $error;
        }

        /**
        *    Add function info here
        **/
        function getError()
        {
            return $this->_error;
        }

        /**
        *    Add function info here
        **/
        function validate($value)
        {
            throw(new Exception("qValidation::validate: This method must be implemented by child classes."));
            die();
        }
    }

?>