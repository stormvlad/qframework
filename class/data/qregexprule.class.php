<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qrule.class.php");

    define("ERROR_RULE_REGEXP_NOT_MATCH", "error_rule_regexp_not_match");

    /**
     * This is an implementation of the 'Strategy' pattern as it can be seen
     * http://www.phppatterns.com/index.php/article/articleview/13/1/1/. Here we use
     * this pattern to validate data received from forms. Its is useful since for example
     * we check in many places if a 'postId' is valid or not. We can put the
     * checkings inside the class and simply reuse this class wherever we want. If we ever
     *`change the format of the postId parameter, we only have to change the code of the
     * class that validates it and it will be automatically used everywhere.
     */
    class qRegExpRule extends qRule
    {
        var $_regExp;
        var $_caseSensitive;

        /**
         * The constructor does nothing.
         */
        function qRegExpRule($regExp, $caseSensitive = DEFAULT_RULE_CASE_SENSITIVE)
        {
            $this->qRule();

            $this->_regExp        = $regExp;
            $this->_caseSensitive = $caseSensitive;
        }

        /**
         * Add function info here
         */
        function getRegExp()
        {
            return $this->_regExp;
        }

        /**
         * Add function info here
         */
        function setRegExp($regExp)
        {
            $this->_regExp = $regExp;
        }

        /**
         * Add function info here
         */
        function isCaseSensitive()
        {
            return $this->_caseSensitive;
        }

        /**
         * Add function info here
         */
        function setCaseSensitive($caseSensitive = DEFAULT_RULE_CASE_SENSITIVE)
        {
            $this->_caseSensitive = $caseSensitive;
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value)
        {
            if ($this->_caseSensitive && ereg($this->_regExp, $value))
            {
                $this->setError(false);
                return true;
            }
            else if (!$this->_caseSensitive && eregi($this->_regExp, $value))
            {
                $this->setError(false);
                return true;
            }
            else
            {
                $this->setError(ERROR_RULE_REGEXP_NOT_MATCH);
                return false;
            }
        }
    }
?>