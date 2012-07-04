<html>
<?php include ("include/head.php"); ?>
<script type="text/javascript">

//<![CDATA[


$(document).ready(function() {

	var prayers = {};
	var gods = [];	
	var godsCount = [];	
	
	$('input').val('');
		
	//who are the zealots?
	$.ajax({
		    type: 'GET',
		    url: 'callers.php',
		    dataType: "json",
		    success: function(data) { 
		    	
		    	
		    	$(data.model).each(function(index,item) {
				    gods.push(item.name);
				    godsCount.push(item.name+' ('+item.count+')');
				}); 
				
						
				$( "#my_god" ).autocomplete({
						source: gods,
						select: function(event, ui) { 
							var mine_found = false;
							$(gods).each(function(index,item) {
								if(item==$('#my_god').val()){
									mine_found=true;
								}
							});
							
							var theirs_found = false;
							$(godsCount).each(function(index,item) {
								if(item==$('#their_god').val()){
									theirs_found=true;
								}
							});
							
							if(mine_found && theirs_found) {
								$('#connect_zealots_action').show('slow');
							}
							
						 }
				});
					
				$( "#their_god" ).autocomplete({
						source: godsCount,
						select: function(event, ui) { 
								var mine_found = false;
								$(gods).each(function(index,item) {
									if(item==$('#my_god').val()){
										mine_found=true;
									}
								});
								
								var theirs_found = false;
								$(godsCount).each(function(index,item) {
									if(item==$('#their_god').val()){
										theirs_found=true;
									}
								});
								
								if(mine_found && theirs_found) {
									$('#connect_zealots_action').show('slow');								
								}
								
							 }
				});
    	
		    }
	});
	

	
	//powerful mojo
	$('#connect_zealots_action').click(function(){
		$(this).attr('href','');
		$('#god_connect').hide();
		$('#verify_caller').show('slow');
		prayers.mine = $('#my_god').val(); 
		prayers.theirs = $('#their_god').val(); 				
		return false;		
	});
	
	//the mana from heaven
	$('#caller_a').keypress(function(event){ 
			if(event.charCode>47 && event.charCode<57){
				var stripped = $(this).val()+(event.charCode-48);
		    	var stripped = stripped.replace(/[\(\)\.\-\ ]/g, '');
		    	if(isNaN(parseInt(stripped)) || !(stripped.length == 10)){
	       			$('#caller_a').removeClass('phone_field_begin');
	       			$('#caller_a').css('color','#B8A632');
	       			$('#caller_a').css('border','1px solid #B8A632');
	       			$('#caller_a').css('background-color','#F2ECC7');
	       			$('#caller_verify_action').hide();
	       		} else {
	       			$('#caller_a').css('color','#389C17');
	       			$('#caller_a').css('border','1px solid #389C17');
	       			$('#caller_a').css('background-color','#D2F2C7');
	       			$('#caller_verify_action').show('slow');	      
	       		}			    				
			} else {
				$('#caller_a').removeClass('phone_field_begin');
	       		$('#caller_a').css('color','#B8A632');
	       		$('#caller_a').css('border','1px solid #B8A632');
	       		$('#caller_a').css('background-color','#F2ECC7');
	       		$('#caller_verify_action').hide();
			}
			 
	
			

       		
       		
       })
       .focus(function(){
       		if($('#caller_a').val() == '(555) 567-8910'){
       			$('#caller_a').val('');
       		}   
       		
       					    				
       });
	
	//testify and be judged
	$('#caller_verify_action').click(function(){
	
		var stripped = $('#caller_a').val();
    	var stripped = stripped.replace(/[\(\)\.\-\ ]/g, '');
    	if(isNaN(parseInt(stripped)) || !(stripped.length == 10)){
   			$('#caller_a').css('color','#B8A632');
   			$('#caller_a').css('border','1px solid #B8A632');
   			$('#caller_a').css('background-color','#F2ECC7');
   			$('#caller_verify_action').hide();
   		} else {
   		  		
	   		//post the prayers		
			prayers.caller_a = '1'+$('#caller_a').val(); 
			
			$.ajax({
			    type: 'POST',
			    url: 'verify.php',
			    data: prayers,
			    dataType: "json",
			    success: function(data) { 
			    	$('#call_status_area').show(); 
			    	$('#poll_status').html(data.status);
					$('#verify_caller').hide();
			    		    			    		    	
			    	if(data.status == 'INITIATED'){
			    		$('#poll_status').html(data.status+' '+data.mine+' & '+data.theirs);
			   										
						//start polling
						setInterval(function(){
						    $.ajax({ 
						    	dataType: "json",
						    	cached: false,
						    	url: 'readypoll.php',
						    	type: 'POST',
						    	data: prayers,
						    	success: function(datatoo){					    		
						        	if(datatoo.mine){
						        		$('#poll_status').html(datatoo.status+' '+datatoo.mine+' & '+datatoo.theirs);
						        	} else {
						        		$('#poll_status').html(datatoo.status);
						        	}
						        						        	
						        	if(datatoo.status=='VERIFIED'){
						        		//$('#make_call').show('slow');
						        		$('#caller_a_call').val($('#caller_a').val());					        							        		
						        	}
						    	}
						    });
							}, 5000);
			    		
			    	} 		 	    
			 	    		 	    
			    }
			});
   		
   		
   				      
   		}		
		
		
		return false; 
		
	});	
	
	$('#caller_call_action').click(function(){	
		$.ajax({
		    type: 'POST',
		    url: 'makecall.php',
		    data: $("#callForm").serialize(),
		    dataType: "json",
		    success: function(data) {  
		    	  
		 	    		 	    
		    }
		});
		return false; 
		
	});
	

	
});

