<{extends file="admin/projectApply/date.inc.tpl"}>

<{block "hour-audit-widget"}>
<{if $_data.apply_status.id == catalog_search('status.apply_status.applying', 'id')}>
    <div class="form-group col-sm-4 pull-right text-right">
        <span>*申报日期错误，可撤销</span>&nbsp;<a class="btn btn-primary" href="<{url('admin/project-apply')
        }>/recall/<{$_data.id}>"
         method="PUT"
         confirm="确认撤销吗？"
         data-toggle="tooltip">撤销</a>

    </div>
    <div class="clearfix"></div>
<{/if}>
    <{/block}>

<{block "hour-apply-widget"}>
    <div class="form-group date_Dates">
        <div class="col-md-12">
            <textarea name="" placeholder="备注" disabled id="" class="form-control" cols="30"
                      rows="5"><{$_data.mark}></textarea>
        </div>
    </div>
    <{/block}>
<{block "apply-plus"}>
    <script>
        (function ($) {
            //审核通过
            $('a[method]:not([method="delete"])', 'body').query();
        })(jQuery);
    </script>

    <{/block}>
<{block "apply-head-plus"}>
    <script>
        var readonly = true;
        var allDate = JSON.parse('<{$_allMonth nofilter}>');
    </script>
    <{/block}>
