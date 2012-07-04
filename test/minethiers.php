<?php
/*
 * Created on Jul 1, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
//get the submit model
$caller_a= preg_replace("/[^0-9]/","", $_REQUEST['caller_a']);
$mine = 'Jesus Christ';
$before = 'Flying Speghetti Monster (12)';
$pos = strrpos($before, "(")-1;
$theirs = substr($before, 0, $pos);
echo $mine. ' & ' . $theirs;

echo '\n\n';

//too fancy	 
//       		event.preventDefault();    	       	
//       		      		
//       		var error = "";
//       		var stripped = $(this).val()+(event.charCode-48);
//		    var stripped = stripped.replace(/[\(\)\.\-\ ]/g, '');     		
//       		
//       		if(isNaN(parseInt(stripped)) || !(stripped.length == 10)){
//       			$('#caller_a').removeClass('phone_field_begin');
//       			$('#caller_a').css('color','#B8A632');
//       			$('#caller_a').css('border','1px solid #B8A632');
//       			$('#caller_a').css('background-color','#F2ECC7');
//       		} else {
//       			$('#caller_a').css('color','#389C17');
//       			$('#caller_a').css('border','1px solid #389C17');
//       			$('#caller_a').css('background-color','#D2F2C7');
//       			$('#caller_verify_action').show('slow');
//      
//       		}
//       		
//       		var newnum  = stripped;
//       		if(stripped.length > 3 && stripped.length <= 6) {
//       			newnum  = '(' + stripped.substring(0,3) + ') '+stripped.substring(3,stripped.length);
//       		} else if(stripped.length > 6 && stripped.length < 10) {
//       			newnum  = '(' + stripped.substring(0,3) + ') ' + stripped.substring(3,6) + '-' + stripped.substring(6,stripped.length);
//       		} else if(stripped.length == 10) {
//       			newnum  = '(' + stripped.substring(0,3) + ') ' + stripped.substring(3,6) + '-' + stripped.substring(6,10);
//       		}
//       		
//       		$(this).val(newnum);

?>
