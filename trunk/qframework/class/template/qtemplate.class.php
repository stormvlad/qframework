<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/template/smarty/Smarty.class.php");

    define(DEFAULT_TEMPLATE_CACHE_DIR, "tmp/");
    define(DEFAULT_TEMPLATE_COMPILE_DIR, "tmp/");
    define(DEFAULT_TEMPLATE_TEMPLATES_DIR, "templates/");

    /**
     * Wrapper around the Smarty class, inspired by the article
     * http://zend.com/zend/tut/tutorial-stump.php
     *
     * Provides additional security settings that by default are not enabled in the
     * original Smarty class.
     *
     * It is recommended to use the TemplateService class to create the Template objects.
     */
    class qTemplate extends Smarty
    {
        var $_templateFile;

        /**
         * Constructor. By default, activates the security mode of Smarty,
         * disabling for example any kind of PHP code embedded in the Smarty templates.
         *
         * @param templateFile Complete path to the template file we are going to render
         * @param cache By default set to true and specifies wether we should cache the
         * contents or not.
         * @param cacheLifeTime How many seconds we would like to cache the objects
         */
        function qTemplate($templateFile, $cache = false, $cacheLifetime = 300)
        {
            // create the Smarty object and set the security values
            $this->Smarty();
            $this->caching        = $cache;
            $this->cache_lifetime = $cacheLifeTime;
            $this->cache_dir      = DEFAULT_TEMPLATE_CACHE_DIR;

            $this->_templateFile  = $templateFile;

            // enable the security settings
            $this->php_handling   = false;

            // default folders
            $this->compile_dir    = DEFAULT_TEMPLATE_COMPILE_DIR;
            $this->template_dir   = DEFAULT_TEMPLATE_TEMPLATES_DIR;

            //$this->template_dir = $config->getValue( "template_folder" )."/blog_1/";
            // this helps if php is running in 'safe_mode'
            $this->use_sub_dirs = false;
        }

        /**
         * By default templates are searched in the folder specified by the
         * template_folder configuration setting, but we can force Smarty to
         * look for those templates somewhere else. This method is obviously to be
         * used *before* rendering the template ;)
         *
         * @param templateFolder The new path where we'd like to search for templates
         * @return Returns always true.
         */
        function setTemplateDir($templateDir)
        {
            $this->template_dir = $templateDir;
            return true;
        }

        /**
         * Returns the name of the template file
         *
         * @return The name of the template file
         */
        function getTemplateFile()
        {
            return $this->_templateFile;
        }

        /**
         * Renders the template and returns the contents as an string
         *
         * @return The result as an string
         */
        function fetch()
        {
            return Smarty::fetch($this->_templateFile);
        }

        /**
         * Displays the result of rendering the template
         *
         * @return I don't know :)
         */
        function display()
        {
            return Smarty::display($this->_templateFile);
        }
    }
?>
