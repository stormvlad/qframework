<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qstringhighlighter.class.php");

    /**
    * Add function info here
    */
    class qSimpleStringHighlighter extends qStringHighlighter
    {
        /**
        * Add function info here
        */
        function qSimpleStringHighlighter($colors = null)
        {
            $this->qStringHighlighter($colors);
        }

        /**
        * Add function info here
        */
        function highlight($str, $terms, $exactWords = false, $caseSensitive = false)
        {
            $totalTerms  = count($terms);
            $totalColors = count($this->_colors);

            if ($totalColors == 0)
            {
                return $str;
            }

            for ($i = 0; $i < $totalTerms; $i++)
            {
                $term  = preg_replace("|([/+-?*])|", "\\$1", trim($terms[$i]));
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