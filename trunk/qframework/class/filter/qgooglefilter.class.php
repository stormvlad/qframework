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
        function qGoogleFilter($colors = null)
        {
            $this->qFilter();

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
                $terms       = array("el");
                $totalTerms  = count($terms);
                $totalColors = count($this->_colors);
                $stringTerms = "";

                for ($i = 0; $i < $totalTerms; $i++)
                {
                    $term         = trim($terms[$i]);
                    $color        = $this->_colors[$i % $totalColors];
                    $stringTerms .= "<span style=\"background:" . $color . "\">" . $term . "</span> ";
                }

                for ($i = 0; $i < $totalTerms; $i++)
                {
                    $term  = trim($terms[$i]);
                    $color = $this->_colors[$i % $totalColors];
                    $text  = preg_replace("/(?!<.*?)(\b" . $term . "\b)(?![^<>]*?>)/si", "<span style=\"background:" . $color . "\">$1</span>", $text);
                }

                $text = str_replace("[GOOGLE_FILTER_TERMS]", $stringTerms, $text);
            }
            else
            {
                $text  = preg_replace("/<div class=\"google\">.+?<\\/div>/si", "", $text);
            }

            print $text;
        }
    }
?>