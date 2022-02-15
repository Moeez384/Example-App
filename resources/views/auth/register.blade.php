<HTMl>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />

</head>

<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <form class="login" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="login__field">
                        <input type="text" required name="name" value="{{ old('name') }}" class="login__input"
                            placeholder="Name">
                    </div>
                    <div class="login__field">
                        <input type="email" required name="email" value="{{ old('email') }}" class="login__input"
                            placeholder="Email">
                    </div>
                    <div class="login__field">
                        <input type="password" required name="password" class="login__input" placeholder="Password">
                    </div>
                    <div class="login__field">
                        <input type="password" required name="password_confirmation" class="login__input"
                            placeholder="Confirm Password">
                    </div>
                    <button class="button login__submit">
                        <span class="button__text">Register Now</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>


                    <a href="{{ route('login') }}" class="button login__submit">
                        <span class="button__text">Login</span>
                    </a>
                </form>
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            @if (isset($errors))
                @foreach ($errors->all() as $message)
                    toastr.error("{{ $message }}");
                @endforeach
            @endif
        });
    </script>
</body>

</HTMl>
