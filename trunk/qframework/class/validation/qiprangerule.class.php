<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qrule.class.php");

    define("ERROR_RULE_IP_NOT_IN_RANGE", "error_rule_ip_not_in_range");

    /**
     * @brief Comprueba que una IP se encuentra dentro un rango.
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
    class qIpRangeRule extends qRule
    {
        var $_range;

        /**
         * The constructor does nothing.
         */
        function qIpRangeRule($range)
        {
            $this->qRule();
            $this->_range = $range;
        }

        /**
         * Add function info here
         */
        function setRange($range)
        {
            $this->_range = $range;
        }

        /**
         * Add function info here
         */
        function getRange()
        {
            return $this->_range;
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value)
        {
            $counter = 0;
            $range   = explode("/", $this->_range);

            if ($range[1] < 32)
            {
                $maskBits  = $range[1];
                $hostBits  = 32 - $maskBits;
                $hostCount = pow(2, $hostBits) - 1;
                $ipStart   = ip2long($range[0]);
                $ipEnd     = $ipStart + $hostCount;

                if ((ip2long($value) > $ipStart) && (ip2long($value) < $ipEnd))
                {
                    $this->setError(false);
                    return true;
                }
            }
            elseif (ip2long($value) == ip2long($range[0]))
            {
                $this->setError(false);
                return true;
            }

            $this->setError(ERROR_RULE_IP_NOT_IN_RANGE);
            return false;
        }
    }
?>