<?php $this->layout('template', ['title' => 'Browse']); ?>

    <style type="text/css">
        a, a:hover, a:active {
            color: #F9690E;
        }
        .browse a {
            color:#666;
        }
        .nav > li > a:hover, .nav > li > a:focus {
            text-decoration: none;
            background-color: #FEE1CF;
        }

        .nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus {
            color: #FFF;
            background-color: #F9690E;
        }
    </style>

    <div class="promo promo-nodes">
        <div class="container">
            <div class="text-center">
                <h1>Nodes</h1>
                <p class="lead">the hyperboria node directory.</p>
            </div>
        </div>
    </div>
    <div class="container" role="main">
        <div class="col-xs-12 col-md-8 col-md-offset-2 centered-pills" style="padding:40px 0;">
            <ul class="nav nav-pills">
                <li role="presentation" class="active"><a href="/node/browse">All</a></li>
                <li role="presentation"><a href="/node/browse?ob=3">Recently Added</a></li>
                <li role="presentation"><a href="/node/search">Search</a></li>
                <li role="presentation"><a href="/node/<?=filter_var($_SERVER['REMOTE_ADDR'])?>">My Node</a></li>
            </ul>
            <hr>
        </div>
        <div class="col-xs-12 col-md-8 col-md-offset-2 browse">
            <?=$node->allKnownNodes($page, $order_by)?>
        </div>
    </div>
    <?php $this->start('extra_js') ?>
    <script type="text/javascript">
    $('.dropdown-toggle').dropdown();
    $('.browse #node_addr').each(function() {
        $(this).html($(this).html().substr(0, $(this).html().length-4)
            + "<span style='color: #F9690E;'>"
            + $(this).html().substr(-4)
            + "</span>");
    });
    </script>


    <?php $this->stop() ?>
   
