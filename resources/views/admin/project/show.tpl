<{extends file="admin/projectApply/date.inc.tpl"}>

<{block "hour-apply-widget"}>
    <div class="form-group col-sm-4 pull-right text-right">
        <a href="javascript:void(0)" id="apply-btn" class="btn btn-sm btn-primary"><i class="fa
                fa-angle-right"></i>
            提交</a>
    </div>
    <div class="clearfix"></div>
    <{/block}>
<{block "apply-head-plus"}>
    <script>
        var allDate = [];
        var readonly = false;
    </script>
<{/block}>
<{block "apply-plus"}>
<script>
    (function ($) {
        var id = "<{$_data.id}>";
        $("#apply-btn").click(function () {
            if (selectDate.length <= 0){
                alert("请选择申报的工时");
                return;
            }
            $('#dates').val(JSON.stringify(selectDate))
            $('#formApply').submit();
        });

        $('#formApply').query();
    })(jQuery);
</script>
<{/block}>
