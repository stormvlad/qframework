<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Container provides storage for user data.
     *
     * @package qframework
     * @since   1.0
     */
    class qConversionPattern extends qObject
    {
        /**
         * The function that will be called when a conversion character is parsed.
         *
         * @access private
         * @since  1.0
         * @type   string
         */
        var $func;

        /**
         * The object containing the function to be called when a conversion
         * character is parsed.
         *
         * @access private
         * @since  1.0
         * @type   object
         */
        var $obj;

        /**
         * A pattern containing conversion characters.
         *
         * @access private
         * @since  1.0
         * @type   string
         */
        var $pattern;

        /**
         * Create a new ConversionPattern instance.
         *
         * @param string A pattern containing conversion characters.
         *
         * @return ConversionPattern A ConversionPattern instance.
         *
         * @access public
         * @since  1.0
         */
        function &qConversionPattern ($pattern = NULL)
        {
            parent::qObject();

            $this->func    = NULL;
            $this->obj     = NULL;
            $this->pattern = $pattern;
        }

        /**
         * Retrieve a parameter for a conversion character.
         *
         * @param int The pattern index at which we're working.
         *
         * @return string A conversion character parameter if one one exists,
         *                otherwise <b>NULL</b>.
         *
         * @access private
         * @since  1.0
         */
        function getParameter (&$index)
        {
            $length = strlen($this->pattern);
            $param  = '';

            // skip ahead to parameter
            $index += 2;

            if ($index < $length)
            {
                // loop through conversion character parameter
                while ($this->pattern{$index} != '}' && $index < $length)
                {
                    $param .= $this->pattern{$index};
                    $index++;
                }

                if ($this->pattern{$index} == '}')
                {
                    return $param;
                }

                // parameter found but no ending }
            }

            // oops, not enough text to go around
            return NULL;
        }

        /**
         * Retrieve the conversion pattern.
         *
         * @return string A conversion pattern.
         *
         * @access public
         * @since  1.0
         */
        function getPattern ()
        {
            return $this->pattern;
        }

        /**
         * Parse the conversion pattern.
         *
         * @return string A string with conversion characters replaced with their
         *                respective values.
         *
         * @access public
         * @since  1.0
         */
        function &parse ()
        {
            if ($this->pattern == NULL)
            {
                throw(new qException("qConversionPattern::parse: A conversion pattern has not been specified."));
                return;
            }

            $length  = strlen($this->pattern);
            $pattern = '';

            for ($i = 0; $i < $length; $i++)
            {
                if ($this->pattern{$i} == '%' && ($i + 1) < $length)
                {
                    if ($this->pattern{$i + 1} == '%')
                    {
                        $data = '%';
                        $i++;
                    }
                    else
                    {
                        // grab conversion char
                        $char  = $this->pattern{++$i};
                        $param = NULL;

                        // does a parameter exist?
                        if (($i + 1) < $length && $this->pattern{$i + 1} == '{')
                        {
                            // retrieve parameter
                            $param = $this->getParameter($i);
                        }

                        if ($this->obj == NULL)
                        {
                            $data = $function($char, $param);
                        }
                        else
                        {
                            $object   =& $this->obj;
                            $function =& $this->func;

                            $data = $object->$function($char, $param);
                        }
                    }

                    $pattern .= $data;
                }
                else
                {
                    $pattern .= $this->pattern{$i};
                }
            }

            return $pattern;
        }

        /**
         * Set the callback function.
         *
         * @param string A function name.
         *
         * @access public
         * @since  1.0
         */
        function setCallbackFunction ($function)
        {
            $this->func = $function;
        }

        /**
         * Set the callback object and function.
         *
         * @param object An object holding a callback function.
         * @param string A function name.
         *
         * @access public
         * @since  1.0
         */
        function setCallbackObject (&$object, $function)
        {
            $this->func =  $function;
            $this->obj  =& $object;
        }

        /**
         * Set the conversion pattern.
         *
         * @param string A pattern consisting of one or more conversion characters.
         *
         * @access public
         * @since  1.0
         */
        function setPattern ($pattern)
        {
            $this->pattern = $pattern;
        }
    }
?>