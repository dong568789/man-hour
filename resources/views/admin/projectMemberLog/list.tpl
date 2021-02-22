<{extends file="admin/extends/list.block.tpl"}>

<{block "title"}>每日明细<{/block}>

<{block "name"}>project-member-log<{/block}>

<{block "filter"}>
<{include file="admin/projectMemberLog/filters.inc.tpl"}>
<{/block}>

<{block "table-th-plus"}>
<th>日期</th>
<th>项目名称</th>
<th>成员</th>
<th>每日</th>
<{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
<td data-from="date">{{data}}</td>
    <td data-from="project" data-orderable="false">{{if data}}{{data.name}}{{/if}}</td>
<td data-from="member" data-orderable="false">{{if data}}<span class="label label-danger">{{data.realname}}</span>{{/if}}</td>
<td data-from="day_cost">{{data}}</td>
<{/block}>
<{block "table-th-options"}><{/block}>
<{block "table-td-options"}><{/block}>
<{block "table-td-options-edit"}><{/block}>
<{block "table-td-options-delete"}><{/block}>
<{block "table-th-timestamps-updated_at"}><{/block}>
<{block "table-td-timestamps-updated_at"}><{/block}>
<{block "table-tools-create"}><{/block}>
<{block "table-td-options-delete-confirm"}>您确定删除这项：{{full.username}}吗？<{/block}>
