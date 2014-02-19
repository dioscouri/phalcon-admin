<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

	<!-- User info -->
	<div class="login-info">
	    <?php $identity = $this->auth->getIdentity(); ?>
		<span> <a href="javascript:void(0);" id="show-shortcut"> <?php echo \Dsc\Lib\ArrayHelper::get($identity, 'name'); ?> </a> </span>
	</div>
	<!-- end user info -->

	<!-- NAVIGATION : This navigation is also responsive

	To make this navigation dynamic please make sure to link the node
	(the reference to the nav > ul) after page load. Or the navigation
	will not initialize.
	-->
	<nav>
		<!-- NOTE: Notice the gaps after each icon usage <i></i>..
		Please note that these links work a bit different than
		traditional hre="" links. See documentation for details.
		-->

		<ul>
			<li>
				<a href="./admin" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
			</li>

		</ul>
	</nav>
	<span class="minifyme"> <i class="fa fa-arrow-circle-left hit"></i> </span>

</aside>
<!-- END NAVIGATION -->