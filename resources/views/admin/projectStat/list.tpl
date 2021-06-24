<{extends file="admin/extends/list.block.tpl"}>

<{block "title"}>项目统计<{/block}>

<{block "name"}>project-stat<{/block}>

<{block "filter"}>
<{include file="admin/projectStat/filters.inc.tpl"}>
<{/block}>
<{block "table-tools-after"}><!-- <a class="btn btn-primary" href="<{url('admin/project-stat')}>/afresh"
                                                                                    method="POST"
                                                                                    confirm="确认重新统计吗？"
                                                                                    data-toggle="tooltip">重新统计</a>--><{/block}>

<{block "table-th-plus"}>
<th>项目名称</th>
<th>负责人</th>
<th>总成本<br><span id="sum_cost" class="text-danger" style="font-size: 12px"></span></th>
<th>每日<br><span id="sum_day_cost" class="text-danger" style="font-size: 12px"></span></th>
<th>状态</th>
<{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
<td data-from="project" data-orderable="false">{{if data}}{{data.name}}{{/if}}</td>
<td data-from="project" data-orderable="false">{{if data.pm}}{{data.pm.realname}}{{/if}}</td>
<td data-from="cost" data-orderable="false">{{data}}</td>
<td data-from="day_cost" data-orderable="false">{{data}}</td>
<td data-from="project" data-orderable="false">
    {{if data}}
    <span class="{{data.style}}"> {{data.project_status.title}}</span>
    {{/if}}
</td>
    <{/block}>
<{block "table-td-options-after"}>
    <a href="<{''|url}>/admin/project-member-stat?f[pid]={{full.pid}}" title="明细" class="btn btn-xs btn-success"><i
                class="fa fa-list"></i> 明细</a>
<{/block}>

<{block "body-scripts"}>
    <script>
        (function($){
            $('#datatable').on('datatable.header', function (event, head, data) {
                if(data[0]){
                    $('#sum_cost', head).html("总额：" + data[0].sum_cost);
                    $('#sum_day_cost', head).html("总额：" + data[0].sum_day_cost);
                }
            });
        })(jQuery);
    </script>
<{/block}>
<{block "table-th-id"}><{/block}>
<{block "table-td-id"}><{/block}>
<{block "table-tools-dropdown-operate"}><{/block}>
<{block "table-td-options-edit"}><{/block}>
<{block "table-td-options-delete"}><{/block}>
<{block "table-th-timestamps"}><{/block}>
<{block "table-td-timestamps"}><{/block}>
<{block "table-tools-create"}><{/block}>
<{block "table-td-options-delete-confirm"}>您确定删除这项：{{full.username}}吗？<{/block}>
