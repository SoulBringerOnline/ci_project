<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>

<link rel="stylesheet" href="<?=base_url('bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')?>"  type="text/css">
<script src="<?=base_url('bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')?>" type="text/javascript"></script>
<script src="<?=base_url('bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')?>" type="text/javascript"></script>


<link rel="stylesheet" type="text/css" href="<?=base_url('bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.css')?>"></link>
<script src="<?=base_url('bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.all.js')?>"></script>

<!-- / Javascript -->

<form method="get" action="<?php echo site_url('gsk_op/project_info');?>">
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
						<span class="input-group-addon" id="news-addon1">项目名称</span>
						<input type="text" class="form-control"  name="project_name" aria-describedby="news-addon1"
						       value="<?=isset($data['filter']['project_name'])?$data['filter']['project_name']:''?>">
					</div>
				</div>
				<div class="col-sm-2" style="margin-left: 50px">
					<div class="input-group">
						<span class="input-group-addon" id="news-addon2">项目id</span>
						<input type="text" class="form-control" name="project_id" aria-describedby="news-addon2"
						       value="<?=isset($data['filter']['project_id'])?$data['filter']['project_id']:''?>">
					</div>
				</div>
				<button type="submit" name="btn_query" class="btn btn-danger" style="float: right; margin-right: 20px; width: 100px">查询</button>
			</div>

			<div class="form-group" >
				<div class="col-sm-2">
					<div class="input-group">
						<span class="input-group-addon" id="news-addon4">创建用户id</span>
						<input type="text" class="form-control" name="user_id" aria-describedby="news-addon4"
						       value="<?=isset($data['filter']['user_id'])?$data['filter']['user_id']:''?>">
					</div>
				</div>

				<div class="col-sm-2" style="margin-left: 50px">
					<div class="input-group">
						<span class="input-group-addon" id="news-addon3">创建用户手机号</span>
						<input type="text" class="form-control" name="user_phone" aria-describedby="news-addon3"
						       value="<?=isset($data['filter']['user_phone'])?$data['filter']['user_phone']:''?>">
					</div>
				</div>

				<div class="col-sm-2" style="margin-left: 50px">
					<div class="input-group">
						<span class="input-group-addon" id="news-addon5">项目人数大于</span>
						<input type="text" class="form-control" name="member_count" aria-describedby="news-addon5"
						       value="<?=isset($data['filter']['member_count'])?$data['filter']['member_count']:0?>">
					</div>
				</div>

				<div class="col-sm-2" style="margin-left: 50px">
					<div class="input-group">
						<span class="input-group-addon" >状态</span>
						<select class="form-control form-group-margin" name="project_state" style="margin-bottom: 0px!important;">
							<option value="1" <?php if($data['filter']['project_state'] == 1){echo 'selected="selected"';} ?>>未认证</option>
							<option value="2" <?php if($data['filter']['project_state'] == 2){echo 'selected="selected"';} ?>>认证中</option>
							<option value="3" <?php if($data['filter']['project_state'] == 3){echo 'selected="selected"';} ?>>认证通过</option>
							<option value="4" <?php if($data['filter']['project_state'] == 4){echo 'selected="selected"';} ?>>认证未通过</option>
							<option value="5" <?php if(!isset($data['filter']['project_state']) || $data['filter']['project_state'] == 5){echo 'selected="selected"';} ?>>全部</option>
						</select>
					</div>
				</div>

				<div class="col-sm-2" style="height: 30px; margin-left: 30px">
					<div class="input-group" >
						<span class="input-group-addon" id="news-addon8" style="line-height:16px">公司名称为空</span>
						<input type="checkbox" class="form-control" name="company_name_empty" style="height:20px; width:20px;margin-top:7px" aria-describedby="news-addon8"
						<?php if( $data['filter']['company_name_empty'] == "on"){echo 'checked';}?> >
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
			<th colspan="5" style="text-align: center">项目信息</th>
			<th colspan="5" style="text-align: center">创建用户信息</th>
			<th colspan="3" style="text-align: center">认证</th>
		</tr>
		<tr>
<!--			<td>项目id</td>-->
			<td>项目名称</td>
			<td>创建时间</td>
			<td>项目类型</td>
			<td>项目人数</td>

			<td>用户昵称</td>
			<td>用户id</td>
			<td>手机号</td>
			<td>注册时间</td>
			<td>公司名称</td>

			<td>状态</td>
			<td>提交认证时间</td>
			<td>操作</td>
		</tr>
		</thead>
		<tbody>
			<?php foreach ($data['data'] as $item): ?>
				<?php if($item['f_company'] != "" || $data['filter']['company_name_empty'] != "on"):?>
					<tr>
						<!--					<td>--><?//=$item['f_prj_id']?><!--</td>-->
						<td><?=$item['f_prj_name']?></td>
						<td><?=human_time($item['f_add_time'])?></td>
						<td><?=project_type($item['f_prj_type'])?></td>
						<td><?=$item['f_member_count']?></td>

						<td><?=$item['f_name']?></td>
						<td><?=$item['f_c_uin']?></td>
						<td><?=$item['f_phone']?></td>
						<td><?=human_time($item['f_create_time'])?></td>
						<td><?=$item['f_company']?></td>

						<td><?=proj_state_type($item['f_auth_status'])?></td>
						<td><?=human_time($item['f_auth_time'])?></td>
						<td>
							<a  target="_blank" href="<?=site_url('gsk_op/project_details?project_id='.$item['f_prj_id'])?>">查看详情</a>
						</td>
					</tr>
				<?php endif;?>
			<?php endforeach;?>
		</tbody>
	</table>
</div>

<div class="row">
	<div class="col-md-12">
		<?=pages($data['page']['cur_page'], $data['filter'], $data['page']['max']['max_page'], $data['page']['max']['max_count']);?>
	</div>
</div>








