<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<table class="table">
<?php

ini_set('display_errors', 'off');
ini_set('error_reporting', E_ALL&~E_NOTICE);
ini_set('html_errors', 'off');

$connection = new MongoClient('mongodb://10.128.6.61:20000');
$mongo_op = new MongoDB($connection, 'gsk_ol');

$q = $_GET['q'];
$total = 0;
if( !empty( $q ) )
{
    $where['f_company'] = new MongoRegex('/' .  $q . '/');
    $rsp = $mongo_op->user->find($where, array('f_name' => 1, 'f_phone' => 1, 'f_company' => 1 , 'f_create_time' => 1) )->sort(array( 'f_create_time' => -1 ));
    foreach( $rsp as $user )
    {
        $total += 1;
        echo '<tr>';
        echo '<td>' . $total . '</td>';
        echo '<td>' . $user['f_name'] . '</td>';
        echo '<td>' . $user['f_phone'] . '</td>';
        echo '<td>' . $user['f_company'] . '</td>';
        echo '<td>' . date("Y-m-d H:i", $user['f_create_time'] ) . '</td>';
        echo '</tr>';
    }
}
echo '<h1>(' . $q . ')总共:' . $total . '人</h1><br>';
$connection->close();
?>

</table>
    </body>
    </html>
