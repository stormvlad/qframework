<?php

/**
 * ADOdb Lite is a PHP class to encapsulate multiple database APIs and is compatible with 
 * a subset of the ADODB Command Syntax. 
 * Currently supports Frontbase, MaxDB, miniSQL, MSSQL, MSSQL Pro, MySQLi, MySQLt, MySQL, PostgresSQL,
 * PostgresSQL64, PostgresSQL7, SqLite and Sybase.
 * 
 * @version 0.01
 */

$ADODB_vers = 'V0.01 ADOdb Lite 5 June 2005  (c) 2005 Mark Dickenson. All rights reserved. Released BSD & LGPL.';

define('ADODB_FETCH_DEFAULT',0);
define('ADODB_FETCH_NUM',1);
define('ADODB_FETCH_ASSOC',2);
define('ADODB_FETCH_BOTH',3);

GLOBAL $ADODB_FETCH_MODE;
$ADODB_FETCH_MODE = ADODB_FETCH_DEFAULT;    // DEFAULT, NUM, ASSOC or BOTH. Default follows native driver default...

/**
 * Database connection
 * Usage: $db = new ADONewConnection('dbtype');
 * 
 * @access public 
 * @param string $dbtype 
 */

function &ADONewConnection( $dbtype = 'mysql' )
{
    return new ADOConnection( $dbtype );
}

/**
 * Alternative Database connection
 * Usage: $db = new NewADOConnection('dbtype');
 * 
 * @access public 
 * @param string $dbtype 
 */

function &NewADOConnection($dbtype='')
{
    $tmp =& ADONewConnection($dbtype);
    return $tmp;
}

class ADOConnection
{
    var $connectionId;
    var $database;
    var $dbtype;
    var $host;
    var $open;
    var $password;
    var $username;
    var $persistent;
    var $error;
    var $errorno = 0;
    var $record_set;
    var $debug = 0;

    function ADOConnection( $dbtype )
    {
        $this->dbtype = strtolower( $dbtype );
    } 

    /**
     * Returns floating point version number of ADOdb Lite
     * Usage: $db->Version();
     * 
     * @access public 
     */

    function Version()
    {
        global $ADODB_vers;
        return (float) substr($ADODB_vers,1);
    }

    /**
     * Returns true if connected to database
     * Usage: $db->IsConnected();
     * 
     * @access public 
     */

    function IsConnected()
    {
        return !empty($this->connectionId);
    }

    /**
    * Set how select queries will retrieve data.
    * Usage: $oldmode = $db->SetFetchMode($mode)
    *
    * @param mode   The fetchmode ADODB_FETCH_ASSOC or ADODB_FETCH_NUM
    * @returns      The previous fetch mode
    */
    function SetFetchMode($mode)
    {   
        GLOBAL $ADODB_FETCH_MODE;
        $old = $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = $mode;
        return $old;
    }

    /**
     * Normal Database connection
     * Usage: $result = $db->Connect('host', 'username', 'password', 'database');
     * 
     * @access public 
     * @param string $database 
     * @param string $host 
     * @param string $password 
     * @param string $username 
     * @param string $forceNew // not implimented 
     */

    function Connect( $host = "", $username = "", $password = "", $database = "", $forceNew = false)
    {
        if ($host != "") $this->host = $host;
        if ($username != "") $this->username = $username;
        if ($password != "") $this->password = $password;
        if ($database != "") $this->database = $database;       
        $this->persistent = 0;

        ///////////////////////////////////////////////////////////////////////////////////////////
        //
        //  Start qDevel modification
        //
        ///////////////////////////////////////////////////////////////////////////////////////////
        
        $this->forceNewConnection = $forceNew;

        ///////////////////////////////////////////////////////////////////////////////////////////
        //
        //  End qDevel modification
        //
        ///////////////////////////////////////////////////////////////////////////////////////////
        
        require_once 'adodbSQL_drivers/' . $this->dbtype . '_driver.php';
        if($this->dbOpen( $this->dbtype )) return true;

        $this->connectionId = false;

        return false;
    } 

