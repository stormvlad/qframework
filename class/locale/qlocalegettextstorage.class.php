<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/locale/qlocalestorage.class.php");
    require_once("PEAR.php");
    require_once("File/Gettext.php");

    define("GETTEXT_FILENAME",      "messages.po");
    define("GETTEXT_LANGUAGE_TEAM", "qDevel <info@qdevel.com>");

    /**
     * Extends the Properties class so that our own configuration file is automatically loaded.
     * The configuration file is under config/config.properties.php
     */
    class qLocaleGettextStorage extends qLocaleStorage
    {
        var $_meta;
        var $_localeCode;

        /**
         * Opens the configuration file. By default it is config/config.properties.php
         * if no parameter is specified. If there is a parameter specified, that
         * is the file the constructor will try to open.
         * If no file name is specified, it defaults to config/config.properties.php.
         *
         * @param configFile The name of the file we would like to use.
         */
        function qLocaleGettextStorage($localeCode)
        {
            $this->qLocaleStorage();

            $this->_meta       = new qProperties();
            $this->_localeCode = $localeCode;
        }

        /**
         * Check the locale file exists and return his full pathname.
         *
         * @return Returns always true.
         */
        function fileExists($localeCode)
        {
            $filename = DEFAULT_LOCALE_PATH . $localeCode . "/LC_MESSAGES/" . GETTEXT_FILENAME;

            if (qFile::exists($filename))
            {
                return $filename;
            }

            $filename = DEFAULT_LOCALE_PATH . substr($localeCode, 0, 2) . "/LC_MESSAGES/" . GETTEXT_FILENAME;

            if (qFile::exists($filename))
            {
                return $filename;
            }

            return false;
        }

        /**
         * Reloads the contents from the configuration file.
         *
         * @return Returns always true.
         */
        function load(&$locale)
        {
            $filename = $this->fileExists($this->_localeCode);

            if (!$filename)
            {
                return false;
            }

            $file = File_Gettext::factory("PO", $filename);
            $ret  = $file->load();

            if( PEAR::isError($ret))
            {
                return false;
            }

            $toarray = $file->toArray();
            $this->_meta->setValues($toarray["meta"]);
            $locale->setValues($toarray["strings"]);

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
        * Save the localization PO file
        *
        * @return True if success or false otherwise.
        */
        function save(&$locale)
        {
            // Check the directory exists
            $dirname = DEFAULT_LOCALE_PATH . $this->_localeCode . "/LC_MESSAGES/";

            if (!qFile::exists($dirname))
            {
                $dirname = DEFAULT_LOCALE_PATH . substr($this->_localeCode, 0, 2) . "/LC_MESSAGES/";

                if (!qFile::exists($dirname))
                {
                    return false;
                }
            }

            // Update and set defaults to metadata on PO file
            if (!$this->_meta->keyExists("Language-Team"))
            {
                $this->_meta->setValue("Language-Team", GETTEXT_LANGUAGE_TEAM);
            }

            if (!$this->_meta->keyExists("Content-Type") && $locale->getCharset())
            {
                $this->_meta->setValue("Content-Type", "text/plain; charset=" . $locale->getCharset());
            }

            $this->_meta->setValue("PO-Revision-Date", date("Y-m-d H:iO"));

            // Convert from array to PO file and save all the string values
            $toarray            = Array();
            $toarray["meta"]    = $this->_meta->getAsArray();
            $toarray["strings"] = $locale->getAsArray();

            $file = File_Gettext::factory("PO", $dirname . GETTEXT_FILENAME);
            $file->fromArray($toarray);
            $ret  = $file->save();

            if( PEAR::isError($ret))
            {
                return false;
            }

            return true;
        }
    }
?>