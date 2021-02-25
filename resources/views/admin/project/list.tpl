<{extends file="admin/extends/list.block.tpl"}>

<{block "title"}>项目<{/block}>

<{block "name"}>project<{/block}>

<{block "filter"}>
<{include file="admin/project/filters.inc.tpl"}>
<{/block}>

<{block "table-th-plus"}>
<th>项目名称</th>
<th>PM</th>
<th>状态</th>
<{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
<td data-from="name">{{data}}</td>
<td data-from="pm"><span class="label label-primary">{{if data}}{{data.realname}}{{/if}}</span></td>

<td data-from="project_status"><span class="{{full.style}}">{{data.title || '未知'}}</span></td>
<{/block}>

<{block "table-td-options-delete-confirm"}>您确定删除这项：{{full.username}}吗？<{/block}>
