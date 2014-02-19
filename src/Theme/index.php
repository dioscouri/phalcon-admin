<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<head>
    <?php echo $this->renderView('Admin/Views::head'); ?>
</head>

<body class="<?php echo !empty($body_class) ? $body_class : ''; ?>">

    <?php echo $this->renderView('Admin/Views::header'); ?>
    
    <?php echo $this->renderView('Admin/Views::Nav/left'); ?>

    <div id="main" role="main">
        
		<div id="ribbon">
		<?php // breadcrumb? ?>
		</div>
		<!-- #ribbon -->
		
    	<div id="content">		
    
            <tmpl type="system.messages" />
    
            <tmpl type="view" />
        
        </div> <!-- #content -->
		
    </div>
    <!-- #main -->
    
    <?php // echo $this->renderView('Admin/Views::footer'); ?>
    
    <?php echo $this->renderView('Admin/Views::js_footer'); ?>
    
</body>

</html>