<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
    * qSearchRequestParser base class
    */
    class qSearchRequestParser extends qObject
    {
        var $_colors;

        /**
        * Constructor
        */
        function qSearchRequestParser($colors = null)
        {
            $this->qObject();

            $this->_colors = $colors;

            if (empty($colors))
            {
                $this->_colors = array("yellow", "lightpink", "aquamarine", "darkgoldenrod", "darkseagreen", "lightgreen", "rosybrown", "seagreen", "chocolate", "violet");
            }
        }

        /**
        * Add function info here
        */
        function getColors()
        {
            return $this->_colors;
        }

        /**
        * Add function info here
        */
        function setColors($colors)
        {
            $this->_colors = $colors;
        }

        /**
        * Add function info here
        */
        function reset()
        {
            throw(new qException("qSearchRequestParser::reset: This method must be implemented by child classes."));
            die();
        }

        /**
        * Add function info here
        */
        function getSearchTermsString()
        {
            throw(new qException("qSearchRequestParser::getSearchTermsString: This method must be implemented by child classes."));
            die();
        }

        /**
        * Add function info here
        */
        function parse($request)
        {
            throw(new qException("qSearchRequestParser::parse: This method must be implemented by child classes."));
            die();
        }
    }

?>