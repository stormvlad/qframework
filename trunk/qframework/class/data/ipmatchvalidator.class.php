<?php

    include_once("framework/class/data/validator.class.php" );

    /**
     * Matches ip address with masks. Returns true wether
     * the given ip address matches with the given mask
     */
    class IpMatchValidator extends Validator
    {

        var $_ip;
        var $_csiext;

        function IpMatchValidator($ip, $csiext)
        {
            $this->Validator();
            $this->_ip     = $ip;
            $this->_csiext = $csiext;
        }

        function validate()
        {
            return $this->checkip($this->_ip, $this->_csiext);
        }

        function checkip($ip , $csiext)
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
