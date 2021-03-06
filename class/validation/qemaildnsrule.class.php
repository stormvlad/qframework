<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qdns.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");

    define("ERROR_RULE_EMAIL_DNS_SERVER_UNREACHABLE", "error_rule_email_dns_server_unreachable");
    define("ERROR_RULE_EMAIL_DNS_NOT_PERMITTED", "error_rule_email_dns_not_permitted");

    /**
     * @brief Comprueba que existe realmente el dominio y una cuenta con un nombre de una cuenta de correo electrónico.
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
    class qEmailDnsRule extends qRule
    {
        /**
         * The constructor does nothing.
         */
        function qEmailDnsRule()
        {
            $this->qRule();
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value, $field = null)
        {
            if (empty($value))
            {
                $this->setError(false);
                return true;
            }

            list($userName, $domain) = explode("@", $value);
            $connectAddress          = $domain;

            if (!qDns::checkdnsrr($domain, "A"))
            {
                $this->setError(ERROR_RULE_EMAIL_DNS_SERVER_UNREACHABLE);
                return false;
            }

            if (qDns::checkdnsrr($domain, "MX") && qDns::getmxrr($domain, $mxHosts))
            {
                $connectAddress = $mxHosts[0];
            }

            if ($connect = fsockopen($connectAddress, 25))
            {
                $out = fgets($connect, 1024);

                if (preg_match("/^220/", $out))
                {
                    $server = &qHttp::getServerVars();
                    fputs($connect, "HELO " . $server->getValue("HTTP_HOST") . "\r\n");
                    $out = fgets($connect, 1024);

                    fputs($connect, "MAIL FROM: <" . $value . ">\r\n");
                    $from = fgets($connect, 1024);

                    fputs($connect, "RCPT TO: <" . $value .">\r\n");
                    $to = fgets($connect, 1024);

                    fputs($connect, "QUIT\r\n");
                    fclose($connect);

                    if (!preg_match("/^250/", $from) || !preg_match("/^250/", $to))
                    {
                         $this->setError(ERROR_RULE_EMAIL_DNS_NOT_PERMITTED);
                         return false;
                    }
                }
            }
            else
            {
                $this->setError(ERROR_RULE_EMAIL_DNS_SERVER_UNREACHABLE);
                return false;
            }

            return true;
        }
    }
?>