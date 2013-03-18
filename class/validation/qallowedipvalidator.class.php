<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qrule.class.php");

    define("ERROR_RULE_IP_IS_NOT_ALLOWED", "error_rule_ip_is_not_allowed");
    define("ALLOWED_IP_VALIDATOR_DEFAULT_SEPARATOR", " ");
    
    /**
    * Add class info here
    */
    class qAllowedIpValidator extends qRule
    {
        var $_ips;
        
        /**
         * The constructor does nothing.
         */
        function qAllowedIpValidator($ips, $sep = ALLOWED_IP_VALIDATOR_DEFAULT_SEPARATOR)
        {
            $this->qRule();
            $this->setIps($ips, $sep);
        }

        /**
        * Add function here
        */
        function setIps($ips, $sep = ALLOWED_IP_VALIDATOR_DEFAULT_SEPARATOR)
        {
            if (empty($ips))
            {
                $ips = array();
            }

            if (!is_array($ips))
            {
                $ips = explode($sep, $ips);
            }

            $this->_ips = $ips;
        }

        /**
        * Add function here
        */
        function validate($value, $field = null)
        {
            if (empty($this->_ips) || !is_array($this->_ips) || count($this->_ips) == 0)
            {
                $this->setError(false);
                return true;
            }

            foreach ($this->_ips as $ip)
            {
                if ($value == trim($ip))
                {
                    $this->setError(false);
                    return true;
                }
            }

            $this->setError(ERROR_RULE_IP_IS_NOT_ALLOWED);
            return false;
        }
    }
?>