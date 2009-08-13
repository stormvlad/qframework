<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qregexprule.class.php");

    /**
     *
     * Documentation extracted from:
     *        http://www.canowhoopass.com/weav/wssig/urlverifyfunc.php?show=yes
     *
     * // Here is the regular expression split up, I have it all on one line below.
     * $regexp = "^(https?://)?";  // http:// or https://
     * $regexp .= "(([0-9a-z_!~*'().&=+$%-]+:)?[0-9a-z_!~*'().&=+$%-]+@)?";  // username:password@
     * $regexp .= "("; // begin domain/ip section
     * $regexp .= "((([12]?[0-9]{1,2}\.){3}[12]?[0-9]{1,2})";  // IP- 199.194.52.184
     * $regexp .= "|";  // allows either IP or domain
     * $regexp .= "(([0-9a-z_!~*'()-]+\.)*"; // tertiary domain(s)- www.
     * $regexp .= "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\."; // second level domain
     * $regexp .= "(com|net|org|edu|mil|gov|int|aero|coop|museum|name|info|biz|pro|[a-z]{2}))"; // top level domains- .coms or .museums or country codes
     * $regexp .= ")"; // end domain/ip section
     * $regexp .= "(:[1-6]?[0-9]{1,4})?"; // port number- :80 or :8080 or :12480
     * $regexp .= "((/?)|"; // a slash isn't required if there is no file name
     * $regexp .= "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$"; // filename/queries/anchors
     *
     * ---------------------------------------------------------------
     *
     * 1- http:// may or may not be there
     * 2- https:// is also allowed
     * 3- "username:password@" functionality. The password is not required allowing
     *     "username@". I allowed the following characters:
     *     "0-9" "a-z" "_" "!" "~" "*" "'" "(" ")" "." "&" "=" "+" "$" "%" "-"
     * 4- The IP is 4 groups of 1 to 3 digits seperated by periods
     * 5- OR - either an IP address or a domain name is needed
     * 6- tertiary domains may use the following unreserved characters:
     *     "a-z" | "1-0" | "_" | "!" | "~" | "*" | "(" | ")" | "-" | "'" | "."
     *     NOTE: I made it so periods could not be first nor side by side
     * 7- secondary domains must be alpha nummeric
     *     Dashes are allowed but not at the beginning or end
     *     must be between 1 and 63 characters
     * 8- top level domains must be either 2 alpha characters (for country codes) or a recognized TLD (including .com and .info)
     * 9- port number, a colon ":" followed by 1 to 5 numbers
     * A- The final slash "/" is needed only if anything follows the domain / ip / port
     *     NOTE: This includes fragments and queries which may not be proper, but unlikely
     * B- Filenames and directories may contain the unreserved characters
     *     along with the following special characters which can be used
     *     for queries, fragments, and escaping extended characters
     *     ";" | "/" | "?" | ":" | "@" | "&" | "=" | "+" | "$" | "," | "%" | "#"
     *     NOTE: I made it so forward slashes could not be side by side, but periods can be
     *     NOTE: I left this section as broad as possible since there are so many
     *             ways to combine files, queries and fragments. Generally it should be:
     *             /directory/file?query&newquery#fragment
     */

    define("URL_FORMAT_RULE_REG_EXP", "^(https?://)?(([0-9a-z_!~*'().&=+$%-]+:)?[0-9a-z_!~*'().&=+$%-]+@)?((([12]?[0-9]{1,2}\.){3}[12]?[0-9]{1,2})|(([0-9a-z_!~*'()-]+\.)*([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.(com|net|org|edu|mil|gov|int|aero|coop|museum|name|info|biz|pro|cat|[a-z]{2})))(:[1-6]?[0-9]{1,4})?((/?)|(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$");
    define("ERROR_RULE_URL_FORMAT_WRONG", "error_rule_url_format_wrong");

    /**
     * @brief Determina si el formato de una URL es correcto.
     *
     * This is an implementation of the 'Strategy' pattern as it can be seen
     * http://www.phppatterns.com/index.php/article/articleview/13/1/1/. Here we use
     * this pattern to validate data received from forms. Its is useful since for example
     * we check in many places if a 'postId' is valid or not. We can put the
     * checkings inside the class and simply reuse this class wherever we want. If we ever
     *`change the format of the postId parameter, we only have to change the code of the
     * class that validates it and it will be automatically used everywhere.
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:22
     * @version 1.0
     * @ingroup validation rule
     */
    class qUrlFormatRule extends qRegExpRule
    {
        /**
         * The constructor does nothing.
         */
        function qUrlFormatRule($requiredProtocol = false)
        {
            $pattern = URL_FORMAT_RULE_REG_EXP;
            
            if (!empty($requiredProtocol))
            {
                $pattern = str_replace("^(https?://)?", "^(https?://)", $pattern);
            }
            
            $this->qRegExpRule($pattern, false);
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value, $field = null)
        {
            if (parent::validate($value, $field))
            {
                $this->setError(false);
                return true;
            }
            else
            {
                $this->setError(ERROR_RULE_URL_FORMAT_WRONG);
                return false;
            }
        }
    }
?>