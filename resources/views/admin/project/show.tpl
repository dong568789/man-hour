<link rel="stylesheet" href="<{'js/calendar/index.css'|static}>">
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
        <div style="background-color:#fff">
            <div class="items-scroll">
            </div>
            <div class=calendar>
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
        <div class="form-group col-sm-4 pull-right text-right">
            <button type="submit" id="apply-btn" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i>
                提交</button>
            &nbsp; &nbsp;
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script>
var selectDate = [];
</script>
<script src="<{'js/calendar/command.js'|static nofilter}>"></script>

<script>
    (function ($) {
        var id = "<{$_data.id}>";
        $("#apply-btn").click(function () {
            if (selectDate.length <= 0){
                alert("请选择申报的工时");
                return;
            }
            LP.http.jQueryAjax.post("<{url('admin/project-apply')}>", {dates : selectDate, pid: id}).then(function
                (res) {
                if (res.result == 'success'){
                    LP.tip.toast_interface({content:"操作成功"}).then(function () {
                        $('#projectModal').modal('hide');
                    });
                }
            }, function (err) {
                LP.tip.toast_interface({content: err.$json.message});
            });
        });
    })(jQuery);
</script>
