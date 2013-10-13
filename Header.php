<?php
//ini_set("display_errors", "1");
//error_reporting(E_ALL);
include("dbconfig.php");
$dbPortal = dbconnect();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link href="css/qpg.css" rel="stylesheet" type="text/css" />
        <script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>
        <script src="js/ajaxupload.3.5.js" type="text/javascript"></script>
        <script src="js/qpg.js" type="text/javascript"></script>
    </head>
    <body>
<?php 
if(!isset($noHtml))
{
?>
        <div class="headerImage">
            <div style="height: 121px;"></div>
        </div>
        <div class="topMenu">
            <ul>
<!--                <li><a href="ClassList.php">Class List</a></li>
                <li><a href="AddQuestion.php">Add Question</a></li>-->
                <li><a href="QuestionPaperList.php">Question Paper List</a></li>
                <li><a href="QuestionTypeList.php">Question Types</a></li>
            </ul>
        </div>
        <div class="mainContainer">
            <div class="leftContainer">
                
            </div>
            <div class="rightContainer">
<?php } ?>