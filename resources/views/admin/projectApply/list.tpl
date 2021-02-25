<{extends file="admin/extends/list.block.tpl"}>

<{block "title"}>我的消息<{/block}>

<{block "name"}>project-apply<{/block}>

<{block "filter"}>
<{include file="admin/projectApply/filters.inc.tpl"}>
<{/block}>

<{block "table-th-plus"}>
<th>消息</th>
<th>日期</th>
<th>状态</th>
<{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
<td data-from="message" data-orderable="false">{{data}}</td>
<td data-from="dates" data-orderable="false">
    {{each data as v k}}
    <span class="label label-primary">{{v}}</span>
    {{/each}}
</td>
    <td data-from="apply_status" data-orderable="false">{{if data}}<span class="{{full.style}}">{{data.title}}</span>{{/if}}</td>
    <{/block}>
<{block "table-td-options-after"}>
    <a href="<{''|url}>/admin/project-apply/{{full.id}}" title="查看" data-toggle="modal"
       data-target="#infoModal" class="btn btn-xs btn-success"><i
                class="fa fa-info"></i> 查看</a>

    <div class="btn-group" role="group">
        <div class="btn-group  btn-group-xs" role="group">
            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"  style="border-radius: 0px">
                审核 <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="<{url('admin/project-apply')}>/{{full.id}}?status=1"
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
                       onclick="setFormUrl({{full.id}})"
                    >拒绝</a></li>
            </ul>
        </div>
    </div>

<{/block}>
<{block "table-th-timestamps-created_at"}><th>申请时间</th><{/block}>

<{block "table-td-options-edit"}><{/block}>
<{block "table-td-options-delete"}><{/block}>
<{block "table-th-timestamps-updated_at"}><{/block}>
<{block "table-td-timestamps-updated_at"}><{/block}>
<{block "table-tools-create"}><{/block}>
<{block "table-td-options-delete-confirm"}>您确定删除这项：{{full.username}}吗？<{/block}>

<{block "body-plus"}>
    <input type="hidden" name="status" id="apply_status" value="1">

    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="height: 250px;overflow: scroll;">

            </div>
        </div>
    </div>
    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="height: 250px;overflow: scroll;">

            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="height: 300px;overflow: scroll;">
                <div class="modal-header">
                    <button type="button" class="close" id="closeLog" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
<script>
    (function($){
        $(document).on('hidden.bs.modal', '#infoModal', function(){
            $(this).removeData("bs.modal");
            $(this).find('.modal-content').children().remove();
        });
        $(document).on('hidden.bs.modal', '#infoModal', function(){
            $(this).removeData("bs.modal");
            $(this).find('.modal-content').children().remove();
        });

        $('#formReject').query(function (resp) {
            if (resp.result == "success") {
                $('#rejectModal').modal('hide');
                $('#reload').trigger('click');
            } else {
                alert(resp.message.content);
            }
        }, 3);
    })(jQuery);

    function setFormUrl(id){
        jQuery('form', '#rejectModal').attr('action', '<{url('admin/project-apply')}>/' + id);
    }
</script>
    <{/block}>
