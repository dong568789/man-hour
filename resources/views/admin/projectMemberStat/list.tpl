<{extends file="admin/extends/list.block.tpl"}>

<{block "title"}>项目明细<{/block}>

<{block "name"}>project-member-stat<{/block}>

<{block "filter"}>
<{include file="admin/projectMemberStat/filters.inc.tpl"}>
<{/block}>

<{block "table-th-plus"}>
<th>项目名称</th>
<th>成员</th>
<th>每日成本</th>
<th>总工时(天)</th>
<th>总成本</th>
<{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
<td data-from="project" data-orderable="false">{{if data}}{{data.name}}{{/if}}</td>
<td data-from="member" data-orderable="false">{{if data}}<span class="label label-danger">{{data.realname}}</span>{{/if}}</td>
<td data-from="member" data-orderable="false">{{data.cost}}</td>
<td data-from="hour">{{data}}</td>
<td data-from="cost">{{data}}</td>
<{/block}>

<{block "table-th-options"}><{/block}>
<{block "table-td-options"}><{/block}>
<{block "table-tools-create"}><{/block}>
<{block "table-th-timestamps-updated_at"}><{/block}>
<{block "table-td-timestamps-updated_at"}><{/block}>
