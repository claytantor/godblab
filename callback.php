<?php
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Say>We are now connecting you to your Godblab discussion partner.</Say>
    <Say>Please have faith that you will be connected soon.</Say>
    <Dial record="true"><?php echo $_REQUEST['number']?></Dial>
</Response>