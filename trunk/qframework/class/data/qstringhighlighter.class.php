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
        function highlight($str, $terms, $exactWords = false, $caseSensitive = false)
        {
            if (empty($terms))
            {
                return $str;
            }

            if (!is_array($terms))
            {
                $terms = split("[[:space:]]+", trim($terms));
            }

            $totalTerms  = count($terms);
            $totalColors = count($this->_colors);

            for ($i = 0; $i < $totalTerms; $i++)
            {
                $term  = preg_replace("|([/+-?*])|", "\\$1", trim($terms[$i]));
                $term  = qFormat::regexpSearchExpand($term, $caseSensitive);
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