<?php
/*
 * Created on Jul 1, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$gods = array();
array_push($gods, array('name'=>'Jesus Christ','count'=>0));
array_push($gods, array('name'=>'Yahweh','count'=>0));
array_push($gods, array('name'=>'Allah','count'=>0));
array_push($gods, array('name'=>'Flying Spaghetti Monster','count'=>0));
array_push($gods, array('name'=>'Unitarian','count'=>0));
array_push($gods, array('name'=>'Athiest','count'=>0));
array_push($gods, array('name'=>'Krishna','count'=>0));
array_push($gods, array('name'=>'Satan','count'=>0));
array_push($gods, array('name'=>'Buddha','count'=>0));
array_push($gods, array('name'=>'Ancestors','count'=>0));



$caller_model = array('total'=>0,'model'=>$gods); 
$json = json_encode($caller_model);
echo $json;
echo '\n\n';
?>
