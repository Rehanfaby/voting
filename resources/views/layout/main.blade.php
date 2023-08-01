<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    <title>{{$general_setting->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{url('manifest.json')}}">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap-datepicker.min.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/jquery-timepicker/jquery.timepicker.min.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/awesome-bootstrap-checkbox.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap-select.min.css') ?>" type="text/css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/font-awesome/css/font-awesome.min.css') ?>" type="text/css">
    <!-- Drip icon font-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/dripicons/webfont.css') ?>" type="text/css">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="<?php echo asset('public/css/grasp_mobile_progress_circle-1.0.0.min.css') ?>" type="text/css">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css') ?>" type="text/css">

    @if(Route::current()->getName() != '/')
        <!-- date range stylesheet-->
        <link rel="stylesheet" href="<?php echo asset('public/vendor/daterange/css/daterangepicker.min.css') ?>" type="text/css">
        <!-- table sorter stylesheet-->
        <link rel="stylesheet" type="text/css" href="<?php echo asset('public/vendor/datatable/dataTables.bootstrap4.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    @endif

    <link rel="stylesheet" href="<?php echo asset('public/css/style.default.css') ?>" id="theme-stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/css/dropzone.css') ?>">


    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery/jquery-ui.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery/bootstrap-datepicker.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery/jquery.timepicker.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/popper.js/umd/popper.min.js') ?>">
    </script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/bootstrap/js/bootstrap-select.min.js') ?>"></script>

    <script type="text/javascript" src="<?php echo asset('public/js/grasp_mobile_progress_circle-1.0.0.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery.cookie/jquery.cookie.js') ?>">
    </script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/chart.js/Chart.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/js/charts-custom.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery-validation/jquery.validate.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')?>"></script>

    <script type="text/javascript" src="<?php echo asset('public/js/front.js') ?>"></script>

    @if(Route::current()->getName() != '/')
        <script type="text/javascript" src="<?php echo asset('public/vendor/daterange/js/moment.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/vendor/daterange/js/knockout-3.4.2.js') ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/vendor/daterange/js/daterangepicker.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/vendor/tinymce/js/tinymce/tinymce.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/js/dropzone.js') ?>"></script>

        <!-- table sorter js-->
        <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/pdfmake.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/vfs_fonts.js') ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/jquery.dataTables.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/dataTables.bootstrap4.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/dataTables.buttons.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/buttons.bootstrap4.min.js') ?>">"></script>
        <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/buttons.colVis.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/buttons.html5.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/buttons.print.min.js') ?>"></script>

        <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/sum().js') ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/dataTables.checkboxes.min.js') ?>"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    @endif

    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="<?php echo asset('public/css/custom-'.$general_setting->theme) ?>" type="text/css" id="custom-style">
</head>

