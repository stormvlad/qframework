<?php
/**
 * ADOdb Lite Transaction Module for Mysql
 * 
 */

eval('class mysql_transaction_EXTENDER extends mysql_'. $last_module . '_ADOConnection { }');

class mysql_transaction_ADOConnection extends mysql_transaction_EXTENDER
{
}

eval('class mysql_transaction_resultset_EXTENDER extends mysql_'. $last_module . '_ResultSet { }');

class mysql_transaction_ResultSet extends mysql_transaction_resultset_EXTENDER
{
}
?>