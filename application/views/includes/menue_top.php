<!-- menue top -->
<div class="navbar navbar-default navbar-fixed-top">
<div class="container">
<div class="navbar-header">
<a href="<?php echo base_url(); ?>" class="navbar-brand"><span class="glyphicon glyphicon-cloud"></span> <?php echo $header_name; ?></a>
<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
</div>
<div class="navbar-collapse collapse" id="navbar-main">
<ul class="nav navbar-nav">	

<li class="dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes"><span class="glyphicon glyphicon-user"></span> <?php echo $page_username; ?> <span class="caret"></span></a>
<ul class="dropdown-menu">
<li><a href="<?php echo site_url('uploads/myuploads');?>">Meine Uploads</a></li>
<li class="divider"></li>
<li><a href="<?php echo base_url(); ?>">Neue Uploads</a></li>
</ul>
</li>

<li><a href="<?php echo base_url(); ?>">Downloadarea</a></li>
<li><a href="<?php echo $this->forum_link; ?>">Forum</a></li>

</ul>
<div class="navbar-collapse collapse">
<form class="navbar-form navbar-right" method="POST" action="<?php echo site_url('uploads/search'); ?>" role="form">
<div class="form-group">
<input type="text" placeholder="Suchen" name="text" class="form-control">
</div> 
<button type="submit" name="submit" value="1" class="btn btn-success">Suchen</button>
</form>
</div><!--/.navbar-collapse -->

</div>
</div>
</div>