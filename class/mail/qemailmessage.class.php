<?php

    include_once("qframework/class/object/qobject.class.php" );

    define( MAX_LINE_LENGTH, 998 );

    /**
     * Represents an email message.
     */
    class qEmailMessage extends qObject {

        var $_toAddrs;
        var $_ccAddrs;
        var $_bccAddrs;
        var $_subject;
        var $_body;
        var $_mimeType;
        var $_attachments;

        /**
         * Constructor
         */
        function qEmailMessage()
        {
            $this->qObject();

            $this->_toAddrs = array();
            $this->_ccAddrs = array();
            $this->_bccAddrs = array();

            $this->_attachments = array();

            $this->_mimeType = "text/plain";
        }

        function addAttachment($attachment)
        {
            array_push($this->_attachments, $attachment);
        }

        /**
         * Adds a destination
         *
         * @param to Destination address.
         */
        function addTo( $to )
        {
            array_push( $this->_toAddrs, rtrim($to) );
        }

        /**
         * Adds a Cc:
         *
         * @param cc The address where we want to Cc this message
         */
        function addCc( $cc )
        {
            array_push( $this->_ccAddrs, rtrim($cc) );
        }

        /**
         * Adds a Bcc address
         *
         * @param bcc The adddress where we want to Bcc
         */
        function addBcc( $bcc )
        {
            array_push( $this->_bccAddrs, rtrim($bcc) );
        }

        /**
         * Sets the from address
         *
         * @param from The originatory address
         */
        function setFrom( $from )
        {
            $this->_from = $from;
        }

        /**
         * Sets the subject of the message
         *
         * @param subject Subject of the message
         */
        function setSubject( $subject )
        {
            $this->_subject = $subject;
        }

        /**
         * Sets the body of the message
         *
         * @param body The text for the body of the message
         */
        function setBody( $body )
        {
            $this->_body = $body;
        }

        /**
         * Sets the MIME type. The default is 'text/plain'
         *
         * @param type The MIME type
         */
        function setMimeType( $type )
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
            return $this->_MimeType;
        }
    }
?>
