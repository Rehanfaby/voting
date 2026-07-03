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
    <!-- Modern admin theme overlay (Alpha Bridge inspired) -->
    <link rel="stylesheet" href="<?php echo asset('public/css/admin-modern.css') ?>?v=20260702-v3" type="text/css" id="admin-modern-style">
    <style>
        /* Header layout guarantee: logo top-left ALONE, fullscreen + language top-right.
           Inline so it always wins over any cached copy of the base theme / admin-modern.css. */
        .header .navbar-holder { display: flex !important; align-items: center !important; justify-content: space-between !important; width: 100%; position: relative; }
        .header .navbar-holder .brand-big {
            position: static !important; left: auto !important; right: auto !important; top: auto !important;
            transform: none !important; margin: 0 auto 0 8px !important; order: -1 !important;
            display: inline-flex !important; align-items: center !important;
        }
        @media all and (max-width: 1024px) { .header .navbar-holder .brand-big { display: inline-flex !important; } }
        .header .navbar-holder .nav-menu { margin-left: auto !important; margin-right: 8px !important; order: 9 !important; flex: 1 1 auto; }
        .header .navbar-holder .nav-menu.ms-header-nav { justify-content: flex-end; }
        .header .navbar-holder .ms-lang-switch--header-end { margin-left: auto !important; }
    </style>
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
                <?php
                $role = \Spatie\Permission\Models\Role::find(Auth::user()->role_id);
                if(!isset($all_permission)) {
                    $permissions = $role->permissions;
                    foreach ($permissions as $permission) {
                        $all_permission[] = $permission->name;
                    }
                }
                $category_permission_active = DB::table('permissions')
                    ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                    ->where([
                        ['permissions.name', 'category'],
                        ['role_id', $role->id] ])->first();
                $index_permission = DB::table('permissions')->where('name', 'products-index')->first();
                $index_permission_active = DB::table('role_has_permissions')->where([
                    ['permission_id', $index_permission->id],
                    ['role_id', $role->id]
                ])->first();
                ?>
                @if(in_array('dashboard', $all_permission))
                    <li data-menu-key="dashboard"><a href="{{ url('/admin') }}"> <i class="dripicons-meter"></i><span>{{ __('file.dashboard') }}</span></a></li>
                @endif
                    @if($category_permission_active || $index_permission_active )
                        <li data-menu-key="product"><a href="#product" aria-expanded="false" data-toggle="collapse"> <i class="dripicons-list"></i><span>{{__('file.product')}}</span><span></a>
                            <ul id="product" class="collapse list-unstyled ">
                                @if($category_permission_active)
                                    <li id="category-menu"><a href="{{route('category.index')}}">{{__('file.category')}}</a></li>
                                @endif
                                @if($index_permission_active)
                                    <li id="product-list-menu"><a href="{{route('products.index')}}">{{__('file.product_list')}}</a></li>
                                        <?php
                                        $add_permission = DB::table('permissions')->where('name', 'products-add')->first();
                                        $add_permission_active = DB::table('role_has_permissions')->where([
                                            ['permission_id', $add_permission->id],
                                            ['role_id', $role->id]
                                        ])->first();
                                        ?>
                                    @if($add_permission_active)
                                        <li id="product-create-menu"><a href="{{route('products.create')}}">{{__('file.add_product')}}</a></li>
                                    @endif
                                @endif
                                @if($category_permission_active)
                                    <li id="ticket-scan"><a href="{{route('admin.ticket.scan.screen')}}">{{__('file.Ticket Scan')}}</a></li>
                                @endif
                                @if($category_permission_active)
                                    <li id="ticket-index"><a href="{{route('admin.ticket.index')}}">{{__('file.Tickets Sold')}}</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                <?php
                $role = DB::table('roles')->find(Auth::user()->role_id);
                $index_permission = DB::table('permissions')->where('name', 'votes-index')->first();
                $index_permission_active = DB::table('role_has_permissions')->where([
                    ['permission_id', $index_permission->id],
                    ['role_id', $role->id]
                ])->first();
                ?>
                @if($index_permission_active)
                    <li data-menu-key="vote"><a href="#vote" aria-expanded="false" data-toggle="collapse"> <i class="dripicons-mail"></i><span>{{trans('file.Vote')}}</span></a>
                        <ul id="vote" class="collapse list-unstyled ">
                            <li id="vote-menu"><a href="{{route('votes.index')}}">{{trans('file.Votes List')}}</a></li>
                            <li id="vote-menu-create"><a id="add-vote" href="">{{trans('file.Create Vote')}}</a></li>
                        </ul>
                    </li>
                <li>
                @endif
                @if(in_array('points_index', $all_permission))
                    <li data-menu-key="point"><a href="#point" aria-expanded="false" data-toggle="collapse"> <i class="fa fa-podcast"></i><span>{{trans('file.Points')}}</span></a>
                        <ul id="point" class="collapse list-unstyled ">
                            <li id="point-awaiting-list"><a href="{{route('points.awaiting_candidates')}}">{{trans('file.Awaiting Candidate')}}</a></li>
                            <li id="point-menu-create"><a href="{{route('points.create')}}">{{trans('file.Grade Candidate')}}</a></li>
                            <li id="point-menu-list"><a href="{{route('points.index')}}">{{trans('file.Grade Listing')}}</a></li>
                        </ul>
                    </li>
                @endif
                @if(in_array('ambassador_point_index', $all_permission))
                    <li data-menu-key="ambassador-point"><a href="#ambassador-point" aria-expanded="false" data-toggle="collapse"> <i class="fa fa-podcast"></i><span>{{trans('file.Ambassador Points')}}</span></a>
                        <ul id="ambassador-point" class="collapse list-unstyled ">
                            <li id="ambassador-point-awaiting-list"><a href="{{route('ambassador_points.awaiting_candidates')}}">{{trans('file.Awaiting Candidate')}}</a></li>
                            <li id="ambassador-point-menu-create"><a href="{{route('ambassador_points.create')}}">{{trans('file.Grade Candidate')}}</a></li>
                            <li id="ambassador-point-menu-list"><a href="{{route('ambassador_points.index')}}">{{trans('file.Grade Listing')}}</a></li>
                        </ul>
                    </li>
                @endif
                @if(in_array('contestant_ranking', $all_permission) || in_array('grading_setting', $all_permission) || in_array('eliminated_candidate', $all_permission) || in_array('qualified_candidate', $all_permission))
                    <li data-menu-key="grading-setting"><a href="#grading-setting" aria-expanded="false" data-toggle="collapse"> <i class="fa fa-gear"></i><span>{{trans('file.Grading')}}</span></a>
                        <ul id="grading-setting" class="collapse list-unstyled ">
                            @if(in_array('grading_setting', $all_permission))
                            <li id="grading-setting-menu"><a href="{{route('setting.grading')}}">{{trans('file.Grading Setting')}}</a></li>
                            @endif
                            @if(in_array('eliminated_candidate', $all_permission))
                            <li id="grading-eliminated"><a href="{{route('report.contestant.eliminated')}}">{{trans('file.Elimination list')}}</a></li>
                            @endif
                            @if(in_array('qualified_candidate', $all_permission))
                            <li id="grading-qualified"><a href="{{route('report.contestant.qualified')}}">{{trans('file.Qualified Contestants')}}</a></li>
                            @endif
                            @if(in_array('contestant_ranking', $all_permission))
                                <li id="contestant-ranking"><a href="{{url('report/contestant/ranking')}}">{{trans('file.Contestant Grading')}}</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                <?php
                $index_permission = DB::table('permissions')->where('name', 'coins-index')->first();
                $index_permission_active = DB::table('role_has_permissions')->where([
                    ['permission_id', $index_permission->id],
                    ['role_id', $role->id]
                ])->first();
                ?>
                @if($index_permission_active)
                    <li data-menu-key="coin"><a href="#coin" aria-expanded="false" data-toggle="collapse"> <i class="fa fa-usd"></i><span>{{trans('file.Coins')}}</span></a>
                        <ul id="coin" class="collapse list-unstyled ">
                            <li id="coin-menu"><a href="{{route('coins.index')}}">{{trans('file.Coins List')}}</a></li>
                            <li id="coin-menu-create"><a id="add-coin" href="">{{trans('file.Create Coins')}}</a></li>
                        </ul>
                    </li>
                    <li>
                @endif
                <?php
                $index_permission = DB::table('permissions')->where('name', 'expenses-index')->first();
                $index_permission_active = DB::table('role_has_permissions')->where([
                    ['permission_id', $index_permission->id],
                    ['role_id', $role->id]
                ])->first();
                ?>

                @if($index_permission_active)
                    <li data-menu-key="expense"><a href="#expense" aria-expanded="false" data-toggle="collapse"> <i class="dripicons-wallet"></i><span>{{trans('file.Expense')}}</span></a>
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
                    <li data-menu-key="people"><a href="#people" aria-expanded="false" data-toggle="collapse"> <i class="dripicons-user"></i><span>{{trans('file.People')}}</span></a>
                        <ul id="people" class="collapse list-unstyled ">

                                    <?php $user_add_permission_active = DB::table('permissions')
                                    ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                                    ->where([
                                        ['permissions.name', 'users-add'],
                                        ['role_id', $role->id] ])->first();
                                    ?>
                                @if($user_add_permission_active)
                                    <li id="user-create-menu"><a href="{{route('user.create')}}">{{trans('file.Add User')}}</a></li>
                                    <li id="user-list-menu"><a href="{{route('user.index')}}">{{trans('file.User List')}}</a></li>
                                    <li id="admin-menu"><a href="{{route('admin.index')}}">{{trans('file.Admin')}}</a></li>
                                    <li id="judge-menu"><a href="{{route('judge.index')}}">{{trans('file.Judges')}}</a></li>
                                    <li id="ambassador-menu"><a href="{{route('ambassador.index')}}">{{trans('file.Ambassadors')}}</a></li>
                                    <li id="voter-menu"><a href="{{route('voter.index')}}">{{trans('file.Voters')}}</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- Dedicated Contestants menu --}}
                @if($index_employee_active || in_array('qualified_candidate', $all_permission) || in_array('contestant_ranking', $all_permission))
                    <li data-menu-key="contestants"><a href="#contestants" aria-expanded="false" data-toggle="collapse"> <i class="fa fa-microphone"></i><span>{{trans('file.Contestants')}}</span></a>
                        <ul id="contestants" class="collapse list-unstyled ">
                            @if($index_employee_active)
                                <li id="employee-menu"><a href="{{route('musician.index')}}">{{trans('file.Contestants')}}</a></li>
                                @if($role->id == 1)
                                    <li id="employee-pending-menu"><a href="{{route('musician.pending.index')}}">{{trans('file.Pending Contestants')}}</a></li>
                                @endif
                            @endif
                            @if(in_array('qualified_candidate', $all_permission))
                                <li id="contestants-qualified"><a href="{{route('report.contestant.qualified')}}">{{trans('file.Qualified Contestants')}}</a></li>
                            @endif
                            @if(in_array('contestant_ranking', $all_permission))
                                <li id="contestants-ranking"><a href="{{url('report/contestant/ranking')}}">{{trans('file.Contestant Grading')}}</a></li>
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
                    <li class="" data-menu-key="account"><a href="#account" aria-expanded="false" data-toggle="collapse"> <i class="dripicons-briefcase"></i><span>{{trans('file.Accounting')}}</span></a>
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
                $voting_report_active = DB::table('permissions')
                    ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                    ->where([
                        ['permissions.name', 'vote-report'],
                        ['role_id', $role->id] ])->first();
                ?>
                @if($voting_report_active)
                    <li data-menu-key="report"><a href="#report" aria-expanded="false" data-toggle="collapse"> <i class="dripicons-document-remove"></i><span>{{trans('file.Reports')}}</span></a>
                        <ul id="report" class="collapse list-unstyled ">
                            @if($voting_report_active)
                                <li id="vote-report-menu">
                                    <a href="{{url('report/voting')}}">{{trans('file.Voting Report')}}</a>
                                </li>
                                <li id="ticket-report-menu">
                                    <a href="{{url('report/ticket/purchase')}}">{{trans('file.Total Purchase Tickets')}}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                <li data-menu-key="setting"><a href="#setting" aria-expanded="false" data-toggle="collapse"> <i class="dripicons-gear"></i><span>{{trans('file.settings')}}</span></a>
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

                        $brand_permission = DB::table('permissions')->where('name', 'brand')->first();
                        $brand_permission_active = DB::table('role_has_permissions')->where([
                            ['permission_id', $brand_permission->id],
                            ['role_id', $role->id]
                        ])->first();

                        $unit_permission = DB::table('permissions')->where('name', 'unit')->first();
                        $unit_permission_active = DB::table('role_has_permissions')->where([
                            ['permission_id', $unit_permission->id],
                            ['role_id', $role->id]
                        ])->first();

                        $tax_permission = DB::table('permissions')->where('name', 'tax')->first();
                        $tax_permission_active = DB::table('role_has_permissions')->where([
                            ['permission_id', $tax_permission->id],
                            ['role_id', $role->id]
                        ])->first();

