<div class="tab-content">
	<div class="tab-pane active" id="form-1">
		<div class="form-group">
			<label class="col-md-3 control-label" for="username">用户名</label>
			<div class="col-md-9">
				<input type="text" id="username" name="username" class="form-control" placeholder="请输入用户名" value="<{$_data.username}>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" for="password">密码</label>
			<div class="col-md-9">
				<input type="password" id="password" name="password" class="form-control" placeholder="请输入密码" value="">
				<span class="help-block hidden" name="no-password">无需修改，可不填写</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" for="password_confirmation">密码确认</label>
			<div class="col-md-9">
				<input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="请确认密码" value="">
				<span class="help-block hidden" name="no-password">无需修改，可不填写</span>
			</div>
		</div>
		<{if $_permissionTable->checkUserRole(['super'])}>
		<div class="form-group">
			<label class="col-md-3 control-label" for="role_ids">用户组</label>
			<div class="col-md-9">
				<select id="role_ids" name="role_ids[]" class="form-control tree-model" value="<{if !empty($_data)}><{$_data->roles->modelKeys()|implode:','}><{else}><{$_role}><{/if}>" data-model="admin/role" data-text="{{display_name}}({{name}})" data-placeholder="请输入用户组" multiple="multiple"></select>
			</div>
		</div>
		<{else}>
		<input type="hidden" name="role_ids[]" value="<{$_role}>">
		<{/if}>
		<div class="form-group">
			<label class="col-md-3 control-label" for="post">岗位</label>
			<div class="col-md-9">
				<select id="post" name="post" class="form-control">
					<{foreach catalog_search('fields.post', 'children') as $v}>
					<option value="<{$v.id}>" <{if !empty($_data.post) && $_data.post.id == $v.id}>selected="selected"<{/if}>><{$v.title}></option>
					<{/foreach}>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" for="realname">真实姓名</label>
			<div class="col-md-9">
				<input type="text" id="realname" name="realname" class="form-control" placeholder="请输入..." value="<{$_data.realname}>">
			</div>
		</div>
		<input type="hidden" name="gender" value="<{catalog_search('fields.gender.male', 'id')}>">
		<div class="form-group">
			<label class="col-md-3 control-label" for="phone">手机</label>
			<div class="col-md-9">
				<input type="text" id="phone" name="phone" class="form-control" placeholder="请输入..." value="<{$_data.phone}>">
				<span class="help-block">支持国内手机，如：13912345678</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" for="email">Email</label>
			<div class="col-md-9">
				<input type="email" id="email" name="email" class="form-control" placeholder="请输入Email" value="<{$_data.email}>">
			</div>
		</div>
	</div>
</div>
<div class="form-group form-actions">
	<div class="col-md-9 col-md-offset-3">
		<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> 提交</button>
	</div>
</div>
