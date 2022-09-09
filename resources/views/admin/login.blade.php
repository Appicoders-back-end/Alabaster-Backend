<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/x-icon">
    <title>Alabaster Login</title>
    <!-- Bootstrap Core and vandor -->
    <link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css"/>
    <!-- Core css -->
    <link rel="stylesheet" href="../assets/css/style.min.css"/>
</head>
<body class="font-muli theme-cyan gradient">
<div class="auth option2">
    <div class="auth_left">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <a class="header-brand" href="#"><img class="" src="../assets/images/logo.png" alt="">
                    </a>
                    <div class="card-title mt-3 text-white">Login to your account</div>
                </div>
                <form action="{{route('admin.do_login')}}" method="POST">
                    <div class="form-group">
                        <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                    </div>
                    @if (isset($errors) && $errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @endif
                    <div class="form-group">
                        <label class="form-label">
                            <a href="forgot-password.html" class="mb-2 text-white float-right small">I forgot password</a></label>
                        <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                    </div>
                    @if (isset($errors) && $errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @endif
{{--                    @if(session()->has('error'))--}}
{{--                        <p class="text-danger">{{session()}}</p>--}}
{{--                    @endif--}}
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-block" title="">Sign in</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Start Main project js, jQuery, Bootstrap -->
<script src="../assets/bundles/lib.vendor.bundle.js"></script>
<!-- Start project main js  and page js -->
<script src="../assets/js/core.js"></script>
</body>
</html>
