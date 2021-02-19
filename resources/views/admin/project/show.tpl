<link rel="stylesheet" href="<{'js/select2/select2.min.css'|static nofilter}>">
<script src="<{'js/select2/select2.min.js'|static nofilter}>"></script>
<script src="<{'js/select2/i18n/zh-CN.js'|static nofilter}>"></script>
<script src="<{'js/laravel.select.min.js'|static nofilter}>"></script>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="exampleModalLabel">项目详情</h4>
</div>
<div class="modal-body">
    <div class="widget">
        <div class="widget-advanced">
            <div class="widget-header text-center themed-background-dark-flatie">
                <h3 class="widget-content-light">
                    <a href="javascript:void(0)" class="themed-color-flatie"><{$_data.name}></a><br>
                    <small><{$_data.name}></small>
                </h3>
            </div>
            <div class="widget-main">
                <h4 class="sub-header">项目成员</h4>
                <table class="table table-borderless table-striped table-condensed table-vcenter">
                    <tbody>
                    <tr>
                        <th class="text-center">姓名</th>
                        <th class="text-center">职位</th>
                        <th class="text-center">工时(单位:天)</th>
                        <th class="text-center">状态</th>
                        <th class="text-center">操作</th>
                    </tr>
                    <{foreach $_data.members as $member}>
                    <tr>
                        <td class="text-center"><a href="javascript:void(0)"><strong><{$member.realname}>(<{$member.username}>)
                                </strong></a></td>
                        <td class="text-center"><{$member.post.title}></td>
                        <td class="text-center"><{$member.pivot.hour}></td>
                        <td class="text-center"><span class="<{$member.style}>"><{catalog_search($member.pivot.member_status, 'title')}></span></td>
                        <td class="text-center" style="width: 70px;">
                            <{if $_permissionTable->checkUserRole(['pm', 'super'])}>
                            <div class="btn-group btn-group-xs">
                                <a href="javascript:void(0)" class="btn btn-default" onclick="deleteMember(<{$member.pivot.id}>, this)"
                                   data-toggle="tooltip"
                                   title=""
                                   data-original-title="Preview">移除</a>
                            </div>
                            <{/if}>
                        </td>

                    </tr>
                    <{/foreach}>
                    </tbody>
                </table>
                <{if $_permissionTable->checkUserRole(['super', 'pm'])}>
                <div class="text-center"><button class="btn btn-danger" data-toggle="modal"
                                            data-target="#applyModal">申请成员</button></div>
                <{/if}>
            </div>
        </div>
    </div>
</div>
<script>
    function deleteMember(id, t){
        LP.http.jQueryAjax.delete('<{url('admin/project-member')}>/' + id).then(function (resp) {
            if(resp.result == 'success') jQuery(t).parents('tr').remove();
        }, function (err) {
            alert(err);
        })
    }
</script>
<div class="modal fade" id="applyModal" tabindex="-2" role="dialog" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content" style="height: 400px;overflow: scroll;margin: 50px;">
            <div class="modal-header">
                <button type="button" class="close" id="closeLog" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">申请成员</h4>
            </div>
            <div class="modal-body">
                <form action="<{url('admin/project-apply')}>" method="POST" class="form-horizontal form-bordered"
                      id="formApply">
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="pid" value="<{$_data.id}>">
                    <{csrf_field() nofilter}>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="money">成员:</label>
                        <div class="col-md-9">
                            <select type="text" id="member_ids" name="member_ids[]" class="form-control select-model"
                                    style="width:100%"
                                    data-model="admin/member"
                                    data-params='{"q":{"ofRole": <{\App\Role::searchRole("administrator.project-member", "id")}>}}'
                                    data-text="{{realname}}({{post.title}})" data-placeholder="请输入关键词..."
                                    value="" multiple="multiple"></select>
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
<script>

    (function($){
        $('#formApply').query(function(response){
            if(response.result == 'success'){
                LP.tip.toast_interface({content:"申请成功，请等待审核。"});
                $('#closeLog').trigger('click');
            }
        }, 3);

        $(document).on('click', '#closeLog', function(){
            $('#applyModal').removeData("bs.modal");
            $('#applyModal').hide();
        });
    })(jQuery);
</script>
