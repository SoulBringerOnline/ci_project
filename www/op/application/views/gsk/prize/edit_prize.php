<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>修改数量</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <link href="<?=base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?=base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?=base_url('assets/css/pixel-admin.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?=base_url('assets/css/widgets.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?=base_url('assets/css/pages.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?=base_url('assets/css/rtl.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?=base_url('assets/css/themes.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?=base_url('assets/css/style.css') ?>" rel="stylesheet" type="text/css">

    <script src="<?=base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?=base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script src="<?=base_url('assets/js/pixel-admin.min.js') ?>"></script>
    <script src="<?=base_url('assets/js/ajaxfileupload.js') ?>"></script>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?=base_url('favicon.ico') ?>" >

</head>
<body class="theme-asphalt">
<div id="content-wrapper" style="top:-20px;">
    <div class="row">
        <div class="col-sm-12">
            <form action="" class="panel form-horizontal" enctype="multipart/form-data">
                <div class="panel-body">
                    <div class="row form-group">
                        <label class="col-sm-2 control-label">奖品总数:</label>
                        <div class="col-sm-6">
                            <input type="text"  value="<?=$f_origin_total ?>" readonly="true" name="f_origin_total" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-2 control-label">奖品剩余:</label>
                        <div class="col-sm-6">
                            <input type="text" value="<?=$f_num ?>"  name="f_num" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-2 control-label">奖品标题:</label>
                        <div class="col-sm-6">
                            <input type="text"  readonly value="<?=$f_prize_name ?>"  name="f_prize_name" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-2 control-label">奖品描述:</label>
                        <div class="col-sm-6">
                            <input type="text"  value="<?=$f_prize_desc ?>"  name="f_prize_desc" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-2 control-label">奖品图片:</label>
                        <div class="col-sm-6">
                            <!--input type="file" class="form-control" id="upfile" name="upfile" onchange="javascript:imgUpload('upfile');" placeholder="卡片图片"-->
                            <!--input type="file" name="f_prize_image" class="form-control"-->
                            <!--input type="hidden" value="<?=$f_prize_img ?>" class="form-control" id="f_card_img" name="f_prize_img"-->
                            <div id="show_card_img"></div>
                            <!--input type="hidden" name="desc" value="" class="form-control"-->
                            <img src="<?=$f_prize_img?>" />
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-primary" id="save">保&nbsp;&nbsp;存</button>
                    <button class="btn btn-primary" id="cancel">取&nbsp;&nbsp;消</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(function(){
            var index = parent.layer.getFrameIndex(window.name);
            $("#cancel").click(function(){
                parent.layer.close(index);
            });

            $("#save").click(function(){
                var prizeId = "<?=$prizeId ?>";
                $.ajax({
                    url: "/gsk/index.php/gsk_prize/show_change?prizeId="+prizeId,
                    type: "GET",
                    data: $("form").serialize(),
                    dataType: "json",
                    success: function (data) {
                        if(data.state){
                            parent.layer.close(index);
                        }
                    }
                });
                return false;
            });
        });
        function imgUpload(id){
            $.ajaxFileUpload({
                url:'http://192.168.164.199/api/aliyun/upload_pic.php',
                secureuri:false,
                fileElementId:id,
                dataType:'json',
                data: {'filename':id},
                success:function(data){
                    if(data.ret == 0){
                        $("#f_card_img").val(data.pic_url);
                        $("#show_card_img").html("<img src="+data.pic_url+">");
                    }else{
                        (data.msg);
                    }
                },
            })
        }
        function showCard() {
            if ($("#type").val() == '12') {
                $(".card").show();
            } else {
                $(".card").hide();
            }
        }

        showCard();
    </script>
</body>
</html>