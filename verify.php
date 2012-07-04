<?php
header("content-type: application/json");

if (!isset($_REQUEST['caller_a'])) {
    $err = urlencode("Must specify a number for verfication.");
    $result = array('status'=>'ERROR','caller'=>'none'); 
	echo json_encode($result);
    die;
}

require "include/Services/Twilio.php"; 
 
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
$fromSMS = "14156897463";

//get the submit model
$caller_a= preg_replace("/[^0-9]/","", $_REQUEST['caller_a']);
$mine = $_REQUEST['mine'];
$pos = strrpos($_REQUEST['theirs'], "(")-1;
$theirs = substr($_REQUEST['theirs'], 0, $pos);

try {
				
//    $people = array(
//        $caller_a
//    );
 
    // Step 5: Loop over all our friends. $number is a phone number above, and 
    // $name is the name next to it
    //foreach ($people as $number => $name) {
 
        $sms = $client->account->sms_messages->create(
            $fromSMS, 
            $caller_a,
            "This number has been requested to start a GodBlab discussion, please reply GODBLAB to be matched with a caller."
        );
        
        //now put the status in the cache
        $caller_cache_item = '';
        
        $ci_array = array('status'=>'INITIATED','caller'=>$caller_a, 'mine'=>$mine, 'theirs'=>$theirs);   
    	apc_store($caller_a, json_encode($ci_array) ,666);
        	
    	if (apc_exists($caller_a)) {
    		$caller_cache_item = apc_fetch($caller_a);
    		echo $caller_cache_item;
			die; 
    	} else {
    		$ci_array = array('status'=>'INITIATED','caller'=>$caller_a, 'mine'=>$mine, 'theirs'=>$theirs);   
    		apc_store($caller_a, json_encode($ci_array) ,666);
    		echo json_encode($ci_array);
			die; 
    	}
    	
	
    //}
	
} catch (Exception $e) {
	$err = urlencode("Error:".$e->getMessage());
	$result = array('status'=>'ERROR','caller'=>$caller_a); 
	echo json_encode($result);
} 


?>

