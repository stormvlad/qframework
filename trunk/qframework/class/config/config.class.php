<?php

    include_once("framework/class/object/object.class.php" );

    /**
     * Extends the Properties class so that our own configuration file is automatically loaded.
     * The configuration file is under config/config.properties.php
     *
     * It is recommented to use this function as a singleton rather than as an object.
     * @see Config
     * @see getConfig
     */
    class Config extends Object {

        /**
         * Initializes the configuration back end.
         *
         * Unless strictly necessary, it is recommended to use the getConfig function,
         * built around the idea of the Singleton pattern.
         * It will also bring increased performance since then there is no need to create the
         * whole ConfigXxxxStorage object. For instance, in the case of the ConfigDbStorage,
         * the whole config table is retrieved in the constructor... Using a singleton will
         * save us that work.
         * @param storage One of the storage methods implemented. Available ones are
         * "db" and "file", but any other can be implemented.
         * @param params An array containing storage backend specific parameters. In the case
         * of the file-based storage it could be the name of the file to use (for example)
         */
        function Config()
        {
            $this->Object();
        }

        /**
         * Makes sure that there is <b>only one</b> instance of this class for everybody.
         * It is not bad to call the constructor but using the getConfig we will
         * save some work.
         *
         * @param storage One of the storage methods implemented. Available ones are
         * "db" and "file", but any other can be implemented.
         * @param params An array containing storage backend specific parameters. In the case
         * of the file-based storage it could be the name of the file to use (for example)
         * @return Returns an instance of the Config class, be it a new one if this is the first
         * time we were calling it or an already created one if somebody else called
         * this method before.
         * @see ConfigDbStorage
         * @see ConfigFileStorage
         */
        function &getConfig()
        {
            throw(new Exception("Config::getConfig: This function must be implemented by child classes."));
            die();
        }
    }
?>
