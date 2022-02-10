<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin Dashboard" name="description" />
    <meta content="ThemeDesign" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Ahamayel - Control Panel</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="{{asset('css/admin/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/icons.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/style.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<!-- Begin page -->
<div class="accountbg"></div>
<div class="wrapper-page">
    <div class="card">
        <div class="card-body">
            <h3 class="text-center m-t-0 m-b-15">
                <a href="{{route('admin.get_login')}}" class="text-light">{{__('global.short_title')}}</a>
            </h3>
            <h4 class="text-muted text-center m-t-0"><b>{{__('global.sign_in')}}</b></h4>
            <form class="form-horizontal m-t-20" method="POST">
                @csrf
                <div class="form-group">
                    <div class="col-12">
                        <input class="form-control" type="text" required="" placeholder="Username" name="user_name">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-12">
                        <div class="input-group">
                            <input class="form-control" type="password" required="" placeholder="Password" name="password">
                            <div class="input-group-append">
                                <a type="buttton" class="input-group-text show-password" style="background: #fff; border-left: 0; cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-12">
                        <div class="checkbox checkbox-primary">
                            <input id="checkbox-signup" type="checkbox" checked name="remember_me">
                            <label for="checkbox-signup">
                                {{__('global.remember_me')}}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group text-center m-t-40">
                    <div class="col-12">
                        <button class="btn btn-primary btn-block btn-lg waves-effect waves-light" type="submit">{{__('global.login')}}</button>
                    </div>
                </div>

                <div class="form-group row m-t-30 m-b-0">
                    <div class="col-sm-7">
                        <a href="#" class="text-muted"><i class="fa fa-lock m-r-5"></i> {{__('global.forgot_password')}}</a>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>



<!-- jQuery  -->
<script src="{{asset('js/admin/jquery.min.js')}}"></script>
<script src="{{asset('js/admin/bootstrap.min.js')}}"></script>
<script src="{{asset('js/admin/modernizr.min.js')}}"></script>
<script src="{{asset('js/admin/detect.js')}}"></script>
<script src="{{asset('js/admin/fastclick.js')}}"></script>
<script src="{{asset('js/admin/jquery.slimscroll.js')}}"></script>
<script src="{{asset('js/admin/jquery.blockUI.js')}}"></script>
<script src="{{asset('js/admin/waves.js')}}"></script>
<script src="{{asset('js/admin/wow.min.js')}}"></script>
<script src="{{asset('js/admin/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/admin/jquery.scrollTo.min.js')}}"></script>
<script src="{{asset('js/admin/app.js')}}"></script>
<script>
    $(".show-password").click(function(){
        const input = $(this).parent().prev()[0];
        if(input.type === "text") input.type = "password";
        else input.type = "text";
    })
</script>
</body>
</html>
