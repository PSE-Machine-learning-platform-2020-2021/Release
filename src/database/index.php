<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
ob_start();
session_start();

include("databaseConnection.php");

# Stop idle Script calls.
if(!isset($_GET["action"])) {
    throw new InvalidArgumentException("Job not specified!");
}

$db = new DataBaseConnection();

# Ensure that always a good value in $_POST
if(($result = json_decode(file_get_contents("php://input"), true)) !== null) {
	$_POST = $result;
}
else if($_POST === null or !is_array($_POST)) {
	$_POST = [];
}

# Ensure that only valid tasks are executed
switch($_GET["action"]) {
    case "get_language_metas":
	    eval("\$db->${_GET["action"]}();");
        break;
    case "load_language":
	case "create_project":
	case "create_data_set":
	case "send_data_point":
	case "load_project":
	case "get_project_metas":
	case "delete_data_set":
	case "register_admin":
	case "register_dataminer":
	case "login_admin":
	case "create_label":
	case "set_label":
	case "delete_label":
	case "send_data_points_again":
		if(count($_POST) === 0) {
			http_response_code(406);
			throw new BadMethodCallException("No parameters passed. This is illegal. Intelligence Agency is informed.");
		}
		eval("\$db->${_GET["action"]}(\$_POST);");
		break;
	default:
		http_response_code(501);
        throw new BadMethodCallException("This method name is illegal. Intelligence Agency is informed.");
}
ob_end_flush();
ob_flush();
flush();
?>
