<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/misc/qosdetect.class.php");

    define("DEFAULT_DNSRR_COMMAND", "nslookup -type=%type %host");
    define("DEFAULT_MXRR_REG_EXP", "^%host\tMX preference = ([0-9]+), mail exchanger = (.*)$");

    /**
     * Implementation of an alternative version of the checkdnsrr and getmxrr functions which
     * are not available in the windows version of the php. The class detects wether we're
     * running windows or linux and then depending on the result, we will use the faster and native
     * version or the alternative one.
     */
    class qDns extends qObject
    {
        /**
         * Static function that acts as a wrapper for the native checkdnsrr function. It first detects
         * wether we're running in Windows or not and then uses the native version or the alternative one.
         *
         * For more information:          http://hk2.php.net/checkdnsrr
         *
         * @param host The we would like to check.
         * @param type It defaults to MX, but could be one of A, MX, NS, SOA, PTR, CNAME, AAAA, or ANY.
         * @return Returns TRUE if any records are found; returns FALSE if no records were found or if an error occurred.
         * @static
         */
        function checkdnsrr($host, $type = "MX")
        {
            if (qOsDetect::isWindows())
            {
                return Dns::_checkdnsrrWindows($host, $type);
            }
            else
            {
                return checkdnsrr($host, $type);
            }
        }

        /**
         * Function shamelessly copied from a comment made by an anonymous poster, that implements
         * an alternative version of checkdnsrr for windows platforms (at least, it works for
         * windows nt, 2000 and xp) I will never work in windows 98 because a) I think it's stupid
         * to run this in a windows 98 machine and b) because windows 98 is outdated anyway.
         *
         * Original function: http://hk2.php.net/checkdnsrr
         *
         * This function should behave in exactly the same way as the native checkdnsrr.
         *
         * @param host The we would like to check.
         * @param type It defaults to MX, but could be one of A, MX, NS, SOA, PTR, CNAME, AAAA, or ANY.
         * @return Returns TRUE if any records are found; returns FALSE if no records were found or if an error occurred.
         * @static
         * @private
         */
        function _checkdnsrrWindows($host, $type = "MX")
        {
            if (!empty($host))
            {
                $command = str_replace("%type", $type, DEFAULT_DNSRR_COMMAND);
                $command = str_replace("%host", $host, $command);

                @exec($command, $output);

                while (list($k, $line) = each($output))
                {
                    if (eregi("^" . $host, $line))
                    {
                        return true;
                    }
                }

                return false;
            }
        }

        /**
         * Static function that detects wether we're running windows or not and then either uses the native version of
         * getmxrr or the alternative one. See getmxrr_windows below for more information.
         *
         * @param host The host for which we want to get the mx records.
         * @param mxhosts The array we are going to fill with the mx records.
         * @return Returns either true or false.
         * @static
         */
        function getmxrr($host, &$mxhosts)
        {
            if (qOsDetect::isWindows())
            {
                return Dns::_getmxrrWindows($host, $mxhosts);
            }
            else
            {
                return getmxrr($host, $mxhosts);
            }
        }

        /**
         * Another function shamelessly copied from the same place which implements an alternative version
         * of getmxrr.
         *
         * See http://hk2.php.net/manual/en/function.getmxrr.php for more details.
         *
         * @param host The host for which we want to get the mx records.
         * @param mxhosts The array we are going to fill with the mx records.
         * @return Returns either true or false.
         * @static
         * @private
         */
        function _getmxrrWindows($host, &$mxhosts)
        {
            if (!is_array($mxhosts))
            {
                $mxhosts = array();
            }

            if (!empty($host))
            {
                $command = str_replace("%type", "MX", DEFAULT_DNSRR_COMMAND);
                $command = str_replace("%host", $host, $command);

                @exec($command, $output);

                while (list($k, $line) = each($output))
                {
                    $regExp = str_replace("%host", $host, DEFAULT_MXRR_REG_EXP);

                    if (ereg($regExp, $line, $parts))
                    {
                        $mxhosts[$parts[1]] = $parts[2];
                    }
                }

                if (count($mxhosts))
                {
                    reset($mxhosts);
                    ksort($mxhosts);

                    $i = 0;

                    while (list($pref, $host) = each($mxhosts))
                    {
                        $mxhosts2[$i] = $host;
                        $i++;
                    }

                    $mxhosts = $mxhosts2;

                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
    }
?>
