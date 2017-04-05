<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>

<style type="text/css">
	.popCon .popRight{max-width:100px;}
	.popCon input{padding:3px 5px;}
	.popList{padding:10px 0;}
	.btnCon{padding:10px 0;text-align: center;}
	.btnCon .center,.btnCon .left,.btnCon .right{padding:3px 5px;border:solid 1px #94BED2;border-radius:3px;}
	.btnCon .orange{background:#BBD647;border:solid 1px #BBD647}
	.popLeftCon{margin-right:10px; float: left;}
	.hasBor{border:solid 1px #ccc;padding:2px;width:100px;}
	.toptd{width:100px;}
	.popTabCon{display: block;overflow:hidden;zoom:1; align-content: center}
	.popTableHasBor{border-spacing:0px;border-top: solid 1px #ccc;border-left: solid 1px #ccc; text-align: center}
	.popTableHasBor td{border-bottom:solid 1px #ccc;border-right:solid 1px #ccc; }
	.popTableHasBor th{width: 100px;}
	.popTable  td{height: 30px}
	.smallTit{font-size: 13px; font-weight:bold;font-style:italic}
</style>

<script type="text/javascript">
	init.push(function () {
		$('body').toggleClass('mmc');

		function GetRadioValue(RadioName)
		{
			var obj;
			obj=document.getElementsByName(RadioName);
			if(obj!=null){
				var i;
				for(i=0;i<obj.length;i++){
					if(obj[i].checked){
						return obj[i].value;
					}
				}
			}
			return null;
		}


		$('#btn_submit').click(function () {
			var btn = $(this);
			var check = GetRadioValue('apply');
			var limit = $('#phone_limit').val();
			if(($('#reject_reason').val().length <= 0) && (check == 0))
			{
				('请说明驳回原因！');
				return;
			}

			$.ajax({
				type:"POST",
				url:"<?=site_url('gsk_op/project_apply');?>",
				data:{
					'prj_id': '<?=$data['f_prj_id']?>' ,
					'prj_audit': check ,
					'prj_phone_litmit': limit,
					'prj_reject_reason': $('#reject_reason').val()
				},
				datatype: "json",
				beforeSend:function(){
					btn.button('loading');
				},
				success:function(data){
					('操作成功')
					window.close();

				} ,
				error: function(){
					btn.button('reset');
					btn.html('操作失败')
				}
			});
		});

		$('#btn_cancel').click(function () {
			window.close();
		});
	});
</script>
<!-- / Javascript -->

<div>
	<div>
		<p style="font-size: 14px; font-weight: bold">项目详细信息</p>
		<div class="popTabCon" style="width: 700px;margin: 0 auto;text-align: center">
			<div class="popLeftCon">
				<table class="popTable">
					<tr>
						<td class="toptd">项目id:</td>
						<td class="hasBor">
							<label>
								<?=$data['f_prj_id']?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">项目名称:</td>
						<td class="hasBor">
							<label>
								<?=$data['f_prj_name']?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">创建时间:</td>
						<td class="hasBor">
							<label>
								<?= human_time($data['f_add_time'])?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">创建人id:</td>
						<td class="hasBor">
							<label>
								<?=$data['f_c_uin']['f_uin']?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">创建人昵称:</td>
						<td class="hasBor">
							<label>
								<?=$data['f_c_uin']['f_name']?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">创建人手机号:</td>
						<td class="hasBor">
							<label>
								<?=$data['f_c_uin']['f_phone']?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">是否认证:</td>
						<td class="hasBor">
							<label>
								<?=proj_state_type($data['f_auth_status'])?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">认证时间:</td>
						<td class="hasBor">
							<label>
								<?= human_time($data['f_auth_time'])?>
							</label>
						</td>
					</tr>
				</table>
			</div>
			<div class="popLeftCon" style="margin-left: 100px">
				<table class="popTable">
					<tr>
						<td class="toptd">所在地:</td>
						<td class="hasBor">
							<label>
								<?=$data['f_prj_address']?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">建筑面积:</td>
						<td class="hasBor">
							<label>
								<?=$data['f_floor_area']?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">工期:</td>
						<td class="hasBor">
							<label>
								<?= get_proj_time($data['f_prj_begin'], $data['f_prj_end'])?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">建筑单位:</td>
						<td class="hasBor">
							<label>
								<?=$data['f_jianzhu_danwei']?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">施工单位:</td>
						<td class="hasBor">
							<label>
								<?=$data['f_shigong_danwei']?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">监理单位:</td>
						<td class="hasBor">
							<label>
								<?=$data['f_jianli_danwei']?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">分包单位:</td>
						<td class="hasBor">
							<label>
								<?=$data['f_fenbao_danwei']?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="toptd">项目人数:</td>
						<td class="hasBor">
							<label>
								<?=$data['f_member_count']?>
							</label>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div style="margin: 0 50px">
			<p class="smallTit">项目成员列表:</p>
			<div class="popTabCon">
				<table class="popTableHasBor table">
					<thead>
					<tr>
						<th>序号</th>
						<th>用户id</th>
						<th>昵称</th>
						<th>手机号</th>
						<th>详情</th>
					</tr>
					</thead>
					<tbody>
					<?$i=1?>
					<?php foreach ($data['app_members'] as $item):?>
						<tr>
							<td><?=$i?></td>
							<td><?=$item['f_uin']?></td>
							<td><?=$item['f_name']?></td>
							<td><?=$item['f_phone']?></td>
							<td>
								<a  target="_blank" href="<?=site_url('gsk_op/user_info?user_id='.$item['f_uin'])?>">查看详情</a>
							</td>
						</tr>
						<?$i++?>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
			<p class="smallTit">认证资料:</p>
			<div class="popTabCon">
				<table class="popTableHasBor table">
					<? if(count($data['proj_history']['f_attach_list']) > 0)
					{
						$i = 1;
						foreach ($data['proj_history']['f_attach_list'] as $list)
						{
							echo '<td style="vertical-align: middle">';
							echo  "资料$i</td>";
							echo '<td style="vertical-align: middle">';
							echo "http://ugc.zy.glodon.com/";
							echo $list["f_attach_url"]."<br>";

							$i++;
						}
					}
					?>
					<?$i=1?>
					<?php foreach ($data['proj_history']['f_attach_list'] as $item):?>
						<tr>



							</td>
							<?=human_time($item['f_time'])?></td>
						</tr>
						<?$i++?>
					<?php endforeach;?>
				</table>
			</div>

			<p class="smallTit">权限调整:</p>
			<div class="popList">
				<span>通话额度： </span>
				<input id="phone_limit" class="right" style="width: 100px"> </input>
			</div>
			<p class="smallTit">调整状态:</p>
			<div class="btnCon">
				<input type="radio" name="apply" id="reject" value="0" class="left orange" style="height:25px; width:25px" <?php if($data['f_auth_status'] != 2){echo 'disabled="disabled"';} ?>>
				<span style="font-size: 14px" class="orange">驳回申请</span>
				<input type="radio" name="apply" id="pass"  value="1" class="right orange" style="height:25px; width:25px;margin-left: 50px" <?php if($data['f_auth_status'] != 2){echo 'disabled="disabled"';} ?>>
				<span style="font-size: 14px" class="orange">通过审核</span>
			</div>
			<p class="smallTit">驳回原因:</p>
			<div class="popList">
				<input id="reject_reason" class="right" style="width: 700px" maxlength="50"> </input>
			</div>
			<p class="smallTit">操作记录:</p>
			<div class="popTabCon">
				<table class="table popTableHasBor">
					<thead>
					<tr>
						<th>序号</th>
						<th>审核状态</th>
						<th>说明</th>
						<th>审核人</th>
						<th>历史认证资料</th>
						<th>审核时间</th>
					</tr>
					</thead>
					<tbody>
					<?$i=1?>
					<?php foreach ($data['proj_history']['f_auth_list'] as $item):?>
						<tr>
							<td style="vertical-align: middle"><?=$i?></td>
							<td style="vertical-align: middle"><?= proj_state_type($item['f_auth_status'])?></td>
							<td style="vertical-align: middle"><?=$item['f_desc']?></td>
							<td style="vertical-align: middle"><?=$item['f_audit_name']?></td>
							<td style="vertical-align: middle">
								<? if(count($item['f_attach_list']) > 0)
									{
										foreach ($item['f_attach_list'] as $list)
										{
											echo 'http://ugc.zy.glodon.com/'.$list['f_attach_url'].'<br>';
										}
									}
								?>
							</td>
							<td style="vertical-align: middle"><?=human_time($item['f_time'])?></td>
						</tr>
						<?$i++?>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
			<div class="btnCon">
				<button class="left" id="btn_submit" style="width: 100px">确认</button>
				<button class="right" style="margin-left: 50px; width:100px;" id="btn_cancel">取消</button>
			</div>
		</div>
	</div>
</div>