<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qfilter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qhost.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qclient.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qipformatrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qipcidrformatrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qiprangerule.class.php");

    /**
     * @brief Control de seguridad para permitir y excluir nombres de host y IP.
     *
     * @author  qDevel - info@qdevel.com
     * @date    07/03/2005 23:46
     * @version 1.0
     * @ingroup filter     
     */
    class qHostsFilter extends qFilter
    {
        var $_blackHosts;
        var $_whiteHosts;
        var $_order;
        var $_view;

        /**
        * Add function info here
        */
        function qHostsFilter()
        {
            $this->qFilter();
            $this->_blackHosts = array();
            $this->_whiteHosts = array();
            $this->_order      = "Allow,Deny";
            $this->_view       = null;
        }

        /**
        * Add function info here
        */
        function getOrder()
        {
            return $this->_order;
        }

        /**
        * Add function info here
        */
        function setOrder($order)
        {
            if ($order != "Allow,Deny" && $order != "Deny,Allow")
            {
                throw(new qException("qHostsFilter::setOrder: Unknown 'order' param (it must be \"Allow,Deny\" or \"Deny,Allow\")."));
                return;
            }

            $this->_order = $order;
        }

        /**
        * Add function info here
        */
        function getView()
        {
            return $this->_view;
        }

        /**
        * Add function info here
        */
        function setView($view)
        {
            $this->_view = $view;
        }

        /**
        * Add function info here
        */
        function _addHost($host, $type)
        {
            if ($type == "+")
            {
                $hosts = &$this->_whiteHosts;
            }
            else if ($type == "-")
            {
                $hosts = &$this->_blackHosts;
            }
            else
            {
                throw(new qException("qHostsFilter::_addHost: Unknown 'type' param (it must be \"+\" or \"-\")."));
                return;
            }

            $ipValidator = new qValidator();
            $ipValidator->addRule(new qIpFormatRule());

            $cidrValidator = new qValidator();
            $cidrValidator->addRule(new qIpCidrFormatRule());

            if ($ipValidator->validate($host))
            {
                array_push($hosts, new qHost($host, 32));
            }
            else if ($cidrValidator->validate($host))
            {
                $tmp = explode("/", $host);
                array_push($hosts, new qHost($tmp[0], $tmp[1]));
            }
            else
            {
                $moreHosts = gethostbynamel($host);

                if (is_array($moreHosts))
                {
                    foreach ($moreHosts as $h)
                    {
                        array_push($hosts, new qHost($h, 32));
                    }
                }
            }
        }

        /**
        * Add function info here
        */
        function addBlackHost($host)
        {
            $this->_addHost($host, "-");
        }

        /**
        * Add function info here
        */
        function addWhiteHost($host)
        {
            $this->_addHost($host, "+");
        }

        /**
        * Add function info here
        */
        function _checkIp(&$hosts, $ip)
        {
            foreach ($hosts as $host)
            {
                $rangeValidator = new qValidator();
                $rangeValidator->addRule(new qIpRangeRule($host->getCidrAddress()));

                if ($rangeValidator->validate($ip))
                {
                    return true;
                }
            }

            return false;
        }

        /**
        * Add function info here
        */
        function _checkIpInBlackHosts($ip)
        {
            return $this->_checkIp($this->_blackHosts, $ip);
        }

        /**
        * Add function info here
        */
        function _checkIpInWhiteHosts($ip)
        {
            return $this->_checkIp($this->_whiteHosts, $ip);
        }

        /**
        * Add function info here
        */
        function run(&$filtersChain)
        {
            if (empty($this->_view))
            {
                throw(new qException("qHostsFilter::run: you should assign a view with qHostsFilter::setView method."));
                return;
            }

            $clientIp = qClient::getIp();
            $allowed  = false;

            if ($this->_order == "Allow,Deny")
            {
                $allowed = ($this->_checkIpInWhiteHosts($clientIp) && !$this->_checkIpInBlackHosts($clientIp));
            }
            else if ($this->_order == "Deny,Allow")
            {
                $allowed = (!$this->_checkIpInBlackHosts($clientIp) || $this->_checkIpInWhiteHosts($clientIp));
            }
            else
            {
                throw(new qException("qHostsFilter::run: Unknown 'order' param (it must be \"Allow,Deny\" or \"Deny,Allow\")."));
                return;
            }

            if ($allowed)
            {
                $filtersChain->run();
            }
            else
            {
                print $this->_view->render();
            }
        }
    }
?>