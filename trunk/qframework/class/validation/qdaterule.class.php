<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qrule.class.php");

    define("ERROR_RULE_WRONG_FORMAT_VALUE", "error_rule_wrong_format_value");
    define("ERROR_RULE_WRONG_DATE", "error_rule_wrong_date");
    define("ERROR_RULE_UNKNOWN_FORMAT", "error_rule_unknown_format");

    /**
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
    class qDateRule extends qRule
    {
        var $_format;

        /**
         * The constructor does nothing.
         */
        function qDateRule($format)
        {
            $this->qRule();
            $this->_format = $format;
        }

        /**
         * Add function info here
         */
        function getFormat()
        {
            return $this->_format;
        }

        /**
         * Add function info here
         */
        function setFormat($format)
        {
            $this->_format = $format;
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value)
        {
            switch ($this->_format)
            {
                case "dd/mm/yyyy":
                    $regExp = "([0-9]{1,2}).([0-9]{1,2}).([0-9]{4})";

                    if (!ereg($regExp, $value, $regs))
                    {
                        $this->setError(ERROR_RULE_WRONG_FORMAT_VALUE);
                        return false;
                    }

                    if (checkDate($regs[2], $regs[1], $regs[3]))
                    {
                        return true;
                    }
                    else
                    {
                        $this->setError(ERROR_RULE_WRONG_DATE);
                        return false;
                    }

                    break;

                case "mm/dd/yyyy":
                    $regExp = "([0-9]{1,2}).([0-9]{1,2}).([0-9]{4})";

                    if (!ereg($regExp, $value, $regs))
                    {
                        $this->setError(ERROR_RULE_WRONG_FORMAT_VALUE);
                        return false;
                    }

                    if (checkDate($regs[1], $regs[2], $regs[3]))
                    {
                        return true;
                    }
                    else
                    {
                        $this->setError(ERROR_RULE_WRONG_DATE);
                        return false;
                    }

                    break;

                case "yyyy/mm/dd":
                    $regExp = "([0-9]{4}).([0-9]{1,2}).([0-9]{1,2})";

                    if (!ereg($regExp, $value, $regs))
                    {
                        $this->setError(ERROR_RULE_WRONG_FORMAT_VALUE);
                        return false;
                    }

                    if (checkDate($regs[2], $regs[3], $regs[1]))
                    {
                        return true;
                    }
                    else
                    {
                        $this->setError(ERROR_RULE_WRONG_DATE);
                        return false;
                    }

                    break;

                default:
                    throw(new qException("qDateRule::_setRegExp: unknown format '" . $this->_format . "'."));
            }

            $this->setError(ERROR_RULE_UNKNOWN_FORMAT);
            return false;
        }
    }
?>