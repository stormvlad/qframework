<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qfilter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qdate.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qredirectview.class.php");

    /**
    * Add function info here
    */
    class qSessionLifeTimeFilter extends qFilter
    {
        var $_lifeTime;
        var $_expiredUrl;

        /**
        * Add function info here
        */
        function qSessionLifeTimeFilter($lifeTime = "3600", $expiredUrl = "/")
        {
            $this->qFilter();

            $this->_lifeTime   = $lifeTime;
            $this->_expiredUrl = $expiredUrl;
        }

        /**
        * Add function info here
        */
        function getLifeTime()
        {
            return $this->_lifeTime;
        }

        /**
        * Add function info here
        */
        function setLifeTime($time)
        {
            $this->_lifeTime = $time;
        }

        /**
        * Add function info here
        */
        function getExpiredUrl()
        {
            return $this->_expiredUrl;
        }

        /**
        * Add function info here
        */
        function setExpiredUrl($url)
        {
            $this->_expiredUrl = $url;
        }

        /**
        * Add function info here
        */
        function run(&$filtersChain)
        {
            $user = &qUser::getInstance();
            $time = $user->getLastActionTime();
            $d1   = new qDate($time);
            $sec1 = $d1->getDate(DATE_FORMAT_UNIXTIME);
            $d2   = new qDate();
            $sec2 = $d2->getDate(DATE_FORMAT_UNIXTIME);

            if (($sec2 - $sec1 < $this->_lifeTime) || empty($time))
            {
                $filtersChain->run();
            }
            else
            {
                $user->destroy();
                $view = new qRedirectView($this->_expiredUrl);
                print $view->render();
            }
        }
    }
?>