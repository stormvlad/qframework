<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qstringhighlighter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qformat.class.php");

    /**
    * Add function info here
    */
    class qGoogleStringHighlighter extends qStringHighlighter
    {
        /**
        * Add function info here
        */
        function qGoogleStringHighlighter($colors = null)
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
                $term = trim($terms[$i]);
                $char = substr($term, 0, 1);

                if ($char != "-")
                {
                    $color = $this->_colors[$i % $totalColors];

                    if ($char == "+")
                    {
                        $term = substr($term, 1);
                        $term  = preg_replace("|([/+-?*])|", "\\1", $term);
                    }
                    else if ($char == "\"")
                    {
                        $term = substr($term, 1, -1);
                        $term  = preg_replace("|([/+-?*])|", "\\1", $term);
                    }
                    else
                    {
                        $term  = preg_replace("|([/+-?*])|", "\\1", $term);
                        $term = qFormat::regexpSearchExpand($term, $caseSensitive);
                    }

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
            }

            return $str;
        }
    }

?>