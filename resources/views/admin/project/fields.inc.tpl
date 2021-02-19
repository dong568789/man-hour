<div class="tab-content">
	<div class="tab-pane active" id="form-1">
		<div class="form-group">
			<label class="col-md-3 control-label" for="name">项目名称</label>
			<div class="col-md-9">
				<input type="text" id="name" name="name" class="form-control" placeholder="请输入项目名称" value="<{$_data.name}>">
			</div>
		</div>
		<{if $_permissionTable->checkUserRole(['super'])}>
		<div class="form-group">
			<label class="col-md-3 control-label" for="pm_uid">PM</label>
			<div class="col-md-9">
				<select type="text" id="pm_uid" name="pm_uid" class="form-control select-model"
						data-model="admin/member"
						data-params='{"q":{"ofRole": <{\App\Role::searchRole("administrator.pm", "id")}>}}'

						data-text="{{username}}({{post.title}})" data-placeholder="请输入关键词..." value="<{$_data.pm_uid}>"></select>
			</div>
		</div>
		<{/if}>
		<div class="form-group">
			<label class="col-md-3 control-label" for="member_ids">项目成员</label>
			<div class="col-md-9">
				<select type="text" id="member_ids" name="member_ids[]" class="form-control select-model"
						data-model="admin/member"
						data-params='{"q":{"ofRole": <{\App\Role::searchRole("administrator.project-member", "id")}>}}'
						data-text="{{username}}({{post.title}})" data-placeholder="请输入关键词..." value="<{if !empty($_data)
				}><{$_data->members->modelKeys()|implode:','}><{/if}>" multiple="multiple"></select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">项目状态</label>
			<div class="col-md-9">
				<{foreach catalog_search('status.project_status', 'children') as $v}>
				<label class="radio-inline">
					<input type="radio" name="project_status" value="<{$v.id}>" <{if !empty($_data.project_status) && $_data.project_status.id	== $v.id}>checked="checked"<{/if}> > <{$v.title}>
				</label>
				<{/foreach}>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" for="cover_id">封面</label>
			<div class="col-md-9">
				<input type="hidden" id="cover_id" name="cover_id" class="form-control" value="<{$_data.cover_id|default:0}>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" for="name">项目描述</label>
			<div class="col-md-9">
				<textarea name="detail" id="detail" cols="30" rows="10" class="form-control"><{$_data.detail}></textarea>
			</div>
		</div>

	</div>
</div>
<div class="form-group form-actions">
	<div class="col-md-9 col-md-offset-3">
		<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> 提交</button>
	</div>
</div>
