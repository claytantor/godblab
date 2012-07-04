<?php
header("content-type: application/json");
try {     
	if (apc_exists('callers')) {
		echo apc_fetch('callers');	
		die;
	} else {		
		$gods = array();
		array_push($gods, array('name'=>'Jesus Christ','count'=>0));
		array_push($gods, array('name'=>'Yahweh','count'=>0));
		array_push($gods, array('name'=>'Allah','count'=>0));
		array_push($gods, array('name'=>'Wicca','count'=>0));
		array_push($gods, array('name'=>'Flying Spaghetti Monster','count'=>0));
		array_push($gods, array('name'=>'Unitarian','count'=>0));
		array_push($gods, array('name'=>'Athiest','count'=>0));
		array_push($gods, array('name'=>'Krishna','count'=>0));
		array_push($gods, array('name'=>'Ganesha','count'=>0));
		array_push($gods, array('name'=>'Vishnu','count'=>0));
		array_push($gods, array('name'=>'Satan','count'=>0));
		array_push($gods, array('name'=>'Buddha','count'=>0));
		array_push($gods, array('name'=>'Ancestors','count'=>0));
		array_push($gods, array('name'=>'Trees','count'=>0));
		
		$caller_model = array('total'=>0,'model'=>$gods); 
		$json = json_encode($caller_model);
		apc_store('callers',$json ,86400);	
		echo $json;
		die;
	}  
} catch (Exception $e) {
	syslog(LOG_WARNING, 'error:'.$e->getMessage());
} 

?>
