<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/libs/magpierss/rss_fetch.inc");

    /**
     * Encapsulates a definition of an object representing a URL
     *
     * Provides getters and setters for all the parts of the url:
     * <ul>
     * <li>url (the complete url)</li>
     * <li>scheme</li>
     * <li>host</li>
     * <li>user</li>
     * <li>password</li>
     * <li>path</li>
     * <li>query</li>
     * <li>fragment</li>
     *
     * </ul>
     * Every time a change is made in one of the fields the
     * url string is recalculated so that any call to getUrl
     * will return the right one.
     */
    class qRss extends qObject
    {
        var $_channel;
        var $_items;
        var $_image;
        var $_textInput;

        /**
        *    Add function info here
        */
        function qRss()
        {
            $this->qObject();

            $this->_channel   = null;
            $this->_items     = null;
            $this->_image     = null;
            $this->_textInput = null;
        }

        /**
        *    Add function info here
        */
        function getChannelInfo()
        {
            return $this->_channel;
        }

        /**
        *    Add function info here
        */
        function getImage()
        {
            return $this->_image;
        }

        /**
        *    Add function info here
        */
        function getItems()
        {
            return $this->_items;
        }

        /**
        *    Add function info here
        */
        function getTextinput()
        {
            return $this->_textInput;
        }

        /**
        *    Add function info here
        */
        function parse($url)
        {
            $rss             = @fetch_rss($url);

            if (empty($rss))
            {
                return false;
            }

            $this->_channel   = $rss->channel;
            $this->_items     = $rss->items;
            $this->_image     = $rss->image;
            $this->_textInput = $rss->textinput;

            return true;
        }
    }
?>
