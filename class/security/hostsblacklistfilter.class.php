<?php

    include_once("framework/class/security/pipelinefilter.class.php");
    include_once("framework/class/security/pipelineresult.class.php");
    include_once("framework/class/security/blockedhost.class.php");
    include_once("framework/class/net/client.class.php");
    include_once("framework/class/data/ipmatchvalidator.class.php");

    define(HOSTS_BLACKLIST_BLOCKED_HOST_FOUND, 300);

    class HostsBlacklistFilter extends PipelineFilter
    {
        var $_blockedHosts;

        /**
        * Add function info here
        */
        function HostsBlacklistFilter()
        {
            $this->PipelineFilter();
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
                        array_push($this->_blockedHosts, new BlockedHost($host, 32));
                    }
                    else
                    {
                        $tmp = explode("/", $host);
                        array_push($this->_blockedHosts, new BlockedHost($tmp[0], $tmp[1]));
                    }
                }
                else
                {
                    $moreHosts = gethostbynamel($host);

                    if (is_array($moreHosts))
                    {
                        foreach ($moreHosts as $h)
                        {
                            array_push($this->_blockedHosts, new BlockedHost($h, 32));
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
                $ipMatchValidator = new IpMatchValidator($clientIp, $blockedHost->getCidrAddress());

                if ($ipMatchValidator->validate())
                {
                    return new PipelineResult(false, HOSTS_BLACKLIST_BLOCKED_HOST_FOUND);
                }
            }

            return new PipelineResult(true);
        }
    }
?>
