<div class="page-header" xmlns="http://www.w3.org/1999/html">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>

<link rel="stylesheet" href="<?=base_url('bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')?>"  type="text/css">
<script src="<?=base_url('bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')?>" type="text/javascript"></script>
<script src="<?=base_url('bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url('bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.css')?>"></link>
<script src="<?=base_url('bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.all.js')?>"></script>
<style type="text/css">
	.bigPop{width:100%;height:100%;position:fixed;opacity:0.5;z-index:888;background:#000;top: 0;left: 0;}
	.popCon{width: 100%;height:100%;position:fixed;z-index:900;top: 0;left: 0;}
	.popDet{border:solid 2px #ccc;overflow:hidden;zoom:1;top:50%;left:50%; position: absolute;padding:20px;border-radius: 1px;background:#fff;}
	.popTit{text-align: left;}
	.popCon .popLeft{}
	.popCon .popRight{max-width:100px;}
	.popCon input{padding:3px 5px;}
	.popList{padding:5px 0 0 40px;}
	.btnCon{padding:10px 0;text-align: center;}
	.btnCon .center,.btnCon .left,.btnCon .right{padding:3px 5px;border:solid 1px #94BED2;border-radius:3px;}
	.hidden{display:none!important}
	.table th{text-align: center}
</style>
<!-- / Javascript -->

<form method="get" action="<?php echo site_url('gsk_op/user_info');?>">
	<div class="row">
		<div class="form-group">
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon">时间</span>
					<div class="input-append date form_datetime"  data-date="<?=isset($data['filter']['start_time'])?date("Y-m-d",strtotime($data['filter']['start_time'])):date("Y-m-d",strtotime("-1 day"))?>">
						<input size="16" name="start_time" type="text" value="<?=isset($data['filter']['start_time'])?date("Y-m-d",strtotime($data['filter']['start_time'])):date("Y-m-d",strtotime("-1 day"))?>" readonly style="height: 30px; width: 250px">
						<span class="add-on" style="height: 30px"><i class="icon-remove"></i></span>
						<span class="add-on" style="height: 30px"><i class="icon-th"></i></span>
					</div>
					<span class="input-group-addon">至</span>
					<div class="input-append date form_datetime"  data-date="<?=isset($data['filter']['end_time'])?date("Y-m-d",strtotime($data['filter']['end_time'])):date("Y-m-d")?>">
						<input size="16" name="end_time" type="text"  value="<?=isset($data['filter']['end_time'])?date("Y-m-d",strtotime($data['filter']['end_time'])):date("Y-m-d")?>" readonly style="height: 30px; width: 250px">
						<span class="add-on" style="height: 30px"><i class="icon-remove"></i></span>
						<span class="add-on" style="height: 30px"><i class="icon-th"></i></span>
					</div>
					<script type="text/javascript">
						$(".form_datetime").datetimepicker({
							startView: 2,
							minView: 2,
							format: "yyyy-mm-dd",
							language: 'zh-CN',
							//			  showMeridian: true,
							autoclose: true,
							todayBtn: true
						});
					</script>
				</div>
			</div>
			<div class="col-sm-1" style="width: 200px"></div>
			<div class="form-group">
				<div class="col-sm-1"></div>
				<div class="col-sm-2" style="margin-left: 50px">
					<div class="input-group">
						<span class="input-group-addon" id="news-addon1">用户昵称</span>
						<input type="text" class="form-control"  name="user_name" aria-describedby="news-addon1"
						       value="<?=isset($data['filter']['user_name'])?$data['filter']['user_name']:''?>">
					</div>
				</div>
				<div class="col-sm-2" style="margin-left: 50px">
					<div class="input-group">
						<span class="input-group-addon" id="news-addon2">用户id</span>
						<input type="text" class="form-control" name="user_id" aria-describedby="news-addon2"
						       value="<?=isset($data['filter']['user_id'])?$data['filter']['user_id']:''?>">
					</div>
				</div>
				<button type="submit" name="btn_query" class="btn btn-danger" style="float: right; margin-right: 20px; width: 100px">查询</button>
			</div>

			<div class="form-group" >
				<div class="col-sm-2">
					<div class="input-group">
						<span class="input-group-addon" id="news-addon4">公司名称</span>
						<input type="text" class="form-control" name="user_company" aria-describedby="news-addon4"
						       value="<?=isset($data['filter']['user_company'])?$data['filter']['user_company']:''?>">
					</div>
				</div>

				<div class="col-sm-2" style="margin-left: 50px">
					<div class="input-group">
						<span class="input-group-addon" id="news-addon3">手机号</span>
						<input type="text" class="form-control" name="user_phone" aria-describedby="news-addon3"
						       value="<?=isset($data['filter']['user_phone'])?$data['filter']['user_phone']:''?>">
					</div>
				</div>

				<div class="col-sm-2" style="margin-left: 50px">
					<div class="input-group">
						<span class="input-group-addon" id="news-addon5">邀请码</span>
						<input type="text" class="form-control" name="user_code" aria-describedby="news-addon5"
						       value="<?=isset($data['filter']['user_code'])?$data['filter']['user_code']:''?>">
					</div>
				</div>

				<div class="col-sm-2" style="margin-left: 50px">
					<div class="input-group">
						<span class="input-group-addon" id="news-addon6">推荐人邀请码</span>
						<input type="text" class="form-control" name="promotion_code" aria-describedby="news-addon6"
						       value="<?=isset($data['filter']['promotion_code'])?$data['filter']['promotion_code']:''?>">
					</div>
				</div>
				<button type="submit" name="btn_export" class="btn btn-danger" style="float: right; margin-right: 20px; width: 100px">导出Excel</button>
			</div>
		</div>
	</div>
</form>
<div class="table-responsive">
	<table class="table" style="text-align: center">
		<thead>
		<tr>
			<th>用户昵称</th>
			<th>用户id</th>
<!--			<th>来源渠道id</th>-->
			<th>注册渠道</th>
<!--			<th>注册账号</th>-->
			<th>手机号</th>
<!--			<th>所在地</th>-->
			<th>公司名称</th>
			<th>公司类型</th>
			<th>工作岗位</th>
			<th>工作年限</th>
			<th>职称</th>
			<th>注册时间</th>
<!--			<th>注册IP</th>-->
<!--			<th>设备ID</th>-->
			<th>最近登录时间</th>
			<th>拥有项目数</th>
			<th>当前积分</th>
			<th>邀请码</th>
			<th>推荐人id</th>
			<th>推荐人邀请码</th>
			<th>操作</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($data['data'] as $item): ?>
			<tr>
				<td id="user_name_<?=$item['f_account_id']?>"><?=$item['f_name']?></td>
				<td><?=$item['f_account_id']?></td>
				<td><?=$item['f_register_channel']?></td>
				<td id="user_phone_<?=$item['f_account_id']?>"><?=$item['f_account_phone']?></td>
				<td><?=$item['f_company']?></td>
				<td><?=$item['f_company_type']?></td>
				<td><?=$item['f_job_type']?></td>
				<td><?=$item['f_years_of_working']?></td>
				<td><?=$item['f_job_title']?></td>
				<td><?=human_time(substr($item['f_account_create_time'], 0, -3))?></td>
				<td><?=human_time($item['f_last_req_time'])?></td>
				<td><?=$item['f_project_count']?></td>
				<td id="user_points_<?=$item['f_account_id']?>"><?=$item['f_points']?></td>
				<td><?=$item['f_code_id']?></td>
				<td><?=$item['f_promotion_id']?></td>
				<td><?=$item['f_promotion_code_id']?></td>
				<td>
					<a href="#" class="integrationBtn" id="tag<?=$item['f_account_id']?>">积分操作</a>
					|<a>通话时长操作</a>
					|<a>账号封禁</a>
				</td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>

<div class="popCon hidden" id="integrationPopCon">
	<div class="popDet" id="integrationPopEdt" >
		<p class="popTit" style="margin-left: -15px">积分操作</p>
		<div class="popList" style="width: 200px">
			<span class="popLeft">用户昵称：</span>
			<label class="popRight" id="lb_name_jifen"></label>
		</div>
		<div class="popList">
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="popLeft">用户id：</span>
			<label class="popRight" id="lb_id_jifen"></label>
		</div>

		<div class="popList">
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="popLeft">手机号：</span>
			<label class="popRight" id="lb_phone_jifen"></label>
		</div>

		<div class="popList">
			&nbsp;&nbsp;&nbsp;<span class="popLeft">当前积分：</span>
			<label class="popRight" id="lb_jifen"></label>
		</div>

		<div class="popList">
			<span class="popLeft">积分调整 </span>
			<input class="popRight" value="0" id="adjust_jifen"> </input>
		</div>
		<div class="popList">
			<span class="popLeft">调整原因 </span>
			<input class="popRight" id="adjust_reason"> </input>
		</div>
		<div class="btnCon">
			<button class="center" id="confirm">调整积分</button>
		</div>
	</div>
</div>

<div class="popCon hidden" id="integrationPopCon_second">
	<div class="popDet">
		<p class="popTit">确定调整用户的积分？</p>
		<div class="popList" style="width: 200px">
			<span class="popLeft">用户昵称：</span>
			<label class="popRight" id="lb_name_jifen_second"></label>
			<label class="popRight hidden" id="lb_id_jifen_second"></label>
		</div>
		<div class="popList">
			&nbsp;<span class="popLeft">积分调整：</span>
			<label class="popRight" id="lb_adjust_jifen"></label>
		</div>
		<div class="popList">
			&nbsp;<span class="popLeft">调整原因：</span>
			<label class="popRight" id="lb_adjust_reason"></label>
		</div>
		<div class="popList">
			&nbsp;<span class="popLeft">调整后积分：</span>
			<label class="popRight" id="lb_adjusted_jifen"></label>
		</div>
		<div class="btnCon">
			<button class="left" id="submit">确 定</button>
			<button class="left" id="cancel">取 消</button>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<?=pages($data['page']['cur_page'], $data['filter'], $data['page']['max']['max_page'], $data['page']['max']['max_count']);?>
	</div>
</div>

<script>
	//调整积分
	$(".integrationBtn").bind("click",function(){
		$("#integrationPopCon").removeClass("hidden");
		var ele=$("#integrationPopCon").find(".popCon");
		ele.css("margin","-"+ele.height()/2+"px -"+ele.width()/2+"px 0 0");
		var btn = $(this).attr("id").substring(3);
		var name = document.getElementById("user_name_"+btn).innerHTML;
		var phone = document.getElementById("user_phone_"+btn).innerHTML;
		var points = document.getElementById("user_points_"+btn).innerHTML;
		document.getElementById("lb_name_jifen").innerText = name;
		document.getElementById("lb_id_jifen").innerText = btn;
		document.getElementById("lb_phone_jifen").innerText = phone;
		document.getElementById("lb_jifen").innerText = points;
	});

	//调整积分--确认调整
	$("#confirm").bind("click",function(){
		$("#integrationPopCon").addClass("hidden");

		var name = document.getElementById("lb_name_jifen").innerText;
		var id = document.getElementById("lb_id_jifen").innerText;
		var jifen = document.getElementById("adjust_jifen").value;
		var reason = document.getElementById("adjust_reason").value;
		var points = document.getElementById("lb_jifen").innerText;

		document.getElementById("lb_name_jifen_second").innerText = name;
		document.getElementById("lb_id_jifen_second").innerText = id;//隐藏传递，最后操作数据库要用
		document.getElementById("lb_adjust_jifen").innerText = jifen;
		document.getElementById("lb_adjust_reason").innerText = reason;
		document.getElementById("lb_adjusted_jifen").innerText = parseInt(points) + parseInt(jifen);

		//恢复初始化
		document.getElementById("lb_name_jifen").innerText = "";
		document.getElementById("lb_id_jifen").innerText = "";
		document.getElementById("lb_phone_jifen").innerText = "";
		document.getElementById("lb_jifen").innerText = "";
		document.getElementById("adjust_reason").value = "";
		document.getElementById("adjust_jifen").value = 0;

		//显示
		$("#integrationPopCon_second").removeClass("hidden");
		var ele=$("#integrationPopCon_second").find(".popCon");
		ele.css("margin","-"+ele.height()/2+"px -"+ele.width()/2+"px 0 0");
	})

	//调整积分--第二次确认
	$("#submit").bind("click",function(){
		$("#integrationPopCon_second").addClass("hidden");

		//写入数据库
		var uid = document.getElementById("lb_id_jifen_second").innerText;
		var points = document.getElementById("lb_adjust_jifen").innerText;
		var reason = document.getElementById("lb_adjust_reason").innerText;
		$.ajax({
			type:"POST",
			url:"<?=site_url('gsk_op/operate_user_points');?>",
			data:{'uid':uid, 'points': points, 'reason': reason},
			datatype: "json",
			success:function(data){
				('操作成功')
			} ,
			error: function(XMLHttpRequest, textStatus, errorThrown){
				('操作失败')
			}
		});

		document.getElementById("lb_name_jifen_second").innerText = '';
		document.getElementById("lb_id_jifen_second").innerText = '';
		document.getElementById("lb_adjust_jifen").innerText = '';
		document.getElementById("lb_adjust_reason").innerText = '';
		document.getElementById("lb_adjusted_jifen").innerText = '';

	});

	//调整积分--取消
	$("#cancel").bind("click",function(){
		$("#integrationPopCon_second").addClass("hidden");
		document.getElementById("lb_name_jifen_second").innerText = '';
		document.getElementById("lb_id_jifen_second").innerText = '';
		document.getElementById("lb_adjust_jifen").innerText = '';
		document.getElementById("lb_adjust_reason").innerText = '';
		document.getElementById("lb_adjusted_jifen").innerText = '';
	});


</script>






