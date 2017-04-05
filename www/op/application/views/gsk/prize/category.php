<link href="<?=base_url('assets/css/jquery.datetimepicker.css') ?>" rel="stylesheet" type="text/css">
<script src="<?=base_url('assets/js/jquery.datetimepicker.js') ?>"></script>
<script src="<?=base_url('assets/js/layer/layer.js') ?>"></script>
<div class="page-header">
    <h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>
<form action="<?php echo site_url('gsk_prize/print_record');?>" method="get">
    <div class="row">
        <div class="form-group">
            <div class="col-sm-7">
                <div class="input-group">
                    <span class="input-group-addon">时间</span>
                    <div class="input-append">
                        <input size="16" name="start_time" id="start" type="text" value="" readonly style="height: 30px; width: 250px">
                    </div>
                    <span class="input-group-addon">至</span>
                    <div class="input-append">
                        <input size="16" name="end_time" id="end" type="text" value="" readonly style="height: 30px; width: 250px">
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
            <div class="col-sm-3">
                <div class="input-group">
                    <span class="input-group-addon">奖品类型</span>
                    <select name="prize_type" width="150" id="status" class="form-control" >
                        <option value="14">任意类型</option>
                        <option value="0">要加油哦</option>
                        <option value="1">阳光普照</option>
                        <option value="2">100积分</option>
                        <option value="3">20M流量</option>
                        <option value="4">30M流量</option>
                        <option value="5">50M流量</option>
                        <option value="6">70M流量</option>
                        <option value="7">100M流量</option>
                        <option value="8">50元京东卡</option>
                        <option value="9">100元京东卡</option>
                        <option value="10">300元京东卡</option>
                        <option value="11">千元机票</option>
                        <option value="12">50M流量包APP外</option>
                        <option value="13">70M流量包APP外</option>
                    </select>
                </div>
            </div>

            <button type="submit" name="btn_query" class="btn btn-danger pull-right" style="margin-right: 10px; width: 120px">查 询</button>
        </div>
        <div class="form-group">
            <div class="col-sm-2">
                <div class="input-group">
                    <span class="input-group-addon" id="news-addon1">用户id</span>
                    <input type="text" class="form-control" name="f_uin" aria-describedby="news-addon1"
                           value="<?=isset($data['filter']['f_uin'])?$data['filter']['f_uin']:''?>">
                </div>
            </div>
            <div class="col-sm-2" >
                <div class="input-group">
                    <span class="input-group-addon" id="news-addon2">用户昵称</span>
                    <input type="text" class="form-control" name="f_name" aria-describedby="news-addon2"
                           value="<?=isset($data['filter']['f_name'])?$data['filter']['f_name']:''?>">
                </div>
            </div>
            <div class="col-sm-2" >
                <div class="input-group">
                    <span class="input-group-addon" id="news-addon4">手机号</span>
                    <input type="text" class="form-control" name="f_phone" aria-describedby="news-addon4"
                           value="<?=isset($data['filter']['f_phone'])?$data['filter']['f_phone']:''?>">
                </div>
            </div>
            <button type="submit"  name="btn_export" class="btn btn-danger pull-right" style=" margin-right: 10px;  width: 120px">导出Excel</button>
        </div>
    </div>
</form>

<div id=""content-wrapper>
    <div class="table-primary">
        <div role="grid" id="jq-datatables-example_wrapper" class="dataTables_wrapper form-inline no-footer">

            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq-datatables-example" aria-describedby="jq-datatables-example_info">
                <thead>
                <tr role="row">
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 218px;">用户ID</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 318px;">用户名称</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 218px;">用户手机号</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 318px;">奖品</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 218px;">对应积分</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 170px;">数量</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 318px;">领取时间</th>
                    <th tabindex="0" rowspan="1" colspan="1" style="width: 318px;">获奖原因</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list['data'] as $val):?>
                    <tr class="gradeA odd">
                        <td class="center"><?=$val['f_uin']?></td>
                        <td class="center"><?=$val['f_name']?></td>
                        <td class="center"><?=$val['f_phone']?></td>
                        <td class="center"><?=$val['f_prize_desc']?></td>
                        <td class="center"><?=$val['f_integral']?></td>
                        <td class="center"><?=1?></td>
                        <td class="center"><?=date("Y-m-d",$val['f_create_time']/1000)?></td>
                        <td class="center"><?=$val['f_reason']?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <div class="table-footer clearfix">
                <div class="DT-label">
                    <div class="dataTables_info" id="jq-datatables-example_info" role="" aria-live="polite" aria-relevant="all">共（<?=$list['page']['max_count']?>）条</div>
                </div>
                <div class="DT-pagination">
                    <div class="dataTables_paginate paging_simple_numbers" id="jq-datatables-example_paginate">
                        <ul class="pagination">
                            <?=paginations($list['page']['max_count'],$list['page']['cur_page'], $list['page']['rows_page'], $offset = 2,$url)?>
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