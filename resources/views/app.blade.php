<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{env('APP_TITLE')}}</title>

	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/angular-loading-bar.min.css') }}" rel="stylesheet">

</head>
<body ng-app="App">
	<div ng-cloak>
        <div class="row" ng-show='appError === 1'>
            <div class="alert alert-danger">
                <ul>
                    <li ng-repeat='e in appErrors'>
                        <% e %>
                    </li>
                </ul>
            </div>
        </div>

        <div id="wrapper" ng-if='appLoadSuccess === 1'>
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ URL::to('/') }}" style="margin-left: 12px;">{{ env('APP_TITLE') }}</a>
                </div>

                @if (!Auth::guest())
    	            <ul class="nav navbar-top-links navbar-right">
    	                <li class="dropdown">
    	                    <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;;">
    	                        @if (Auth::user()->name != "") {{ Auth::user()->name }} @else {{ Auth::user()->username }} @endif <span class="caret"></span>
    	                    </a>
    	                    <ul class="dropdown-menu dropdown-user">
    	                        <li><a href="#/settings">{{ trans('app.settings') }}</a></li>
    							<li><a href="{{ URL::route('logout') }}">{{ trans('app.logout') }}</a></li>
    	                    </ul>
    	                </li>
    	            </ul>
                @endif

                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu" ng-controller="NavController as nav">
                            <li>
                                <a href="#/" ng-class='{ active:nav.activeLink === 0 }' ng-click='nav.activeLink = 0;nav.activeSubLink = null'>
                                	<i class="fa fa-home"></i>
                                	{{ trans('app.dashboard') }}
                                </a>
                            </li>
                            <li ng-repeat="n in nav.routes track by $index" ng-init='menuNum=$index' ng-if='user_roles.permissions[n.name+"_access"]'>
                                <a href='#<% n.url %>' data-toggle="collapse" data-target="#sub<% $index+1 %>" ng-class='{ active:nav.activeLink === $index+1 }' ng-click='nav.activeLink = $index+1;nav.activeSubLink = null'>
                                	<i class="fa <% n.icon %> fa-fw" ng-show='n.icon !== undefined'></i>
                                	<% n.title %>
                                	<span class="fa arrow" ng-show='n.subMenu !== undefined'></span>
                                </a>
                                <ul ng-show='n.subMenu !== undefined' id="sub<% $index+1 %>" class="nav nav-second-level collapse out">
                                	<li ng-repeat="s in n.subMenu track by $index" ng-if='user_roles.permissions[s.name+"_access"]'>
                                		<a href="#<% s.url %>" ng-class='{ active:nav.activeSubLink === menuNum+"-"+$index+1 }' ng-click='nav.activeSubLink = menuNum+"-"+$index+1; nav.activeLink = null'>
                                			<i class="fa <% s.icon %> fa-fw" ng-show='s.icon !== undefined'></i>
                                			<% s.title %>
                                		</a>
                                	</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div id="page-wrapper" ui-view></div>
        </div>
    </div>
    @include('footer')

	<!-- Scripts -->
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/angular.min.js') }}"></script>
	<script src="{{ asset('js/angular-ui-router.min.js') }}"></script>
	<script src="{{ asset('js/angular-loading-bar.min.js') }}"></script>
    <script src="{{ asset('js/smart-table.min.js') }}"></script>
	<script src="{{ asset('js/config.js') }}"></script>
	<script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/angular.directives.js') }}"></script>

</body>
</html>
