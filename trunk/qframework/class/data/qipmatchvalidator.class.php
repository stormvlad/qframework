<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qvalidator.class.php");

    /**
     * Matches ip address with masks. Returns true wether
     * the given ip address matches with the given mask
     */
    class qIpMatchValidator extends qValidator
    {
        var $_ip;
        var $_csiext;

        /**
        * Add function info here
        */
        function qIpMatchValidator($ip, $csiext)
        {
            $this->qValidator();
            $this->_ip     = $ip;
            $this->_csiext = $csiext;
        }

        /**
        * Add function info here
        */
        function validate()
        {
            return $this->checkIp($this->_ip, $this->_csiext);
        }

        /**
        * Add function info here
        */
        function checkIp($ip , $csiext)
        {
            $counter = 0;
            $range   = explode("/", $csiext);

            if ($range[1] < 32)
            {
                $maskbits  = $range[1];
                $hostbits  = 32 - $maskbits;
                $hostcount = pow(2, $hostbits) - 1;
                $ipstart   = ip2long($range[0]);
                $ipend     = $ipstart + $hostcount;

                if ((ip2long($ip) > $ipstart) && (ip2long($ip) < $ipend))
                {
                    return true;
                }
            }
            elseif (ip2long($ip) == ip2long($range[0]))
            {
                return true;
            }

            return false;
        }
    }
?>
