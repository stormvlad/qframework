<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Formateo i desformateo de cadenas y valores
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 16:16
     * @version 1.0
     * @ingroup data
     */
    class qFormat extends qObject
    {
        /**
        * Add function info here
        */
        function sanitize($str, $charSpaceReplace = "-", $length = null)
        {
            $str = qFormat::removeAccents($str);
            $str = qFormat::stripTags($str);
            $str = strtolower($str);
            $str = preg_replace("/&.+?;/", "", $str);
            $str = preg_replace("/[^a-z0-9 _-]/", "", $str);
            $str = preg_replace("/\s+/", " ", $str);
            $str = str_replace(" ", $charSpaceReplace, $str);
            $str = preg_replace("|-+|", $charSpaceReplace, $str);
            $str = trim($str, $charSpaceReplace);

            if (!empty($length))
            {
                $str = substr($str, 0, $length);
            }

            return $str;
        }

        /**
        * Add function info here
        */
        function camelize($str)
        {
            return str_replace(" ", "", ucwords(preg_replace("/[^A-Z^a-z^0-9]+/", " ", $str)));
        }

        /**
        * Add function info here
        */
        function underscore($str)
        {
            return  strtolower(preg_replace("/[^A-Z^a-z^0-9]+/", "_", preg_replace("/([a-zd])([A-Z])/", "1_2", preg_replace("/([A-Z]+)([A-Z][a-z])/", "1_2", $str))));
        }

        /**
        * Add function info here
        */
        function tableize($str1, $str2)
        {
            $names = array(strtolower($str1), strtolower($str2));
            sort($names);
            return implode("_", $names);
        }
        
        /**
        * Add function info here
        */
        function stripTags($str, $allowedTags = null)
        {
            return strip_tags($str, $allowedTags);
        }

        /**
        * Add function info here
        */
        function truncate($string, $length = 80, $etc = "...", $breakWords = false)
        {
            if ($length == 0)
            {
                return "";
            }
        
            if (strlen($string) > $length)
            {
                $length -= strlen($etc);
                
                if (!$breakWords)
                {
                    $string = preg_replace("/\s+?(\S+)?$/", "", substr($string, 0, $length + 1));
                }
            
                return substr($string, 0, $length) . $etc;
            }
            else
            {
                return $string;
            }
        }

        /**
        * Add function info here
        */
        function truncateUrl($url, $maxLength)
        {
            $i = 0;

            while (strlen($url) > $maxLength)
            {
                $url = preg_replace("|/([^/]+)((/\\.\\.\\.)*)/([^/]*)$|", "/...$2/$4", $url);
                $i++;

                if ($i == 100)
                {
                    return $url . "[Infinite Bucle]";
                }
            }

            return $url;
        }

        /**
        * Add function info here
        */
        function removeAccents($string)
        {
            $chars['in']  = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
                            .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
                            .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
                            .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
                            .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
                            .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
                            .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
                            .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
                            .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
                            .chr(252).chr(253).chr(255);
            $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

            if (qFormat::seemsUtf8($string))
            {
                $invalid_latin_chars = array(chr(197).chr(146) => 'OE', chr(197).chr(147) => 'oe', chr(197).chr(160) => 'S', chr(197).chr(189) => 'Z', chr(197).chr(161) => 's', chr(197).chr(190) => 'z', chr(226).chr(130).chr(172) => 'E');
                $string              = utf8_decode(strtr($string, $invalid_latin_chars));
            }

            $string              = strtr($string, $chars['in'], $chars['out']);
            $double_chars['in']  = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
            $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
            $string              = str_replace($double_chars['in'], $double_chars['out'], $string);

            return $string;
        }

        // by bmorel at ssi dot fr
        function seemsUtf8($Str)
        {
            for ($i = 0; $i < strlen($Str); $i++)
            {
                if (ord($Str[$i]) < 0x80)
                {
                    continue; // 0bbbbbbb
                }
                elseif ((ord($Str[$i]) & 0xE0) == 0xC0)
                {
                    $n=1; // 110bbbbb
                }
                elseif ((ord($Str[$i]) & 0xF0) == 0xE0)
                {
                    $n=2; // 1110bbbb
                }
                elseif ((ord($Str[$i]) & 0xF8) == 0xF0)
                {
                    $n=3; // 11110bbb
                }
                elseif ((ord($Str[$i]) & 0xFC) == 0xF8)
                {
                    $n=4; // 111110bb
                }
                elseif ((ord($Str[$i]) & 0xFE) == 0xFC)
                {
                    $n=5; // 1111110b
                }
                else
                {
                    return false; // Does not match any model
                }

                for ($j = 0; $j < $n; $j++) // n bytes matching 10bbbbbb follow ?
                {
                    if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80))
                    return false;
                }
            }

            return true;
        }

        /**
        * Add function info here
        */
        function regexpSearchExpand($str, $caseSensitive = false)
        {
            if ($caseSensitive)
            {
                $patterns = array(
                    "/[AÀÁÂÃÄÅÆ]/",
                    "/[CÇ]/",
                    "/[DÐ]/",
                    "/[EÈÉÊË]/",
                    "/[IÌÍÎÏ]/",
                    "/[NÑ]/",
                    "/[OÒÓÔÕÖØ¼]/",
                    "/[S¦]/",
                    "/[UÙÚÛÜ]/",
                    "/[YÝ¾¥]/",
                    "/[Z´]/",
                    "/[aàáâãäåæ]/",
                    "/[cç]/",
                    "/[eèéêë]/",
                    "/[iìíîï]/",
                    "/[nñ]/",
                    "/[oðòóôõöø½]/",
                    "/[s¨ß]/",
                    "/[uùúûüµ]/",
                    "/[yýÿ]/",
                    "/[z¸]/"
                    );

                $replaces = array(
                    "[AÀÁÂÃÄÅÆ]",
                    "[CÇ]",
                    "[DÐ]",
                    "[EÈÉÊË]",
                    "[IÌÍÎÏ]",
                    "[NÑ]",
                    "[OÒÓÔÕÖØ¼]",
                    "[S¦]",
                    "[UÙÚÛÜ]",
                    "[YÝ¾¥]",
                    "[Z´]",
                    "[aàáâãäåæ]",
                    "[cç]",
                    "[eèéêë]",
                    "[iìíîï]",
                    "[nñ]",
                    "[oðòóôõöø½]",
                    "[s¨ß]",
                    "[uùúûüµ]",
                    "[yýÿ]",
                    "[z¸]"
                    );
            }
            else
            {
                $patterns = array(
                    "/[AÀÁÂÃÄÅÆaàáâãäåæ]/",
                    "/[CÇcç]/",
                    "/[DÐ]/",
                    "/[EÈÉÊËeèéêë]/",
                    "/[IÌÍÎÏiìíîï]/",
                    "/[NÑnñ]/",
                    "/[OÒÓÔÕÖØ¼oðòóôõöø½]/",
                    "/[S¦s¨ß]/",
                    "/[UÙÚÛÜuùúûüµ]/",
                    "/[YÝ¾¥yýÿ]/",
                    "/[Z´z¸]/"
                    );

                $replaces = array(
                    "[AÀÁÂÃÄÅÆaàáâãäåæ]",
                    "[CÇcç]",
                    "[DÐ]",
                    "[EÈÉÊËeèéêë]",
                    "[IÌÍÎÏiìíîï]",
                    "[NÑnñ]",
                    "[OÒÓÔÕÖØ¼oðòóôõöø½]",
                    "[S¦s¨ß]",
                    "[UÙÚÛÜuùúûüµ]",
                    "[YÝ¾¥yýÿ]",
                    "[Z´z¸]"
                    );
            }

            return preg_replace($patterns, $replaces, $str);
        }

        /**
        * Add function info here
        */
        function html2plain($html, $baseUrl = null, $width = 70)
        {
            include_once(QFRAMEWORK_CLASS_PATH . "qframework/libs/html2text/class.html2text.inc");

            $h2t  = &new html2text($html);
            $h2t->width = $width;

            if (!empty($baseUrl))
            {
                $h2t->set_base_url($baseUrl);
            }

            $text = trim($h2t->get_text());
            $text = str_replace("\'", "'", $text);

            return $text;
        }

        /**
        * Add function info here
        */
        function normalizeSize($size, $decimals = null)
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
    }

?>