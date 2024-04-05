@include('admin.header')
<div id="app">
    <section class="section">
        <div class="auth-section-wrapper">
                <div class="login-thumb">
                    <img class="img" src="{{ asset($setting->admin_login_page) }}" alt="login-thumb"/>
                </div>
                <div class="form-area-wrapper">
                        <div class="form-content-wrapper">
                        <div class="logo">
                          <img src="{{ asset($setting->logo) }}" alt="logo"/>
                        </div>
                            <div class="card card-primary card-wrapper-auth">
                                <div class="card-body">
                                    <div class="tex-content">
                                    <h1>{{__('admin.Admin Dashboard')}}</h1>
                                    <p class="des">{{__('admin.Login Your  fashion shopping .')}} </p>
                                    </div>
                                    <form class="needs-validation" novalidate="" action="{{ route('admin.login') }}" method="POST">
                                        @csrf

                                        <div class="form-group">
                                            <label for="email">{{__('admin.Email')}}<sup>*</sup></label>
                                            <input id="email exampleInputEmail" type="email" class="form-control" name="email" tabindex="1" autofocus value="{{ old('email') }}">
                                        </div>

                                        <div class="form-group">
                                            <div class="d-block">
                                            <label for="password" class="control-label">{{__('admin.Password')}}<sup>*</sup></label>

                                            </div>
                                            <input id="password exampleInputPassword" type="password" class="form-control" name="password" tabindex="2">
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="remember">{{__('admin.Remember Me')}}</label>
                                            </div>
                                        </div>

                                        <div class="form-group login-sub-btn">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                            {{__('admin.Login')}}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="simple-footer">
                    {{ $setting->copyright }}
                </div>
        </div>
    </section>
  </div>

@include('admin.footer')


