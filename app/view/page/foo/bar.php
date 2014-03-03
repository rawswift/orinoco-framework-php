<!-- you can use '$this' object context reference -->
<h1><?=$this->title?></h1>
<!-- or no reference at all -->
<p><?=$content?></p>

<p><?php echo '/' . $app->Request->Route->getController() . '/' . $app->Request->Route->getAction(); ?></p>
