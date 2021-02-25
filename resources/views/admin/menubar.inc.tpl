<header class="navbar navbar-default">
<div class="navbar-header">
	<ul class="nav navbar-nav-custom pull-right visible-xs">
		<li>
			<a href="javascript:void(0)" data-toggle="collapse" data-target="#horizontal-menu-collapse" class="collapsed" aria-expanded="false">菜单</a>
		</li>
	</ul>
	<!-- Left Header Navigation -->
	<ul class="nav navbar-nav-custom">
		<!-- Main Sidebar Toggle Button -->
		<li>
			<a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
				<i class="fa fa-bars fa-fw"></i>
			</a>
		</li>
	</ul>
	<!-- END Left Header Navigation -->
</div>
<ul class="nav navbar-nav-custom pull-right hidden-xs">
	<!-- Template Options -->
	<!-- Change Options functionality can be found in js/app.js - templateOptions() -->
	<li class="dropdown">
		<a href="javascript:void(0)" class="dropdown-toggle " data-toggle="dropdown">
			<i class="gi gi-settings"></i>
		</a>
		<ul class="dropdown-menu dropdown-custom dropdown-options dropdown-menu-right">
			<li class="dropdown-header text-center">菜单栏样式</li>
			<li>
				<div class="btn-group btn-group-justified btn-group-sm">
					<a href="javascript:void(0)" class="btn btn-primary" id="options-header-default">浅色</a>
					<a href="javascript:void(0)" class="btn btn-primary" id="options-header-inverse">深色</a>
				</div>
			</li>
			<li class="dropdown-header text-center">页面风格</li>
			<li>
				<div class="btn-group btn-group-justified btn-group-sm">
					<a href="javascript:void(0)" class="btn btn-primary" id="options-main-style">内容深色</a>
					<a href="javascript:void(0)" class="btn btn-primary" id="options-main-style-alt">标题深色</a>
				</div>
			</li>
		</ul>
	</li>
	<!-- END Template Options -->
</ul>

<{block "menubar-menus"}>
<div class="collapse navbar-collapse" id="horizontal-menu-collapse">
	<ul class="nav navbar-nav">

		<{if !$_permissionTable->checkUserRole(['project-member'])}>
		<li>
			<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">用户 <i class="fa fa-angle-down"></i></a>
			<ul class="dropdown-menu">
				<{if $_permissionTable->checkUserRole(['super'])}>
				<li><a href="<{'admin/member'|url}>?q[ofRole]=17"><i class="fa fa-user fa-fw pull-right"></i>
						PM列表</a></li>
				<{/if}>
				<{if $_permissionTable->checkUserRole(['super', 'pm', 'finance'])}>
				<li><a href="<{'admin/member'|url}>?q[ofRole]=18"><i class="fa fa-user fa-fw pull-right"></i>
						成员列表</a></li>
				<{/if}>
				<{if $_permissionTable->checkUserRole(['super'])}>
				<li><a href="<{'admin/member'|url}>?q[ofRole]=19"><i class="fa fa-user fa-fw pull-right"></i>
						财务列表</a></li>
				<{/if}>
			</ul>
		</li>
		<{/if}>
		<{if $_permissionTable->checkUserRole(['super', 'finance', 'pm'])}>
		<li>
			<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">项目管理 <i class="fa fa-angle-down"></i></a>
			<ul class="dropdown-menu">
				<li><a href="<{'admin/project'|url}>"><i class="fa fa-list fa-fw pull-right"></i>
						项目列表</a></li>
				<li><a href="<{'admin/project/create'|url}>"><i class="fa fa-plus fa-fw pull-right"></i>
						添加项目</a></li>
			</ul>
		</li>
		<{/if}>
		<{if $_permissionTable->checkUserRole(['super', 'finance'])}>
		<li>
			<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">财务管理 <i
						class="fa fa-angle-down"></i></a>
			<ul class="dropdown-menu">
				<li><a href="<{'admin/project-stat'|url}>"><i class="fa fa-bar-chart fa-fw pull-right"></i>
						项目统计</a></li>
				<li><a href="<{'admin/project-member-stat'|url}>"><i class="fa fa-bar-chart fa-fw pull-right"></i>
						成员统计</a></li>
			</ul>
		</li>
		<{/if}>
		<li><a href="<{'admin/project-apply'|url}>">
				申请记录</a></li>
		<li><a href="<{'admin/project-member'|url}>">
				工时明细</a></li>
		<{pluginclude file="admin/menubar.inc.tpl"}>
	</ul>
</div>
<{/block}>
<div class="clearfix"></div>
</header>

<!-- END Header -->
