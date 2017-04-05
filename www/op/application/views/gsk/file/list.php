<script src="<?=base_url('assets/js/ajaxfileupload.js') ?>"></script>
<div>
	<div class="panel">
		<div class="panel-heading">
			<span class="panel-title">文件操作</span>
			<div class="panel-heading">
				<span class="panel-title">
					<?php while(count($path)>0) {
						$item = array_pop($path);
						?>
						<a href="<?=site_url('gsk_file?fid='.$item['f_fid']);?>"><?php echo $item['f_name'];?>/</a>
					<?php }?>
				</span>
				<div style="float:right;">
					<button class="btn btn-info" data-toggle="modal" data-target="#myModal">新建文件夹</button> &nbsp;&nbsp;
					<input type="file" class="form-control" style="display: none;" id="upfile" name="upfile" onchange="javascript:fileUpload('upfile');" placeholder="卡片图片">
					<button class="btn btn-info" onclick="upfile.click();">上传文件</button>&nbsp;&nbsp;
					<button class="btn btn-info" data-toggle="modal" data-target="#myModal2">保存链接</button>
				</div>
				<select id="env_id" class="form-control" style="width:170px">
					<option value="online">online</option>
					<option value="online">offline</option>
				</select>

			</div>
		</div>
		<div class="panel-body">

			<table class="table" fid="<?php echo $fid;?>" id="curFolder">
				<thead>
				<tr>
					<th>名称</th>
					<th>上传时间</th>
					<th>排序</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($list as $file) {?>
				<tr>
					<td>

						<?php if($file['f_type'] == "99") {?>
						<a href="<?=site_url('gsk_file?fid='.$file['f_fid']);?>">
							<div class="col-md-10 col-sm-4">
						<i class="fa fa-folder"></i>&nbsp;&nbsp;&nbsp;&nbsp;
								<?php echo $file['f_name'];?>
							</div></a>
						<?php }else { ?>
							<div class="col-md-10 col-sm-4">
								<i class="fa fa-file-o"></i>&nbsp;&nbsp;&nbsp;&nbsp;
								<?php echo $file['f_name'];?>
							</div>

						<?php }?>
					</td>
					<td>
						<?php echo date("Y-m-d H:i:s", intval($file['f_uploadtime']));?>
					</td>
					<td>
						1
					</td>
					<td>
						<button class="btn btn-success btn-xs" id="editBtn" data-toggle="modal" data-target="#editMyModal" onclick="editFile(<?php echo $file['f_fid']; ?>)">编辑</button>&nbsp;&nbsp;
						<button class="btn btn-success btn-xs btn-outline" id="delBtn" onclick="delteFile(this, <?php echo $file['f_fid']; ?>)">删除</button>
					</td>
				</tr>
				<?php }?>

				</tbody>
			</table>
		</div>
	</div>

	<div id="myModal" class="modal fade in" tabindex="-1" role="dialog" style="display: none;" aria-hidden="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">添加文件夹</h4>
				</div>
				<div class="modal-body">
					<p>
					<label for="name">文件夹名字：</label>
					<input id="foldname" name="name" type="input"/>
					<label for="sort">sort：</label>
					<input id="foldsort" name="sort" type="input"/>

					</p>
				</div> <!-- / .modal-body -->
				<div class="modal-footer">
					<button type="button" id="closeBtn" class="btn btn-default" data-dismiss="modal">关闭</button>
					<button type="button" id="createFolderBtn" class="btn btn-primary" onclick="onclickBtn();">保存</button>
				</div>
			</div> <!-- / .modal-content -->
		</div> <!-- / .modal-dialog -->
	</div>

	<div id="editMyModal" class="modal fade in" tabindex="-1" role="dialog" style="display: none;" aria-hidden="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">修改文件</h4>
				</div>
				<div class="modal-body">
					<p>
						<label for="name1">文件夹名字：</label>
						<input id="foldname1" name="name1" type="input"/>

					</p>
				</div> <!-- / .modal-body -->
				<div class="modal-footer">
					<button type="button" id="closeBtn1" class="btn btn-default" data-dismiss="modal">关闭</button>
					<button type="button" id="createFolderBtn1" class="btn btn-primary" onclick="onclickEdit();">保存</button>
				</div>
			</div> <!-- / .modal-content -->
		</div> <!-- / .modal-dialog -->
	</div>
	<div id="myModal2" class="modal fade in" tabindex="-1" role="dialog" style="display: none;" aria-hidden="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">修改文件</h4>
				</div>
				<div class="modal-body">
					<p>
						<label for="name2">文件名：</label>
						<input id="foldname2" name="name2" type="input"/>
						<label for="link2">文件链接：</label>
						<input id="link2" name="link2" type="input"/>
						<label for="docsize">文件大小：</label>
						<input id="docsize" name="docsize" type="input"/>
					</p>
				</div> <!-- / .modal-body -->
				<div class="modal-footer">
					<button type="button" id="closeBtn2" class="btn btn-default" data-dismiss="modal">关闭</button>
					<button type="button" id="createFolderBtn1" class="btn btn-primary" onclick="onclickEdit2();">保存</button>
				</div>
			</div> <!-- / .modal-content -->
		</div> <!-- / .modal-dialog -->
	</div>

	<script type="text/javascript">
		var edit_fid=0;
		var onclickBtn = function() {
			var name = $('#foldname')[0].value;
			var sort = $('#foldsort')[0].value;
			var env = $('#env_id')[0].value;
			var fid = <?php echo $fid;?>;
			$.ajax({
				type:"post",
				data:{name:name, sort:sort,type:1,fpid:fid,env:env},
				url:"<?=site_url('gsk_file/addFile');?>",

				success:function (o) {
					$('#foldname')[0].value = "";
					$('#foldsort')[0].value = "";
					$('#closeBtn')[0].click();
					window.location.reload();
				}
			})
		}

			function fileUpload(id){
				$.ajaxFileUpload({
					url:'http://192.168.164.199/api/aliyun/upload_file.php',
					secureuri:false,
					fileElementId:id,
					dataType:'json',
					data: {'filename':id},
					success:function(data){
						//data = eval(data);
						console.log(data.file_size);
						if(data.ret == 0){
							// 保存上传文件操作
							var name = data.file_name;
							var sort=1;
							var type = 0;
							var fid = <?php echo $fid;?>;
							$.ajax({
								type:"post",
								data:{name:name, sort:sort,type:0,fpid:fid,path:data.path, size:data.file_size},
								url:"<?=site_url('gsk_file/addFile');?>",
								success:function (o) {
									window.location.reload();
								}
							})
						}else{
							(data.msg);
						}
					}
				})
			}

		function delteFile(e,id) {
			var env = $('#env_id')[0].value;
			$.ajax({
				type:"post",
				data:{fid:id, env:env},
				url:"<?=site_url('gsk_file/deleteFile');?>",
				success:function (o) {
					//data = eval();
					console.log(o);
					window.location.reload();
				}
			})
		}

		function editFile(id) {
			edit_fid = id;
		}

		function onclickEdit() {
			edit_fid;
			var env = $('#env_id')[0].value;
			var name = $('#foldname1')[0].value;

			var fid = <?php echo $fid;?>;
			$.ajax({
				type:"post",
				data:{name:name, fid:edit_fid, env:env},
				url:"<?=site_url('gsk_file/editFile');?>",

				success:function (o) {
					$('#foldname1')[0].value = "";
					$('#closeBtn1')[0].click();
					window.location.reload();
				}
			})
		}

		function onclickEdit2() {
			edit_fid;
			var env = $('#env_id')[0].value;
			var name = $('#foldname2')[0].value;
			var link = $('#link2')[0].value;
			var size = $('#docsize')[0].value;

			var fid = <?php echo $fid;?>;
			$.ajax({
				type:"post",
				data:{name:name, sort:0,type:0,fpid:fid,path:link, size:size},
				url:"<?=site_url('gsk_file/addFile');?>",

				success:function (o) {
					$('#foldname2')[0].value = "";
					$('#link2')[0].value = "";
					$('#docsize')[0].value  = "";
					$('#closeBtn2')[0].click();
					window.location.reload();
				}
			})
		}
	</script>

</div>