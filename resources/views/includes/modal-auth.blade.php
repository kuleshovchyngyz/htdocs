<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="modal-header">
                    <div class="form-title">
                        Авторизация
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('/public/images/home/close.svg') }}" alt="">
                    </button>
                </div>
                <div class="modal-form__content">
                    <div class="form-group row">
                        <div class="form__user from__icon w-100 position-relative d-flex align-items-center">
                            <input id="email" type="text" class="form-control form__user" name="email"
                                value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="Логин">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form__password from__icon w-100 position-relative  d-flex align-items-center">

                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password" placeholder="Пароль">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="w-100">
                            <button type="submit" class="main-btn w-100 mt-4    ">
                                {{ __('Войти') }}
                            </button>
                        </div>
                    </div>

                    <div class="form-group row mb-0 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <div>
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