<body onload="myFunction()">
<div id="loader"></div>
<!-- Side Navbar -->
<nav class="side-navbar">
    <div class="side-navbar-wrapper">
        <!-- Sidebar Header    -->
        <!-- Sidebar Navigation Menus-->
        <div class="main-menu">
            <ul id="side-main-menu" class="side-menu list-unstyled">
                @if(Auth::user()->role_id != 7)
                    <li><a href="{{url('/')}}"> <i class="dripicons-meter"></i><span>{{ __('file.dashboard') }}</span></a></li>
                @endif
                <?php
                $role = DB::table('roles')->find(Auth::user()->role_id);

                $index_permission = DB::table('permissions')->where('name', 'expenses-index')->first();
                $index_permission_active = DB::table('role_has_permissions')->where([
                    ['permission_id', $index_permission->id],
                    ['role_id', $role->id]
                ])->first();
                ?>
                @if($index_permission_active)
                    <li><a href="#expense" aria-expanded="false" data-toggle="collapse"> <i class="dripicons-wallet"></i><span>{{trans('file.Expense')}}</span></a>
                        <ul id="expense" class="collapse list-unstyled ">
                            <li id="exp-cat-menu"><a href="{{route('expense_categories.index')}}">{{trans('file.Expense Category')}}</a></li>
                            @if(Auth::user()->role_id != 7)
                                <li id="exp-list-menu"><a href="{{route('expenses.index')}}">{{trans('file.Expense List')}}</a></li>
                            @endif
                                <?php
                                $add_permission = DB::table('permissions')->where('name', 'expenses-add')->first();
                                $add_permission_active = DB::table('role_has_permissions')->where([
                                    ['permission_id', $add_permission->id],
                                    ['role_id', $role->id]
                                ])->first();
                                ?>
                            @if($add_permission_active)
                                <li><a id="add-expense" href=""> {{trans('file.Add Expense')}}</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                <?php

                $user_index_permission_active = DB::table('permissions')
                    ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                    ->where([
                        ['permissions.name', 'users-index'],
                        ['role_id', $role->id] ])->first();

                $index_employee = DB::table('permissions')->where('name', 'employees-index')->first();
                $index_employee_active = DB::table('role_has_permissions')->where([
                    ['permission_id', $index_employee->id],
                    ['role_id', $role->id]
                ])->first();
                ?>
                @if($user_index_permission_active || $index_employee_active)
                    <li><a href="#people" aria-expanded="false" data-toggle="collapse"> <i class="dripicons-user"></i><span>{{trans('file.People')}}</span></a>
                        <ul id="people" class="collapse list-unstyled ">

                            @if($user_index_permission_active)
                                <li id="user-list-menu"><a href="{{route('user.index')}}">{{trans('file.User List')}}</a></li>
                                    <?php $user_add_permission_active = DB::table('permissions')
                                    ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                                    ->where([
                                        ['permissions.name', 'users-add'],
                                        ['role_id', $role->id] ])->first();
                                    ?>
                                @if($user_add_permission_active)
                                    <li id="user-create-menu"><a href="{{route('user.create')}}">{{trans('file.Add User')}}</a></li>
                                @endif

                                @if($index_employee_active)
                                    <li id="employee-menu"><a href="{{route('musician.index')}}">Musician</a></li>
                                @endif
                            @endif
                        </ul>
                    </li>
                @endif
                <?php
                $department = DB::table('permissions')->where('name', 'department')->first();
                $department_active = DB::table('role_has_permissions')->where([
                    ['permission_id', $department->id],
                    ['role_id', $role->id]
                ])->first();
                $index_permission = DB::table('permissions')->where('name', 'account-index')->first();
                $index_permission_active = DB::table('role_has_permissions')->where([
                    ['permission_id', $index_permission->id],
                    ['role_id', $role->id]
                ])->first();

                ?>
                @if($index_permission_active)
                    <li class=""><a href="#account" aria-expanded="false" data-toggle="collapse"> <i class="dripicons-briefcase"></i><span>{{trans('file.Accounting')}}</span></a>
                        <ul id="account" class="collapse list-unstyled ">
                            @if($index_permission_active)
                                <li id="account-list-menu"><a href="{{route('accounts.index')}}">{{trans('file.Account List')}}</a></li>
                                @if($department_active)
                                    <li id="dept-menu"><a href="{{route('departments.index')}}">{{trans('file.Department')}}</a></li>
                                @endif
                                <li><a id="add-account" href="">{{trans('file.Add Account')}}</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                <?php
                $best_seller_active = DB::table('permissions')
                    ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                    ->where([
                        ['permissions.name', 'best-seller'],
                        ['role_id', $role->id] ])->first();
                ?>
                @if($best_seller_active)
                    <li><a href="#report" aria-expanded="false" data-toggle="collapse"> <i class="dripicons-document-remove"></i><span>{{trans('file.Reports')}}</span></a>
                        <ul id="report" class="collapse list-unstyled ">
                            @if($best_seller_active)
                                <li id="best-seller-report-menu">
                                    <a href="{{url('report/best_seller')}}">{{trans('file.Best Seller')}}</a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif

                <li><a href="#setting" aria-expanded="false" data-toggle="collapse"> <i class="dripicons-gear"></i><span>{{trans('file.settings')}}</span></a>
                    <ul id="setting" class="collapse list-unstyled ">
                        <?php
                        $send_notification_permission = DB::table('permissions')->where('name', 'send_notification')->first();
                        $send_notification_permission_active = DB::table('role_has_permissions')->where([
                            ['permission_id', $send_notification_permission->id],
                            ['role_id', $role->id]
                        ])->first();

                        $currency_permission = DB::table('permissions')->where('name', 'currency')->first();
                        $currency_permission_active = DB::table('role_has_permissions')->where([
                            ['permission_id', $currency_permission->id],
                            ['role_id', $role->id]
                        ])->first();


                        $general_setting_permission = DB::table('permissions')->where('name', 'general_setting')->first();
                        $general_setting_permission_active = DB::table('role_has_permissions')->where([
                            ['permission_id', $general_setting_permission->id],
                            ['role_id', $role->id]
                        ])->first();


                        ?>
                        @if($role->name == 'Admin')
                            <li id="role-menu"><a href="{{route('role.index')}}">{{trans('file.Role Permission')}}</a></li>
                        @endif
                        @if($send_notification_permission_active)
                            <li id="notification-menu">
                                <a href="" id="send-notification">{{trans('file.Send Notification')}}</a>
                            </li>
                        @endif
                        @if($currency_permission_active)
                            <li id="currency-menu"><a href="{{route('currency.index')}}">{{trans('file.Currency')}}</a></li>
                        @endif
                        <li id="user-menu"><a href="{{route('user.profile', ['id' => Auth::id()])}}">{{trans('file.User Profile')}}</a></li>
                        {{--                      @if($create_sms_permission_active)--}}
                        {{--                      <li id="create-sms-menu"><a href="{{route('setting.createSms')}}">{{trans('file.Create SMS')}}</a></li>--}}
                        {{--                      @endif--}}
                        @if($general_setting_permission_active)
                            <li id="general-setting-menu"><a href="{{route('setting.general')}}">{{trans('file.General Setting')}}</a></li>
                        @endif
                        {{--                      @if($sms_setting_permission_active)--}}
                        {{--                      <li id="sms-setting-menu"><a href="{{route('setting.sms')}}">{{trans('file.SMS Setting')}}</a></li>--}}
                        {{--                      @endif--}}

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Side Navbar -->
<header class="header">
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
                <a id="toggle-btn" href="#" class="menu-btn"><i class="fa fa-bars"> </i></a>
                <span class="brand-big">
                    @if($general_setting->site_logo)
                        <a href="{{url('/')}}"><img src="{{url('public/logo', $general_setting->site_logo)}}" width="115"></a>
                    @else
                        <a href="{{url('/')}}"><h1 class="d-inline">{{$general_setting->site_title}}</h1></a>
                    @endif
                  </span>

                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                    <li class="nav-item"><a id="btnFullscreen" data-toggle="tooltip" title="{{trans('file.Full Screen')}}"><i class="dripicons-expand"></i></a></li>


                    <li class="nav-item">
                        <a rel="nofollow" data-toggle="tooltip" class="nav-link dropdown-item"><i class="dripicons-web"></i></a>
                        <ul class="right-sidebar">
                            <li>
                                <a href="{{ url('language_switch/en') }}" class="btn btn-link"> English</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/es') }}" class="btn btn-link"> Español</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/ar') }}" class="btn btn-link"> عربى</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/pt_BR') }}" class="btn btn-link"> Portuguese</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/fr') }}" class="btn btn-link"> Français</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/de') }}" class="btn btn-link"> Deutsche</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/id') }}" class="btn btn-link"> Malay</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/hi') }}" class="btn btn-link"> हिंदी</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/vi') }}" class="btn btn-link"> Tiếng Việt</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/ru') }}" class="btn btn-link"> русский</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/bg') }}" class="btn btn-link"> български</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/tr') }}" class="btn btn-link"> Türk</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/it') }}" class="btn btn-link"> Italiano</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/nl') }}" class="btn btn-link"> Nederlands</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/lao') }}" class="btn btn-link"> Lao</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-item"><i class="dripicons-user"></i> <span>{{ucfirst(Auth::user()->name)}}</span> <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="right-sidebar">
                            <li>
                                <a href="{{route('user.profile', ['id' => Auth::id()])}}"><i class="dripicons-user"></i> {{trans('file.profile')}}</a>
                            </li>
                            @if($general_setting_permission_active)
                                <li>
                                    <a href="{{route('setting.general')}}"><i class="dripicons-gear"></i> {{trans('file.settings')}}</a>
                                </li>
                            @endif

                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();"><i class="dripicons-power"></i>
                                    {{trans('file.logout')}}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<div class="page">

    <!-- expense modal -->
    <div id="expense-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Add Expense')}}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                    {!! Form::open(['route' => 'expenses.store', 'method' => 'post']) !!}
                    <?php
                    $lims_expense_category_list = DB::table('expense_categories')->where('is_active', true)->get();
                    $lims_account_list = \App\Account::where('is_active', true)->get();
                    $lims_department_list = \App\Department::where('is_active', true)->get();
                    ?>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>{{trans('file.Expense Category')}} *</label>
                            <select name="expense_category_id" class="selectpicker form-control" required data-live-search="true"   title="Select Expense Category...">
                                @foreach($lims_expense_category_list as $expense_category)
                                    <option value="{{$expense_category->id}}">{{$expense_category->name . ' (' . $expense_category->code. ')'}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{trans('file.Amount')}} *</label>
                            <input type="number" name="amount" step="any" required class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label> {{trans('file.Account')}}</label>
                            <select class="form-control selectpicker" name="account_id">
                                @foreach($lims_account_list as $account)
                                    @if($account->is_default)
                                        <option selected value="{{$account->id}}">{{$account->name}} [{{$account->account_no}}]</option>
                                    @else
                                        <option value="{{$account->id}}">{{$account->name}} [{{$account->account_no}}]</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{trans('file.Note')}}</label>
                        <textarea name="note" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{trans('file.submit')}}</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <!-- end expense modal -->



    <!-- notification modal -->
    <div id="notification-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Send Notification')}}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                    {!! Form::open(['route' => 'notifications.store', 'method' => 'post']) !!}
                    <div class="row">
                        <?php
                        $lims_user_list = DB::table('users')->where([
                            ['is_active', true],
                            ['id', '!=', \Auth::user()->id]
                        ])->get();
                        ?>
                        <div class="col-md-6 form-group">
                            <label>{{trans('file.User')}} *</label>
                            <select name="user_id" class="selectpicker form-control" required data-live-search="true"   title="Select user...">
                                @foreach($lims_user_list as $user)
                                    <option value="{{$user->id}}">{{$user->name . ' (' . $user->email. ')'}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>{{trans('file.Message')}} *</label>
                            <textarea rows="5" name="message" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{trans('file.submit')}}</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <!-- end notification modal -->

    <!-- account modal -->
    <div id="account-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Add Account')}}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                    {!! Form::open(['route' => 'accounts.store', 'method' => 'post']) !!}
                    <div class="form-group">
                        <label>{{trans('file.Account No')}} *</label>
                        <input type="text" name="account_no" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>{{trans('file.Department')}} *</label>
                        <select class="form-control selectpicker" name="department_id" data-live-search="true" required>
                            <option value="0">Choose department</option>
                            @foreach($lims_department_list as $account)
                                <option value="{{$account->id}}">{{$account->name}} - {{$account->code}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{trans('file.name')}} *</label>
                        <input type="text" name="name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>{{trans('file.Initial Balance')}}</label>
                        <input type="number" name="initial_balance" step="any" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>{{trans('file.Note')}}</label>
                        <textarea name="note" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{trans('file.submit')}}</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <!-- end account modal -->


    <div style="display:none" id="content" class="animate-bottom">
        @yield('content')
    </div>

    <footer class="main-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <p>&copy; {{$general_setting->site_title}} | {{trans('file.Developed')}} {{trans('file.By')}} <span class="external">{{$general_setting->developed_by}}</span></p>
                </div>
            </div>
        </div>
    </footer>
