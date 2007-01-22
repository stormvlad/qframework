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
        var $_callUserFunction;

        /**
        * Add function info here
        */
        function qHostsFilter()
        {
            $this->qFilter();
            $this->_blackHosts       = array();
            $this->_whiteHosts       = array();
            $this->_order            = "Allow,Deny";
            $this->_view             = null;
            $this->_callUserFunction = null;
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
                trigger_error("Unknown 'order' param (it must be \"Allow,Deny\" or \"Deny,Allow\").", E_USER_WARNING);
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
        function getCallUserFunction()
        {
            return $this->_callUserFunction;
        }

        /**
        * Add function info here
        */
        function setCallUserFunction($func)
        {
            $this->_callUserFunction = $func;
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
                trigger_error("Unknown 'type' param (it must be \"+\" or \"-\").", E_USER_WARNING);
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
            if (empty($this->_view) && empty($this->_callUserFunction))
            {
                trigger_error("You should set view or call_user_func with setters methods.", E_USER_WARNING);
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
                trigger_error("Unknown 'order' param (it must be \"Allow,Deny\" or \"Deny,Allow\").", E_USER_WARNING);
                return;
            }

            if ($allowed)
            {
                $filtersChain->run();
            }
            else if (!empty($this->_view))
            {
                print $this->_view->render();
            }
            else if (!empty($this->_callUserFunction))
            {
                call_user_func($this->_callUserFunction);
            }
        }
    }
?>