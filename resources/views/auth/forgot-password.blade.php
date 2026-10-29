<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | {{ config('app.name', 'Apex Coaching') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    @vite(['resources/css/app.css'])
</head>
<body class="hold-transition login-page bg-light">
<div class="login-box">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header text-center">
            <a href="/" class="h1 font-weight-bold text-indigo"><b>APEX</b> ERP</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg text-sm text-muted">
                Forgot your account password? Enter your verified email address below and we'll dispatch a secure recovery link.
            </p>

            @if (session('status'))
                <div class="alert alert-success text-sm py-2">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Account Email" value="{{ old('email') }}" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                            <i class="fas fa-paper-plane mr-1"></i> Send Password Reset Link
                        </button>
                    </div>
                </div>
            </form>

            <p class="mt-3 mb-1 text-center">
                <a href="{{ route('login') }}" class="text-sm font-weight-bold text-indigo">
                    <i class="fas fa-arrow-left mr-1"></i> Back to sign in
                </a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
