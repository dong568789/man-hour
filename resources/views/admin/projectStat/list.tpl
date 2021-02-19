<{extends file="admin/extends/list.block.tpl"}>

<{block "title"}>项目统计<{/block}>

<{block "name"}>project-stat<{/block}>

<{block "filter"}>
<{include file="admin/projectStat/filters.inc.tpl"}>
<{/block}>

<{block "table-th-plus"}>
<th>项目名称</th>
<th>负责人</th>
<th>总成本</th>
<th>每日</th>
<th>状态</th>
<{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
<td data-from="project" data-orderable="false">{{if data}}{{data.name}}{{/if}}</td>
<td data-from="project" data-orderable="false">{{if data.pm}}{{data.pm.username}}{{/if}}</td>
<td data-from="cost">{{data}}</td>
<td data-from="day_cost">{{data}}</td>
<td data-from="project" data-orderable="false">
    {{if data}}
    <span class="label label-danger"> {{data.project_status.title}}</span>
    {{/if}}
</td>
    <{/block}>
<{block "table-td-options-after"}>
    <a href="<{''|url}>/admin/project-member-stat?f[pid]={{full.pid}}" title="明细" class="btn btn-xs btn-success"><i
                class="fa fa-list"></i> 明细</a>
<{/block}>

<{block "table-td-options-edit"}><{/block}>
<{block "table-td-options-delete"}><{/block}>
<{block "table-th-timestamps-updated_at"}><{/block}>
<{block "table-td-timestamps-updated_at"}><{/block}>
<{block "table-tools-create"}><{/block}>
<{block "table-td-options-delete-confirm"}>您确定删除这项：{{full.username}}吗？<{/block}>
