<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL&~E_NOTICE);
ini_set('html_errors', 'on');
date_default_timezone_set('Asia/Shanghai');


require_once dirname(__FILE__) . '/attr.php';
$day = intval( (time() + 28800) / 86400 );
if( isset( $_GET['datetimepicker'] ) && !empty( $_GET['datetimepicker'] ) )
{
    list( $y, $m , $d ) = explode("-", $_GET['datetimepicker']);
    $day = intval( (mktime(0,0,0,$m,$d,$y) + 28800) / 86400 );
}
$server = "";
if( isset( $_GET['server'] ) && !empty( $_GET['server'] ) )
{
    $server = trim(  $_GET['server'] );
}

function widget_select($srv, $cur_srv)
{
    $selected = "";
    if( $srv == $cur_srv )
    {
        $selected = 'selected="selected"';
    }
    if( empty($srv) )
    {
        return '<option value="" ' . $selected . '>总览</option>'; 
    }
    else
    {
        return '<option value="' . $srv . '" ' . $selected . '>' . $srv .  '</option>'; 
    }
}


?>

<!DOCTYPE html>
<html>
    <head>
        <title>监控</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
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
        <input type="hidden" name="u" value="<?php echo $u;?>">
        <input type="hidden" name="p" value="<?php echo $p;?>">
            <div class="form-group">
              <input type="text" class="form-control" name="datetimepicker" id="datetimepicker" value="<?php echo $_GET['datetimepicker'];?>">
            </div>
            <div class="form-group">
            <select class="form-control" name="server" id="server">  
                <?php echo widget_select( "", $server );?>
                <?php echo widget_select( "192.168.164.199", $server );?>
                <?php echo widget_select( "192.168.164.200", $server );?>
            </select>  
            </div>
            <button type="submit" class="btn btn-default">查看</button>
          </form>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>




<?php
$i = 0;
foreach ($attr as $id => $desc) 
{
    if( $i % 3 == 0 )
    {
        echo '<div class="row">';
    }
?>
        <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <span class="panel-title"><?php echo $desc . '(' . $id . ')';?></span>
            <div class="pull-right">
                <span id="max_<?php echo $id;?>" class="text-right label label-danger"></span>
		    </div>
		  </div>
		  <div class="panel-body" >
			  <div style="padding:20px;width:100%;height:220px;" id="chart_<?php echo $id;?>"></div>
		  </div>
		</div>
		</div>

    <?php
	    	if( $i % 3 == 2 )
			{
				echo '</div>';
			}
			$i++;
	    }
    ?>
	
    </body>

	<script type="text/javascript">
	$(document).ready( function() {
		$('#datetimepicker').datetimepicker({
			lang:'ch',
			timepicker:false,
			format:'Y-m-d',
		});

		<?php
			
	    	foreach ($attr as $id => $desc) 
	    	{
	    		if( empty($server) )
	    		{
	    			$file = "data/" . $day . "/" . $id ;
	    		} 
	    		else
	    		{
	    			$file = "data/" . $server . "/" . $day . "/" . $id ;
	    		}
	    ?>
	    $.getJSON("<?php echo $file;?>.json",  function(d){ 
			var lines_info = []
			lines_info[0] = {'label' : '' , 'data' :  d , 'show_points' : false , 'color' : '#78cd51' }
			plot_chart( $("#chart_<?php echo $id;?>") , lines_info );
		}); 
		$.getJSON("<?php echo $file;?>.dat",  function(d){ 
			$("#max_<?php echo $id;?>").html('峰值 ' + d)
		}); 
		
	    <?php
	    	}
	    ?>	
		});  
	</script>
</html>

