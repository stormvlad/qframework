<?php
/**
 * ADOdb Lite Transaction Module for MaxDB
 * 
 */

eval('class maxdb_transaction_EXTENDER extends maxdb_'. $last_module . '_ADOConnection { }');

class maxdb_transaction_ADOConnection extends maxdb_transaction_EXTENDER
{
}

eval('class maxdb_transaction_resultset_EXTENDER extends maxdb_'. $last_module . '_ResultSet { }');

class maxdb_transaction_ResultSet extends maxdb_transaction_resultset_EXTENDER
{
}
?>