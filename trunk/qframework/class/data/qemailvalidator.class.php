<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qdns.class.php");

    define(DEFAULT_CHECK_EMAIL_ADDRESS, false);
    define(EMAIL_REG_EXP, "^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}");

    /**
     * Extends the validator class to determine wether an email address is valid or not.
     */
    class qEmailValidator extends qValidator
    {
        var $_checkEmailAddress;

        function qEmailValidator($email, $checkEmailAddress = DEFAULT_CHECK_EMAIL_ADDRESS)
        {
            $this->qValidator($email);
            $this->_checkEmailAddress = $checkEmailAddress;
        }

        /**
         * Returns true if the email address is a valid one, or false otherwise.
         *
         * @return Returns true if it's a valid address or false otherwise.
         */
        function validate()
        {
             if (!eregi(EMAIL_REG_EXP, $this->_email))
            {
                $this->_valid = false;
                $this->setMessage("email_validator_wrong_format");
            }

            if ($this->_checkEmailAddress)
            {
                $this->_valid = $this->_checkEmailAddress();
            }
            else
            {
                $this->_valid = true;
            }

            return $this->_valid;
        }

        /**
         * Returns true if the email address is a valid one, or false otherwise.
         *
         * @return Returns true if it's a valid address or false otherwise.
         */
        function _checkEmailAddress($debug = false)
        {
            // split the domain off
            list ($userName, $domain) = split("@", $this->_email);
            // Check to see if email exists

            // see if mx record exists on server
            if (Dns::checkdnsrr($domain, "MX"))
            {
                if ($debug)
                {
                    echo "Confirmation : MX record about {$Domain} exists.<br>";
                }

                 if (Dns::getmxrr($domain, $MXHost))
                 {
                      if ($debug)
                    {
                        echo "Confirmation : Is confirming address by MX LOOKUP.<br>";

                        for ($i = 0, $j = 1; $i < count ($MXHost); $i++, $j++)
                        {
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Result($j) - $MXHost[$i]<br>";
                        }
                    }
                 }

                 $connectAddress = $MXHost[0];
            }
            else
            {
                 $connectAddress = $domain;

                 if ($debug)
                 {
                     echo "Confirmation : MX record about {$domain} does not exist.<br>";
                 }
            }

            // connect to desired address and verify it exists
            // Success in socket connection
            if ($connect = fsockopen($connectAddress, 25))
            {
                if ($debug)
                {
                    echo "Connection succeeded to {$connectAddress} SMTP.<br>";
                }

                if (ereg( "^220", $out = fgets($connect, 1024)))
                {
                    $Out = fgets($connect, 1024);
                    $Out = fgets($connect, 1024);

                    fputs($connect, "HELO $HTTP_HOST\r\n");

                    $out = fgets($Connect, 1024);

                    if ($debug)
                    {
                        echo "out -- {$out}<br>";
                    }

                    fputs($connect, "MAIL FROM: <{$this->_email}>\r\n");
                    $from = fgets($connect, 1024); // Receive server's answering cord.

                    if ($debug)
                    {
                        echo "from -- {$from}<br>";
                    }

                    fputs($connect, "RCPT TO: <{$this->_email}>\r\n");
                    $to = fgets($connect, 1024); // Receive server's answering cord.

                    if ($debug)
                    {
                        echo "to -- {$to}<br>";
                    }

                    fputs($connect, "QUIT\r\n");
                    fclose($connect);

                    if (!ereg("^250", $from) || !ereg ("^250", $to))
                    {
                         if ($debug)
                         {
                             echo "{$this->_email} is address does not admit in E-Mail server.<br>";
                         }

                         $this->_message = "{$this->_email} is address does not admit in E-Mail server";
                         return false;
                    }
                }
            }
            else
            {
                if ($debug)
                {
                    echo "Can not connect E-Mail server ({$connectAddress}).<br>";
                }

                $this->_message = "Can not connect E-Mail server ({$connectAddress})";
                return false;
            }

            return true;
        }
    }
?>
