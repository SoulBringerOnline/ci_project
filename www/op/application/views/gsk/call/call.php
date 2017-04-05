<link href="<?=base_url('assets/css/jquery.datetimepicker.css') ?>" rel="stylesheet" type="text/css">
<script src="<?=base_url('assets/js/jquery.datetimepicker.js') ?>"></script>
<script src="<?=base_url('assets/js/layer/layer.js') ?>"></script>
<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>
<div id=""content-wrapper>
	<div class="table-primary">
		<div role="grid" id="jq-datatables-example_wrapper" class="dataTables_wrapper form-inline no-footer">
			<div class="table-header clearfix">
				<form class="form-inline">
					时间：<div class="form-group">
						<input type="text" class="form-control" name="start" id="start" placeholder="点击选择">
					</div>
					至
					<div class="form-group">
						<input type="text" class="form-control" name="end" id="end" placeholder="点击选择">
					</div>
					&nbsp;&nbsp;&nbsp;&nbsp;状态
					<div class="form-group">
						<select name="status" id="status" class="form-control" >
							<option value="2">不限</option>
							<option value="0">失败</option>
							<option value="1">成功</option>
						</select>
					</div>
					&nbsp;&nbsp;&nbsp;&nbsp;条件
					<div class="form-group">
						<select name="condition" id="condition" class="form-control" >
							<option value="0">不限</option>
							<option value="1">手机号</option>
							<option value="2">用户ID</option>
							<option value="3">用户昵称</option>
						</select>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="condition_value" id="condition_value" placeholder="搜索条件...">
					</div>&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" id="search" class="btn">搜索</button>
				</form>
			</div>
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq-datatables-example" aria-describedby="jq-datatables-example_info">
				<thead>
				<tr role="row">
					<th tabindex="0" rowspan="1" colspan="1" style="width: 218px;">时间</th>
					<th tabindex="0" rowspan="1" colspan="1" style="width: 318px;">对话ID</th>
					<th tabindex="0" rowspan="1" colspan="1" style="width: 218px;">用户昵称</th>
					<th tabindex="0" rowspan="1" colspan="1" style="width: 318px;">用户ID</th>
					<th tabindex="0" rowspan="1" colspan="1" style="width: 218px;">手机号</th>
					<th tabindex="0" rowspan="1" colspan="1" style="width: 170px;">拨打号码</th>
					<th tabindex="0" rowspan="1" colspan="1" style="width: 170px;">通话时长()</th>
					<th tabindex="0" rowspan="1" colspan="1" style="width: 170px;">状态</th>
					<th tabindex="0" rowspan="1" colspan="1" style="width: 170px;">操作</th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($data['list'] as $val):?>
					<tr class="gradeA odd">
						<td class="sorting_1"><?=$val['f_create_time']?></td>
						<td><?=$val['f_call_id']?></td>
						<td><?=$val['f_name']?></td>
						<td class="center"><?=$val['f_uin']?></td>
						<td class="center"><?=$val['f_phone']?></td>
						<td class="center"><?=$val['f_to_phone']?></td>
						<td class="center"><?php if($val['f_duration']>60){
							echo floor($val['f_duration']/60)."分钟".($val['f_duration']%60).秒;
						}else{
							echo $val['f_duration']."秒";
						}?></td>
						<td class="center <?php  if($val['f_status'] == "异常"){ echo "bg-warning";}?>"><?=$val['f_status']?></td>
						<td class="center <?php  if($val['f_status'] == "异常"){?>fix<?php }?>" data-sid="<?=$val['f_call_id']?>"><button class="btn btn-sm btn-labeled <?php  if($val['f_status'] == "异常"){?>btn-info<?php }?>"><span class="btn-label icon fa fa-camera-retro"></span>修复</button></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<div class="table-footer clearfix">
				<div class="DT-label">
					<div class="dataTables_info" id="jq-datatables-example_info" role="" aria-live="polite" aria-relevant="all">共（<?=$data['total']?>）条</div>
				</div>
				<div class="DT-pagination">
					<div class="dataTables_paginate paging_simple_numbers" id="jq-datatables-example_paginate">
						<ul class="pagination">
							<?=paginations($data['total'], $data['page'], $data['size'], $offset = 2, $url)?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(function(){
		$('#start').datetimepicker({
			lang:'ch',
			timepicker:false,
			format:'Y-m-d',
		});
		$('#end').datetimepicker({
			lang:'ch',
			timepicker:false,
			format:'Y-m-d',
		});
		var condition = <?=json_encode($condition)?>;
		for (var key in condition) {
			if ($.inArray(key, ["start", "end", "status"])) {
				$("#"+key).val(condition['key']);
			}
			if ($.inArray(key, ["uid", "username", "phone"])) {
				switch (key) {
					case "uid": $("#condition").find("")
				}
			}
		}
	})

	$("#search").click(function(){
		var url = location.pathname;
		var search = new Object();
		if ($("#start").val()) {
			search.start = $("#start").val();
		}
		if ($("#end").val()) {
			search.end = $("#end").val();
		}
		if ($("#status").val() != 2) {
			search.status = $("#status").val();
		}
		if ($("#condition").val() != 0 && $("#condition_value").val()) {
			switch (parseInt($("#condition").val())) {
				case 1: search.phone = $("#condition_value").val();break;
				case 2: search.uid = $("#condition_value").val();break;
				case 3: search.username = $("#condition_value").val();break;
			}
		}
		if (JSON.stringify(search).length > 2) {
			var _hash = "";
			for (var key in search) {
				_hash += "&"+key+'='+search[key];
			}
			_hash = _hash.substring(1);
			location.href = location.pathname+"?"+_hash;
			return true;
		}

		location.href = location.pathname;
	});
	var lock = 0;
	$(".fix").click(function(){
		if (lock) {
			return;
		}
		lock = 1;
		var that = $(this);
		var callSid = that.attr("data-sid");
		$.get("/gsk/index.php/gsk_news/call_record_fix?sid="+callSid, function(json){
			lock = 0;
			if(json.state){
				layer.msg(json.msg);
				//console.info(that);
				that.unbind();
				that.closest(".bg-warning").removeClass('bg-warning');
				that.removeClass("fix");
				that.find("button").removeClass('btn-info');
			} else {
				layer.msg(json.msg);
			}
		});
	});
</script>