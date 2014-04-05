<h1><?=$this->title?></h1>
<p><?=$this->content?></p>

<p><?php echo '/' . $this->app->Request->Route->getController() . '/' . $this->app->Request->Route->getAction(); ?></p>
