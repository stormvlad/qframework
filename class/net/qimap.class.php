<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Add class info here
     */
    class qImap extends qObject
    {
        var $_mbox;
        var $_path;
        
        /**
        * Constructor
        */
        function qImap($path = "{localhost:143}INBOX")
        {
            $this->_mbox = null;
            $this->_path = $path;
        }

        /**
        * Add function info here
        */
        function getPath()
        {
            return $this->_path;
        }
        
        /**
        * Add function info here
        */
        function setPath($path)
        {
            $this->_path = $path;
        }
        
        /**
        * Add function info here
        */
        function open($user, $pass)
        {
            $this->_mbox = @imap_open($this->_path, $user, $pass);            
            return $this->_mbox;
        }

        /**
        * Add function info here
        */
        function close()
        {
            if (!empty($this->_mbox))
            {
                return imap_close($this->_mbox);
            }

            return false;
        }

        /**
        * Add function info here
        */
        function status($flags = SA_ALL)
        {
            $item = imap_status($this->_mbox, $this->_path, $flags);
            return $item;
        }
        
        /**
        * Add function info here
        */
        function getUnseenCount()
        {
            $item = $this->status(SA_UNSEEN);
            return $item->unseen;
        }
        
        /**
        * Add function info here
        */
        function search($criteria, $onlyIds = false)
        {
            $items = imap_search($this->_mbox, $criteria);
            
            if (empty($onlyIds) && is_array($items) && count($items) > 0)
            {
                $result = array();
                
                foreach ($items as $item)
                {
                    $header = imap_headerinfo($this->_mbox, $item);
                    $result[$item] = $header;
                }
                
                return $result;
            }
            
            return $items;
        }
    }
?>