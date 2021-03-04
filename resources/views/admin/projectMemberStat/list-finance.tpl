<{extends file="admin/extends/list.block.tpl"}>

<{block "title"}>工时统计<{/block}>

<{block "name"}>project-member-stat<{/block}>

<{block "filter"}>
<{include file="admin/projectMemberStat/filters.inc.tpl"}>
<{/block}>

<{block "table-th-plus"}>
<th>项目名称</th>
<th>成员</th>
<th>每日成本</th>
<th>总工时(天)<br><span id="sum_hour" class="text-danger" style="font-size: 12px"></span></th>
<th>总成本<br><span id="sum_cost" class="text-danger" style="font-size: 12px"></span></th>
<{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
<td data-from="project" data-orderable="false">{{if data}}{{data.name}}{{/if}}</td>
<td data-from="member" data-orderable="false">{{if data}}<span class="label label-danger">{{data.realname}}</span>{{/if}}</td>
<td data-from="member" data-orderable="false">{{data.cost}}</td>
<td data-from="hour">{{data}}</td>
<td data-from="cost">{{data}}</td>
<{/block}>

<{block "body-scripts"}>
    <script>
        (function($){
            $('#datatable').on('datatable.header', function (event, head, data) {
                if(data[0]){
                    $('#sum_cost', head).html("总额：" + data[0].sum_cost);
                    $('#sum_hour', head).html("总计：" + data[0].sum_hour);
                }
            });
        })(jQuery);
    </script>
<{/block}>

<{block "table-td-options-after"}>
    <a href="<{''|url}>/admin/project-member?f[uid]={{full.uid}}&f[pid]={{full.pid}}" title="明细" class="btn btn-xs btn-success"><i
                class="fa fa-list"></i> 明细</a>
    <{/block}>

<{block "table-td-options-edit"}><{/block}>
<{block "table-td-options-delete"}><{/block}>
<{block "table-tools-create"}><{/block}>
<{block "table-th-timestamps-updated_at"}><{/block}>
<{block "table-td-timestamps-updated_at"}><{/block}>