    /**
     * Persistent Database connection
     * Usage: $result = $db->PConnect('host', 'username', 'password', 'database');
     * 
     * @access public 
     * @param string $database 
     * @param string $host 
     * @param string $password 
     * @param string $username 
     */

    function PConnect( $host = "", $username = "", $password = "", $database = "")
    {
        if ($host != "") $this->host = $host;
        if ($username != "") $this->username = $username;
        if ($password != "") $this->password = $password;
        if ($database != "") $this->database = $database;       
        $this->persistent = 1;

        include_once 'adodbSQL_drivers/' . $this->dbtype . '_driver.php';
        if($this->dbOpen( $this->dbtype )) return true;

        $this->connectionId = false;

        return false;
    } 

    /**
     * Returns SQL query and instantiates sql statement & resultset driver
     * Driver set by $this->dbtype
     * Usage: $linkId =& $db->execute( 'SELECT * FROM foo ORDER BY id' );
     * 
     * @access public 
     * @param string $sql 
     * @return mixed Resource ID, Associative Array
     */

    function &execute( $sql, $inputarr = false )
    {
        $stmt = $this->dbtype . 'Statement';
        $stmt = new $stmt( $sql, $this->connectionId );
        $rs = $stmt->do_query(); 
        $this->record_set = $rs;
        return $rs;
    } 

    /**
     * Returns SQL query and instantiates sql statement & resultset driver
     * Driver set by $this->dbtype
     * Usage: $linkId =& $db->SelectLimit( 'SELECT * FROM foo ORDER BY id', $nrows, $offset );
     *        $nrows and $offset are optional
     * 
     * @access public 
     * @param string $sql 
     * @param string $nrows 
     * @param string $offset 
     * @return mixed Resource ID, Associative Array
     */

    function &SelectLimit( $sql, $nrows=-1, $offset=-1, $inputarr=false, $secs2cache=0 )
    {
        if($nrows > -1 && $offset <= 0)
        {
            $sql .= " LIMIT " . $nrows;
        }
        else
        if($nrows > -1 && $offset > 0)
        {
            $sql .= " LIMIT " . $offset . ", " . $nrows;
        }
        
        $stmt = $this->dbtype . 'Statement';
        $stmt = new $stmt( $sql, $this->connectionId );
        $rs = $stmt->do_query(); 
        $this->record_set = $rs;
        return $rs;
    } 

    /**
     * Returns the last record id of an inserted item
     * Usage: $db->Insert_ID();
     * 
     * @access public 
     */

    function Insert_ID()
    {
        switch ($this->dbtype)
        {
            case "mysql": 
            case "mysqli": 
            case "mysqlt": 
                $insertid = mysql_insert_id($this->connectionId);
                break;
            case "msql": 
                $insertid = '';  //not implimented yet
                break;
            case "mssql": 
            case "mssqlpo": 
                $insertid = '';  //not implemented yet
                break;
            case "postgres": 
            case "postgres64": 
            case "postgres7": 
                $insertid = pg_getlastoid($this->record_set->_resultid);
                break;
            case "fbsql": 
                $insertid = fbsql_insert_id( $this->connectionId );
                break;
            case "maxdb": 
                $insertid = maxdb_insert_id( $this->connectionId );
                break;
            case "sqlite": 
                $insertid = sqlite_last_insert_rowid( $this->connectionId );
                break;
            case "sybase": 
                $insertid = '';  //not implimented yet
                break;
            default:
                $insertid = '';
                break;
        }

        return $insertid;
    }

    /**
     * Closes database connection
     * Usage: $db->close();
     * 
     * @access public 
     */

