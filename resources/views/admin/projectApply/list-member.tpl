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
    <td data-from="apply_status" data-orderable="false">{{if data}} <span class="{{full.style}}">{{data.title}}</span>{{/if}}</td>
    <{/block}>
<{block "table-td-options-after"}>
    <a href="<{''|url}>/admin/project-apply/{{full.id}}" title="查看" data-toggle="modal"
       data-target="#infoModal" class="btn btn-xs btn-success"><i
                class="fa fa-info"></i> 查看</a>
    <{/block}>
<{block "table-th-timestamps-created_at"}><th>申请时间</th><{/block}>

<{block "table-td-options-edit"}><{/block}>
<{block "table-td-options-delete"}><{/block}>
<{block "table-th-timestamps-updated_at"}><{/block}>
<{block "table-td-timestamps-updated_at"}><{/block}>
<{block "table-tools-create"}><{/block}>
<{block "table-td-options-delete-confirm"}>您确定删除这项：{{full.username}}吗？<{/block}>


<{block "body-plus"}>
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
    <{/block}>
