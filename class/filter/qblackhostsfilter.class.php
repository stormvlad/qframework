<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qfilter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qblackhost.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qclient.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qipformatrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qipcidrformatrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qiprangerule.class.php");

    class qBlackHostsFilter extends qFilter
    {
        var $_blackHosts;
        var $_view;

        /**
        * Add function info here
        */
        function qBlackHostsFilter(&$controllerParams)
        {
            $this->qFilter($controllerParams);
            $this->_blackHosts = array();
            $this->_view       = null;
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
        function run(&$filtersChain)
        {
            if (empty($this->_view))
            {
                throw(new qException("qBlackHostsFilter::run: you should assign a view with qBlackHostFilter::setView method."));
                return;
            }

            $clientIp = qClient::getIp();

            foreach ($this->_blackHosts as $host)
            {
                $rangeValidator = new qValidator();
                $rangeValidator->addRule(new qIpRangeRule($host->getCidrAddress()));

                if ($rangeValidator->validate($clientIp))
                {
                    print $this->_view->render();
                    return;
                }
            }

            $filtersChain->run();
        }
    }
?>
