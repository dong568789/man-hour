
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
                        <td class="text-center"><span class="<{$message.style}>"><{$message.apply_status.title}></span></td>
                        <td class="text-center">
                            <a data-toggle="modal"
                               data-target="#infoModal"
                               href="<{url('admin/project-apply', [$message.id])}>"
                               class="btn btn-xs btn-success"> 查看</a>
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

<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="height: 500px;overflow: scroll;">

        </div>
    </div>
</div>

<script>
    (function($){
        $(document).on('hidden.bs.modal', '#infoModal', function(){
            $(this).removeData("bs.modal");
            $(this).find('.modal-content').children().remove();
        });
    })(jQuery);
</script>


