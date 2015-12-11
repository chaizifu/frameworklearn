<?php defined('APPNAME') OR exit('No direct script access allowed');?>
<html>
<head>
    <title>DEBUG</title>
    <style type="text/css">
        *{margin: 0px;padding: 0px;}
        body{margin: 20px;}
        #debug{width: 880px;border: 1px solid #dcdcdc;margin-top: 20px;padding: 10px;}
        fieldset{padding: 10px;font-size: 14px;}
        legend{padding: 5px;}
        p{background-color: #666;font-size: 12px;color: #FFF;margin-top: 10px;padding: 3px;}
    </style>
</head>
<body>
    <div id="debug">
        <h2>DEBUG</h2>
        <?php if(isset($e['message'])){?>
        <fieldset>
            <legend>ERROR</legend>
            <?php echo $e['message']?>
        </fieldset>
        <?php }?>
        <?php if(isset($e['info'])){?>
        <fieldset>
            <legend>TRACE</legend>
            <?php echo $e['info']?>
        </fieldset>
        <?php }?>
    </div>
</body>
</html>

