<?php

    include_once("framework/class/security/qpipelinefilter.class.php");
    include_once("framework/class/security/qpipelineresult.class.php");
    include_once("framework/class/security/qblockedhost.class.php");
    include_once("framework/class/net/qclient.class.php");
    include_once("framework/class/data/qipmatchvalidator.class.php");

    define(HOSTS_BLACKLIST_BLOCKED_HOST_FOUND, 300);

    class qHostsBlacklistFilter extends qPipelineFilter
    {
        var $_blockedHosts;

        /**
        * Add function info here
        */
        function qHostsBlacklistFilter()
        {
            $this->qPipelineFilter();
            $this->_blockedHosts = array();
        }

        /**
        * Add function info here
        */
        function addBlockedHost($host)
        {
            if (is_string($host))
            {
                if (ereg("[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}", $host))
                {
                    if (strpos($host, "/") === false)
                    {
                        array_push($this->_blockedHosts, new qBlockedHost($host, 32));
                    }
                    else
                    {
                        $tmp = explode("/", $host);
                        array_push($this->_blockedHosts, new qBlockedHost($tmp[0], $tmp[1]));
                    }
                }
                else
                {
                    $moreHosts = gethostbynamel($host);

                    if (is_array($moreHosts))
                    {
                        foreach ($moreHosts as $h)
                        {
                            array_push($this->_blockedHosts, new qBlockedHost($h, 32));
                        }
                    }
                }
            }
            elseif (is_object($host))
            {
                array_push($this->_blockedHosts, $host);
            }
        }

        /**
        * Add function info here
        */
        function filter()
        {
            $clientIp = Client::getIp();

            foreach ($this->_blockedHosts as $blockedHost)
            {
                $ipMatchValidator = new qIpMatchValidator($clientIp, $blockedHost->getCidrAddress());

                if ($ipMatchValidator->validate())
                {
                    return new qPipelineResult(false, HOSTS_BLACKLIST_BLOCKED_HOST_FOUND);
                }
            }

            return new qPipelineResult(true);
        }
    }
?>
