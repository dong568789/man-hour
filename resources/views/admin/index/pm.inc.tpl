
<div class="row">
    <div class="col-sm-8 col-md-8">
        <div class="block">
            <div class="block-title">
                <h2> <strong>项目列表</strong> </h2>
            </div>
            <table class="table table-borderless table-striped table-vcenter table-bordered">
                <thead>
                <tr>
                    <th class="text-center">项目名称</th>
                    <th class="text-center">负责人</th>
                    <th class="text-center">状态</th>
                    <th class="text-center">创建时间</th>
                    <th class="text-center">操作</th>
                </tr>
                </thead>
                <tbody>
                <{foreach $_projects as $project}>
                    <tr>
                        <td class="text-center"><strong><{$project.name}></strong></td>
                        <td class="text-center"><{$project.pm.realname}></td>
                        <td class="text-center">
                            <span class="<{$project.style}>"><{$project.project_status.title}></span>
                        </td>
                        <td class="text-center"><{$project.created_at}></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-xs">
                                <a href="<{url('admin/project', [$project.id])}>/edit" class="btn
                                btn-success">编辑</a>
                            </div>
                        </td>
                    </tr>
                    <{/foreach}>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm4 col-md-4">
        <div class="block">
            <div class="block-title">
                <h2> <strong>我的消息</strong> </h2>
            </div>
            <table class="table table-bordered table-striped table-vcenter">
                <tbody>
                <{if !empty($_messages)}>
                    <{foreach $_messages as $message}>
                    <tr>
                        <td class="text-center">
                            <{$message.message}>
                        </td>
                        <td class="text-center">
                            <a data-toggle="modal"
                               data-target="#infoModal"
                               href="<{url('admin/project-apply', [$message.id])}>"
                               class="btn btn-xs btn-success"> 查看</a>
                            <div class="btn-group" role="group">
                                <div class="btn-group  btn-group-xs" role="group">
                                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false"  style="border-radius: 0px">
                                        审核 <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<{url('admin/project-apply')}>/<{$message.id}>"
                                               method="PUT"
                                               confirm="确认审核通过吗？"
                                               data-toggle="tooltip"
                                               selector="#apply_status"
                                               title="同意"
                                            >通过</a></li>
                                        <li><a href="javascript:void(0);"
                                               title="拒绝"
                                               data-toggle="modal"
                                               data-target="#rejectModal"
                                               onclick="setFormUrl(<{$message.id}>)"
                                            >拒绝</a></li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <{/foreach}>
                    <{else}>
                    <tr><td class="text-center"> 暂无信息 </td></tr>
                    <{/if}>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="status" id="apply_status" value="1">
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="height: 300px;overflow: scroll;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">拒绝理由</h4>
            </div>
            <div class="modal-body">
                <form action="" method="PUT" class="form-horizontal form-bordered" id="formReject">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="status" value="0">
                    <{csrf_field() nofilter}>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="mark">备注:</label>
                        <div class="col-md-9">
                            <textarea name="mark" class="form-control" id="" cols="10" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-md-9 col-md-offset-3">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> 提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="height: 250px;overflow: scroll;">

        </div>
    </div>
</div>
<script>
    (function($){

        $('#formReject').query(function (resp) {
            if (resp.result == "success") {
                $('#rejectModal').modal('hide');
                window.location.reload();
            } else {
                alert(resp.message.content);
            }
        }, 3);
        $(document).on('hidden.bs.modal', '#infoModal', function(){
            $(this).removeData("bs.modal");
            $(this).find('.modal-content').children().remove();
        });
        //审核通过
        $('a[method]:not([method="delete"])', 'body').query();
    })(jQuery);

    function applyMember(flag, t) {
        var id = jQuery(t).attr('ref');
        if (flag === 0) {
            var html = "<p>dfdf</p>"
            LP.tip.diy_interface({title: "拒绝理由", content:html}, null, {timeout: 2000})
        }
        LP.http.jQueryAjax.put("<{url('admin/project-apply')}>/" + id, {status : flag}).then(function (res) {
            if (res.result == 'success'){
                LP.tip.toast_interface({content:"操作成功"});
                jQuery(t).parents('tr').remove();
            }
        }, function (err) {
            LP.tip.toast_interface({content: err.$json.message});
        });
    }

    function setFormUrl(id){
        jQuery('form', '#rejectModal').attr('action', '<{url('admin/project-apply')}>/' + id);
    }
</script>
