<?php

if (!class_exists('Env')) {
    
    class Env {   	
    	const ENV = 'DEV';
    }
        
    global $env;
	$env = new Env();	
}
?>