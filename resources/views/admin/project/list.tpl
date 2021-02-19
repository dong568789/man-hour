<{extends file="admin/extends/list.block.tpl"}>

<{block "title"}>项目<{/block}>

<{block "name"}>project<{/block}>

<{block "filter"}>
<{include file="admin/project/filters.inc.tpl"}>
<{/block}>

<{block "table-th-plus"}>
<th>项目名称</th>
<th>PM</th>
<th>项目成员</th>
<th>状态</th>
<{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
<td data-from="name">{{data}}</td>
<td data-from="pm"><span class="label label-danger">{{if data}}{{data.realname}}{{/if}}</span></td>
<td data-from="members" data-orderable="false">
{{each data as v k}}
<span class="{{v.style}}">{{v.realname}}({{v.post.title}})</span>
{{/each}}
</td>
<td data-from="project_status"><span class="label label-primary">{{data.title || '未知'}}</span></td>
<{/block}>

<{block "table-td-options-delete-confirm"}>您确定删除这项：{{full.username}}吗？<{/block}>
