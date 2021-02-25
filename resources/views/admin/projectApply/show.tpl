<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="exampleModalLabel">审核信息</h4>
</div>
<div class="modal-body">
    <div class="row">
        <table class="table table-bordered table-striped table-vcenter">
            <tbody>
            <tr>
                <td class="text-right">日期</td>
                <td id="apply_date">
                    <{foreach $_data.dates as $date}>
                    <span class="label label-primary"><{$date}></span>
                    <{/foreach}>
                </td>
            </tr
            <tr>
                <td class="text-right">状态</td>
                <td id="apply_status"><span class="<{$_data.style}>"><{$_data.apply_status.title}></span></td>
            </tr>
            <tr>
                <td class="text-right">备注</td>
                <td id="mark"><{$_data.mark}></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
