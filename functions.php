<?php
/*
 * Created on Jul 2, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
function removeCallerFromModels($caller_number, $status){
		$god_name='';
		if(apc_exists($caller_number)){
			$caller_status = json_decode(apc_fetch($caller_number));
			$god_name = $caller_status->mine;		
		}
		
		syslog(LOG_WARNING, 'REMOVE caller from waiting in god queue '.$god_name);	
	
		//delete from models
		if (apc_exists('callers') && apc_exists($caller_number)) {
			$caller_model = json_decode(apc_fetch('callers'));	
			$gods = array();
			$total =  $caller_model->total;
			foreach ( $caller_model->model as $god ) {	
				//syslog(LOG_WARNING, 'god in calling model:'.print_r($god,true));			
				if($god->name==$god_name){
					$total = $total-1;
					array_push($gods, array('name'=>$god->name,'count'=>$god->count-1));						
				} else {
					array_push($gods, array('name'=>$god->name,'count'=>$god->count));
				}											
			}	
			
			$pcaller_model = array('total'=>$total,'model'=>$gods); 
			$json = json_encode($pcaller_model);						
			apc_store('callers',$json ,86400);
		
		}
		
		//waiting model
		if (apc_exists('waiting') && apc_exists($caller_number)) {
			$waiting_model = json_decode(apc_fetch('waiting'));
			$gods=array();
			foreach ( $waiting_model->model as $god ) {				
				if($god->name==$god_name && count($god->numbers)>0){
					$new_numbers = array();
					foreach ( $god->numbers as $number ) {
						//remove expired numbers
						$parts = explode(",",$number);					
						if($parts[0] != $caller_number){
							array_push($new_numbers,$parts[0]);
						} 						
					}
					array_push($gods, array('name'=>$god->name,'numbers'=>$new_numbers));
	
				} else {
					array_push($gods, array('name'=>$god->name,'numbers'=>$god->numbers));
				}
																
			}			
			$new_waiting_model = array('model'=>$gods); 
			$json = json_encode($new_waiting_model);
			apc_store('waiting',$json ,86400);			
		}	
		//apc_delete($caller_number);
		$ci_new_a = array('status'=>$status,'caller'=>$caller_number, 'mine'=>$caller_cache_item_decoded->mine, 'theirs'=>$caller_cache_item_decoded->theirs);    		
		
		//will expire in 30 seconds
		apc_store($caller_number,json_encode($ci_new_a) ,30);
		
}


function removeFromModelsExpired($time_max=666){	
		//waiting model
		if (apc_exists('waiting')) {
			$waiting_model = json_decode(apc_fetch('waiting'));
			$gods=array();
			foreach ( $waiting_model->model as $god ) {				
				if(count($god->numbers)>0){
					$new_numbers = array();
					foreach ( $god->numbers as $number ) {
						//remove expired numbers
						$parts = explode(",",$number);
						$time_start = intval($parts[1]);
						
						$time_elapsed = time() - $time_start;
						syslog(LOG_WARNING, 'elapsed:'.$time_elapsed.' test:'.$time_max);	
										
						if($time_elapsed < $time_max){
							array_push($new_numbers,$number);
						} else {
							removeCallerFromModels($parts[0], 'EXPIRED');
						}											
					}
					array_push($gods, array('name'=>$god->name,'numbers'=>$new_numbers));
	
				} else {
					array_push($gods, array('name'=>$god->name,'numbers'=>$god->numbers));
				}
																
			}			
			$new_waiting_model = array('model'=>$gods); 
			$json = json_encode($new_waiting_model);
			apc_store('waiting',$json ,86400);			
		}	

		
}


function makeCall($caller_a, $caller_b){
	 
	/* Set our AccountSid and AuthToken */
	$AccountSid = "AC740d77bcb8d591a9557eb2fe977ba546";
	$AuthToken = "1ddd97300bc0fb94502107cc574bad25";
	 
	/* Your Twilio Number or an Outgoing Caller ID you have previously validated
	    with Twilio */
	$from= '18554638862';
	 	 
	/* Directory location for callback.php file (for use in REST URL)*/
	$url = 'http://ec2-107-20-128-208.compute-1.amazonaws.com/godblab/';
	 
	/* Instantiate a new Twilio Rest Client */
	$client = new Services_Twilio($AccountSid, $AuthToken);
	
	
	try {

		//update caller model
	    if (apc_exists($caller_a)) {
			$caller_cache_item = apc_fetch($caller_a);
			$caller_cache_item_decoded = json_decode($caller_cache_item);
			
			$ci_new_a = array('status'=>'MATCHED','caller'=>$caller_a, 'mine'=>$caller_cache_item_decoded->mine, 'theirs'=>$caller_cache_item_decoded->theirs);    		
			apc_store($caller_a,json_encode($ci_new_a) ,666);	
			
			$ci_new_b = array('status'=>'MATCHED','caller'=>$caller_b, 'mine'=>$caller_cache_item_decoded->theirs, 'theirs'=>$caller_cache_item_decoded->mine);
			apc_store($caller_b,json_encode($ci_new_b) ,666);
			
			
			syslog(LOG_WARNING, 'MATCHED:'.$caller_a.' '.uniqid());
			
			$call = $client->account->calls->create(
				$from, // The number of the phone initiating the call
				$caller_a, // The number of the phone receiving call
				'http://ec2-107-20-128-208.compute-1.amazonaws.com/godblab/callback.php?number=' . $caller_b // The URL Twilio will request when the call is answered
				);

			//syslog(LOG_WARNING, 'CALLER:'.$caller_a.' IS CALLING '..' '.uniqid());
			
			removeCallerFromModels($caller_a, 'IN PROGRESS');
			removeCallerFromModels($caller_b, 'IN PROGRESS'); 
			
				
						
		} else {
			syslog(LOG_WARNING, 'not found:'.$caller_a);
		}  
		 		
		die;
	
	} catch (Exception $e) {
		$err = urlencode("Error:".$e->getMessage());
	    echo '{ "status" :"FAILED", "message":"'.$err.'"}';
		die;
	} 	
} 
?>
