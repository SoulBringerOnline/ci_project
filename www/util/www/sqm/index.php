<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL&~E_NOTICE);
ini_set('html_errors', 'on');
date_default_timezone_set('Asia/Shanghai');
require_once dirname(__FILE__) . '/cmd.php';
$day = intval( (time() + 28800) / 86400 );
if( isset( $_GET['datetimepicker'] ) && !empty( $_GET['datetimepicker'] ) )
{
    list( $y, $m , $d ) = explode("-", $_GET['datetimepicker']);
    $day = intval( (mktime(0,0,0,$m,$d,$y) + 28800) / 86400 );
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>监控</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
        <link href="//cdn.bootcss.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="asset/jquery.datetimepicker.css"/ >
        <script src="asset/jquery.min.js"></script>
        <script src="asset/jquery.flot.cust.js"></script>
        <script src="asset/jquery.flot.tooltip.js"></script>
        <script src="asset/jquery.flot.resize.js"></script>
        <script src="asset/jquery.datetimepicker.js"></script>
        <script src="asset/app.js"></script>
        <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    </head>
    <body>
    <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">监控</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
          <form class="navbar-form navbar-left" id="date_picker">
            <div class="form-group">
              <input type="text" class="form-control" name="datetimepicker" id="datetimepicker" value="<?php echo $_GET['datetimepicker'];?>">
            </div>
            <button type="submit" class="btn btn-default">查看</button>
          </form>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
<?php
    foreach ($CMD as $id => $desc) 
    {
?>
    <div class="row" id="<?php echo $id;?>">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                <div class="row">
                    <div class="col-md-12">
                    <span class="panel-title" style="padding:0 0 10px 0;"><strong><i class="fa fa-bar-chart"></i>  <?php echo $desc;?></strong></span>
                    <span class="pull-right text text-muted" id="desc_<?php echo  $id;?>"></span>
                    </div>
                </div>
                

                </div>
                <div class="panel-body" >
                <div class="row">
                    <div class="col-md-2" style="padding:0 0 0 20px;width:20%;"><span id="tag_elapsed_<?php echo  $id;?>" class="pull-left"></span></div>
                    <div class="col-md-2" style="padding:0 0 0 20px;width:20%;"><span id="tag_req_<?php echo  $id;?>" class="pull-left"></div>
                    <div class="col-md-2" style="padding:0 0 0 20px;width:20%;"><span id="tag_rsp_<?php echo  $id;?>" class="pull-left"></div>
                    <div class="col-md-2" style="padding:0 0 0 20px;width:20%;"><span id="tag_error_<?php echo  $id;?>" class="pull-left"></div>
                    <div class="col-md-2" style="padding:0 0 0 20px;width:20%;"><span id="tag_timeout_<?php echo  $id;?>" class="pull-left"></div>
                </div>
                <div class="row">
                    <div class="col-md-2" style="padding:20px;width:20%;height:220px;" id="chart_elapsed_<?php echo $id;?>"></div>
                    <div class="col-md-2" style="padding:20px;width:20%;height:220px;" id="chart_req_<?php echo $id;?>"></div>
                    <div class="col-md-2" style="padding:20px;width:20%;height:220px;" id="chart_rsp_<?php echo $id;?>"></div>
                    <div class="col-md-2" style="padding:20px;width:20%;height:220px;" id="chart_error_<?php echo $id;?>"></div>
                    <div class="col-md-2" style="padding:20px;width:20%;height:220px;" id="chart_timeout_<?php echo $id;?>"></div>
                </div>
                </div>
            </div>
        </div>
    </div>
<?php
    }
?>
<div style="position:fixed;bottom:0px;left:0px;width:100%;font-family:Microsoft YaHei;font-size:14px;background-color:#337ab7;padding:10px 0;opacity: 0.5">
<?php
    $i = 0;
    foreach ($CMD as $id => $desc) 
    {
        if($i % 10 == 0){ echo '<div class="row"><div class="col-md-1"></div>'; }
?>
<div class="col-md-1" >
    <span style="width: 30px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;padding-left:3px;">
    <?php 
        $d = $desc;
        if( strlen($desc) > 12 ){ $d = substr($desc, 0 , 12); }
        echo '<a style="color:white;" href="#' . $id . '">' . '[0x' . dechex($id) . '] ' . $d . '</a>';
    ?>
    </span>
</div>
<?php
        if($i % 10 == 9){ echo '<div class="col-md-1"></div></div>'; }
        $i++;
    }
?>
</div>

    </body>

	<script type="text/javascript">
	$(document).ready( function() {
		$('#datetimepicker').datetimepicker({
			lang:'ch',
			timepicker:false,
			format:'Y-m-d',
		});

		<?php
	    	foreach ($CMD as $id => $desc) 
	    	{
	    		$file_elapsed = "data/" . $day . "/" . $id . "_elapsed.json" ;
                $file_req = "data/" . $day . "/" . $id . "_req.json" ;
                $file_rsp = "data/" . $day . "/" . $id . "_rsp.json" ;
                $file_error = "data/" . $day . "/" . $id . "_error.json" ;
                $file_timeout = "data/" . $day . "/" . $id . "_timeout.json" ;

                $tag_elapsed = "data/" . $day . "/" . $id . "_elapsed.dat" ;
                $tag_req = "data/" . $day . "/" . $id . "_req.dat" ;
                $tag_rsp = "data/" . $day . "/" . $id . "_rsp.dat" ;
                $tag_error = "data/" . $day . "/" . $id . "_error.dat" ;
                $tag_timeout = "data/" . $day . "/" . $id . "_timeout.dat" ;
                $desc = "data/" . $day . "/" . $id . "_desc.dat" ;
	    ?>
    	    $.getJSON("<?php echo $file_elapsed;?>",  function(d){ 
    			var lines_info = []
    			lines_info[0] = {'label' : '' , 'data' :  d , 'show_points' : false , 'color' : '#78cd51' }
    			plot_chart( $("#chart_elapsed_<?php echo $id;?>") , lines_info );
    		}); 
            $.getJSON("<?php echo $file_req;?>",  function(d){ 
                var lines_info = []
                lines_info[0] = {'label' : '' , 'data' :  d , 'show_points' : false , 'color' : '#78cd51' }
                plot_chart( $("#chart_req_<?php echo $id;?>") , lines_info );
            }); 
            $.getJSON("<?php echo $file_rsp;?>",  function(d){ 
                var lines_info = []
                lines_info[0] = {'label' : '' , 'data' :  d , 'show_points' : false , 'color' : '#78cd51' }
                plot_chart( $("#chart_rsp_<?php echo $id;?>") , lines_info );
            }); 
            $.getJSON("<?php echo $file_error;?>",  function(d){ 
                var lines_info = []
                lines_info[0] = {'label' : '' , 'data' :  d , 'show_points' : false , 'color' : '#78cd51' }
                plot_chart( $("#chart_error_<?php echo $id;?>") , lines_info );
            }); 
            $.getJSON("<?php echo $file_timeout;?>",  function(d){ 
                var lines_info = []
                lines_info[0] = {'label' : '' , 'data' :  d , 'show_points' : false , 'color' : '#78cd51' }
                plot_chart( $("#chart_timeout_<?php echo $id;?>") , lines_info );
            }); 

    		$.getJSON("<?php echo $tag_elapsed;?>",  function(d){ 
                $("#tag_elapsed_<?php echo $id;?>").html('<code>' + d + '</code>')
            }); 
            $.getJSON("<?php echo $tag_req;?>",  function(d){ 
                $("#tag_req_<?php echo $id;?>").html('<code>' + d + '</code>')
            }); 
            $.getJSON("<?php echo $tag_rsp;?>",  function(d){ 
                $("#tag_rsp_<?php echo $id;?>").html('<code>' + d + '</code>')
            }); 
            $.getJSON("<?php echo $tag_error;?>",  function(d){ 
                $("#tag_error_<?php echo $id;?>").html('<code>' + d + '</code>')
            }); 
            $.getJSON("<?php echo $tag_timeout;?>",  function(d){ 
                $("#tag_timeout_<?php echo $id;?>").html('<code>' + d + '</code>')
            }); 
            $.getJSON("<?php echo $desc;?>",  function(d){ 
                $("#desc_<?php echo $id;?>").html(d)
            }); 

	    <?php
	    	}
	    ?>	
		});  
	</script>
</html>

