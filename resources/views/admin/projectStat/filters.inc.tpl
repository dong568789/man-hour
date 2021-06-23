<!-- Form Content -->
<form action="<{'admin'|url nofilter}>/<{block "name"}><{/block}>/" method="GET" class="form-bordered form-horizontal">
	<div class="form-group col-sm-3">
		<label class="col-md-3 control-label" for="name">项目</label>
		<div class="col-md-9">
			<select type="text" id="pid" name="f[pid]" class="form-control select-model"
					data-model="admin/project"
					data-text="{{name}}" data-placeholder="请输入关键词..." value="<{$_filters.pid.eq}>"></select>
		</div>
	</div>
	<{if $_permissionTable->checkUserRole(['super'])}>
	<div class="form-group col-sm-3">
		<label class="col-md-3 control-label" for="pm_uid">PM</label>
		<div class="col-md-9">
			<select type="text" id="pm_uid" name="f[pm_uid]" class="form-control select-model"
					data-model="admin/member"
					data-params='{"q":{"ofRole": <{\App\Role::searchRole("administrator.pm", "id")}>}}'
					data-text="{{username}}({{post.title}})" data-placeholder="请输入关键词..." value="<{$_filters.pm_uid.eq}>"></select>
		</div>
	</div>
	<{/if}>
	<div class="form-group col-sm-4 pull-right text-right">
		<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> 检索</button> &nbsp; &nbsp;
	</div>
	<div class="clearfix"></div>
</form>
<!-- END Form Content -->
<script>
(function($){
	$().ready(function(){
		$('#created_at-min').on('focus',function(){
			WdatePicker({
				skin: 'twoer',
				isShowClear:true,
				readOnly:true,
				dateFmt:'yyyy-MM-dd',
				isShowOthers:false,
				maxDate: '#F{$dp.$D(\'created_at-max\')}'
			});
		});
		$('#created_at-max').on('focus',function(){
			WdatePicker({
				skin: 'twoer',
				isShowClear:true,
				readOnly:true,
				dateFmt:'yyyy-MM-dd 23:59:59',
				isShowOthers:false,
				minDate: '#F{$dp.$D(\'created_at-min\')}'
			});
		});
	});
})(jQuery);
</script>
