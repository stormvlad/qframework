<?php

    include_once("qframework/class/object/qobject.class.php" );
    include_once("qframework/class/config/qconfig.class.php" );
    include_once("qframework/class/database/adodb/adodb.inc.php" );
    include_once("qframework/class/database/qdb.class.php" );

    define( DEFAULT_DB_PREFIX, "plog_" );

    /**
     * Represents a data model according to the MVC architecture.
     *
     * This class provides all the classes extending it with a database
     * connection so that classes don't have to worry about that. Later on, the Model
     * classes will be used by the corresponding action object.
     */
    class qModel extends qObject {

        var $_db;
        var $_config;
        var $_prefix;

        /**
         * So far, it only initializes the connection to the database, using the ADOdb API.
         */
        function qModel()
        {
            //$this->_fileConfig = new ConfigFileStorage();

            $this->_db =& Db::getDb();

            // fetch the database prefix
            $this->_config = qConfig::getConfig();
            $this->_prefix = $this->_config->getValue( "db_prefix", DEFAULT_DB_PREFIX );

            $this->_db->LogSQL( true );
            $this->_db->debug=false;
        }

        /**
         * Private method that connects to the database
         */
        function _connectToDb()
        {
            $this->_db = NewADOConnection('mysql');

            $username = $this->_fileConfig->getValue( "db_username" );
            $password = $this->_fileConfig->getValue( "db_password" );
            $host     = $this->_fileConfig->getValue( "db_host" );
            $dbname   = $this->_fileConfig->getValue( "db_database" );

            if( !$this->_db->PConnect( $host, $username, $password, $dbname )) {
                throw( new qException( "Fatal error: could not connect to the database!" ));
                die();
            }

            // just in case, forcing to use indexing by field name instead of
            // by field number
            $this->_db->SetFetchMode( ADODB_FETCH_ASSOC );
        }

        /**
         * Returns the last error message from the database.
         */
        function DbError()
        {
            return $this->_db->ErrorMsg();
        }

        function getPrefix()
        {
            return $this->_prefix;
        }
    }
?>
