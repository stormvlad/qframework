<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/security/qfilterschain.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/security/qblackhost.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qclient.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qipformatrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qipcidrformatrule.class.php");

    define(ERROR_FILTER_BLACK_HOST_MATCHED, "error_filter_black_host_matched");

    class qBlackHostsFilter extends qFiltersChain
    {
        var $_blackHosts;

        /**
        * Add function info here
        */
        function qBlackHostsFilter()
        {
            $this->qFiltersChain();
            $this->_blackHosts = array();
        }

        /**
        * Add function info here
        */
        function addBlackHost($host)
        {
            if (is_string($host))
            {
                $ipValidator = new qValidator();
                $ipValidator->addRule(new qIpFormatRule());

                $cidrValidator = new qValidator();
                $cidrValidator->addRule(new qIpCidrFormatRule());

                if ($ipValidator->validate($host))
                {
                    array_push($this->_blackHosts, new qBlackHost($host, 32));
                }
                else if ($cidrValidator->validate($host))
                {
                    $tmp = explode("/", $host);
                    array_push($this->_blackHosts, new qBlackHost($tmp[0], $tmp[1]));
                }
                else
                {
                    $moreHosts = gethostbynamel($host);

                    if (is_array($moreHosts))
                    {
                        foreach ($moreHosts as $h)
                        {
                            array_push($this->_blackHosts, new qBlackHost($h, 32));
                        }
                    }
                }
            }
            elseif (is_object($host))
            {
                array_push($this->_blackHosts, $host);
            }
        }

        /**
        * Add function info here
        */
        function filter(&$controller, &$httpRequest, &$user)
        {
            $clientIp = qClient::getIp();

            foreach ($this->_blackHosts as $host)
            {
                $rangeValidator = new qValidator();
                $rangeValidator->addRule(new qIpRangeRule($host->getCidrAddress()));

                if ($rangeValidator->validate($clientIp))
                {
                    $this->_setError(ERROR_FILTER_BLACK_HOST_MATCHED);
                    return false;
                }
            }

            return true;
        }
    }
?>
