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
    <{if $_permissionTable->checkUserPermission('finance.update')}>
    <th>成本(单位:元)</th>
    <{/if}>
    <th>手机</th>
    <th>用户组</th>
    <{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
    <td data-from="username">{{data}}</td>
    <td data-from="realname">{{data}}</td>
    <td data-from="post">{{if data}}<span class="label label-primary">{{data.title}}</span>{{/if}}</td>
    <{if $_permissionTable->checkUserPermission('finance.update')}>
    <td data-from="cost">{{data}}</td>
    <{/if}>
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
<{if $_permissionTable->checkUserPermission('finance.update')}>
    <{block "table-td-options-after"}>
    <button data-toggle="modal" data-target="#costModal" title="每日成本" onclick="setAdminId({{full.id}})" class="btn btn-xs btn-success">每日成本</button>
    <{/block}>
    <{/if}>
<{block "table-td-options-delete-confirm"}>您确定删除这项：{{full.username}}吗？<{/block}>

<{block "body-plus"}>
    <div class="modal fade" id="costModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closeLog" aria-label="Close"><span aria-hidden="true">&times;
        </span></button>
                    <h4 class="modal-title" id="exampleModalLabel">每日/成本</h4>
                </div>
                <div class="modal-body">
                    <form action="" method="PUT" class="form-horizontal form-bordered" id="formCost">
                        <input type="hidden" name="_method" value="PUT">
                        <{csrf_field() nofilter}>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="money">金额:</label>
                            <div class="col-md-9">
                                <input type="text" id="cost" name="cost" class="form-control" placeholder="单位：元"
                                       value="">
                            </div>
                        </div>
                        <div class="form-group form-actions">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> 提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function($){
            $(document).on('hidden.bs.modal', '#costModal', function(){
                $(this).removeData("bs.modal");
                $('#cost', this).val("");
                $('input[name="type"]').eq(0).prop("checked", true);
            });

            $(document).on('click', '#closeLog', function(){
                $('#costModal').modal('hide');
            });

            $('#formCost').query(function(response){
                if(response.result == 'success'){
                    $('#costModal').modal('hide')
                    $('#reload').trigger('click');
                }
            }, 3);

        })(jQuery);

        function setAdminId(id){
            jQuery('form', '#costModal').attr('action', '<{url('admin/member/cost')}>/' + id);
        }
    </script>
    <{/block}>
