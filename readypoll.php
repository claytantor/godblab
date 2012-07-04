<?php
require "include/Services/Twilio.php"; 
require "functions.php"; 

header("content-type: application/json");
$caller_a = preg_replace("/[^0-9]/","", $_REQUEST['caller_a']); 
$mine = $_REQUEST['mine'];
$before = $_REQUEST['theirs'];
$pos = strrpos($before, "(")-1;
$theirs = substr($before, 0, $pos);


try {     
	if (apc_exists($caller_a)) {
		$caller_model = json_decode(apc_fetch($caller_a));
			
		//now get the waiting model
		if ($caller_model->status=='VERIFIED' && apc_exists('waiting') ) {
			$waiting_model = json_decode(apc_fetch('waiting'));
			
			$gods=array();
			foreach ( $waiting_model->model as $god ) {				
				if($god->name==$theirs && count($god->numbers)>0){
					$caller_b = array_pop($god->numbers);						
					//start the call
					if(isset($caller_a) && isset($caller_b)){
						syslog(LOG_WARNING, 'MAKE CALL:'.print_r( $caller_a,true).' & '.print_r( $caller_b,true));
						$parts = explode(",",$caller_b);
						makeCall($caller_a, $parts[0]);
					}		
				}												
			}
		} 
					
		echo apc_fetch($caller_a);	
		die;
		
		
	} else {		
		removeCallerFromModels($caller_a, 'NO MATCH FOUND');  
		echo json_encode($ci_array);
		die;
	}  
} catch (Exception $e) {
	$ci_array = array('status'=>'ERROR:'.$e->getMessage(),'caller'=>$caller_a);       		
	echo json_encode($ci_array);
	die;
}





?>
