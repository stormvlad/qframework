<?php
/**
 * ADOdb Lite Transaction Module for Frontbase
 * 
 */

eval('class fbsql_transaction_EXTENDER extends fbsql_'. $last_module . '_ADOConnection { }');

class fbsql_transaction_ADOConnection extends fbsql_transaction_EXTENDER
{
}

eval('class fbsql_transaction_resultset_EXTENDER extends fbsql_'. $last_module . '_ResultSet { }');

class fbsql_transaction_ResultSet extends fbsql_transaction_resultset_EXTENDER
{
}
?>