</div>
@yield('scripts')
<script>
    if ('serviceWorker' in navigator ) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/saleproposmajed/service-worker.js').then(function(registration) {
                // Registration was successful
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                // registration failed :(
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }
</script>
<script type="text/javascript">

    if ($(window).outerWidth() > 1199) {
        $('nav.side-navbar').removeClass('shrink');
    }
    function myFunction() {
        setTimeout(showPage, 150);
    }
    function showPage() {
        document.getElementById("loader").style.display = "none";
        document.getElementById("content").style.display = "block";
    }

    $("div.alert").delay(3000).slideUp(750);

    function confirmDelete() {
        if (confirm("Are you sure want to delete?")) {
            return true;
        }
        return false;
    }


    $("a#add-expense").click(function(e){
        e.preventDefault();
        $('#expense-modal').modal();
    });

    $("a#send-notification").click(function(e){
        e.preventDefault();
        $('#notification-modal').modal();
    });

    $("a#report-link").click(function(e){
        e.preventDefault();
        $("#product-report-form").submit();
    });

    $("a#report-link-category").click(function(e){
        e.preventDefault();
        $("#category-report-form").submit();
    });


    $(".daterangepicker-field").daterangepicker({
        callback: function(startDate, endDate, period){
            var start_date = startDate.format('YYYY-MM-DD');
            var end_date = endDate.format('YYYY-MM-DD');
            var title = start_date + ' To ' + end_date;
            $(this).val(title);
            $('#account-statement-modal input[name="start_date"]').val(start_date);
            $('#account-statement-modal input[name="end_date"]').val(end_date);
        }
    });

    $('.selectpicker').selectpicker({
        style: 'btn-link',
    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>
</body>
</html>
