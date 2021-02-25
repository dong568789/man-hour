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
					<li><a href="<{'admin/project-member'|url}>"><i class="fa fa-list"></i> 工时明细</a></li>
					<li><a href="<{'admin/project-apply'|url}>"><i class="gi gi-envelope"></i> 我的消息</a></li>
				</ul>
			</div>
			<{include file="admin/index/$_tpl.inc.tpl"}>
		</div>
		<{include file="admin/copyright.inc.tpl"}>
	</div>
	<!-- END Main Container -->
</div>
<!-- END Page Container -->


<!-- Scroll to top link, initialized in js/app.js - scrollToTop() -->
<a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>

</body>
</html>
