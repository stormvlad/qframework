<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qfilter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurl.class.php");

    /**
    * Add function info here
    */
    class qGoogleFilter extends qFilter
    {
        var $_colors;

        /**
        * Add function info here
        */
        function qGoogleFilter(&$controllerParams, $colors = null)
        {
            $this->qFilter($controllerParams);

            if (empty($colors))
            {
                $this->_colors = array("yellow",
                                       "lightpink",
                                       "aquamarine",
                                       "darkgoldenrod",
                                       "darkseagreen",
                                       "lightgreen",
                                       "rosybrown",
                                       "seagreen",
                                       "chocolate",
                                       "violet");
            }
            else
            {
                $this->_colors = $colors;
            }
        }

        /**
        * Add function info here
        */
        function getColors()
        {
            return $this->_colors;
        }

        /**
        * Add function info here
        */
        function setColors($colors)
        {
            $this->_colors = $colors;
        }

        /**
        * Add function info here
        */
        function run(&$filtersChain)
        {
            ob_start();
            $filtersChain->run();
            $text = ob_get_contents();
            ob_end_clean();

            $server = &qHttp::getServerVars();
            $url    = new qUrl($server->getValue("HTTP_REFERER"));

            if (preg_match("/^http:\/\/w?w?w?\.?google.*/i", $url->getHost()))
            {
                $queryArray = $url->getQueryArray();

                if (empty($queryArray["q"]))
                {
                    return false;
                }

                $terms       = explode(" ", $query_array["q"]);
                $totalTerms  = count($terms);
                $totalColors = count($this->_colors);

                for ($i = 0; $i < $totalTerms; $i++)
                {
                    $term  = trim($terms[$i]);
                    $color = $this->_colors[$i % $totalColors];
                    $text  = preg_replace("/(?!<.*?)(\b" . $term . "\b)(?![^<>]*?>)/si", "<span style=\"background:" . $color . "\">$1</span>", $text);
                }
            }

            print $text;
        }
    }
?>