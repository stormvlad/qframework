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

        var $_smtpHost;
        var $_smtpPort;
        var $_smtpUseAuthentication;
        var $_smtpUser;
        var $_smtpPass;

        /**
         * Constructor
         */
        function qEmailService($serviceType = DEFAULT_EMAIL_SERVICE_TYPE, $smtpHost = false, $smtpPort = 25)
        {
            $this->qObject();

            $this->_serviceType           = $serviceType;
            $this->_smtpHost              = $smtpHost;
            $this->_smtpPort              = $smtpPort;
            $this->_smtpUseAuthentication = false;
            $this->_smtpUser              = false;
            $this->_smtpPass              = false;
        }

        function getServiceType()
        {
            return $this->_serviceType;
        }

        function setServiceType($type)
        {
            $this->_serviceType = $type;
        }

        function getSmtpHost()
        {
            return $this->_smtpHost;
        }

        function setSmtpHost($host)
        {
            $this->_smtpHost = $host;
        }

        function getSmtpPort()
        {
            return $this->_smtpPort;
        }

        function setSmtpPort($port)
        {
            $this->_smtpPort = $port;
        }

        function getSmtpUser()
        {
            return $this->_smtpUser;
        }

        function setSmtpUser($user)
        {
            $this->_smtpUser = $user;
        }

        function getSmtpPass()
        {
            return $this->_smtpPass;
        }

        function setSmtpPass($pass)
        {
            $this->_smtpPass = $pass;
        }

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
            $mail->Subject  = $message->getSubject();
            $mail->Body     = $message->getBody();
            $mail->From     = $message->getFrom();

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
                        throw(new qException("qEmailService::sendMessage: Please provide a username and a password if you wish to use SMTP authentication"));
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
                    throw(new qException("qEmailService::sendMessage: You should specify an SMTP server in order to send emails."));
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
                throw(new qException("qEmailService::sendMessage: Unrecognized value of the email_service_type setting. Reverting to PHP built-in mail() functionality"));
            }

            if (!$mail->Send())
            {
                throw(new qException("qEmailService::sendMessage: Error sending message: " . $mail->ErrorInfo));
                die();
            }

            return true;
        }
    }
?>