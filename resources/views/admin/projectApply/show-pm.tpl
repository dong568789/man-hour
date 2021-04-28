<{extends file="admin/projectApply/date.inc.tpl"}>

<{if $_data.apply_status.id == catalog_search('status.apply_status.applying', 'id')}>
    <{block "hour-audit-widget"}>
    <div class="form-group col-sm-4 pull-right text-right">
        <div class="btn-group" role="group">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"  style="border-radius: 0px">
                    审核 <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="<{url('admin/project-apply')}>/<{$_data.id}>"
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
                           onclick="setFormUrl(<{$_data.id}>)"
                        >拒绝</a></li>
                </ul>
            </div>
        </div>
    </div>
    <input type="hidden" name="status" id="apply_status" value="1">
    <div class="clearfix"></div>
<{/block}>
    <{block "apply-modal-plus"}>
    <div class="modal fade" id="rejectModal" tabindex="-2" role="dialog" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="height: 300px;overflow: scroll;margin: 50px;">
                <div class="modal-header">
                    <button type="button" class="close" id="closeLog" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">拒绝理由3</h4>
                </div>
                <div class="modal-body">
                    <form action="" method="PUT" class="form-horizontal form-bordered" id="formReject">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="status" value="0">
                        <{csrf_field() nofilter}>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="mark">备注:</label>
                            <div class="col-md-9">
                                <textarea name="mark" id="mark" class="form-control" id="" cols="10"
                                          rows="5"></textarea>
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
    <{/block}>
    <{block "apply-plus"}>
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

            $(document).on('shown.bs.modal', '#rejectModal', function(){
                $('.modal-backdrop').eq(1).remove();
            });

            $(document).on('hidden.bs.modal', '#rejectModal', function(){
                $(this).removeData("bs.modal");
                $(this).find('.modal-content').children().remove();
            });

            $(document).on('click', '#closeLog', function(){
                $('#rejectModal').removeData("bs.modal");
                $('#rejectModal').find('#mark').val("");
                $('#rejectModal').hide();
            });

            //审核通过
            $('a[method]:not([method="delete"])', 'body').query();
        })(jQuery);

        function setFormUrl(id){
            jQuery('form', '#rejectModal').attr('action', '<{url('admin/project-apply')}>/' + id);
        }
    </script>
    <{/block}>
<{else}>
    <{block "hour-apply-widget"}>
    <div class="form-group date_Dates">
        <div class="col-md-12">
            <textarea name="" placeholder="备注" disabled id="" class="form-control" cols="30" rows="5"><{$_data.mark}></textarea>
        </div>
    </div>
    <{/block}>
<{/if}>
<{block "apply-head-plus"}>
    <script>
        var readonly = true;
        var allDate = JSON.parse('<{$_allMonth nofilter}>');
    </script>
<{/block}>
