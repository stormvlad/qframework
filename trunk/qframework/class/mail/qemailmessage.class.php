<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("MAX_LINE_LENGTH", 998);
    define("DEFAULT_EMAIL_MESSAGE_MIME_TYPE", "text/plain");

    /**
     * @brief Representa un mensaje de correo electrónico
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 15:59
     * @version 1.0
     * @ingroup net
     */
    class qEmailMessage extends qObject
    {
        var $_toAddrs;
        var $_ccAddrs;
        var $_bccAddrs;
        var $_subject;
        var $_body;
        var $_mimeType;
        var $_attachments;
        var $_isHtml;

        /**
         * Constructor
         */
        function qEmailMessage()
        {
            $this->qObject();

            $this->_toAddrs     = array();
            $this->_ccAddrs     = array();
            $this->_bccAddrs    = array();
            $this->_attachments = array();
            $this->_mimeType    = DEFAULT_EMAIL_MESSAGE_MIME_TYPE;
            $this->_isHtml      = false;
        }

        /**
         * Add function info here
         */
        function addAttachment($attachment, $name = null)
        {
            if (empty($name))
            {
                $name = $attachment;
            }

            $this->_attachments[$name] = $attachment;
        }

        /**
         * Add function info here
         */
        function isHtml()
        {
            return $this->_isHtml;
        }

        /**
         * Add function info here
         */
        function setHtml($isHtml)
        {
            $this->_isHtml = $isHtml;
        }

        /**
         * Adds a destination
         *
         * @param to Destination address.
         */
        function addTo($to)
        {
            $addreces = explode(",", $to);

            foreach ($addreces as $address)
            {
                array_push($this->_toAddrs, trim($address));
            }
        }

        /**
         * Adds a Cc:
         *
         * @param cc The address where we want to Cc this message
         */
        function addCc($cc)
        {
            $addreces = explode(",", $cc);

            foreach ($addreces as $address)
            {
                array_push($this->_ccAddrs, trim($address));
            }
        }

        /**
         * Adds a Bcc address
         *
         * @param bcc The adddress where we want to Bcc
         */
        function addBcc($bcc)
        {
            $addreces = explode(",", $bcc);

            foreach ($addreces as $address)
            {
                array_push($this->_bccAddrs, trim($address));
            }
        }

        /**
         * Sets the from address
         *
         * @param from The originatory address
         */
        function setFrom($from)
        {
            $this->_from = $from;
        }

        /**
         * Sets the subject of the message
         *
         * @param subject Subject of the message
         */
        function setSubject($subject)
        {
            $this->_subject = $subject;
        }

        /**
         * Sets the body of the message
         *
         * @param body The text for the body of the message
         */
        function setBody($body)
        {
            $this->_body = $body;
        }

        /**
         * Sets the MIME type. The default is 'text/plain'
         *
         * @param type The MIME type
         */
        function setMimeType($type)
        {
            $this->_mimeType = $type;
        }

        function getAttachments()
        {
            return $this->_attachments;
        }

        /**
         * Returns the "To:" list, properly arranged
         *
         * @return An string with the 'to:' field
         */
        function getTo()
        {
            return $this->_toAddrs;
        }

        /**
         * Returns the "Cc:" list, properly arranged
         *
         * @return An string with the 'Cc:' field
         */
        function getCc()
        {
            return $this->_ccAddrs;
        }

        /**
         * Returns the "Bcc:" list, properly arranged
         *
         * @return An string with the 'Bcc:' field
         */
        function getBcc()
        {
            return $this->_bccAddrs;
        }

        /**
         * Returns the From address.
         *
         * @return The from address.
         */
        function getFrom()
        {
            return $this->_from;
        }

        /**
         * Returns the body.
         *
         * @return The body.
         */
        function getBody()
        {
           return $this->_body;
        }

        /**
         * Returns the subject
         *
         * @return The subject.
         */
        function getSubject()
        {
            return $this->_subject;
        }

        /**
         * Gets the MIME content type of the message
         *
         * @return The MIME type
         */
        function getMimeType()
        {
            return $this->_mimeType;
        }
    }
?>
