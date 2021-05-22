<{extends file="admin/extends/list.block.tpl"}>

<{block "title"}>每日明细<{/block}>

<{block "name"}>project-member<{/block}>

<{block "filter"}>
<{include file="admin/projectMember/filters.inc.tpl"}>
<{/block}>
<{block "order"}>[[1, "desc"]]<{/block}>
<{block "table-th-plus"}>
<th>日期</th>
<th>项目名称</th>
<{if !$_permissionTable->checkUserRole(['project-member'])}>
<th>成员</th>
<{/if}>
<{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
<td data-from="date">{{data}}</td>
    <td data-from="project" data-orderable="false">{{if data}}{{data.name}}{{/if}}</td>
    <{if !$_permissionTable->checkUserRole(['project-member'])}>
<td data-from="member" data-orderable="false">{{if data}}{{data.realname}}{{/if}}</td>
    <{/if}>
<{/block}>

<{block "table-td-options-edit"}><{/block}>
<{if !$_permissionTable->checkUserRole(['super'])}>
    <{block "table-th-options"}><{/block}>
    <{block "table-td-options"}><{/block}>
<{/if}>
<{if !$_permissionTable->checkUserRole(['super'])}>
<{block "table-tools-dropdown-operate"}><{/block}>
    <{/if}>
<{block "table-th-timestamps-updated_at"}><{/block}>
<{block "table-td-timestamps-updated_at"}><{/block}>
<{block "table-tools-create"}><{/block}>
<{block "table-td-options-delete-confirm"}>您确定删除这项：{{full.username}}吗？<{/block}>
