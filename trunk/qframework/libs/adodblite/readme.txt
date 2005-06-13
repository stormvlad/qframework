ADODB LITE version 0.1

We have been using ADODB for a number of our web sites for quite 
some time and decided to look into the memory requirements of the 
latest version.  We were quite frankly horrified to find out ADODB 
uses approximately 650k of ram for each page load.  On some of our 
sites this is 10-50 times larger than the pages of PHP code.  That 
is quite frankly unacceptable.  So we decided to come up with a 
very scaled back ADODB LITE that includes the most commonly used 
functions while being compact.  The current version only uses 
about 80k of ram.

We are only supporting a subset of the commands from ADODB but 
these are commands most people will be using.  We dropped all of 
the esoteric commands as their usefulness is minimal while they 
consume vast amounts of resources needlessly.

I really hope the ADODB team will finally decide to break down the 
main code into modules that are loaded ONLY when they are needed 
instead of doing the shotgun method of loading everything.  The 
100+k adodb.inc.php from ADODB compiles to a size of over 450k 
when it is executed.  If they went a totally modular route this 
would be drastically reduced.

One of the added benifits of ADODB LITE is speed.  It is virtually 
just as fast as using native PHP database commands.

The currently supported databases are a subset of the databases 
supported by ADODB.

fbsql (FrontBase)
maxdb
msql
mssql
mysql
mysqli
mysqlt
postgres
postgres7
postgres64
sqlite
sybase

ADODB LITE Initial Startup
--------------------------
require_once "adodb/adodb.inc.php"; 
$db = ADONewConnection("mysql");

$result = $db->Connect("$dbhost", "$dbuname", "$dbpass", "$dbname");

or persistent connection

$result = $db->PConnect("$dbhost", "$dbuname", "$dbpass", "$dbname");


The availible command subset...

$ADODB_FETCH_MODE = 'ADODB_FETCH_DEFAULT'|'ADODB_FETCH_NUM'|'ADODB_FETCH_ASSOC'|'ADODB_FETCH_BOTH'

$db->Execute($sql)
$db->SelectLimit( $sql, [nrows], [offset] ) - Not supported for all databases
$db->Insert_ID()
$db->close()
$db->ErrorMsg()
$db->ErrorNo()
$db->Version()
$db->IsConnected()
$db->SetFetchMode($mode) - $mode = 'ADODB_FETCH_DEFAULT'|'ADODB_FETCH_NUM'|'ADODB_FETCH_ASSOC'|'ADODB_FETCH_BOTH'

$result->Affected_Rows()
$result->Fields([column])
$result->Fields
$result->RecordCount()
$result->MoveNext()
$result->MoveFirst()
$result->MoveLast()
$result->Move([row])
$result->EOF()
$result->EOF
$result->GetArray([nRows])
$result->GetRows([nRows])
$result->GetAll([nRows])
$result->close()

This is just a very small subset of the ADODB commands.  We will add 
others as they are needed in the future.  We created this mainly for 
our websites and Alien Assault Traders as we don't need all of the 
bells and whistles from ADODB.

Examples:

<?
include('adodb.inc.php');
$db = &ADONewConnection('access');
$db->Connect('mysql');
$result = $db->Execute('select * from table');
if (!$result) 
	print $db->ErrorMsg();
else
while (!$result->EOF) {
	print $result->fields[0].' '.$result->fields[1].'<BR>';
	$result->MoveNext();
}
$result->Close();
$db->Close();
?>

<?
include('adodb.inc.php');
$db = &ADONewConnection('access');
$db->Connect('mysql');
$db->SetFetchMode(ADODB_FETCH_NUM);
$result1 = $db->Execute('select * from table');
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$result2 = $db->Execute('select * from table');
print_r($result1->fields); # shows array([0]=>'v0',[1] =>'v1')
print_r($result2->fields); # shows array(['col1']=>'v0',['col2'] =>'v1')
?>

If you are not familier with ADOdb then I would suggest reading the ADOdb Manual located at http://phplens.com/lens/adodb/docs-adodb.htm

