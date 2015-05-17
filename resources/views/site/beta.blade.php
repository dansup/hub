@extends('app')

@section('content')

<div class="row">
<div class="col-xs-12">
    <div class="page-header text-center">
        <h2>Hub Beta</h2>
    </div>
    <p class="lead text-center">Welcome to the public hub beta!</p>
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 panel panel-default">
        <div class="panel-body" style="font-size:17px;">
            <p>We are making great progress, we hope launch the public beta this weekend!</p>
            <p class="text-right small">posted: May 14 2015</p>
        </div>
    </div>

    <div class="col-xs-12 col-sm-8 col-sm-offset-2 panel panel-default">
        <div class="panel-body" style="font-size:17px;">
            <p class="lead text-center">Known Bugs:</p>
            <ul>
                <li><strike>CAPI Cron Collection</strike> - Fixed using closure artisan scheduling</li>
                <li><strike>IPv6 Padding</strike> - Fixed w/ custom <code>Request::ip()</code></li>
                <li>Non-unique avatar - need to port code from slim-legacy</li>
                <li>cjdns bug counts <code>self::public_key</code> as a peer - temporary fix by unsetting first result, should fix on the <code>$node->save()</code> method</li>
            </ul>
        </div>
    </div>

    <div class="col-xs-12 col-sm-8 col-sm-offset-2 panel panel-default">
        <div class="panel-body" style="font-size:17px;">
            <p class="lead text-center">Todo:</p>
            <ul>
                <li>duplicate pk2ip handler (requires <code>public_key</code> as primary key)</li>
                <li>Missing foreign keys on <code>App\Node</code> model</li>
                <li>fix styling on followModal</li>
                <li>API v1</li>
                <li>API v1.1 (oauth)</li>
                <li>nmapper cron, controller, model</li>
                <li>nodeinfo.json discoverer</li>
                <li>lucerne ?</li>
                <li>caching (<code>Cache::</code>)</li>
                <li>News</li>
                <li>Maps</li>
                <li>People</li>
                <li>Services</li>
            </ul>
        </div>
    </div>
</div>
</div>

@endsection