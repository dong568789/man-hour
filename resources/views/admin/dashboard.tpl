<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<{include file="common/title.inc.tpl"}>
	<meta name="csrf-token" content="<{csrf_token()}>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="renderer" content="webkit">
	<{include file="common/icons.inc.tpl"}>
	<{include file="admin/common/styles.inc.tpl"}>
	<{include file="admin/common/scripts.inc.tpl"}>
	<script src="<{'js/chart/echarts/echarts.min.js'|static nofilter}>"></script>
	<script src="<{'js/chart/flot/jquery.flot.min.js'|static}>"></script>

	<style>
		.widget-advanced .widget-main{
			padding: 23px 15px 15px;
		}
	</style>
</head>
<body class="page-loading">
<{include file="admin/common/loading.inc.tpl"}>
<div id="page-container" class="sidebar-partial sidebar-visible-lg sidebar-no-animations">
	<{include file="admin/sidebar.inc.tpl"}>

	<!-- Main Container -->
	<div id="main-container">
		<{include file="admin/menubar.inc.tpl"}>

		<div id="page-content" style="min-height: 1030px;">
			<div class="content-header">
				<ul class="nav-horizontal text-center">
					<li class="active">
						<a href="<{url('admin')}>"><i class="fa fa-home"></i> 主页</a>
					</li>
					<{if $_permissionTable->checkUserRole(['super', 'finance'])}>
					<li><a href="<{'admin/member'|url}>?q[ofRole]=<{\App\Role::searchRole('administrator.pm', 'id')
						}>"><i class="fa fa-user"></i>PM管理</a></li>
					<li><a href="<{'admin/member'|url}>?q[ofRole]=<{\App\Role::searchRole('administrator.project-member','id')}>"><i class="fa fa-user"></i> 成员管理</a></li>
					<{/if}>

					<{if $_permissionTable->checkUserRole(['super', 'pm', 'finance'])}>
					<li><a href="<{'admin/project'|url}>"><i class="fa fa-users"></i> 项目管理</a></li>
					<{/if}>
					<{if $_permissionTable->checkUserRole(['super'])}>
					<li><a href="<{'admin/member'|url}>?q[ofRole]=<{\App\Role::searchRole('administrator.finance','id')}>"><i class="fa fa-user"></i> 财务管理</a></li>
					<{/if}>
					<{if $_permissionTable->checkUserRole(['super', 'finance'])}>
					<li><a href="<{'admin/project-stat'|url}>"><i class="fa fa-bar-chart"></i> 项目统计</a></li>
					<li><a href="<{'admin/project-member-stat'|url}>"><i class="fa fa-bar-chart"></i> 成员统计</a></li>
					<{/if}>
				</ul>
			</div>
			<div class="row">
				<div class="col-sm-8 col-md-8">
					<div class="block">
						<div class="block-title">
							<h2> <strong>项目列表</strong> </h2>
						</div>
						<div class="row">
							<{foreach $_projects as $project}>
							<div class="col-sm-4 col-md-4">

								<a data-toggle="modal" href="<{url('admin/project', [$project.id])}>" data-target="#projectModal">
									<div class="widget">
										<div class="widget-advanced">
											<div class="widget-header text-center">
												<img src="<{null|attachment}>/<{$project->cover_id}>" alt="<{$project->name}>" style="width:100%" class="widget-background animation-pulseSlow">
											</div>
											<div class="widget-main">
												<div class="row text-center animation-fadeIn">
													<div class="col-xs-12">
														<p><strong><{$project->name}></strong></p>
													</div>
													<div class="col-xs-6">
														<p><span class="label label-danger"><{$project->project_status->title}></span></p>
													</div>
													<div class="col-xs-6">
														<p><{$project->pm->realname}></p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</a>

							</div>
							<{/foreach}>
						</div>
					</div>
				</div>
				<div class="col-sm4 col-md-4">
					<div class="block">
						<div class="block-title">
							<h2> <strong>我的消息</strong> </h2>
						</div>
						<table class="table table-bordered table-striped table-vcenter">
							<tbody>
							<{if !empty($_messages)}>
							<{foreach $_messages as $message}>
							<tr>
								<td class="text-center">
									<{$message.message}>
								</td>
								<td class="text-center">
									<a href="javascript:void(0)" ref="<{$message.id}>" class="btn btn-xs btn-success"
									onclick="applyMember
									(1, this)"> 同意</a>
									<a href="javascript:void(0)" ref="<{$message.id}>" class="btn btn-xs btn-danger"
									   onclick="applyMember
									(0, this)"> 拒绝</a>
								</td>
							</tr>
							<{/foreach}>
							<{else}>
							<tr><td class="text-center"> 暂无信息 </td></tr>
							<{/if}>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<{include file="admin/copyright.inc.tpl"}>
	</div>
	<!-- END Main Container -->
</div>
<!-- END Page Container -->
<div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="height: 500px;overflow: scroll;">

		</div>
	</div>
</div>
<script>
	(function($){
		$(document).on('hidden.bs.modal', '#projectModal', function(){
			$(this).removeData("bs.modal");
			$(this).find('.modal-content').children().remove();
		});
	})(jQuery);

	function applyMember(flag, t) {
		var id = jQuery(t).attr('ref');
		LP.http.jQueryAjax.put("<{url('admin/project-apply')}>/" + id, {status : flag}).then(function (res) {
			if (res.result == 'success'){
				LP.tip.toast_interface({content:"操作成功"});
				jQuery(t).parents('tr').remove();
			}
		}, function (err) {
			LP.tip.toast_interface({content: err.$json.message});
		});
	}
</script>

<!-- Scroll to top link, initialized in js/app.js - scrollToTop() -->
<a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>

</body>
</html>
