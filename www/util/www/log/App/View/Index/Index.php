<?php View::tplInclude('Public/header'); ?>


<section style="padding:45px ; padding-top:2px" >	
    <div class="jumbotron alert alert-warning alert-dismissable">
        <div class="container">
        <form action="index.php" role="search" method="post">
        	<div class="row ">
        		<input type="hidden" name="a" value="Index" />
                <input type="hidden" name="query_page" value="1" />
        		<?php
        			$text = '';
        			if( isset($param['query_text']) AND strlen( $param['query_text'] )  )
        			{
        				$text = $param['query_text'];
        			}
        		?>
               	<div class="col-md-12 col-lg-12 col-sm-12"><input type="text" value="<?php echo $text; ?>" name="query_text" class="form-control input-lg"  placeholder="搜索技巧( cmd=获取用户资料 name=kk666666 phone=13401031602 uin=85288528 dye=123456 ip=10.232.42.78 )"></div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 text-center" style="margin-top:20px">
					<button class="btn btn-primary btn-lg" type="submit">搜    索</button>
					<button class="btn btn-default" type="reset" onclick="location.reload()">重置</button>
					<button class="btn btn-default" type="reset" disabled="disabled">导出EXCEL</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div class="bs-callout bs-callout-info col-md-12 col-lg-12 col-sm-12 ">
        <ul class="pagination ">
        <?php
            for( $i = 0 ; $i < 25; $i++ )
            {
                $info = '<li';
                if( $param['query_page'] == $i )
                {
                    $info .= ' class="active"';
                }
                $info .= '>';

                $info .= '<a href="?query_page=' . $i;
                if( isset($param['query_text']) AND strlen( $param['query_text'] )  )
                {
                     $info .= '&query_text=' . $param['query_text'];
                }
                $info .= '">';
                $info .= $i+1;
                $info .= '<span class="sr-only">(current)</span></a></li>';
                echo $info;
            }
        ?>
        </ul>
        <div class="table table-condensed table-responsive">
		<table class="table">
		<thead class="text-center">
		    <th style="width:10%"></th>
		    <th style="width:5%"><h4>昵称</h4></th>
		    <th style="width:5%"><h4>手机</h4></th>
		    <th style="width:5%"><h4>用户ID</h4></th>
            <th style="width:10%"><h4>时间</h4></th>
            <th></th>
		</thead>
		<tbody>
		    <?php 
			foreach ($data as $d){
		    ?>
		    
		    <tr>

		    <td>
            <a href="?query_text=cmd_info<?php echo urlencode('=' . $d['f_cmd_info']);?>" >
		    <?php
            // echo $d['f_msg_type'];
            if( $d['f_msg_type'] == 1 )
            echo '<i class="fa fa-share"></i><code>' . $d['f_cmd_info'] . '</code>' ;
            else if( $d['f_msg_type'] == 2 )
            echo '<code>' . $d['f_cmd_info'] . '</code><i class="fa fa-reply"></i>' ;
            else if( $d['f_msg_type'] == 3 )
            echo '<code>' . $d['f_cmd_info'] . '</code><i class="fa fa-paypal"></i>' ;
            else
            echo '<code>' . $d['f_cmd_info'] . '</code>' ;
		    ?>
		    </a>
		    </td>

            <!-- 昵称 -->
		    <td>
		    <a href="?query_text=name<?php echo urlencode('=' . $d['f_name']);?>"  >
		    <?php if( strlen($d['f_name']) ) { echo $d['f_name'] ; } ?>
		    </a>
		    </td>

            <!-- 手机 -->
            <td>
            <a href="?query_text=phone<?php echo urlencode('=' . $d['f_phone']);?>"  >
            <?php if( strlen($d['f_phone']) ) { 
                echo '<span class="';
                echo get_label_class( intval($d['f_phone']) );
                echo '">';
                echo $d['f_phone'] ; 
                echo '</span>';
            } 
            ?>
            </a>
            </span>
            </td>

            <!-- 用户ID -->
            <td>
            <a href="?query_text=uin<?php echo urlencode('=' . $d['f_uin']);?>"  >
            <?php if( strlen($d['f_uin']) ) { 
                echo '<span class="';
                echo get_label_class( intval($d['f_uin']) );
                echo '">';
                echo $d['f_uin'] ; 
                echo '</span>';
            } 
            ?>
            </a>
            </td>


            <!-- TIME -->
            <td>
            <?php echo lava_date( $d['f_time']);?>
            </td>

		   
            <!-- LOG -->
            <td>
            <?php 
                echo '<strong>[客户端]</strong>';
                if ($d['f_client_id'] > 0 )
                {
                    if($d['f_client_id'] == 2)
                    {
                        echo '<i class="fa fa-apple text-danger" ></i>';
                    }
                    else if($d['f_client_id'] == 3)
                    {
                        echo '<i class="fa fa-android  text-primary" ></i>';
                    }
                }

                if( strlen($d['f_phone_info']) )
                {
                    echo '  <span class="text-primary">' . $d['f_phone_info'] . '</span>';
                }
                if( strlen($d['f_phone_os']) )
                {
                    echo '  <span class="text-danger">OS_VER:' . $d['f_phone_os'] . '</span>';
                }
                if( strlen($d['f_phone_sp']) )
                {
                    echo '  <span class="text-warning">' . $d['f_phone_sp'] . '</span>';
                }
                if( strlen($d['f_phone_network']) )
                {
                    echo '  <span class="text-success">' . $d['f_phone_network'] . '</span>';
                }
                if ($d['f_client_version'] > 0 )
                {
                    echo '  <span class="text-primary"> CLT_VER:' . $d['f_client_version'] . '</span>';
                }
                 echo '<br>';
                if( $d['f_cmd'] != 259 && strlen($d['f_log']) ) { 
                    echo '<strong>[日志]</strong><span style="width: 60000px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">' . $d['f_log'] . '</span>';
                } ?>
            </dl>
            </td>

		    </tr>
		    <?php 
			}
		    ?>
		</tbody>
		</table>
        </div>
        <ul class="pagination">
        <?php
            for( $i = 0 ; $i < 25; $i++ )
            {
                $info = '<li';
                if( $param['query_page'] == $i )
                {
                    $info .= ' class="active"';
                }
                $info .= '>';

                $info .= '<a href="?query_page=' . $i;
                if( isset($param['query_text']) AND strlen( $param['query_text'] )  )
                {
                     $info .= '&query_text=' . $param['query_text'];
                }
                $info .= '">';

                $info .= $i+1;
                $info .= '<span class="sr-only">(current)</span></a></li>';
                echo $info;
            }
        ?>
        </ul>
    </div>
 
</section>

<?php View::tplInclude('Public/footer'); ?>
