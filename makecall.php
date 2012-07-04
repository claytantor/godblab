<?php
header("content-type: application/json");

if (!isset($_REQUEST['caller_a']) || !isset($_REQUEST['caller_b'])) {
    $err = urlencode("Must specify both parties phone number.");
    //header("Location: index.php?msg=$err");
   	echo '{ "status" :"FAILED", "message":"Must specify both parties phone number."}';
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



try {
	// Initiate a new outbound call
	$caller_a = preg_replace("/[^0-9]/","", $_REQUEST['caller_a']);
	$caller_b = preg_replace("/[^0-9]/","", $_REQUEST['caller_b']);
	
	//update caller model
    if (apc_exists($caller_a)) {
		$caller_cache_item = apc_fetch($caller_a);
		$caller_cache_item_decoded = json_decode($caller_cache_item);
		$ci_new = array('status'=>'CALLING','caller'=>$caller_a);     		
		apc_store($stripped,json_encode($ci_new) ,666);	
	} else {
		syslog(LOG_WARNING, 'not found:'.$caller_a);
	}  
	 
	$call = $client->account->calls->create(
		$from, // The number of the phone initiating the call
		$caller_a, // The number of the phone receiving call
		'http://ec2-107-20-128-208.compute-1.amazonaws.com/godblab/callback.php?number=' . $caller_b // The URL Twilio will request when the call is answered
	);
	//echo 'Started call: ' . $call->sid;
	/* redirect back to the main page with CallSid */
	$msg = urlencode("Connecting... ".$call->sid);
	echo '{ "status" :"CONNECTED", "message":"'.$msg.'"}';
	die;

} catch (Exception $e) {
	$err = urlencode("Error:".$e->getMessage());
    echo '{ "status" :"FAILED", "message B":"'.$err.'"}';
	die;
} 


?>

