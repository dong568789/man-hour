<{extends file="admin/projectApply/date.inc.tpl"}>

<{block "hour-apply-widget"}>

<div class="form-group date_Dates">
    <div class="col-md-12">
        <textarea name="" placeholder="备注" disabled id="" class="form-control" cols="30" rows="5"><{$_data.mark}></textarea>
    </div>
</div>
<{/block}>

<{block "apply-head-plus"}>
    <script>
        var readonly = true;
        var allDate = JSON.parse('<{$_allMonth nofilter}>');
    </script>
<{/block}>