//]]>
</script>
<body>
		<div class="container">
				

			<div class="span-24 last" style="text-align: center; margin-top: 20px;">
				<img src="images/gb_main.png"/>
				<h3>Yea, verily thou rightous discourse on "The One" shall commence henceforth!</h3>
				<?php if(!empty($_GET['msg'])):?>
				<p>Message: <?php echo $_GET['msg'] ?></p>
				<?php endif;?>
				<div id="call_status_area" style="display:none">
					<div class="explain">You must stay on this webpage to recieve your call.</div>
					<div id="poll_status"></div>
				</div>
				
				
				<div class="span-24 last" id="god_connect">
					<div class="explain">You are commencing on a spiritual journey by discourse on the telephone. We will match you based on the type of discussion you would like to have. If you choose a God with no online callers you will have to wait until one arrives.</div>
					<div class="span-10" style="text-align: right;">
						<div class="god_title">My God Is</div>
						<input type="text" class="text_field god_fields" name="my_god" style="text-align: right;" id="my_god" />
					</div>
					<div class="span-3" style="text-align: center;">
						<div style="margin-top: 10px;">let me talk to a Zealot</div>
						<a href="nojavascript.php" id="connect_zealots_action" class="gb_button" style="margin-top: 10px; display:none">Connect</a>
					</div>
					
					<div class="span-10" style="text-align: left;">
						<div class="god_title">Who Believes In&nbsp;<span style="color:#aaa; font-size:0.6em;">(# online)</span> </div>
						<input type="text" class="text_field god_fields" name="their_god" style="text-align: left;" id="their_god"/>
					</div>				
				</div>
				
				
				<div style="display:none;" id="verify_caller">
					<h3>verify your number</h3>
					<div class="explain">Please enter your phone number as numbers only, we will send you a text message that you will reply to with "GODBLAB". <span style="color:#666">YOUR CONVERSATION IS RECORDED</span>, and we may choose to share it so be discreet! We NEVER share your number with ANYONE, the other party will not see your number, and we will cast it from the kingdom once your call is over. USA 10 digit numbers only.</div>
					<form id="verifyForm" method="post">
					    <span><input type="text" name="caller_a" id="caller_a" class="phone_field phone_field_begin" value="(555) 567-8910"/></span>
					    <a href="nojavascript.php" id="caller_verify_action" class="gb_button" style="display:none; margin-right:5px;"/>Verify!</a>
					</form>
				</div>
				
				<div id="make_call" style="display:none">
					<h3>make the call</h3>
					<form method="post" id="callForm">
					    <span style="display:none"><input type="hidden" name="caller_a" id="caller_a_call"/></span>
					    <span>The Number To Call: <input type="text" name="caller_b"/></span>
					    <a href="nojavascript.php" id="caller_call_action" class="gb_button" />Make Call!</a>
					</form>
				</div>
			</div>
		</div>


</body>
</html>