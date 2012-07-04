<?php
require "functions.php"; 

header("content-type: application/json"); 
if(!empty($_REQUEST['time'])){
	removeFromModelsExpired(intval($_REQUEST['time']));	
} else {
	removeFromModelsExpired(666);	
}

?>{ "status": "CLEANED" }