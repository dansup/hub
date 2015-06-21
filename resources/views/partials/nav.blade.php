<nav class="navbar navbar-default">
<div class="container">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">Hub</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li><a href="{{ url('/nodes') }}">Nodes</a></li>
            {{-- Hidden until finished! <li><a href="{{ url('/maps/graph') }}">Map</a></li>
           <li><a href="{{ url('/services') }}">Services</a></li> --}}
            <form class="navbar-form navbar-right">
            <input type="text" id="autocomplete" class="form-control" placeholder="Search...">
            <div class="autocomplete-suggestions">
            </div>
            </form>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            @if (Auth::guest())
{{--             <li><a href="{{ url('/auth/login') }}">Login</a></li>
            <li><a href="{{ url('/auth/register') }}">Register</a></li> --}}
            @else
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
            <li><a href="{{ url('/nodes/me') }}">My Node</a></li>
            <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
            </ul>
            </li>
            @endif
        </ul>
    </div>
</div>
</nav>