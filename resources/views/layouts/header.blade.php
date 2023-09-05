{{-- menu mobile --}}
@php
    use App\Models\Majors;
    $majors = Majors::query()->get();
@endphp
<div class="responsive-header">
    <div class="responsive-menubar">
        @if (Auth::guard('user')->check())
            <div class="res-logo"><a href="{{ route('home') }}" title=""><img
                        src="{{ asset(Auth::guard('user')->user()->images) }}" alt="" /></a>
            </div>
        @endif
        <div class="menu-resaction">
            <div class="res-openmenu">
                <img src="{{ asset('home/images/icon.png') }}" alt="" /> Menu
            </div>
            <div class="res-closemenu">
                <img src="{{ asset('home/images/icon2.png') }}" alt="" /> Close
            </div>
        </div>
    </div>
    <div class="responsive-opensec">
        <div class="responsivemenu">
            <ul>
                <li class="menu-item-has-children">
                    <a href="{{ route('home') }}" title="">Trang chủ</a>
                </li>
                <li class="menu-item-has-children">
                    <a href="#" title="">Ngành nghề</a>
                    <ul>
                        @foreach ($majors as $item)
                            <li>
                                <a href="{{ route('client.nganh-nghe.show', $item->id) }}"
                                    title="">{{ $item->name }}</a>
                            </li>
                        @endforeach

                    </ul>
                </li>
                <li class="menu-item-has-children">
                    <a href="{{ route('company.index') }}" title="">Nhà tuyển dụng</a>
                </li>
                <li class="menu-item-has-children">
                    <a href="#" title="">Câu hỏi thường hỏi</a>
                </li>
            </ul>
        </div>
        @if (!Auth::guard('user')->check())
            <div class="btn-extars">
                <ul class="account-btns" style="display: flex; align-items: center">
                    <li class="signup-popup"><a title=""><i class="la la-key"></i>Đăng ký</a></li>
                    <li class="signin-popup"><a title=""><i class="la la-external-link-square"></i>
                            Đăng nhâp</a></li>
                    <li class="signin-popup">
                        <a title="" class="btn btn-primary"><i class="fa fa-desktop"></i>
                            Nhà tuyển dụng
                        </a>
                    </li>
                </ul>
            </div>
        @else
            <div class="btns-profiles-sec">
                <span><img src="{{ asset('home/images/resource/profile.jpg') }}"
                        alt="">{{ Auth::guard('user')->user()->name }}<i class="la la-angle-down"></i></span>
                <ul>
                    <li><a href="{{ route('users.profile.index', Auth::guard('user')->user()->slug) }}"
                            title=""><i class="la la-file-text"></i>Thông tin
                            cá nhân</a></li>
                    <li><a href="{{ route('users.apply') }}" title=""><i class="la la-briefcase"></i>Công
                            việc đã ứng tuyển</a></li>
                    <li><a href="{{ route('users.love') }}" title=""><i class="la la-money"></i>Công việc
                            đã ưu thích</a></li>
                    <li><a href="{{ route('users.apply') }}" title=""><i class="la la-paper-plane"></i>Công việc
                            đã ứng tuyển</a>
                    </li>
                    <li><a href="{{ route('users.file.index') }}" title=""><i class="la la-user"></i>Quản lý
                            CV</a>
                    </li>
                    <li><a href="{{ route('users.changePassword') }}" title=""><i class="la la-lock"></i>Đổi mật
                            khẩu</a></li>
                    <li><a href="{{ route('logout') }}" title=""><i class="la la-unlink"></i>Logout</a>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</div>
{{-- header --}}
<header class="stick-top forsticky">
    <div class="menu-sec">
        <div class="container">
            <div class="logo">
                <a href="{{ route('home') }}" title=""><img class="hidesticky"
                        src="{{ asset('home/images/resource/logo.png') }}" alt="" /><img class="showsticky"
                        src="{{ asset('home/images/resource/logo10.png') }}" alt="" /></a>
            </div>
            <!-- Logo -->
            @if (!Auth::guard('user')->check())
                <div class="btn-extars">
                    <ul class="account-btns" style="display: flex; align-items: center">
                        <li class="signup-popup"><a title=""><i class="la la-key"></i>Đăng ký</a></li>
                        <li class="signin-popup"><a title=""><i class="la la-external-link-square"></i>
                                Đăng nhâp</a></li>
                        <li>
                            <a href="{{ route('register') }}" class="btn btn-primary"><i class="fa fa-desktop"></i>
                                Nhà tuyển dụng
                            </a>
                        </li>
                    </ul>
                </div>
            @else
                <div class="btns-profiles-sec">
                    <span><img src="{{ asset('home/images/resource/profile.jpg') }}"
                            alt="">{{ Auth::guard('user')->user()->name }}<i
                            class="la la-angle-down"></i></span>
                    <ul>
                        <li><a href="{{ route('users.profile.index', Auth::guard('user')->user()->slug) }}"
                                title=""><i class="la la-file-text"></i>Thông tin
                                cá nhân</a></li>
                        <li><a href="{{ route('users.apply') }}" title=""><i class="la la-briefcase"></i>Công
                                việc đã ứng tuyển</a></li>
                        <li><a href="{{ route('users.love') }}" title=""><i class="la la-money"></i>Công việc
                                đã ưu thích</a></li>
                        <li><a href="{{ route('users.file.index') }}" title=""><i class="la la-user"></i>Quản
                                lý CV</a>
                        </li>
                        <li><a href="{{ route('users.changePassword') }}" title=""><i
                                    class="la la-lock"></i>Đổi mật
                                khẩu</a></li>
                        <li><a href="{{ route('logout') }}" title=""><i class="la la-unlink"></i>Logout</a>
                        </li>
                    </ul>
                </div>
            @endif

            <!-- Btn Extras -->
            <nav>
                <ul>
                    <li class="menu-item-has-children">
                        <a href="{{ route('home') }}" title="">Trang chủ</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#" title="">Ngành nghề</a>
                        <ul>
                            @foreach ($majors as $item)
                                <li>
                                    <a href="{{ route('client.nganh-nghe.show', $item->id) }}"
                                        title="">{{ $item->name }}</a>
                                </li>
                            @endforeach

                        </ul>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="{{ route('company.index') }}" title="">Nhà tuyển dụng</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="{{ route('faqs.index') }}" title="">Câu hỏi thường hỏi</a>
                    </li>
                </ul>
            </nav><!-- Menus -->
        </div>
    </div>
</header>
<div class="account-popup-area signin-popup-box">
    <login-user
        :data="{{ json_encode([
            'urlStore' => route('login.store'),
            'urlBack' => route('home'),
        ]) }}">
    </login-user>
</div><!-- LOGIN POPUP -->
<div class="account-popup-area signup-popup-box">
    <form-register-user
        :data="{{ json_encode([
            'urlStore' => route('login.register'),
            'urlBack' => route('home'),
        ]) }}">
    </form-register-user>

</div><!-- SIGNUP POPUP -->
