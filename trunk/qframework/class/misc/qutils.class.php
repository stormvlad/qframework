<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("DEFAULT_UTILS_DECIMALS", 8);

    class qUtils extends qObject
    {
        /**
         * Add function info here
         */
        function formatSize($size, $decimals = DEFAULT_UTILS_DECIMALS)
        {
            $sizes = array("B", "KB", "MB", "GB", "TB", "PB", "EB");
            $ext   = $sizes[0];
            $count = count($sizes);

            for ($i = 1; ($i < $count) && ($size >= 1024); $i++)
            {
                $size = $size / 1024;
                $ext  = $sizes[$i];
            }

            return round($size, $decimals). " " . $ext;
        }

        /**
         * Add function info here
         */
        function formatSeconds($seconds)
        {
            $hours   = (int) ($seconds / 3600);
            $minutes = (int) (($seconds % 3600) / 60);
            $seconds = (int) (($seconds % 3600) % 60);

            return sprintf("%02s:%02s:%02s", $hours, $minutes, $seconds);
        }

        /**
         * Add function info here
         */
        function decimal2rgb($color)
        {

            $bin = sprintf("%024s", decbin($color));
            $r   = substr($bin, 0, 8);
            $g   = substr($bin, 8, 8);
            $b   = substr($bin, 16, 8);

            return sprintf("#%02s%02s%02s", dechex(bindec($r)), dechex(bindec($g)), dechex(bindec($b)));
        }

        /**
         * Add function info here
         */
        function rgb2decimal($color)
        {
            $r = hexdec(substr($color, 1, 2));
            $g = hexdec(substr($color, 3, 2));
            $b = hexdec(substr($color, 5, 2));

            return ($r << 16) + ($g << 8) + $b;
        }

        /**
         * Add function info here
         */
        function addDirSep($file)
        {
            if (substr($file, -1) != "/")
            {
                $file .= "/";
            }

            return $file;
        }

        /**
         * Add function info here
         */
        function explode($separator, $string)
        {
            if (empty($separator))
            {
                $len   = strlen($string);
                $items = array();

                for ($i = 0; $i < $len; $i++)
                {
                    $items[] = substr($string, $i, 1);
                }

                return $items;
            }
            else
            {
                return explode($separator, $string);
            }
        }
    }
?>