    function close()
    {
        if ( $this->dbtype == 'pgsql' )
            $this->dbtype = 'pg';

        $closeConnection = $this->dbtype . '_close';
        $this->connectionId = $closeConnection( $this->connectionId );
    }

    /**
     * Database connection handle
     * 
     * @access protected 
     * @return mixed Link Indentifier
     */

    function getConnectionId()
    {
        return $this->connectionId;
    } 

    /**
     * Return database error message
     * Usage: $errormessage =& $db->ErrorMsg();
     * 
     * @access public
     */

    function ErrorMsg()
    {
        switch ($this->dbtype)
        {
            case "mysql": 
            case "mysqli": 
            case "mysqlt": 
                $this->error = @mysql_error();
                break;
            case "msql": 
                $this->error = @msql_error();
                break;
            case "mssql": 
            case "mssqlpo": 
                $this->error = @mssql_get_last_message();
                break;
            case "postgres": 
            case "postgres64": 
            case "postgres7": 
                $this->error = @pg_last_error( $this->connectionId );
                break;
            case "fbsql": 
                $this->error = @fbsql_error( $this->connectionId );
                break;
            case "maxdb": 
                $this->error = @maxdb_error( $this->connectionId );
                break;
            case "sqlite": 
                $this->errorno = @sqlite_last_error( $this->connectionId );
                $this->error = ($this->errorno) ? sqlite_error_string($this->errorno) : '';
                break;
            case "sybase": 
                $this->error = @sybase_get_last_message();
                break;
            default:
                $this->error = '';
                break;
        }

        return $this->error;
    } 

    /**
     * Return database error message
     * Usage: $errormessage =& $db->ErrorMsg();
     * 
     * @access public
     */

    function ErrorNo()
    {
        switch ($this->dbtype)
        {
            case "mysql": 
            case "mysqli": 
            case "mysqlt": 
                if (empty($this->connectionId))  $this->errorno = @mysql_errno();
                else $this->errorno = @mysql_errno($this->connectionId);
                break;
            case "msql": 
                $this->errorno = (msql_error()) ? -1 : 0;
                break;
            case "mssql": 
            case "mssqlpo": 
                if (empty($this->error)) {
                    $this->error = @mssql_get_last_message();
                }
                $result = @mssql_query("select @@ERROR",$this->connectionId);
                if (!$result) $this->errorno = false;
                $array = mssql_fetch_array($result);
                @mssql_free_result($result);
                if (is_array($array)) $this->errorno = $array[0];
                else $this->errorno = -1;
                break;
            case "postgres": 
            case "postgres64": 
            case "postgres7": 
                $this->error = @pg_last_error( $this->connectionId );
                $this->errorno = strlen($this->error) ? $this->error : 0;
                break;
            case "fbsql": 
                $this->errorno = @fbsql_errno($this->connectionId);
                break;
            case "maxdb": 
                $this->errorno = @maxdb_errno( $this->connectionId );
                break;
            case "sqlite": 
                $this->errorno = @sqlite_last_error( $this->connectionId );
                break;
            case "sybase": 
                $this->error = @sybase_get_last_message();
                $this->errorno = strlen($this->error) ? $this->error : 0;
                break;
            default:
                $this->errorno = '';
                break;
        }

        return $this->errorno;
    } 

    /**
     * Connection to database server and selected database
     * Set via $this->dbtype
     * 
     * @access private 
     */

    function dbOpen( $dbtype )
    {
        if($dbtype == "mysql" || $dbtype == "mysqli" || $dbtype == "mysqlt")
        {
            $dbtype = "mysql";
        }

        if($dbtype == "mssql" || $dbtype == "mssqlpo")
        {
            $dbtype = "mssql";
        }

        if($dbtype == "postgres" || $dbtype == "postgres64" || $dbtype == "postgres7")
        {
            $dbtype = "postgres";
        }

        return $this->$dbtype();        
    } 

    /**
     * miniSQL connection to database server and selected database
     * Sets persistent connection if $this->persistent is true
     * 
     * @access private 
     */

