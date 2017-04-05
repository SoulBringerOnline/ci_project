<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>

<script type="text/javascript">
	init.push(function () {
		function promotion_exchange_status(status, lab)
		{
			if(status == null)
			{
				lab.setAttribute("class",'pull-right label label-warning');
				return '审核中...';
			}
			else
			{
				switch (status)
				{
					case "0":
						lab.setAttribute("class",'pull-right label label-warning');
						return "审核中...";
						break;
					case "1":
						lab.setAttribute("class",'pull-right label label-success');
						return "审核通过";
						break;
					case "-1":
						lab.setAttribute("class",'pull-right label label-danger');
						return "未通过";
					default:
						return status;
				}
			}
		}

		function change_workers_status(btn) {
			var lab = document.getElementById('label_' + btn.attr('name'));
			$.ajax({
				type:"POST",
				url:"<?=site_url('gsk_promotion/change_workers_status');?>",
				data:{'f_phone':btn.attr('name') , 'f_status': btn.val() },
				datatype: "json",
				success:function(data){
					lab.innerHTML='申请状态:' + promotion_exchange_status(btn.val(), lab);
				} ,
				error: function(){
					lab.val('失败');
				}
			});
		}

		$('.sel_workers_status').change(function () {
			change_workers_status($(this))
		});
	});
</script>
<form method="get" action="<?php echo site_url('gsk_promotion/audit');?>"  name="form_statis">
	<div class="row">
		<div class="form-group">
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon2">姓名</span>
					<input type="text" class="form-control" name="f_worker_name" aria-describedby="news-addon2"
					       value="<?=isset($data['filter']['f_worker_name'])?$data['filter']['f_worker_name']:''?>">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon3">手机号</span>
					<input type="text" class="form-control" name="f_worker_phone" aria-describedby="news-addon3"
					       value="<?=isset($data['filter']['f_worker_phone'])?$data['filter']['f_worker_phone']:''?>">
				</div>
			</div>
			<button type="submit" id="btn_query" class="btn btn-danger" style="float: left; margin-left: 20px; width: 80px">查 询</button>
		</div>
	</div>
</form>

<div class="panel colourable">
	<div class="panel-heading">
		<span class="panel-title"></span>
	</div>
	<!-- List group -->
	<ul class="list-group">
		<?php foreach ($data['data'] as $item): ?>
			<li class="list-group-item">
				id :<?=$item['_id']?>
				&nbsp;&nbsp;&nbsp;<span class="text-muted">姓名：<?= $item['f_name']?></span>
				&nbsp;&nbsp;&nbsp; 身份证号 :<?=$item['f_IDcard']?>
				&nbsp;&nbsp;&nbsp;<span class="text-muted">手机号 :<?= $item['f_phone']?></span>
				&nbsp;&nbsp;&nbsp;类别 :<?=$item['f_type']?>
				&nbsp;&nbsp;&nbsp;<span class="text-muted">邀请码 :<?= $item['f_invite_code']?></span>
					审批 :
					<select class="form-control sel_workers_status" style="width: 120px; display: inline"  name="<?=$item['f_phone']?>">
						<option value="0" <?php if($item['f_status'] == 0){echo 'selected="selected"';} ?>>审核中</option>
						<option value="1" <?php if($item['f_status'] == 1){echo 'selected="selected"';} ?>>审核通过</option>
						<option value="-1" <?php if($item['f_status'] == -1){echo 'selected="selected"';} ?>>未通过</option>
					</select>
				<span id="label_<?=$item['f_phone']?>" class="pull-right label label-<?=get_state($item['f_status'])?>" style="height: 25px">申请状态:<?=promotion_exchange_status($item['f_status'])?></span>

			</li>
		<?php endforeach;?>
	</ul>
</div>

<div class="row">
	<div class="col-md-12">
		<?=pages($data['page']['cur_page'], $data['filter'], $data['page']['max']['max_page'], $data['page']['max']['max_count']);?>
	</div>
</div>
