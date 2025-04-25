<?php
require_once("dbcontroller.php");
$db_handle = new DBController();
if( strlen($_POST["editval"]) > 255){
    $_POST["editval"] = substr($_POST["editval"],0,255);
}
$result = $db_handle->executeUpdate("UPDATE character_building set " . $_POST["column"] . " = '".$_POST["editval"]."' WHERE  id=".$_POST["id"]);
echo $result;
?>
