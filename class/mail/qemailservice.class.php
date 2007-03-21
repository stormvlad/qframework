<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/mail/qemailmessage.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/libs/phpmailer/class.phpmailer.php");

    define("DEFAULT_EMAIL_SERVICE_TYPE", "php");

    /**
     * @brief Proporciona servicios para enviar emails
     * con la capacidades del nPHP
     * Provides services to send emails via PHPs built-in smtp capabilities.
     *
     * Este servicio tiene dependencias externas según el método que empleemos
     * para mandar los emails. Existen los siguientes métodos:
     *
     * - smtp - soporte sockets incluido en el nucleo de PHP
     * - php - soporte mail() que requiere sendmail en Linux, o la configuración adecuada de php.ini en Windows
     * - sendmail MTA - requiere la configuración adecuada de este agente
     * - qmail MTA - requiere la configuración adecuada de este agente
     *
     * Mas información:
     * - http://phpmailer.sourceforge.net/
     * - http://es2.php.net/mail
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 14:13
     * @version 1.0
     * @ingroup net
     */
    class qEmailService extends qObject
    {
        var $_serviceType;

        var $_charset;
        
        var $_smtpHost;
        var $_smtpPort;
        var $_smtpUseAuthentication;
        var $_smtpUser;
        var $_smtpPass;

        var $_customHeaders;

        /**
         * Constructor
         */
        function qEmailService($serviceType = DEFAULT_EMAIL_SERVICE_TYPE, $smtpHost = false, $smtpPort = 25)
        {
            $this->qObject();

            $this->_serviceType           = $serviceType;
            $this->_charset               = "iso-8859-15";
            $this->_smtpHost              = $smtpHost;
            $this->_smtpPort              = $smtpPort;
            $this->_smtpUseAuthentication = false;
            $this->_smtpUser              = false;
            $this->_smtpPass              = false;
            $this->_customHeaders         = array();
        }

        /**
         * Add function info here
         */
        function addCustomHeader($header)
        {
            $this->_customHeaders[] = $header;
        }

        /**
         * Add function info here
         */
        function getCustomHeaders()
        {
            return $this->_customHeaders;
        }
        
        /**
         * Add function info here
         */
        function getCharset()
        {
            return $this->_charset;
        }

        /**
         * Add function info here
         */
        function setCharset($charset)
        {
            $this->_charset = $charset;
        }

        /**
         * Add function info here
         */
        function getServiceType()
        {
            return $this->_serviceType;
        }

        /**
         * Add function info here
         */
        function setServiceType($type)
        {
            $this->_serviceType = $type;
        }

        /**
         * Add function info here
         */
        function getSmtpHost()
        {
            return $this->_smtpHost;
        }

        /**
         * Add function info here
         */
        function setSmtpHost($host)
        {
            $this->_smtpHost = $host;
        }

        /**
         * Add function info here
         */
        function getSmtpPort()
        {
            return $this->_smtpPort;
        }

        /**
         * Add function info here
         */
        function setSmtpPort($port)
        {
            $this->_smtpPort = $port;
        }

        /**
         * Add function info here
         */
        function getSmtpUser()
        {
            return $this->_smtpUser;
        }

        /**
         * Add function info here
         */
        function setSmtpUser($user)
        {
            $this->_smtpUser = $user;
        }

        /**
         * Add function info here
         */
        function getSmtpPass()
        {
            return $this->_smtpPass;
        }

        /**
         * Add function info here
         */
        function setSmtpPass($pass)
        {
            $this->_smtpPass = $pass;
        }

        /**
         * Add function info here
         */
        function setSmtpUseAuthentication($auth, $user = false, $pass = false)
        {
            $this->_smtpUseAuthentication = $auth;
            $this->_smtpUser              = $user;
            $this->_smtpPass              = $pass;
        }

        /**
         * Sends the given message.
         *
         * @param message Object from the EmailMessage class that encapsulates all the different fields
         * an email can have (quite basic, though)
         * @return Returns true if operation was successful or false otherwise.
         */
        function sendMessage(&$message)
        {
            $mail           = new PHPMailer();
            $mail->Encoding = "quoted-printable";
            $mail->CharSet  = $this->getCharset();
            $mail->Subject  = $message->getSubject();
            $mail->Body     = $message->getBody();
            $mail->AltBody  = $message->getAltBody();
            $mail->From     = $message->getFrom();
            
            $mail->IsHTML($message->isHtml());

            $attachments = $message->getAttachments();

            foreach ($attachments as $name => $attachment)
            {
                if ($name == $attachment)
                {
                    $mail->AddAttachment($attachment);
                }
                else
                {
                    $mail->AddAttachment($attachment, $name);
                }
            }

            $images = $message->getEmbeddedImages();

            if (is_array($images) && count($images) > 0)
            {
                include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
            
                foreach ($images as $cid => $image)
                {
                    $mail->AddEmbeddedImage($image, $cid);
                }
            }
            
            $headers = $this->getCustomHeaders();

            if (count($headers) > 0)
            {
                foreach ($headers as $header)
                {
                    $mail->AddCustomHeader($header);
                }
            }
            
            if (eregi("([^<]+)<([^>]+)>", $mail->From, $regs))
            {
                $mail->From     = $regs[2];
                $mail->FromName = trim($regs[1]);
            }

            foreach ($message->getTo() as $to)
            {
                $mail->AddAddress($to);
            }

            foreach ($message->getCc() as $cc)
            {
                $mail->AddCC($cc);
            }

            foreach ($message->getBcc() as $bcc)
            {
                $mail->AddBCC($bcc);
            }

            if ($this->_serviceType == "php")
            {
                $mail->IsMail();
            }
            elseif ($this->_serviceType == "qmail")
            {
                $mail->IsQmail();
            }
            elseif ($this->_serviceType == "sendmail")
            {
                $mail->IsSendmail();
            }
            elseif ($this->_serviceType == "smtp")
            {
                $mail->IsSMTP();

                if ($this->_smtpUseAuthentication)
                {
                    if (empty($this->_smtpUser) || empty($this->_smtpPass))
                    {
                        trigger_error("Please provide a username and a password if you wish to use SMTP authentication.", E_USER_WARNING);
                        $mail->SMTPAuth = false;
                    }
                    else
                    {
                        $mail->SMTPAuth = true;
                        $mail->Username = $this->_smtpUser;
                        $mail->Password = $this->_smtpPass;
                    }
                }
                else
                {
                    $mail->SMTPAuth = false;
                }

                if (empty($this->_smtpHost))
                {
                    trigger_error("You should specify an SMTP server if you wish to use SMTP service email.", E_USER_ERROR);
                    return false;
                }
                else
                {
                    $mail->Host = $this->_smtpHost;
                    $mail->Port = $this->_smtpPort;
                }
            }
            else
            {
                $mail->IsMail();
                trigger_error("Unrecognized value of the email_service_type setting. Reverting to PHP built-in mail() functionality.", E_USER_WARNING);
            }

            if (!$mail->Send())
            {
                trigger_error("Error sending email message: " . $mail->ErrorInfo, E_USER_WARNING);
                return false;
            }

            return true;
        }
    }
?>