<div class="tab-pane" id="tab6">


</div>

                     
                      

<!-- Matter ends -->

<script>
    $("document").ready(function(){
        <?php
        if(isset($user_info['f_info']))
        {
            echo 'init_zhuchang_info('.$user_info['f_info']['f_uin'].' , '.$user_info['f_info']['f_platform'].' , '.$user_info['f_info']['f_srv'].');';
        }

        ?>
    });

    function init_zhuchang_info(uin, plt, srv)
    {
        var qualityBaseUrl = "<?=base_url('assets/img/icon/icon_')?>"

        $.ajax({

            type:"GET",

            dataType:"json",

            data:{"query_uin":uin, "query_plt":plt, 'query_srv':srv, 'query_info_type':6},
            url:"<?php echo site_url().'/laki_ajax_api/get_user_data' ?>",

            success:function(json) {
                var res = eval(json);
                var strHtml = ""
                for(var i=0; i<res.length; ++i)
                {
                    strHtml = strHtml + '<tr>';
                    strHtml = strHtml + '<td>' + get_quality_icon(qualityBaseUrl, res[i]['f_quality']) + res[i]['f_name'] + '</td>';
                    strHtml += '<td><code><strong>' + res[i]['f_level'] + '</strong></code></td>';
                    strHtml += '<td>' + res[i]['f_exp'] + '</td>';
                    strHtml += '<td>' + get_attr_desc(res[i]['f_front_type']) + ':<code>' + res[i]['f_front_value'] + '</code></td>';
                    strHtml += '<td>' + get_attr_desc(res[i]['f_mid_type']) + ':<code>' + res[i]['f_mid_value'] + '</code></td>';
                    strHtml += '<td>' + get_attr_desc(res[i]['f_back_type']) + ':<code>' + res[i]['f_back_value'] + '</code></td>';
                }
                $("#lava_zhuchang_info").html(strHtml);
            }

        });
    }

</script>