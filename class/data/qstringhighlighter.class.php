<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qformat.class.php");

    /**
    * Add function info here
    */
    class qStringHighlighter extends qObject
    {
        var $_colors;

        /**
        * Add function info here
        */
        function qStringHighlighter($colors = null)
        {
            $this->qObject();
            $this->_colors = $colors;

            if (empty($colors))
            {
                $this->_colors = array("yellow", "lightpink", "aquamarine", "darkgoldenrod", "darkseagreen", "lightgreen", "rosybrown", "seagreen", "chocolate", "violet");
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
        function getTerms($terms)
        {
            $terms = trim($terms);

            if (empty($terms))
            {
                return array();
            }

            if (!is_array($terms))
            {
                $terms = explode(" ", $terms);
            }

            $totalTerms  = count($terms);
            $totalColors = count($this->_colors);
            $result      = array();

            for ($i = 0; $i < $totalTerms; $i++)
            {
                $term  = trim($terms[$i]);
                $color = $this->_colors[$i % $totalColors];

                $result[] = "<span style=\"background:" . $color . "\">" . $term . "</span>";
            }

            return $result;
        }

        /**
        * Add function info here
        */
        function getTermsString($terms)
        {
            return trim(implode(" ", $this->getTerms($terms)));
        }

        /**
        * Add function info here
        */
        function highlight($str, $terms, $exactWords = false)
        {
            $terms = trim($terms);

            if (empty($terms))
            {
                return $str;
            }

            if (!is_array($terms))
            {
                $terms = explode(" ", $terms);
            }

            $totalTerms  = count($terms);
            $totalColors = count($this->_colors);

            for ($i = 0; $i < $totalTerms; $i++)
            {
                $term  = str_replace("/", "\\/", trim($terms[$i]));
                $term  = qFormat::regexpSearchExpand($term);
                $color = $this->_colors[$i % $totalColors];

                if ($exactWords)
                {
                    $pattern = "/(?!<.*?)([^[:alnum:]_]|^)(" . $term . ")([^[:alnum:]_]|$)(?![^<>]*?>)/si";
                    $str = preg_replace($pattern, "$1<span style=\"background:" . $color . "\">$2</span>$3", $str);
                }
                else
                {
                    $pattern = "/(?!<.*?)(" . $term . ")(?![^<>]*?>)/si";
                    $str = preg_replace($pattern, "<span style=\"background:" . $color . "\">$1</span>", $str);
                }
            }

            return $str;
        }
    }

?>