//                        $customer_group_permission = DB::table('permissions')->where('name', 'customer_group')->first();
//                        $customer_group_permission_active = DB::table('role_has_permissions')->where([
//                            ['permission_id', $customer_group_permission->id],
//                            ['role_id', $role->id]
//                        ])->first();

                        $warehouse_permission = DB::table('permissions')->where('name', 'warehouse')->first();
                        $warehouse_permission_active = DB::table('role_has_permissions')->where([
                            ['permission_id', $warehouse_permission->id],
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
                        @if(in_array('announcement_index', $all_permission))
                            <li id="announcement-menu">
                                <a href="{{ route('announcement.index') }}">{{trans('file.Announcement')}}</a>
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
                            <li id="site-content-menu"><a href="{{route('setting.site_content')}}">Site Content &amp; Sections</a></li>
                            <li id="about-us-menu"><a href="{{route('about_us.settings')}}">{{trans('file.About Us Page')}}</a></li>
                            <li id="about-us-team-menu"><a href="{{route('about_us.index')}}">{{trans('file.Leadership Team')}}</a></li>
                        @endif
{{--                        @if($customer_group_permission_active)--}}
{{--                            <li id="customer-group-menu"><a href="{{route('customer_group.index')}}">{{trans('file.Customer Group')}}</a></li>--}}
{{--                        @endif--}}
                        @if($brand_permission_active)
                            <li id="brand-menu"><a href="{{route('brand.index')}}">{{trans('file.Brand')}}</a></li>
                        @endif
                        @if($unit_permission_active)
                            <li id="unit-menu"><a href="{{route('unit.index')}}">{{trans('file.Unit')}}</a></li>
                        @endif
                        @if($currency_permission_active)
                            <li id="currency-menu"><a href="{{route('currency.index')}}">{{trans('file.Currency')}}</a></li>
                        @endif
                        @if($warehouse_permission_active)
                            <li id="warehouse-menu"><a href="{{route('warehouse.index')}}">{{trans('file.Warehouse')}}</a></li>
                        @endif
                        {{--                      @if($sms_setting_permission_active)--}}
                        {{--                      <li id="sms-setting-menu"><a href="{{route('setting.sms')}}">{{trans('file.SMS Setting')}}</a></li>--}}
                        {{--                      @endif--}}

                    </ul>
                </li>
            </ul>
        </div>

        @php $sabRoleName = $role->name ?? 'Staff'; @endphp
        <div class="side-admin-block">
            <div class="sab-user">
                <div class="sab-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
                <div class="sab-info">
                    <div class="sab-name">{{ Auth::user()->name }}</div>
                    <span class="sab-role">{{ ucwords(str_replace('_', ' ', $sabRoleName)) }}</span>
                </div>
            </div>
            <div class="sab-links">
                <a href="{{ route('user.profile', Auth::user()->id) }}"><i class="dripicons-user"></i> <span>{{trans('file.profile')}}</span></a>
                <a href="#" class="sab-signout" onclick="event.preventDefault(); document.getElementById('sab-logout-form').submit();"><i class="dripicons-export"></i> <span>{{trans('file.logout')}}</span></a>
                <form id="sab-logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
            </div>
        </div>
    </div>
</nav>
<!-- End Side Navbar -->
<script type="text/javascript">
    /* Apply the admin-configured side-menu order (Settings > Site Content > Side Menu). */
    (function () {
        var order = {!! json_encode(\App\Helpers\SiteContent::menuOrder()) !!};
        var menu = document.getElementById('side-main-menu');
        if (!menu || !order || !order.length) { return; }
        order.forEach(function (key) {
            var children = menu.children;
            for (var i = 0; i < children.length; i++) {
                var li = children[i];
                if (li.tagName === 'LI' && li.getAttribute('data-menu-key') === key) {
                    menu.appendChild(li);
                    break;
                }
            }
        });
    })();
</script>
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

                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center ms-header-nav">
                    <li class="nav-item"><a id="btnFullscreen" data-toggle="tooltip" title="{{trans('file.Full Screen')}}"><i class="dripicons-expand"></i></a></li>

                    @php $headerRoleName = $role->name ?? 'Staff'; @endphp
                    <li class="nav-item nav-user-dropdown dropdown">
                        <a href="#" class="nav-link dropdown-toggle nav-user-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="nav-user-chip">
                                <span class="nav-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                                <span class="nav-user-meta d-none d-md-inline-flex">
                                    <span class="nav-user-name">{{ Auth::user()->name }}</span>
                                    <span class="nav-user-role">{{ strtoupper(str_replace('_', ' ', $headerRoleName)) }}</span>
                                </span>
                                <i class="fa fa-angle-down nav-user-chevron"></i>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right nav-user-menu">
                            <li class="dropdown-header">{{ trans('file.My Account') }}</li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/admin') }}"><i class="fa fa-th-large"></i> {{ trans('file.Admin Dashboard') }}</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.profile', Auth::user()->id) }}"><i class="dripicons-user"></i> {{ trans('file.profile') }}</a>
                            </li>
                            <li class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item nav-user-logout" href="#" onclick="event.preventDefault(); document.getElementById('header-logout-form').submit();">
                                    <i class="dripicons-export"></i> {{ trans('file.logout') }}
                                </a>
                                <form id="header-logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item ms-lang-switch ms-lang-switch--header-end">
                        <a href="{{ url('language_switch/en') }}" class="ms-lang {{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                        <a href="{{ url('language_switch/fr') }}" class="ms-lang {{ app()->getLocale() == 'fr' ? 'active' : '' }}">FR</a>
                    </li>

                    <li class="nav-item" style="display:none">
                        <a rel="nofollow" data-toggle="tooltip" class="nav-link dropdown-item"><i class="dripicons-web"></i></a>
                        <ul class="right-sidebar">
                            <li>
                                <a href="{{ url('language_switch/en') }}" class="btn btn-link"> English</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/fr') }}" class="btn btn-link"> Français</a>
                            </li>
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/es') }}" class="btn btn-link"> Español</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/ar') }}" class="btn btn-link"> عربى</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/pt_BR') }}" class="btn btn-link"> Portuguese</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/de') }}" class="btn btn-link"> Deutsche</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/id') }}" class="btn btn-link"> Malay</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/hi') }}" class="btn btn-link"> हिंदी</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/vi') }}" class="btn btn-link"> Tiếng Việt</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/ru') }}" class="btn btn-link"> русский</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/bg') }}" class="btn btn-link"> български</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/tr') }}" class="btn btn-link"> Türk</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/it') }}" class="btn btn-link"> Italiano</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/nl') }}" class="btn btn-link"> Nederlands</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('language_switch/lao') }}" class="btn btn-link"> Lao</a>--}}
{{--                            </li>--}}
                        </ul>
                    </li>
                    <li class="nav-item" style="display:none">
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

    <!-- expense modal -->
    <div id="vote-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Add Vote')}}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                    {!! Form::open(['route' => 'votes.store', 'method' => 'post']) !!}
                    <?php
                    $users = \App\User::where('role_id', 3)->where('is_active', true)->where('is_deleted', false)->orderBy('id', 'desc')->get();
                    $contentants = \App\Employee::where('is_active', true)->where('is_approve', true)->orderBy('id', 'desc')->get();
                    ?>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>{{trans('file.Reference')}} *</label>
                            <input type="text" name="reference" step="any" required class="form-control" placeholder="Any reference no">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{trans('file.Votes')}} *</label>
                            <input type="number" name="vote" step="any" required class="form-control" value="1">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{trans('file.Voter name')}} *</label>
                            <select name="user_id" class="selectpicker form-control" required data-live-search="true"   title="{{trans('file.Select Voter')}}...">
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{trans('file.Contestants name')}} *</label>
                            <select name="musician_id" class="selectpicker form-control" required data-live-search="true"   title="Select Contentant...">
                                @foreach($contentants as $contentant)
                                    <option value="{{$contentant->id}}">{{$contentant->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <input class="mt-2" type="checkbox" name="status" value="1" checked>
                        <label class="mt-2"><strong>Complete</strong></label>
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


    <!-- expense modal -->
    <div id="coin-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Add Coin')}}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                    {!! Form::open(['route' => 'coins.store', 'method' => 'post']) !!}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>{{trans('file.Phone Number')}} *</label>
                            <input type="number" name="phone" step="any" required class="form-control" value="+237" placeholder="{{trans('file.Phone Number')}}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{trans('file.Coins')}} *</label>
                            <input type="number" name="coin" step="any" required class="form-control" >
                        </div>
                        <div class="col-md-6 form-group">
                            <label><strong>{{trans('file.Code')}} *</strong> </label>
                            <div class="input-group">
                                <input type="text" name="code" required class="form-control">
                                <div class="input-group-append">
                                    <button id="genbutton" type="button" class="btn btn-default">{{trans('file.Generate')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input class="mt-2" type="checkbox" name="is_active" value="1" checked>
                        <label class="mt-2"><strong>Active</strong></label>
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
        <nav id="ms-section-tabs" class="ms-section-tabs" aria-label="Section navigation" style="display:none"></nav>
        @yield('content')
    </div>

    <footer class="main-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <p>&copy; {{$general_setting->site_title}} | {{trans('file.Developed')}} {{trans('file.By')}} <span class="external">{{env('DEVELOPED_BY')}}</span></p>
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
    /* Build colorful page-level tabs from the active sidebar submenu group. */
    (function () {
        function buildSectionTabs() {
            var host = document.getElementById('ms-section-tabs');
            if (!host) return;
            var path = (window.location.pathname || '/').replace(/\/+$/, '') || '/';
            var groups = document.querySelectorAll('nav.side-navbar .side-menu ul.collapse');
            var activeGroup = null, activeLink = null, bestLen = -1;
            groups.forEach(function (ul) {
                ul.querySelectorAll('a').forEach(function (a) {
                    var href = a.getAttribute('href');
                    if (!href || href === '#' || href === '') return;
                    var lp;
                    try { lp = (new URL(a.href, window.location.origin).pathname || '').replace(/\/+$/, '') || '/'; } catch (e) { return; }
                    if (lp === '/') return;
                    if (path === lp || path.indexOf(lp + '/') === 0) {
                        if (lp.length > bestLen) { bestLen = lp.length; activeGroup = ul; activeLink = a; }
                    }
                });
            });
            if (!activeGroup) { host.style.display = 'none'; return; }
            var palette = ['#3b82f6', '#22c55e', '#f59e0b', '#a855f7', '#ec4899', '#14b8a6', '#ef4444', '#6366f1', '#0ea5e9', '#84cc16'];
            var frag = document.createDocumentFragment(), i = 0;
            activeGroup.querySelectorAll('a').forEach(function (a) {
                var href = a.getAttribute('href');
                if (!href || href === '#' || href === '') return;
                var color = palette[i % palette.length]; i++;
                var pill = document.createElement('a');
                pill.href = a.href;
                pill.className = 'ms-tab';
                pill.textContent = (a.textContent || '').trim();
                pill.style.setProperty('--tab-color', color);
                if (a === activeLink) pill.classList.add('is-active');
                frag.appendChild(pill);
            });
            if (!frag.childNodes.length) { host.style.display = 'none'; return; }
            host.innerHTML = '';
            host.appendChild(frag);
            host.style.display = 'flex';
        }
        if (document.readyState !== 'loading') buildSectionTabs();
        else document.addEventListener('DOMContentLoaded', buildSectionTabs);
    })();
</script>
<script type="text/javascript">
    /* No dropdowns: turn every collapsible sidebar parent into a direct link
       that opens its first real sub-page. Sub-navigation lives in the page tabs. */
    (function () {
        function flattenSidebar() {
            var toggles = document.querySelectorAll('nav.side-navbar .side-menu > li > a[data-toggle="collapse"]');
            toggles.forEach(function (a) {
                var li = a.parentElement;
                var submenu = li ? li.querySelector('ul') : null;
                if (!submenu) return;
                var target = null;
                submenu.querySelectorAll('a').forEach(function (s) {
                    if (target) return;
                    var href = (s.getAttribute('href') || '').trim();
                    if (!href || href === '#' || href.indexOf('javascript') === 0) return;
                    target = s.href;
                });
                if (!target) return;
                a.setAttribute('href', target);
                a.removeAttribute('data-toggle');
                a.removeAttribute('aria-expanded');
                a.classList.add('ms-direct');
            });
        }
        if (document.readyState !== 'loading') flattenSidebar();
        else document.addEventListener('DOMContentLoaded', flattenSidebar);
    })();
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

    $("a#add-vote").click(function(e){
        e.preventDefault();
        $('#vote-modal').modal();
    });

    $("a#add-coin").click(function(e){
        e.preventDefault();
        $('#coin-modal').modal();
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


    $('#genbutton').on("click", function(){
        $.get('/user/genpass', function(data){
            $("input[name='code']").val(data);
        });
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

    /** Collect row ids from a DataTables listing (selected rows + checked boxes). */
    function collectSelectedTableIds(tableSelector) {
        var ids = [];
        var $table = $(tableSelector);
        if (!$table.length || !$.fn.DataTable.isDataTable($table)) {
            return ids;
        }
        var table = $table.DataTable();
        table.rows({ selected: true }).every(function () {
            var id = $(this.node()).data('id');
            if (id) { ids.push(id); }
        });
        if (!ids.length) {
            $table.find('tbody tr').each(function () {
                if ($(this).find('input[type="checkbox"]').is(':checked')) {
                    var id = $(this).data('id');
                    if (id) { ids.push(id); }
                }
            });
        }
        return ids;
    }
</script>

<script type="text/javascript">
/* Paste-an-image support for photo upload fields (Contestant / Jury / Ambassador).
   Crop/copy an image anywhere, click the form, then press Ctrl/Cmd+V and it is
   attached to that record's photo field with a live preview. */
(function () {
    var IMG_SELECTOR = 'input[type="file"][name="image"]';
    var lastTarget = null;

    function enhance(input) {
        if (input.dataset.pasteReady === '1') { return; }
        input.dataset.pasteReady = '1';

        var hint = document.createElement('div');
        hint.className = 'paste-image-hint';
        hint.style.cssText = 'font-size:12px;color:#3b5bdb;margin-top:4px;';
        hint.innerHTML = '<i class="dripicons-clipboard"></i> Tip: click here, then paste a copied/cropped image (Ctrl+V / \u2318V).';
        input.insertAdjacentElement('afterend', hint);

        var preview = document.createElement('div');
        preview.className = 'paste-image-preview';
        preview.style.cssText = 'margin-top:6px;display:none;';
        preview.innerHTML = '<img alt="preview" style="max-height:90px;border-radius:6px;border:1px solid #d0d7de;">' +
                            '<span class="paste-image-name" style="display:block;font-size:11px;color:#6b7280;margin-top:2px;"></span>';
        hint.insertAdjacentElement('afterend', preview);

        var mark = function () { lastTarget = input; };
        input.addEventListener('focus', mark);
        input.addEventListener('click', mark);
        var group = input.closest('.form-group') || input.parentElement;
        if (group) { group.addEventListener('click', mark); }
    }

    function showPreview(input, file) {
        var wrap = input.parentElement.querySelector('.paste-image-preview');
        if (!wrap) { return; }
        var img = wrap.querySelector('img');
        var name = wrap.querySelector('.paste-image-name');
        var reader = new FileReader();
        reader.onload = function (ev) {
            img.src = ev.target.result;
            if (name) { name.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)'; }
            wrap.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    function pickTarget() {
        var openModal = document.querySelector('.modal.show ' + IMG_SELECTOR + ', .modal.in ' + IMG_SELECTOR);
        if (openModal && openModal.offsetParent !== null) { return openModal; }
        if (lastTarget && document.body.contains(lastTarget) && lastTarget.offsetParent !== null) { return lastTarget; }
        var all = Array.prototype.slice.call(document.querySelectorAll(IMG_SELECTOR));
        for (var i = 0; i < all.length; i++) { if (all[i].offsetParent !== null) { return all[i]; } }
        return null;
    }

    function attach(input, blob) {
        var ext = (blob.type && blob.type.split('/')[1]) ? blob.type.split('/')[1] : 'png';
        var file = new File([blob], 'pasted-' + Date.now() + '.' + ext, { type: blob.type || 'image/png' });
        try {
            var dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
        } catch (err) {
            return false;
        }
        input.dispatchEvent(new Event('change', { bubbles: true }));
        showPreview(input, file);
        return true;
    }

    function init() {
        Array.prototype.slice.call(document.querySelectorAll(IMG_SELECTOR)).forEach(enhance);
    }

    document.addEventListener('paste', function (e) {
        var items = (e.clipboardData || window.clipboardData) ? (e.clipboardData || window.clipboardData).items : null;
        if (!items) { return; }
        var blob = null;
        for (var i = 0; i < items.length; i++) {
            if (items[i].type && items[i].type.indexOf('image') === 0) {
                blob = items[i].getAsFile();
                break;
            }
        }
        if (!blob) { return; }
        var target = pickTarget();
        if (!target) { return; }
        e.preventDefault();
        if (attach(target, blob)) {
            var group = target.closest('.form-group') || target.parentElement;
            if (group) {
                group.style.transition = 'box-shadow .3s';
                group.style.boxShadow = '0 0 0 3px rgba(59,91,219,.35)';
                setTimeout(function () { group.style.boxShadow = 'none'; }, 900);
            }
        }
    });

    if (document.readyState !== 'loading') { init(); } else { document.addEventListener('DOMContentLoaded', init); }
    // Re-scan when Bootstrap modals open (edit dialogs inject their inputs then).
    if (window.jQuery) {
        jQuery(document).on('shown.bs.modal', init);
    }
})();
</script>
</body>
</html>
