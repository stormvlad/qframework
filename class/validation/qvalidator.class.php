<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qvalidation.class.php");

    /** @defgroup validator Validadores 
      * @ingroup validation
      */

    /**
     * @brief Base de un validador o regla
     *
     * This is an implementation of the 'Strategy' pattern as it can be seen
     * http://www.phppatterns.com/index.php/article/articleview/13/1/1/. Here we use
     * this pattern to validate data received from forms. Its is useful since for example
     * we check in many places if a 'postId' is valid or not. We can put the
     * checkings inside the class and simply reuse this class wherever we want. If we ever
     *`change the format of the postId parameter, we only have to change the code of the
     * class that validates it and it will be automatically used everywhere.
      *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:22
     * @version 1.0
     * @ingroup validation validator
     */
   class qValidator extends qValidation
    {
        var $_rules;

        /**
         * The constructor does nothing.
         */
        function qValidator()
        {
            $this->qValidation();
            $this->_rules = array();
        }

        /**
        *    Add function info here
        **/
        function addRule(&$rule)
        {
            $this->_rules[] = &$rule;
        }

        /**
        *    Add function info here
        **/
        function addValidator(&$validator)
        {
            foreach ($validator->_rules as $rule)
            {
                $this->addRule($rule);
            }
        }

        /**
        *    Add function info here
        **/
        function validate($value, $field = null)
        {
            foreach ($this->_rules as $rule)
            {
                if (!$rule->validate($value, $field))
                {
                    $this->setError($rule->getError());
                    return false;
                }
            }

            return true;
        }
    }

?>