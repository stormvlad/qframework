<?php

    include_once("framework/class/object/qobject.class.php" );

    class qStringUtils extends qObject {

        function htmlTranslate( $string )
        {
            return htmlspecialchars( $string );
        }

        function cutString( $string, $n )
        {
            return substr( $string, 0, $n );
        }

        /**
         * Returns an array with all the links in a string.
         *
         * @param string The string
         * @return An array with the links in the string.
         */
        function getLinks( $string )
        {
            $regexp = "|<a href=\"(.+)\">(.+)</a>|U";
            $result = Array();

            if( preg_match_all( $regexp, $string, $out, PREG_PATTERN_ORDER )) {
                foreach( $out[1] as $link ) {
                     array_push( $result, $link );
                }
            }

            return $result;
        }

        /**
         * Returns a size formatted and with its unit: "bytes", "KB", "MB" or "GB"
         *
         * @param size The amount
         * @return A string with the formatted size.
         */
        function formatSize( $size )
        {
            if ($size < pow(2,10)) return $size." bytes";
            if ($size >= pow(2,10) && $size < pow(2,20)) return round($size / pow(2,10), 0)." KB";
            if ($size >= pow(2,20) && $size < pow(2,30)) return round($size /pow(2,20), 1)." MB";
            if ($size > pow(2,30)) return round($size / pow(2,30), 2)." GB";
        }
    }
?>
