<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("DEFAULT_PAGER_REGS_FOR_PAGE", 25);
    define("DEFAULT_PAGER_MAX_PAGES", 10);

    /**
     * Operating system detection functions. This class provides a bunch of functions in order to detect
     * on which operating system our php parser is running. Please bear in mind that this has not been
     * thoroughly tested and that at the moment it only provides detection for windows and linux.
     */
    class qPager extends qObject
    {
        var $_baseUrl;

        var $_offset;
        var $_totalRegs;
        var $_regsForPage;
        var $_maxPages;

        var $_totalPages;
        var $_curPage;
        var $_totalGroupPages;
        var $_curGroup;

        var $_startPage;
        var $_endPage;

        var $_startReg;
        var $_endReg;

        /**
         * Returns the OS string returned by php_uname
         *
         * @return The OS string.
         * @static
         */
        function qPager($baseUrl, $offset, $totalRegs, $regsForPage = DEFAULT_PAGER_REGS_FOR_PAGE, $maxPages = DEFAULT_PAGER_MAX_PAGES)
        {
            $this->_baseUrl     = $baseUrl;
            $this->_offset      = $offset;
            $this->_totalRegs   = $totalRegs;
            $this->_regsForPage = $regsForPage;
            $this->_maxPages    = $maxPages;

            if ($regsForPage == 0)
            {
                $this->_regsForPage = $totalRegs;
            }

            $this->_init();
        }

        /**
        *    Add function info here
        */
        function setBaseUrl($url)
        {
            $this->_baseUrl = $url;
        }

        /**
        *    Add function info here
        */
        function setOffset($offset)
        {
            $this->_offset = $offset;
            $this->_init();
        }

        /**
        *    Add function info here
        */
        function setTotalRegs($regs)
        {
            $this->_totalRegs = $regs;
            $this->_init();
        }

        /**
        *    Add function info here
        */
        function setRegsForPage($regs)
        {
            $this->_regsForPage = $regs;
            $this->_init();
        }

        /**
        *    Add function info here
        */
        function getBaseUrl()
        {
            return $this->_baseUrl;
        }

        /**
        *    Add function info here
        */
        function getOffset($page = null)
        {
            if ($page === null)
            {
                return $this->_offset;
            }
            else
            {
                return $this->_regsForPage * $page;
            }
        }

        /**
        *    Add function info here
        */
        function getTotalRegs()
        {
            return $this->_totalRegs;
        }

        /**
        *    Add function info here
        */
        function getRegsForPage()
        {
            return $this->_regsForPage;
        }

        /**
        *    Add function info here
        */
        function getMaxPages()
        {
            return $this->_maxPages;
        }

        /**
        *    Add function info here
        */
        function getTotalPages()
        {
            return $this->_totalPages;
        }

        /**
        *    Add function info here
        */
        function getCurPage()
        {
            return $this->_curPage;
        }

        /**
        *    Add function info here
        */
        function getStartReg()
        {
            return $this->_startReg;
        }

        /**
        *    Add function info here
        */
        function getEndReg()
        {
            return $this->_endReg;
        }

        /**
        *    Add function info here
        */
        function getTotalGroupPages()
        {
            return $this->_totalGroupPages;
        }

        /**
        *    Add function info here
        */
        function getCurGroup()
        {
            return $this->_curGroup;
        }

        /**
        *    Add function info here
        */
        function getStartPage()
        {
            return $this->_startPage;
        }

        /**
        *    Add function info here
        */
        function getEndPage()
        {
            return $this->_endPage;
        }

        /**
        *    Add function info here
        */
        function isFirstPage()
        {
            return ($this->_curPage === 0);
        }

        /**
        *    Add function info here
        */
        function isLastPage()
        {
            return ($this->_curPage == $this->_totalPages - 1);
        }

        /**
        *    Add function info here
        */
        function _init()
        {
            $this->_totalPages       = ceil($this->_totalRegs / $this->_regsForPage);
            $this->_curPage          = intVal($this->_offset / $this->_regsForPage);
            $this->_startReg         = $this->_offset + 1;
            $this->_endReg           = $this->_offset + $this->_regsForPage;
            $this->_curGroup         = intVal(($this->_offset / $this->_regsForPage) / $this->_maxPages);
            $this->_totalGroupPages  = ceil(($this->_totalRegs / $this->_regsForPage) / $this->_maxPages);
            $this->_startPage        = $this->_curGroup * $this->_maxPages;
            $this->_endPage          = $this->_startPage + $this->_maxPages;

            if (($this->_offset % $this->_regsForPage) > 0)
            {
                $this->_offset = intVal($this->_offset / $this->_regsForPage) * $this->_regsForPage;
            }

            if ($this->_endReg > $this->_totalRegs)
            {
                $this->_endReg = $this->_totalRegs;
            }

            if ($this->_endPage > $this->_totalPages)
            {
                $this->_endPage = $this->_totalPages;
            }
        }

    }
?>