    function msql()
    {
        if($this->persistent == 1)
        {
            $this->connectionId = msql_pconnect( $this->host );
        }
        else
        {
            $this->connectionId = msql_connect( $this->host );
        }
        if ($this->connectionId === false) return false;
        @msql_select_db( $this->database, $this->connectionId );
        return true;
    } 

    /**
     * * Get msql sequence for index auto increment
     *
     * @access public 
     * @param string $table
     * @param string $connection
     */

    function getSequence( $table, $connection )
    {
        $result = @msql_query( 'select _seq from ' . $table, $connection );
        $seq = msql_fetch_array( $result );
         
        return $seq[_seq];
    } 

    /**
     * MSSQL connection to database server and selected database
     * Sets persistent connection if $this->persistent is true
     * 
     * @access private 
     */

    function mssql()
    {
        if($this->persistent == 1)
        {
            $this->connectionId = @mssql_pconnect( $this->host, $this->username, $this->password );
        }
        else
        {
            $this->connectionId = @mssql_connect( $this->host, $this->username, $this->password );
        }
        if ($this->connectionId === false) return false;
        @mssql_select_db( $this->database, $this->connectionId );
        return true;
    } 

    /**
     * MySQL connection to database server and selected database
     * Sets persistent connection if $this->persistent is true
     * 
     * @access private 
     */

    function mysql()
    {
        if($this->persistent == 1)
        {
            $this->connectionId = @mysql_pconnect( $this->host, $this->username, $this->password, /* Start qDevel Modification */ $this->forceNewConnection /* End qDevel Modification */);
        }
        else
        {
            $this->connectionId = @mysql_connect( $this->host, $this->username, $this->password, /* Start qDevel Modification */ $this->forceNewConnection /* End qDevel Modification */);
        }
        if ($this->connectionId === false) return false;
        @mysql_select_db( $this->database, $this->connectionId );
        return true;
    } 

    /**
     * PostgresSQL connection to database server and selected database
     * Sets persistent connection if $this->persistent is true
     * 
     * @access private 
     */

    function postgres()
    {
        //$this->connectionId = pg_connect( 'host=' . $this->host . ' dbname=' . $this->database . ' user=' . $this->username . ' password=' . $this->password );
        if($this->persistent == 1)
        {
            $this->connectionId = @pg_pconnect( "host=$this->host dbname=$this->database user=$this->username password=$this->password" );
        }
        else
        {
            $this->connectionId = @pg_connect( "host=$this->host dbname=$this->database user=$this->username password=$this->password" );
        }
        if ($this->connectionId === false) return false;
        return true;
    } 

    /**
     * SQLite connection to database server and selected database
     * Sets persistent connection if $this->persistent is true
     * 
     * @access private 
     */

