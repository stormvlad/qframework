<?php

    include_once("framework/class/object/object.class.php" );

    /**
     * this object is passed from pipeline filters to the pipeline, and
     * carries some information about what the outcome of the processing
     *
     */
    class PipelineResult extends Object {

        var $_valid;
        var $_errorCode;
        var $_errorMessage;

        function PipelineResult($valid = true, $errorCode = 0, $errorMessage = "")
        {
            $this->_valid        = $valid;
            $this->_errorCode    = $errorCode;
            $this->_errorMessage = $errorMessage;
        }

        /**
         * Returns wether the pipeline failed or succeeded
         *
         * @return A boolean value, true if successful or false otherwise
         */
        function isValid()
        {
            return $this->_valid;
        }

        /**
         * Extended error code
         *
         * @return An error code carrying extended information. The value of this
         * field is completely depending on the implementation of the filter
         */
        function getErrorCode()
        {
            return $this->_errorCode;
        }

        /**
         * An optional error message
         *
         * @return An string describing the error, if any
         */
        function getErrorMessage()
        {
            return $this->_errorMessage;
        }
    }
?>
