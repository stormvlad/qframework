<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qrule.class.php");

    define("ERROR_RULE_TOO_SMALL", "error_rule_too_small");
    define("ERROR_RULE_TOO_LARGE", "error_rule_too_large");

    /**
     * @brief Comprueba si un valor se encuentra dentro de un intervalo
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
     * @ingroup validation rule
     */
    class qRangeRule extends qRule
    {
        var $_minValue;
        var $_maxValue;

        /**
         * The constructor does nothing.
         */
        function qRangeRule($minValue, $maxValue = 0)
        {
            $this->qRule();

            $this->_minValue = $minValue;
            $this->_maxValue = $maxValue;
        }

        /**
         * Add function info here
         */
        function getMinValue()
        {
            return $this->_minValue;
        }

        /**
         * Add function info here
         */
        function setMinValue($minValue)
        {
            $this->_minValue = $minValue;
        }

        /**
         * Add function info here
         */
        function getMaxValue()
        {
            return $this->_maxValue;
        }

        /**
         * Add function info here
         */
        function setMaxValue($maxValue)
        {
            $this->_maxValue = $maxValue;
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value, $field = null)
        {
            $len = strlen($value);

            if ($len < $this->_minValue)
            {
                $this->setError(ERROR_RULE_TOO_SMALL);
                return false;
            }
            else if ($this->_maxValue != 0 && $len > $this->_maxValue)
            {
                $this->setError(ERROR_RULE_TOO_LARGE);
                return false;
            }
            else
            {
                $this->setError(false);
                return true;
            }
        }
    }
?>