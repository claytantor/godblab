<html>
<?php include ("include/head.php"); ?>
<body>
		<div class="container">
			<div class="span-24 last" style="text-align: center; margin-top: 20px;">
				<img src="images/gb_main.png"/>
			</div>
			<div class="span-24 last" style="text-align: left; margin-top: 20px;">
			<h4>callers</h4>
			<a href="cache.php?delete=callers">delete callers model</a>
			<pre>
<?php
if(!empty( $_REQUEST['delete'])){
	 apc_delete($_REQUEST['delete']);
}


if (apc_exists('callers')) {	
	$callers_cache_item = apc_fetch('callers');
	$callers_cache_item_decoded = json_decode($callers_cache_item);
	print_r($callers_cache_item_decoded);
} else {
	echo 'no callers model';
}
?>	
			</pre>
			
			</div>
			<h4>waiting</h4>
			<a href="cache.php?delete=waiting">delete waiting model</a>
			<pre>
<?php
if (apc_exists('waiting')) {	
	$waiting_cache_item = apc_fetch('waiting');
	$waiting_cache_item_decoded = json_decode($waiting_cache_item);
	print_r($waiting_cache_item_decoded);
} else {
	echo 'no waiting model';
}
?>	
			</pre>
			
			</div>			
		</div>
</body>
</html>
