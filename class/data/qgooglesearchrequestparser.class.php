<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qsearchrequestparser.class.php");

    /**
    * qGoogleSearchRequestParser base class
    */
    class qGoogleSearchRequestParser extends qSearchRequestParser
    {
        var $_allTerms;

        var $_terms;
        var $_textualTerms;
        var $_requiredTerms;
        var $_excludedTerms;
        var $_localeExcludedTerms;

        var $_localeStopWordsList;

        /**
        * Constructor
        */
        function qGoogleSearchRequestParser($colors = null)
        {
            $this->qSearchRequestParser($colors);

            $this->_allTerms            = array();
            $this->_terms               = array();
            $this->_textualTerms        = array();
            $this->_requiredTerms       = array();
            $this->_excludedTerms       = array();
            $this->_localeExcludedTerms = array();
            $this->_localeStopWordsList = array();
        }

        /**
        * Singleton method call
        */
        function reset()
        {
            $this->_allTerms            = array();
            $this->_terms               = array();
            $this->_textualTerms        = array();
            $this->_requiredTerms       = array();
            $this->_excludedTerms       = array();
            $this->_localeExcludedTerms = array();
        }

        /**
        * Add function info here
        */
        function getAllTerms()
        {
            return $this->_allTerms;
        }

        /**
        * Add function info here
        */
        function getTerms()
        {
            return $this->_terms;
        }

        /**
        * Add function info here
        */
        function getTextualTerms()
        {
            return $this->_textualTerms;
        }

        /**
        * Add function info here
        */
        function getRequiredTerms()
        {
            return $this->_requiredTerms;
        }

        /**
        * Add function info here
        */
        function getExcludedTerms()
        {
            return $this->_excludedTerms;
        }

        /**
        * Add function info here
        */
        function getLocaleExcludedTerms()
        {
            return $this->_localeExcludedTerms;
        }

        /**
        * Add function info here
        */
        function getLocaleStopWordsList()
        {
            return $this->_localeStopWordsList;
        }

        /**
        * Add function info here
        */
        function setLocaleStopWordsList(&$list)
        {
            $this->_localeStopWordsList = &$list;
        }

        /**
        * Add function info here
        */
        function getSearchTermsString()
        {
            $totalTerms  = count($this->_allTerms);
            $totalColors = count($this->_colors);

            if ($totalColors == 0)
            {
                return trim(implode($this->_allTerms));
            }

            $i = 0;
            $result = "";

            foreach ($this->_allTerms as $term)
            {
                if (in_array($term, $this->_localeExcludedTerms) || in_array(substr($term, 1), $this->_excludedTerms))
                {
                    $result .= $term . " ";
                }
                else
                {
                    $color   = $this->_colors[$i++ % $totalColors];
                    $result .= "<span style=\"background:" . $color . "\">" . $term . "</span> ";
                }
            }

            return trim($result);
        }

        /**
         * Add function info here
         * @private
         */
        function _replaceTextualTerms($matches)
        {
            $matches[2] = str_replace("_", "\\_", $matches[2]);
            $matches[2] = str_replace(" ", "__", $matches[2]);

            return $matches[1] . $matches[2] . $matches[3];
        }

        /**
         * Add function info here
         * @private
         */
        function _replaceTerms($matches)
        {
            $char = substr($matches[2], 0, 1);

            switch ($char)
            {
                case "+":
                    $this->_allTerms[]      = $matches[2];
                    $this->_requiredTerms[] = substr($matches[2], 1);
                    break;

                case "-":
                    $this->_allTerms[]      = $matches[2];
                    $this->_excludedTerms[] = substr($matches[2], 1);
                    break;

                case "\"":
                    $matches[2] = str_replace("__", " ", $matches[2]);
                    $matches[2] = str_replace("\\_", "_", $matches[2]);

                    $this->_allTerms[]     = $matches[2];
                    $this->_textualTerms[] = substr($matches[2], 1, -1);
                    break;

                default:
                    $this->_allTerms[] = $matches[2];
                    $this->_terms[]    = $matches[2];
                    break;
            }

            return $matches[0];
        }

        /**
        * Add function info here
        */
        function parse($request)
        {
            $this->reset();

            $request = preg_replace_callback("/([[:space:]]+|^)?(\".+?\")([[:space:]]+|$)/si", array(&$this, "_replaceTextualTerms"), $request);

            $request = preg_replace_callback("/([[:space:]]+|^)?([\"+-]?.+?\"?)([[:space:]]+|$)/si", array(&$this, "_replaceTerms"), $request);

            $this->_localeExcludedTerms = array_unique(array_intersect($this->_terms, $this->_localeStopWordsList));
            $this->_terms = array_diff($this->_terms, $this->_localeExcludedTerms);

            if (count($this->_allTerms) == 1 && count($this->_localeExcludedTerms) == 1)
            {
                $this->_requiredTerms[]     = $this->_localeExcludedTerms[0];
                $this->_localeExcludedTerms = array();
            }

            return true;
        }
    }

?>