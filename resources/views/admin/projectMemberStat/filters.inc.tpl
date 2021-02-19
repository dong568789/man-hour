<!-- Form Content -->
<form action="<{'admin'|url nofilter}>/<{block "name"}><{/block}>/" method="GET" class="form-bordered form-horizontal">
	<div class="form-group col-sm-3">
		<label class="col-md-3 control-label" for="name">项目名称</label>
		<div class="col-md-9">
			<div class="input-group">
				<select type="text" id="pid" name="f[pid]" class="form-control select-model"
						data-model="admin/project"
						data-text="{{name}}" data-placeholder="请输入关键词..." value="<{$_filters.pid.eq}>"></select>
			</div>
		</div>
	</div>
	<div class="form-group col-sm-3">
		<label class="col-md-3 control-label" for="member_id">项目成员</label>
		<div class="col-md-9">
			<select type="text" id="uid" name="f[uid]" class="form-control select-model"
					data-model="admin/member"
					data-params='{"q":{"ofRole": <{\App\Role::searchRole("administrator.project-member", "id")}>}}'
					data-text="{{realname}}({{post.title}})" data-placeholder="请输入关键词..." value="<{$_filters.uid.eq}>"></select>
		</div>
	</div>
	<div class="form-group col-sm-4 pull-right text-right">
		<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> 检索</button> &nbsp; &nbsp;
	</div>
	<div class="clearfix"></div>
</form>
<!-- END Form Content -->
