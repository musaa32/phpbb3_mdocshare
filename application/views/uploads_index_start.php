<div class="container">
<div class="page-header" id="banner">
<div class="row">
<div class="col-lg-6">
<h1>Downloadarea</h1>
<p class="lead">Hier findest du alle Uploads geordnet nach Kategorien</p>
</div>     
</div>
		
<div class="row">
<div class="col-lg-12">
<?php 
if($this->session->flashdata('fehlertext'))
{	
echo '<div class="alert alert-dismissable alert-warning">';
echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
echo $this->session->flashdata('fehlertext');
echo '</div>';
}
?>
<ol class="breadcrumb">
<li><a href="<?php echo site_url('uploads'); ?>">Downloadarea</a></li>
<li class="active">Neue Uploads (10)</li>
</ol>
<p><?php echo $forum_name['forum_desc']; ?> </p>

</div>
<!--  FORUM KATEGORIEN -->
<div class="col-lg-3">

<div class="list-group">
<?php for($i = 0 ; $i < count($page_context); $i++) {
$active = '';
if($page_context[$i]['forum_id'] == $forum_name['forum_id'])
	$active = ' active';
?>


<a href="<?php echo site_url('uploads/'.$page_context[$i]['forum_id']); ?>" class="list-group-item<?php echo $active; ?>">
<?php echo $page_context[$i]['forum_name']; ?>
</a>
<?php } ?>
</div>

</div>
<!-- ENDE FORUM KATEGORIEN -->
        
		
<div class="col-lg-9">

<div class="list-group">

<?php for($i = 0 ; $i < count($page_new_attachments); $i++) {?>
<a href="<?php echo $this->forum_link.'download/file.php?id='.$page_new_attachments[$i]['attach_id']; ?>" class="list-group-item">
<h4 class="list-group-item-heading"> <?php echo $page_new_attachments[$i]['real_filename']; ?></h4>
<h5><span class="glyphicon glyphicon-floppy-disk"></span> <?php echo (int)($page_new_attachments[$i]['filesize']/1024); ?> KB &middot; <span class="glyphicon glyphicon-user"></span> <?php echo $page_new_attachments[$i]['username']; ?>  &middot; <span class="glyphicon glyphicon-calendar"></span> <?php echo date($config_dateformat, $page_new_attachments[$i]['filetime']); ?> &middot;  <span class="glyphicon glyphicon-save"></span> <?php echo $page_new_attachments[$i]['download_count'];?> mal heruntergeladen </h5>
<p class="list-group-item-text"><?php echo $page_new_attachments[$i]['attach_comment']; ?></p>
</a>
 <?php } ?>                 
</div>	

</div>
</div>
		
</div>

    

 
 

<footer>
<div class="row">
<div class="col-lg-12">
<p>Coded with <span class="glyphicon glyphicon-heart"></span> in Austria by MA.</p>
</div>	
</div>
</footer>
</div>
