<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/locale/qlocalestorage.class.php");

    /**
     * Extends the Properties class so that our own configuration file is automatically loaded.
     * The configuration file is under config/config.properties.php
     */
    class qLocaleFileStorage extends qLocaleStorage
    {
        var $_localeFile;

        /**
         * Opens the configuration file. By default it is config/config.properties.php
         * if no parameter is specified. If there is a parameter specified, that
         * is the file the constructor will try to open.
         * If no file name is specified, it defaults to config/config.properties.php.
         *
         * @param localeFile string The name of the file we would like to use.
         */
        function qLocaleFileStorage($localeFile)
        {
            $this->qLocaleStorage();
            $this->_localeFile = $localeFile;
        }

        /**
         * Reloads the contents from the configuration file.
         *
         * @param locale qLocale
         * @return Returns always true.
         */
        function load(&$locale)
        {
            include($this->_localeFile);
            $locale->setValues($messages);
            return true;
        }

        /**
         * Returns the name of the configuration file being used.
         *
         * @return The name of the configuration file being used.
         */
        function getLocaleFileName()
        {
            return $this->_localeFile;
        }

        /**
         * Saves a setting to the configuration file. If the setting already exists, the current
         * value is overwritten. Otherwise, it will be appended in the end of the file.
         * <b>NOTE:</b> This method is highly unoptimized because every time that we call saveValue,
         * we are writing the whole file to disk... Bad ;) But it works, so we'll leave it as it
         * is for the time being...
         *
         * @param locale qLocale
         * @param name Name of the setting.
         * @param value Value of the setting.
         * @return True if success or false otherwise.
         */
        function saveValue(&$locale, $name, $value)
        {
            throw(new qException("qLocaleFileStorage::saveValue: This method has not implemented yet."));
            die();
        }

        /**
         *    Add function info here
         *
         * @param locale qLocale
         */
        function save(&$locale)
        {
            throw(new qException("qLocaleFileStorage::save: This method has not implemented yet."));
            die();
        }
    }
?>