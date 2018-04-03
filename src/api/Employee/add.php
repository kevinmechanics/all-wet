<?php
/**
 * All Wet
 * 2018
 * 
 * API
 * Employee
 * add
 */

require_once("../../_system/keys.php");
require_once("../_secure.php");
require_once("../_boot.php");

$obj = new AllWet\Employee($mysqli);

function throwError($msg){
	if(empty($msg)) $msg = "An error happened";
	$error = array(
		"code"=>"500",
		"message"=>$msg
	);
	die(json_encode($error));
}


if(empty($_REQUEST['employee_name'])) throwError("Empty name");
if(empty($_REQUEST['employee_username'])) throwError("Empty username");
if(empty($_REQUEST['employee_password'])) throwError("Empty password");
if(empty($_REQUEST['employee_image'])) throwError("Empty image");


$employee_name = $_REQUEST['employee_name'];
$employee_username = $_REQUEST['employee_username'];
$employee_password = $_REQUEST['employee_password'];
$employee_image = $_REQUEST['employee_image'];

$array = array(

	"employee_name" => $employee_name,
	"employee_username" => $employee_username,
	"employee_password" => $employee_password,
	"employee_image" => $employee_image
);

$result = $obj->add($array);

if($result){
	$res = array(
		"code" => "200",
		"message" => "Successfully Added"
	);
} else {
	$res = array(
		"code" => "400",
		"message" => "Fail to add"
	);
}

echo(json_encode($res));

?>