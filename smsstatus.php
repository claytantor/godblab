<?php
require "functions.php"; 

header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$body = $_POST['Body'];
if(preg_match("/godblab/i", $body)){
//now put the status in the cache
        $caller_cache_item = '';
        $stripped = preg_replace("/[^0-9]/","", $_POST['From']);  

        try {         	
        	$mine ='';
        	$theirs='';
        	$status='';
        	
        	//set the caller model   
	    	if (apc_exists($stripped)) {
	    		$caller_cache_item = apc_fetch($stripped);
	    		$caller_cache_item_decoded = json_decode($caller_cache_item);	   	
	    		$mine = $caller_cache_item_decoded->mine;
	    		$theirs = $caller_cache_item_decoded->theirs;
	    		$status = $caller_cache_item_decoded->status;
	    		if($status=='INITIATED'){
	    			$ci_new = array('status'=>'VERIFIED','caller'=>$stripped, 'mine'=>$caller_cache_item_decoded->mine, 'theirs'=>$caller_cache_item_decoded->theirs);  
	    			apc_store($stripped,json_encode($ci_new) ,666);
	    			
	    			
	    			//get the caller model
			    	if (apc_exists('callers')) {
						$caller_model = json_decode(apc_fetch('callers'));	
						$gods = array();
						$total =  $caller_model->total;
						foreach ( $caller_model->model as $god ) {	
							//syslog(LOG_WARNING, 'god in calling model:'.print_r($god,true));			
							if($god->name==$mine){
								$total = $total+1;
								array_push($gods, array('name'=>$god->name,'count'=>$god->count+1));						
							} else {
								array_push($gods, array('name'=>$god->name,'count'=>$god->count));
							}											
						}	
						
						$pcaller_model = array('total'=>$total,'model'=>$gods); 
						$json = json_encode($pcaller_model);						
						apc_store('callers',$json ,86400);
													
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
						
						$total = 1;
						foreach ( $gods as $god ) {				
							if($god['name']==$mine){
								$god['count'] = 1;
							}											
						}
						
						$pcaller_model = array('total'=>$total,'model'=>$gods); 
						$json = json_encode($pcaller_model);
						apc_store('callers',$json ,86400);	
						
									
					}
									
					//now get the waiting model
					if (apc_exists('waiting')) {
						$waiting_model = json_decode(apc_fetch('waiting'));						
						$gods = array();
						foreach ( $waiting_model->model as $god ) {				
							if($god->name==$mine){
								$numbers = $god->numbers;
								array_push($numbers,$stripped.','.time());
								array_push($gods, array('name'=>$god->name,'numbers'=>$numbers));						
							} else {
								array_push($gods, array('name'=>$god->name,'numbers'=>$god->numbers));
							}											
						}	
						
						$new_waiting_model = array('model'=>$gods); 
						$json = json_encode($new_waiting_model);
						apc_store('waiting',$json ,86400);
											
					} else {
						$gods = array();
						$numbers = array();
						$names = array();
						array_push($names,
							'Jesus Christ',
							'Yahweh',
							'Allah',
							'Wicca',
							'Flying Spaghetti Monster',
							'Unitarian',
							'Athiest',
							'Krishna',
							'Ganesha',
							'Vishnu',
							'Satan',
							'Buddha',
							'Ancestors',
							'Trees'	
						);
						
						foreach ( $names as $name ) {	
							if($name==$mine){
								$nnums = array();
								array_push($nnums,$stripped.','.time());
								array_push($gods, array('name'=>$name,'numbers'=>$nnums));								
							} else {
								array_push($gods, array('name'=>$name,'numbers'=>$numbers));
							}
						}						
						
						$new_waiting_model = array('model'=>$gods); 
						$json = json_encode($new_waiting_model);
						apc_store('waiting',$json ,86400);	
										
					}	    			
	    			
	    		}	
	
	    		    			
	    	} else {
	    		syslog(LOG_WARNING, 'not found:'.$stripped);
	    	}  
	    	 	
	    	
    	} catch (Exception $e) {
			syslog(LOG_WARNING, 'error:'.$e->getMessage());
		}   			
}	    
?>
<Response>
    <Sms>Thanks for having Faith in Godblab. Please be patient while we try to match you.</Sms>
</Response>
