<{extends file="admin/extends/list.block.tpl"}>

<{block "title"}>工时统计<{/block}>

<{block "name"}>project-member-stat<{/block}>

<{block "filter"}>
<{include file="admin/projectMemberStat/filters.inc.tpl"}>
<{/block}>

<{block "table-th-plus"}>
<th>项目名称</th>
<th>成员</th>
<th>总工时(天)<br><span id="sum_day" class="text-danger" style="font-size: 12px"></span></th>
<{/block}>

<!-- DataTable的Block -->
<{block "table-td-plus"}>
<td data-from="project" data-orderable="false">{{if data}}{{data.name}}{{/if}}</td>
<td data-from="member" data-orderable="false">{{if data}}<span class="label label-danger">{{data.realname}}</span>{{/if}}</td>
<td data-from="hour">{{data}}</td>
<{/block}>


<{block "body-scripts"}>
    <script>
        (function($){
            $('#datatable').on('datatable.header', function (event, head, data) {
                if(data[0]){
                    $('#sum_day', head).html("合计：" + data[0].sum_day);
                }
            });
        })(jQuery);
    </script>
    <{/block}>

<{block "table-td-options-after"}>
    <a href="<{''|url}>/admin/project-member?f[uid]={{full.uid}}&f[pid]={{full.pid}}&f[date][min]=<{$_filters.date.min}>&f[date][max]=<{$_filters.date.max}>"
       title="明细"
       class="btn
    btn-xs btn-success"><i
                class="fa fa-list"></i> 明细</a>
    <{/block}>

<{block "table-th-id"}><{/block}>
<{block "table-td-id"}><{/block}>
<{block "table-tools-dropdown-operate"}><{/block}>

<{block "table-td-options-edit"}><{/block}>
<{block "table-td-options-delete"}><{/block}>
<{block "table-tools-dropdown-operate"}><{/block}>
<{block "table-th-timestamps"}><{/block}>
<{block "table-td-timestamps"}><{/block}>
