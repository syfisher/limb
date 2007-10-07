<?php

set_include_path(dirname(__FILE__) . '/../../../../');
require_once('limb/core/common.inc.php');
require_once('limb/dbal/common.inc.php');

if($native_db = sqlite_open('/tmp/benchdb'))
{
  sqlite_query($native_db, 'CREATE TABLE foo (bar varchar(10))');
  sqlite_query($native_db, "INSERT INTO foo VALUES ('some value')");
}

$mark = microtime(true);

for($i=0;$i<1000;$i++)
{
  $query = sqlite_query($native_db, 'SELECT bar FROM foo');
  while($entry = sqlite_fetch_array($query, SQLITE_ASSOC))
   $bar = $entry['bar'];
}

echo "native sqlite fetching: " . (microtime(true) - $mark) . "\n";

$conn = lmbDBAL :: newConnection('sqlite://localhost//tmp/benchdb');

$mark = microtime(true);

for($i=0;$i<1000;$i++)
{
  $rs = lmbDBAL :: fetch('SELECT bar FROM foo', $conn);
  foreach($rs as $record)
   $bar = $record['bar'];
}

echo "lmbDBAL :: fetch(), array access: " . (microtime(true) - $mark) . "\n";

$mark = microtime(true);

for($i=0;$i<1000;$i++)
{
  $rs = lmbDBAL :: fetch('SELECT bar FROM foo', $conn);
  foreach($rs as $record)
   $bar = $record->get('bar');
}

echo "lmbDBAL :: fetch(), getter: " . (microtime(true) - $mark) . "\n";

$mark = microtime(true);

for($i=0;$i<1000;$i++)
{
  $stmt = $conn->newStatement('SELECT bar FROM foo');
  $rs = $stmt->getRecordSet();
  foreach($rs as $record)
   $bar = $record->get('bar');
}

echo "lmbSqliteConnection :: newStatement(), getter: " . (microtime(true) - $mark) . "\n";

unlink('/tmp/benchdb');