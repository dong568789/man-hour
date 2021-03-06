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
    <{include file="common/validate.inc.tpl"}>
</head>

<body>
<!-- Login Alternative Row -->
<div class="container">
    <div class="row">
        <div class="col-md-5 col-md-offset-1">
            <div id="login-alt-container">
                <!-- Title -->
                <h1 class="push-top-bottom">
                    <small>管理系统</small>
                </h1>
                <!-- END Title -->

                <!-- Key Features -->
                <ul class="fa-ul text-muted">
                    <li><i class="fa fa-arrow-down fa-li text-success"></i> Request 请求</li>
                    <li><i class="fa fa-arrow-down fa-li text-success"></i> Router 路由</li>
                    <li><i class="fa fa-arrow-down fa-li text-success"></i> Middleware 中间件</li>
                    <li><i class="fa fa-arrow-down fa-li text-success"></i> Controller 控制器</li>
                    <li><i class="fa fa-arrow-down fa-li text-success"></i> Event 事件</li>
                    <li><i class="fa fa-arrow-down fa-li text-success"></i> Model 数据库</li>
                    <li><i class="fa fa-arrow-down fa-li text-success"></i> Cache 缓存</li>
                    <li><i class="fa fa-arrow-down fa-li text-success"></i> View 视图</li>
                    <li><i class="fa fa-arrow-down fa-li text-success"></i> Response 响应</li>
                    <li><i class="fa fa-gavel fa-li text-success"></i> 精彩页面呈现眼前!</li>
                </ul>
                <!-- END Key Features -->

                <!-- Footer -->
                <footer class="text-muted push-top-bottom">
                    <small><span id="year-copy"></span> &copy; <a href="https://git.load-page.com/r/lp/" target="_blank">Laravel plus (L+) v6.0</a></small>
                </footer>
                <!-- END Footer -->
            </div>
        </div>
        <div class="col-md-5">
            <!-- Login Container -->
            <div id="login-container">
                <!-- Login Title -->
                <div class="login-title text-center">
                    <h1><strong>注册</strong></h1>
                </div>
                <!-- END Login Title -->

                <!-- Login Block -->
                <div class="block push-bit">
                    <!-- Login Form -->
                    <form action="<{'admin/register/store'|url}>" method="post" id="form" class="form-horizontal">
                        <input type="hidden" name="_token" value="<{csrf_token()}>">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="gi gi-user"></i></span>
                                    <input type="text" id="username" name="username" value="<{old('username')}>" class="form-control input-lg"
                                           placeholder="用户名">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="gi gi-user"></i></span>
                                    <input type="text" id="realname" name="realname" value=""
                                           class="form-control input-lg"
                                           placeholder="真实姓名">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                                    <input type="password" id="password" name="password" class="form-control input-lg" placeholder="密码">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control input-lg" placeholder="密码确认">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="gi gi-tag"></i></span>
                                    <select name="post" id="post" class="form-control">
                                        <option value="">请选择职位</option>
                                        <{foreach catalog_search('fields.post', 'children') as $post}>
                                        <option value="<{$post.id}>"><{$post.title}></option>
                                        <{/foreach}>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group radio">
                            <{foreach $_fields.gender as $v}>
                            <label><input type="radio" class="" name="gender" value="<{$v['id']}>"> <{$v['title']}></label>
                            <{/foreach}>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group form-actions">
                            <div class="col-xs-5">
                                <label>
                                    <input type="checkbox" checked="checked" class="" name="accept_license" id="accept_license"
                                           value="1"> 我已阅读并同意协议
                                </label>
                            </div>
                            <div class="col-xs-7 text-right">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> <span id="submit-text">注册</span></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 text-center">
                                <small>已有帐号?</small>
                                <a href="<{url('admin/auth/login')}>" id="link-register"><small>点此登录</small></a>
                            </div>
                        </div>
                    </form>
                    <!-- END Login Form -->
                </div>
                <!-- END Login Block -->
            </div>
            <!-- END Login Container -->
        </div>
    </div>
</div>
<!-- END Login Alternative Row -->
<script>
    (function($){
        $().ready(function(){
            $('#remember').on('click', function(){
                $('#submit-text').text(this.checked ? '登录(并记住我)' : '登录');
            }).triggerHandler('click');
        <{call validate selector='#form'}>
        });
    })(jQuery);
</script>
</body>
</html>
