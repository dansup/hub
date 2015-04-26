<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="/favicon.ico">
<title><?=$this->e($title)?> - Hub</title>
<link href="/assets/css/clear-sans.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/core.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/landing.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="masthead clearfix">
            <div class="inner">
              <h3 class="masthead-brand">Hub</h3>
              <nav>
                <ul class="nav masthead-nav">
                  <li class="active"><a href="/">Home</a></li>
                  <li><a href="/site/features">Features</a></li>
                  <li><a href="/nodes">NodeDB</a></li>
                </ul>
              </nav>
            </div>
          </div>

	<?=$this->section('content')?>

          <div class="mastfoot">
            <div class="inner">
              <p></p>
            </div>
          </div>

        </div>

      </div>

<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/jquery.timeago.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery("time.timeago").timeago();
});
</script>
<?=$this->section('extra_js')?>
</body>
</html>