<?php
/* 1. upload this file to your webserver together with class.nodb.php and time.php
 * 2. run it with webbrowser
 */
$accessRights = 0770; // create databases/files/tables with these accessrights 
echo "<hr><h1 color='red'>test nodb database management commands</h1><br>";
include("class.nodb.php");
$nodbObj = new nodb("./databases"); // specify root folder where all databases reside in

// success($nodbObj->delDatabase(array("databaseTest1","databaseTest2","databaseTest3"))); // reset test conditions

echo "output instance as string<br>";

echo($nodbObj);

echo "<hr><h1 color='red'>test database commands</h1><br>";
comment("create database");
success($nodbObj->addDatabase("databaseTest1"));

comment("copy database");
success($nodbObj->copyDatabase("databaseTest1","databaseTest2"));

comment("rename database");
success($nodbObj->renameDatabase("databaseTest2","databaseTest3"));

comment("delete database");
success($nodbObj->delDatabase("databaseTest3"));

echo "<hr><h1 color='red'>test table commands</h1><br>";

comment("create table");
success($nodbObj->addTable("testTable1","databaseTest1"));

comment("copy table");
success($nodbObj->copyTable("testTable1","testTable2","databaseTest1"));

comment("rename table");
success($nodbObj->renameTable("testTable2","testTable3","databaseTest1"));

comment("delete table");
success($nodbObj->delTable("testTable3","databaseTest1"));

echo "<hr><h1 color='red'>test column commands</h1><br>";

comment("create column");
success($nodbObj->addColumn("columnTest1","testTable1","databaseTest1"));

comment("create column");
success($nodbObj->addColumn("columnTest2","testTable1","databaseTest1"));

comment("rename column");
success($nodbObj->renameColumn("columnTest2","columnTest3","testTable1"));

comment("delete column");
success($nodbObj->delColumn("columnTest1"));
success($nodbObj->delColumn("columnTest3"));

echo "<hr><h1 color='red'>test record management commands</h1><br>";

success($nodbObj->addColumn("name","testTable1","databaseTest1"));
success($nodbObj->addColumn("street","testTable1","databaseTest1"));
success($nodbObj->addColumn("phone","testTable1","databaseTest1"));
success($nodbObj->addColumn("mail","testTable1","databaseTest1"));

comment("add record at end (no lineNumber given)");
success($nodbObj->add("name:tom;street:street;mail:tom@mail.com;")); // add tom without phone number (should insert empty line)
success($nodbObj->add("name:Sandra;street:street;phone:+000000000000000;mail:sandra@mail.com;"));
success($nodbObj->add("name:Sellerie;street:street;phone:+111111111111111;mail:sellerie@mail.com;"));
success($nodbObj->add("name:Sauerkraut;phone:+22222222222222;mail:sauerkraut@mail.com;")); // add without street
success($nodbObj->add("name:jerry;street:street;phone:+33333333333333333;mail:jerry@mail.com;"));

comment("insert record at position (lineNumber given)");
success($nodbObj->insert(1,"name:joe;street:street;phone:+00981232112312;mail:joe@mail.com;"));
success($nodbObj->insert(3,"name:jim;street:street;phone:+00981232112312;mail:jim@mail.com;"));
success($nodbObj->insert(2,"name:jennifer;street:street;phone:+00981232112312;mail:jennifer@mail.com;"));

comment("insert record at illegal index(lineNumber) 0 - you get an error");
success($nodbObj->insert(0,"name:jennifer;street:street;phone:+00981232112312;mail:jennifer@mail.com;"));

comment("change/replace/update record");	
success($nodbObj->update(2,"name:jill;phone:+12345;"));

comment("change/replace/update multiple records");
success($nodbObj->update(array(1,2,3),"name:NewName;phone:+NewNumber;street:NewStreet;"));

comment("change/replace/update the all records where name = jill with joe");
success($nodbObj->update($nodbObj->where("NewName","name"),"name:joe;phone:+999999;"));

echo "<hr><h1 color='red'>try read commands</h1><br>";

comment("get one single record from table");
print_r_html($nodbObj->read(1));
success($nodbObj->worked);

comment("get one mutliple record from a table");
print_r_html($nodbObj->read(array(1,2,3)));
success($nodbObj->worked);

comment("get a range of records from a table");
print_r_html($nodbObj->read("1-3"));
success($nodbObj->worked);

comment("get all records where name == 'jim' \$nodbObj->read(\$nodbObj->where('jim'));");
print_r_html($nodbObj->read($nodbObj->where('jim','name')));
success($nodbObj->worked);

