<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?php echo $this->_vars->root; ?>" />
<title><?php echo $this->_vars->page_title; ?></title>
<link rel="stylesheet" href="<?php echo $this->_vars->main_theme; ?>" type="text/css" />
<?php if(isset($this->_vars->page_theme) && strlen($this->_vars->page_theme) > 0){ ?>
<link rel="stylesheet" href="<?php echo $this->_vars->page_theme; ?>" type="text/css" />
<?php } ?>
<?php foreach($this->_vars->scripts as $script){ ?>
<script type="text/javascript" src="<?php echo $script; ?>" ></script>
<?php } ?>
</head>
<body>
<div id="content">
	<?php
	echo $this->_vars->content . "\n";
	?>
</div>
</body>
</html>