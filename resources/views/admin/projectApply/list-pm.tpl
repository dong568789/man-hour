<{extends file="admin/extends/list.block.tpl"}>

<{block "title"}>申报记录<{/block}>

<{block "name"}>project-apply<{/block}>

<{block "filter"}>
<{include file="admin/projectApply/filters.inc.tpl"}>
<{/block}>

<{block "table-th-plus"}>
<th>申报人</th>
<th>申报项目</th>
<th>状态</th>
<{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
<td data-from="member" data-orderable="false">{{data.realname}}</td>
<td data-from="project" data-orderable="false">{{data.name}}</td>
    <td data-from="apply_status" data-orderable="false">{{if data}}<span class="{{full.style}}">{{data.title}}</span>{{/if}}</td>
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
    <input type="hidden" name="status" id="apply_status" value="1">
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