    function sqlite()
    {
        if($this->persistent == 1)
        {
            $this->connectionId = @sqlite_popen( $this->database );
        }
        else
        {
            $this->connectionId = @sqlite_open( $this->database );
        }
        if ($this->connectionId === false) return false;
        return true;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    //
    //  Start qDevel modification
    //
    ///////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Always force a new connection to database
     *
     * @param [argHostname]     Host to connect to
     * @param [argUsername]     Userid to login
     * @param [argPassword]     Associated password
     * @param [argDatabaseName] database
     *
     * @return true or false
     */
    function NConnect( $host = "", $username = "", $password = "", $database = "")
    {
        return $this->Connect($host, $username, $password, $database, true);
    }

    function _findschema(&$table,&$schema)
    {
        if (!$schema && ($at = strpos($table,'.')) !== false) {
            $schema = substr($table,0,$at);
            $table = substr($table,$at+1);
        }
    }

    /**
     * @param ttype can either be 'VIEW' or 'TABLE' or false.
     *      If false, both views and tables are returned.
     *      "VIEW" returns only views
     *      "TABLE" returns only tables
     * @param showSchema returns the schema/user with the table name, eg. USER.TABLE
     * @param mask  is the input mask - only supported by oci8 and postgresql
     *
     * @return  array of tables for current database.
     */
    function &MetaTables($ttype=false,$showSchema=false,$mask=false)
    {
        global $ADODB_FETCH_MODE;

        $metaTablesSQL = "SHOW TABLES";
        
        if ($showSchema && is_string($showSchema)) {
            $metaTablesSQL .= " FROM $showSchema";
        }
        
        if ($mask) {
            $mask = $this->qstr($mask);
            $metaTablesSQL .= " LIKE $mask";
        }

        // complicated state saving by the need for backward compat
        $save = $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($this->fetchMode !== false) $savem = $this->SetFetchMode(false);

        $rs = $this->Execute($metaTablesSQL);
        if (isset($savem)) $this->SetFetchMode($savem);
        $ADODB_FETCH_MODE = $save;

        if ($rs === false) return false;
        $arr =& $rs->GetArray();
        $arr2 = array();

        if ($hast = ($ttype && isset($arr[0][1]))) {
            $showt = strncmp($ttype,'T',1);
        }

        for ($i=0; $i < sizeof($arr); $i++) {
            if ($hast) {
                if ($showt == 0) {
                    if (strncmp($arr[$i][1],'T',1) == 0) $arr2[] = trim($arr[$i][0]);
                } else {
                    if (strncmp($arr[$i][1],'V',1) == 0) $arr2[] = trim($arr[$i][0]);
                }
            } else
                $arr2[] = trim($arr[$i][0]);
        }
        $rs->Close();
        return $arr2;
    }

    /**
     * @returns an array with the primary key columns in it.
     */
    function MetaPrimaryKeys($table, $owner=false)
    {
    // owner not used in base class - see oci8
        $p = array();
        $objs =& $this->MetaColumns($table);
        if ($objs) {
            foreach($objs as $v) {
                if (!empty($v->primary_key))
                    $p[] = $v->name;
            }
        }
        if (sizeof($p)) return $p;
        if (function_exists('ADODB_VIEW_PRIMARYKEYS'))
            return ADODB_VIEW_PRIMARYKEYS($this->databaseType, $this->database, $table, $owner);
        return false;
    }

    /**
     * List columns in a database as an array of ADOFieldObjects.
     * See top of file for definition of object.
     *
     * @param table table name to query
     * @param upper uppercase table name (required by some databases)
     * @schema is optional database schema to use - not supported by all databases.
     *
     * @return  array of ADOFieldObjects for current table.
     */
    function &MetaColumns($table)
    {
        global $ADODB_FETCH_MODE;
            $save = $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if ($this->fetchMode !== false)
            $savem = $this->SetFetchMode(false);
            $rs = $this->Execute(sprintf("SHOW COLUMNS FROM %s",$table));
            if (isset($savem)) $this->SetFetchMode($savem);
            $ADODB_FETCH_MODE = $save;
        if (!is_object($rs))
            return false;
            
            $retarr = array();
            while (!$rs->EOF){
                $fld = new ADOFieldObject();
                $fld->name = $rs->fields[0];
                $type = $rs->fields[1];
                
                // split type into type(length):
                $fld->scale = null;
            if (preg_match("/^(.+)\((\d+),(\d+)/", $type, $query_array)) {
                    $fld->type = $query_array[1];
                    $fld->max_length = is_numeric($query_array[2]) ? $query_array[2] : -1;
                    $fld->scale = is_numeric($query_array[3]) ? $query_array[3] : -1;
                } elseif (preg_match("/^(.+)\((\d+)/", $type, $query_array)) {
                    $fld->type = $query_array[1];
                    $fld->max_length = is_numeric($query_array[2]) ? $query_array[2] : -1;
                } else {
                $fld->type = $type;
                    $fld->max_length = -1;
                }
                $fld->not_null = ($rs->fields[2] != 'YES');
                $fld->primary_key = ($rs->fields[3] == 'PRI');
                $fld->auto_increment = (strpos($rs->fields[5], 'auto_increment') !== false);
            $fld->binary = (strpos($type,'blob') !== false);
            $fld->unsigned = (strpos($type,'unsigned') !== false);
                
                if (!$fld->binary) {
                    $d = $rs->fields[4];
                if ($d != '' && $d != 'NULL') {
                        $fld->has_default = true;
                        $fld->default_value = $d;
                    } else {
                        $fld->has_default = false;
                    }
                }
            
            if ($save == ADODB_FETCH_NUM) {
                $retarr[] = $fld;
            } else {
                $retarr[strtoupper($fld->name)] = $fld;
            }
                $rs->MoveNext();
            }
        
            $rs->Close();
            return $retarr;
    }

    /**
     * List columns names in a table as an array.
     * @param table table name to query
     *
     * @return  array of column names for current table.
     */
    function &MetaColumnNames($table, $numIndexes=false)
    {
        $objarr =& $this->MetaColumns($table);
        if (!is_array($objarr)) return false;

        $arr = array();
        if ($numIndexes) {
            $i = 0;
            foreach($objarr as $v) $arr[$i++] = $v->name;
        } else
            foreach($objarr as $v) $arr[strtoupper($v->name)] = $v->name;

        return $arr;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    //
    //  End qDevel modification
    //
    ///////////////////////////////////////////////////////////////////////////////////////////
} 

    /**
     * Frontbase connection to database server and selected database
     * Sets persistent connection if $this->persistent is true
     * 
     * @access private 
     */

    function fbsql()
    {
        if($this->persistent == 1)
        {
            $this->connectionId = @fbsql_pconnect( $this->host, $this->username, $this->password );
        }
        else
        {
            $this->connectionId = @fbsql_connect( $this->host, $this->username, $this->password );
        }
        if ($this->connectionId === false) return false;
        @fbsql_select_db( $this->database, $this->connectionId );
        return true;
    } 

    /**
     * MaxDB connection to database server and selected database
     * Sets persistent connection if $this->persistent is true
     * 
     * @access private 
     */

    function maxdb()
    {
        $this->connectionId = @maxdb_connect( $this->host, $this->username, $this->password );

        if ($this->connectionId === false) return false;
        @maxdb_select( $this->connectionId, $this->database );
        return true;
    } 

    /**
     * Sybase connection to database server and selected database
     * Sets persistent connection if $this->persistent is true
     * 
     * @access private 
     */

    function sybase()
    {
        if($this->persistent == 1)
        {
            $this->connectionId = @sybase_pconnect( $this->host, $this->username, $this->password );
        }
        else
        {
            $this->connectionId = @sybase_connect( $this->host, $this->username, $this->password );
        }
        if ($this->connectionId === false) return false;
        @sybase_select_db( $this->database, $this->connectionId );
        return true;
    }

///////////////////////////////////////////////////////////////////////////////////////////
//
//  Start qDevel modification
//
///////////////////////////////////////////////////////////////////////////////////////////
    
/**
* Helper class for FetchFields -- holds info on a column
*/
class ADOFieldObject
{
    var $name = '';
    var $max_length=0;
    var $type="";

    // additional fields by dannym... (danny_milo@yahoo.com)
    var $not_null = false;
    // actually, this has already been built-in in the postgres, fbsql AND mysql module? ^-^
    // so we can as well make not_null standard (leaving it at "false" does not harm anyways)

    var $has_default = false; // this one I have done only in mysql and postgres for now ...
        // others to come (dannym)
    var $default_value; // default, if any, and supported. Check has_default first.
}

///////////////////////////////////////////////////////////////////////////////////////////
//
//  End qDevel modification
//
///////////////////////////////////////////////////////////////////////////////////////////

?>