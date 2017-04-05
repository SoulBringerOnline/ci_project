<?php
    require_once('includes/init.php');
    require_once($_CFG['DIR_HELPER'].'helper_info.php'); 

    $log = Logger::instance(  'log/qrcode' , Logger::DEBUG);

    //获取参数
    $type = $_REQUEST['type'];
    $id = $_REQUEST['id'];
    $app = $_REQUEST['a'];
    $os = $_REQUEST['os'];

    $log->logInfo(client_ip() , $_REQUEST);

    //配置
    $errno = 1;
    $scheme_host = array('1' => 'user', '2' => 'group' , '3' => 'project' );
    $scheme = get_dl_page( array( 'c' => 10 ) );
    if( !empty($type) && !empty($id) && !empty($scheme_host[$type]) )
    {
        $errno = 0;
        $scheme = 'zhuyou://' . $scheme_host[$type] . '?id=' . $id . '&op=get';
    }
    
    //生成逻辑
    if( $app == 'zhuyou' )
    {
        header('Content-Type: application/json');
        echo json_encode( array('code' => $errno , 'scheme' => $scheme ) );
    }
    else
    {
        header('Content-Type: text/html');
?>
<!DOCTYPE html>
<html>
<head>
<title>筑友</title>
<meta name="content-type" content="text/html; charset=UTF-8">
</head>
<body>
<iframe id="ifr" src="<?php echo $scheme; ?>" style="display:none"></iframe>
</body>
</html>

<script>
    function testApp() {
        var timeout, t = 1000, hasApp = true;
        setTimeout(
                function() {
                    if (!hasApp) {
                        window.location.href = "<?php echo $scheme; ?>";
                    }
                }, 2000);

        var t1 = Date.now();
        timeout = setTimeout(function() {
            var t2 = Date.now();
            if (!t1 || t2 - t1 < t + 100) {
                hasApp = false;
            }
        }, t);
    }
    testApp();
</script>

<?php
}
?>
