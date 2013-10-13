<?php
/*
#**************************************************************#
# #### © OYSTOR (Your World, Simplified.) :: ADMIN PORTAL #### #
#**************************************************************#
*/
/*
    Document    : dbconfig
    Created on  : 22 Feb, 2011
    Author      : Manoharan
    Description : db configuration page
*/

//define("PORTALDB", "qpg");
define("PORTALDB", "moodle_qpg");
define("DBHOST","localhost");
define("DBUSER", "root");
define("DBPASS", "");

include("lib/ADODB/adodb-exceptions.inc.php");
include("lib/ADODB/adodb.inc.php");

function dbconnect()
{
    $dbPortal = NewADOConnection('mysql');
    try {
    $saveErrHandlers = $dbPortal->IgnoreErrors();
        $dbPortal->Connect(DBHOST, DBUSER, DBPASS,PORTALDB);
    } catch (exception $e) {
           var_dump($e); 
           adodb_backtrace($e->gettrace());
  }
    return $dbPortal;
}

function Execute($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->Execute($sql);
        return true;
    } catch (exception $e) {
        $func->errorLog('function - Execute, dbConnection - Portal',$e);
    }
}

function Affected_Rows()
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->Affected_Rows();
        return $result;
    } catch (exception $e) {
        $func->errorLog('function - Affected_Rows, dbConnection - Portal',$e);
        return false;
    }
}
function InsertExecute($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->Execute($sql);
        return $dbPortal->Insert_ID();
    } catch (exception $e) {
        $func->errorLog('function - InsertExecute, dbConnection - Portal',$e);
        return false;
    }
}
function CacheExecute($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->CacheExecute($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - CacheExecute, dbConnection - Portal',$e);
    }
}

function GetAll($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->GetAll($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - GetAll, dbConnection - Portal',$e);
    }
}

function CacheGetAll($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->CacheGetAll($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - CacheGetAll, dbConnection - Portal',$e);
    }
}

function GetRow($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->GetRow($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - GetRow, dbConnection - Portal',$e);
    }
}

function CacheGetRow($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->CacheGetRow($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - CacheGetRow, dbConnection - Portal',$e);
    }
}

function GetArray($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->GetArray($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - GetArray, dbConnection - Portal',$e);
    }
}

function CacheGetArray($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->CacheGetArray($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - CacheGetArray, dbConnection - Portal',$e);
    }
}

function GetOne($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->GetOne($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - GetOne, dbConnection - Portal',$e);
    }
}

function CacheGetOne($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->CacheGetOne($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - CacheGetOne, dbConnection - Portal',$e);
    }
}

function GetCol($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->GetCol($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - GetCol, dbConnection - Portal',$e);
    }
}

function CacheGetCol($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->CacheGetCol($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - CacheGetCol, dbConnection - Portal',$e);
    }
}

function GetAssoc($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->GetAssoc($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - GetAssoc, dbConnection - Portal',$e);
    }
}

function CacheGetAssoc($sql)
{
    global $dbPortal,$func;
    try
    {
        $result = $dbPortal->CacheGetAssoc($sql);
        return $result;
    } catch (exception $e) {
    $func->errorLog('function - CacheGetAssoc, dbConnection - Portal',$e);
    }
}
?>