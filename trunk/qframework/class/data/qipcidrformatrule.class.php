<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qregexprule.class.php");

    define(IP_CIDR_FORMAT_RULE_REG_EXP, "^([0-9]{1,3})\\.([0-9]{1,3})\\.([0-9]{1,3})\\.([0-9]{1,3})/([0-9]{1,2})$");
    define(ERROR_RULE_IP_CIDR_FORMAT_WRONG, "error_rule_ip_cidr_format_wrong");

    /**
     * This is an implementation of the 'Strategy' pattern as it can be seen
     * http://www.phppatterns.com/index.php/article/articleview/13/1/1/. Here we use
     * this pattern to validate data received from forms. Its is useful since for example
     * we check in many places if a 'postId' is valid or not. We can put the
     * checkings inside the class and simply reuse this class wherever we want. If we ever
     *`change the format of the postId parameter, we only have to change the code of the
     * class that validates it and it will be automatically used everywhere.
     */
    class qIpCidrFormatRule extends qRegExpRule
    {
        /**
         * The constructor does nothing.
         */
        function qIpCidrFormatRule()
        {
            $this->qRegExpRule(IP_CIDR_FORMAT_RULE_REG_EXP, false);
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value)
        {
            if (!ereg($this->_regExp, $value, $regs))
            {
                $this->setError(ERROR_RULE_IP_CIDR_FORMAT_WRONG);
                return false;
            }
            else if ($regs[1] > 255 || $regs[2] > 255 || $regs[3] > 255 || $regs[4] > 255 || $regs[5] > 32)
            {
                $this->setError(ERROR_RULE_IP_CIDR_FORMAT_WRONG);
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