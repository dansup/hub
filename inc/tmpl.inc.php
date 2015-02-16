<?php

class Template {
	public function getCss($type=null)
	{
		$type = isset($type) ? filter_var($type) : "default";
		switch ($type) {
			case 'default':
			$css = '<link href="/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/prod/core.css?v=3" rel="stylesheet" type="text/css" />
<link href="/assets/prod/app.css?v='.time().'" rel="stylesheet" type="text/css" />

';
			break;
			case 'node':
			$css = '<link href="/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/prod/core.css?v=3" rel="stylesheet" type="text/css" />
<link href="/assets/prod/app.css?v='.time().'" rel="stylesheet" type="text/css" />
<link href="/assets/prod/morris.css" rel="stylesheet" type="text/css" />

';
			break;

			case 'nodeactivity':
			$css = '<link href="/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/prod/core.css?v=3" rel="stylesheet" type="text/css" />
<link href="/assets/prod/app.css" rel="stylesheet" type="text/css" />
<link href="/assets/prod/morris.css" rel="stylesheet" type="text/css" />

';
			break;
			case 'full':
			$css = '<link href="/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/prod/core.css?v=3" rel="stylesheet" type="text/css" />
<link href="/assets/prod/app.css" rel="stylesheet" type="text/css" />

';
			break;
			case 'user':
			$css = '<link href="/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/prod/core.css?v=3" rel="stylesheet" type="text/css" />
<link href="/assets/prod/app.css" rel="stylesheet" type="text/css" />
<link href="/assets/prod/user.css" rel="stylesheet" type="text/css" />
';
			break;
			default:
			$css = '<link href="/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/prod/core.css?v=3" rel="stylesheet" type="text/css" />
<link href="/assets/prod/app.css" rel="stylesheet" type="text/css" />

';
			break;
		}
		return $css;
	}
	public function getNav($type, $active_page, $nav_data = null)
	{
		$menu = null;$home = null;$about = null;$apis = null;$apps = null;$nodes = null;$people = null;$pgp = null;$rss = null;$services = null;$websites = null;$mynode = null;
		$show_usermenu = ($type === 'account') ? true : false;

		switch ($active_page) {
			case 'Home':
				$home = ' class="active"';
				break;
			case 'About':
				$about = ' class="active"';
				break;
			case 'APIs':
				$apis = ' class="active"';
				break;
			case 'Apps':
				$apps = ' class="active"';
				break;
			case 'Nodes':
				$nodes = ' class="active"';
				break;
			case 'People':
				$people = ' class="active"';
				break;
			case 'PGP':
				$pgp = ' class="active"';
				break;
			case 'RSS':
				$rss = ' class="active"';
				break;
			case 'Services':
				$services = ' class="active"';
				break;
			case 'Websites':
				$websites = ' class="active"';
				break;
			case 'My Node':
				$mynode = ' class="active"';
				break;
			default:
				break;
		}
		$menu .= '<li'.$nodes.'><a href="/node/browse">Nodes</a></li>';
		$menu .= '<!--<li'.$people.'><a href="/people">People</a></li>-->';
		$menu .= '<li'.$services.'><a href="/services">Services</a></li>';
		$menu .= '<li class="divider"></li>';
		if($show_usermenu === true && $nav_data !== false)
		{
		$username = htmlentities($nav_data['username']);
		$avatar = $nav_data['avatar'];
		$email = $nav_data['email'];
		$menu .= '</ul><ul class="nav navbar-nav navbar-right"><li'.$mynode.'><a href="/me">My Node</a></li>
                                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Account
                                        <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <div class="navbar-content">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <img src="'.$avatar.'"
                                                                alt="Alternate Text" width="120px" class="img-responsive" />
                                                            <p class="text-center small">
                                                                <a href="/account/edit-avatar">Change Photo</a></p>
                                                        </div>
                                                        <div class="col-md-7">
                                                            <span>'.$username.'</span>
                                                            <p class="text-muted small">
                                                                '.$email.'</p>
                                                            <div class="divider">
                                                            </div>
                                                            <a href="/people/'.$username.'" class="btn btn-primary btn-sm active">View Profile</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="navbar-footer">
                                                    <div class="navbar-footer-content">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <a href="/account/edit-password" class="btn btn-default btn-sm">Change Password</a>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <a href="/logout" class="btn btn-default btn-sm pull-right">Sign Out</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>';
		}
		else {
		$menu .= '</ul>
<form class="navbar-form navbar-left" role="search" action="/search" method="GET">
<input type="hidden" name="t" value="node"> 
<input type="hidden" name="l" value="en-US">    
<input type="hidden" name="oi" value="'.sha1($_SERVER['REMOTE_ADDR']).'">
<input type="hidden" name="ts" value="'.time().'">
<div class="form-group">
<input type="text" class="form-control" name="q" placeholder="search ip, owner, host">
</div>
</form>
<ul class="nav navbar-nav navbar-right">
<li'.$mynode.'><a href="/me">My Node</a></li>';
		}
		return $menu;
	}
	public function getJs($type)
	{
		$type = isset($type) ? filter_var($type) : "default";
		switch ($type) {
			case 'default':
			$js = '
<footer class="footer">
<div class="container">
<p class="text-muted"><a href="/about">About</a> &nbsp; <a href="/blog">Blog</a> &nbsp; <a href="/blog/2014/12/help-3ba9fdc3ee">Help</a> &nbsp; <a href="/blog/2014/12/report-issue-1bf2b513b9">Report</a></p>
</div>
</footer>
<script src="/assets/js/jquery.min.js?v=production"></script>
<script src="/assets/js/bootstrap.min.js?v=production"></script>
<script src="/assets/js/jquery.timeago.js?v=production"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery("time.timeago").timeago();
});
</script>
';
			break;
			case 'full':
			$js = '
<footer class="footer">
<div class="container">
<p class="text-muted"><a href="/about">About</a> &nbsp; <a href="/blog">Blog</a> &nbsp; <a href="/blog/2014/12/help-3ba9fdc3ee">Help</a> &nbsp; <a href="/blog/2014/12/report-issue-1bf2b513b9">Report</a></p>
</div>
</footer>
<script src="/assets/js/jquery.min.js?v=production"></script>
<script src="/assets/js/bootstrap.min.js?v=production"></script>
<script src="/assets/js/application.js?v=production"></script>
<script src="/assets/js/jquery.timeago.js?v=production"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery("time.timeago").timeago();
});
</script>
';
			break;
			default:
			$js = '
<footer class="footer">
<div class="container">
<p class="text-muted"><a href="/about">About</a> &nbsp; <a href="/blog">Blog</a> &nbsp; <a href="/blog/2014/12/help-3ba9fdc3ee">Help</a> &nbsp; <a href="/blog/2014/12/report-issue-1bf2b513b9">Report</a></p></div>
</footer>
<script src="/assets/js/jquery.min.js?v=production"></script>
<script src="/assets/js/bootstrap.min.js?v=production"></script>
<script src="/assets/js/jquery.timeago.js?v=production"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery("time.timeago").timeago();
});
</script>
';
			break;
		}
		return $js;        
	}
}
$template = new Template();