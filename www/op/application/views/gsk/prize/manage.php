<link href="<?=base_url('assets/css/jquery.datetimepicker.css') ?>" rel="stylesheet" type="text/css">
<script src="<?=base_url('assets/js/jquery.datetimepicker.js') ?>"></script>
<script src="<?=base_url('assets/js/layer/layer.js') ?>"></script>
<div class="page-header">
    <h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>
<div id=""content-wrapper>
    <div class="table-primary">
        <div role="grid" id="jq-datatables-example_wrapper" class="dataTables_wrapper form-inline no-footer">

            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq-datatables-example" aria-describedby="jq-datatables-example_info">
                <thead>
                <tr role="row">
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 218px;">序号</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 318px;">奖品名称</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 218px;">总份数</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 218px;">已发放份数</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 218px;">剩余份数</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 318px;">限制</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 318px;">APP内/外</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 318px;">图片链接</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 170px;">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $val):?>
                    <tr class="gradeA odd">

                        <td><?=$val['f_prize_id']+1 ?></td>
                        <td><?=$val['f_prize_name']?></td>
                        <td class="center"><?=$val['f_origin_total']?></td>
                        <td class="center"><?=$val['f_origin_total']-$val['f_num']?></td>
                        <td class="center"><?=$val['f_num']?></td>
                        <?php
                        $range_res ="";
                        $rang_result = array(0=>"积分",1=>"移动",2=>"电信",3=>"联通");
                        foreach( $val['f_range_list'] as $item){
                            $range_res = $range_res.$rang_result[$item].",";
                        }
                        $range_res = rtrim($range_res,',');


                        ?>
                        <td class="center"><?=$range_res?></td>
                        <td class="center"><?=$val['f_type']==1?"内":"外"?></td>
                        <td class="center"><a href="<?=$val['f_prize_img']?>" target="_blank"><?=$val['f_prize_img']?></a></td>

                        <td class="center <?php // if (!in_array($val['f_prize_id'], array('8','9','10','11'))){ ?>edit<?php // }?>" data-prize-id="<?=$val['f_prize_id']?>" ><a href="javascript:">修改</a></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
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
    $(".edit").click(function(){
        var prizeId = $(this).attr("data-prize-id");

        layer.open({
            title: "修改奖品信息",
            type: 2,
            area: ['550px', '750px'],
            fix: false, //不固定
            maxmin: true,
            content: '/gsk/index.php/gsk_prize/prize_change?prizeId='+prizeId
        });
    });
</script>