comment("get all records from a table, top-array-keys represent the columns");
print_r_html($nodbObj->readTable("testTable1"));
success($nodbObj->worked);

/* CREATE MORE DATA */
$nodbObj->addTable("testTable2","databaseTest1",$accessRights);
$nodbObj->addColumn("name");
$nodbObj->addColumn("street");
$nodbObj->addColumn("phone");
$nodbObj->addColumn("mail");
$nodbObj->add("name:tom;street:street;phone:+00981232112312;mail:tom@mail.com;");
$nodbObj->add("name:jerry;street:street;phone:+00123212323232;mail:jerry@mail.com;");
$nodbObj->addColumn("columnTest2");
$nodbObj->add("name:tom;street:street;phone:+00981232112312;mail:tom@mail.com;");
$nodbObj->add("name:jerry;street:street;phone:+00981232112312;mail:jerry@mail.com;");
$nodbObj->addColumn("columnTest3");
$nodbObj->add("name:tom;street:street;phone:+00981232112312;mail:tom@mail.com;");
$nodbObj->add("name:jerry;street:street;phone:+00981232112312;mail:jerry@mail.com;");
/* CREATE MORE DATA FINISHED */

comment("get whole database as a object-array with sub arrays");
$result = $nodbObj->readDatabase();
print_r_html($result);
success($nodbObj->worked);

comment("add column to table where columns have content (needs to be all same linecount = insync)");
$nodbObj->addColumn("test","testTable1","databaseTest1");

echo "<hr><h1 color='red'>try delete commands</h1><br>";

comment("try to delete with problematic index, you should see an error following:");
success($nodbObj->delete(null));

comment("delete one record");
success($nodbObj->delete(1));

comment("delete multiple records");
success($nodbObj->delete(array(1,2,3)));

comment("add record at end (no lineNumber given)");
success($nodbObj->add("name:tom;street:street;phone:+00981232112312;mail:tom@mail.com;"));
success($nodbObj->add("name:jerry;street:street;phone:+00981232112312;mail:jerry@mail.com;"));

comment("delete range of records");
success($nodbObj->delete("1-2"));

echo "<hr><h1 color='red'>import / export commands</h1><br>";

echo "... not yet implemented. sry.";

// importMySQL($mysqldumb); // parses the mysqldumb and tries to create a file-based database

// exportMySQL($dbName); // tries to create a MySQL-dumb of the file-based-database

echo "<hr><h1 color='red'>DESTROY TEST DATABASE</h1><br>";

comment("delete database");
success($nodbObj->delDatabase("databaseTest1"));

/*
echo "<hr><h1 color='red'>test file operations command 'ls'</h1><br>";

comment("read parent directory");
print_r_html(ls(".."));
comment("read absolute path");
print_r_html(ls("/var/www"));

comment("read current directory");
print_r_html(ls("."));
comment("0 = SORT_REGULAR - Default. Compare items normally (don't change types)");
$sort = SORT_REGULAR;
print_r_html(ls(".",$sort));

comment("1 = SORT_NUMERIC - Compare items numerically");
$sort = SORT_NUMERIC;
print_r_html(ls(".",$sort));

comment("2 = SORT_STRING - Compare items as strings");
$sort = SORT_STRING;
print_r_html(ls(".",$sort));

comment("3 = SORT_LOCALE_STRING - Compare items as strings, based on current locale");
$sort = SORT_LOCALE_STRING;
print_r_html(ls(".",$sort));

comment(" 4 = SORT_NATURAL - Compare items as strings using natural ordering");
$sort = SORT_NATURAL;
print_r_html(ls(".",$sort));

comment("5 = SORT_FLAG_CASE -");
$sort = SORT_FLAG_CASE;
print_r_html(ls(".",$sort));
*/

/* print an array or variable like print_r would do it but with browser readable <br> instead of \n linebreaks */
function print_r_html($input)
{
	echo str_replace(array("\r\n", "\r","\n"), "<br>", print_r($input,true));
}

/* explain what is being done */
function comment($input)
{
	echo "<h3>".strval($input)."____________________________________________________________</h3><br>";
}
// colorful output about the outcomes of the functions
function success($worked)
{
	if($worked)
	{
		echo "<h3 style='color:green;'>worked</h3><br>";
	}
	else
	{
		echo "<h3 style='color:red;'>failed</h3><br>";
	}
}

// destroy the instance, free the ram
unset($nodbObj); // happens automatically on end of program

?>