<{extends file="admin/extends/list.block.tpl"}>

<{block "title"}>用户<{/block}>

<{block "name"}>member<{/block}>
<{block "header"}>
	<!-- Form Header -->
	<ul class="breadcrumb breadcrumb-top">
		<li><a href="<{''|url}>/<{block "namespace"}>admin<{/block}>"><{config('settings.title')}></a></li>
		<li><a href="<{''|url}>/<{block "namespace"}>admin<{/block}>/member?q[ofRole]=<{$_queries['ofRole'] }>"><{block
				"title"}><{/block}>管理</a></li>
		<li class="active">会员列表</li>
	</ul>
<!-- END Form Header -->
	<{/block}>
<{block "head-scripts-after"}>
<script src="<{'static/js/emojione.js'|url}>"></script>
<{/block}>

<{block "filter"}>
<{include file="admin/member/filters.inc.tpl"}>
<{/block}>

<{block "table-th-plus"}>
<th>用户名</th>
<th>姓名</th>
<th>岗位</th>
<th>手机</th>
<th>用户组</th>
<{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
<td data-from="username">{{data}}</td>
<td data-from="realname">{{data}}</td>
<td data-from="post">{{if data}}<span class="label label-danger">{{data.title}}</span>{{/if}}</td>
<td data-from="phone">{{data}}</td>
<td data-from="roles" data-orderable="false">
{{each data as v k}}
<span class="label label-info">{{v.display_name}}</span>
{{/each}}
</td>
<{/block}>
<{block "table-td-options-edit"}>
	<{if $_permissionTable->checkMethodPermission('edit')}>
	<a href="<{''|url}>/admin/member/{{full.id}}/edit?role=<{$_queries['ofRole']}>" data-toggle="tooltip" title="编辑" class="btn btn-xs
	btn-default"><i class="fa fa-pencil"></i></a>
	<{/if}>
	<{/block}>
<{block "table-tools-create"}>
	<{if $_permissionTable->checkMethodPermission('create')}>
	<a class="btn btn-success btn-sm btn-alt" data-toggle="tooltip" title="新建<{block 'title'}><{/block}>" href="<{''|url}>/<{block "namespace"}>admin<{/block}>/member/create?role=<{$_queries['ofRole'] }>" data-shortcuts="ctrl+n"><i class="fa fa-plus animated pulse infinite"></i></a>
	<{/if}>
	<{/block}>
<{block "table-td-options-delete-confirm"}>您确定删除这项：{{full.username}}吗？<{/block}>

