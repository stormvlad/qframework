<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");

    /**
     * Very basic functions related to client-side information such as
     * retrieving the "real" ip, user-agent or accepted content type...
     */
    class qClient extends qObject
    {
        /**
        * Add function info here
        */
        function getBrowser()
        {
            $server = &qHttp::getServerVars();
            return $server->getValue("HTTP_USER_AGENT");
        }

        /**
        * Add function info here
        */
        function isIeBrowser()
        {
            return strpos(qClient::getBrowser(), "MSIE");
        }

        /**
        * Add function info here
        */
        function getIps()
        {
            $server   = &qHttp::getServerVars();
            $clientIp = $server->getValue("REMOTE_ADDR");
            $proxyIp  = false;

            if ($ip = $server->getValue("HTTP_X_FORWARDED_FOR"))
            {
                $proxyIp = $ip;
            }
            else if ($ip = $server->getValue("HTTP_X_FORWARDED"))
            {
                $proxyIp = $ip;
            }
            else if ($ip = $server->getValue("HTTP_FORWARDED_FOR"))
            {
                $proxyIp = $ip;
            }
            else if ($ip = $server->getValue("HTTP_FORWARDED"))
            {
                $proxyIp = $ip;
            }
            else if ($ip = $server->getValue("HTTP_VIA"))
            {
                $proxyIp = $ip;
            }
            else if ($ip = $server->getValue("HTTP_X_COMING_FROM"))
            {
                $proxyIp = $ip;
            }
            else if ($ip = $server->getValue("HTTP_COMING_FROM"))
            {
                $proxyIp = $ip;
            }

            if (empty($proxyIp))
            {
                return array($clientIp, $proxyIp);
            }
            else
            {
                return array($proxyIp, $clientIp);
            }
        }

        /**
        * Add function info here
        */
        function getProxyIp()
        {
            $ips = qClient::getIps();
            return $ips[1];
        }

        /**
        * Gets the "true" IP address of the current user
        *
        * @return  string   the ip of the user
        *
        * @access  private
        */
        function getIp()
        {
            $ips = qClient::getIps();
            return $ips[0];
        }
    }
?>
