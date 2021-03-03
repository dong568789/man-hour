<{block "apply-head-plus"}><{/block}>
<link rel="stylesheet" href="<{'js/calendar/index.css'|static}>">
<script src="<{'js/calendar/command.js'|static nofilter}>"></script>
<style>
    @media screen and (max-width: 720px) {
        .dateDay,.date_Dates {
            padding: 0px 5%;
        }
    }
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="exampleModalLabel">工时申报</h4>
</div>
<div class="modal-body">
    <div class="row">
            <div style="background-color:#fff;">
                <div class="items-scroll"></div>
                <div class="calendar">
                    <div class="status-act" style="text-align: center;padding: 10px 0px">
                        <a href="javascript:void(0);" class="btn btn-xs btn-default">已申报</a>
                        <a href="javascript:void(0);" class="btn btn-xs btn-warning">待审核</a>
                        <a href="javascript:void(0);" class="btn btn-xs btn-info">待申报</a>
                        <{block "hour-audit-widget"}><{/block}>
                    </div>
                    <div class="dateDay">
                        <div class="td">周日</div>
                        <div class="td">周一</div>
                        <div class="td">周二</div>
                        <div class="td">周三</div>
                        <div class="td">周四</div>
                        <div class="td">周五</div>
                        <div class="td">周六</div>
                    </div>
                    <div class="d_content1">

                    </div>
                    <div class="d_content2">

                    </div>
                </div>
            </div>
            <form action="<{url('admin/project-apply')}>" method="POST"
                  id="formApply">
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="pid" value="<{$_data.id}>">
                <input type="hidden" name="dates" id="dates" value="">
                <{csrf_field() nofilter}>
                <{block "hour-apply-widget"}><{/block}>
            </form>
    </div>
</div>
<{block "apply-modal-plus"}><{/block}>
<script>
    (function ($) {
        var  url = "<{url('admin/project/date', [$_data.id])}>?type=<{$_type}>";
        ajaxData(url);
    })(jQuery);
</script>
<{block "apply-plus"}><{/block}>
