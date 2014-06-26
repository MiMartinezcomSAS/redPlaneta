<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die();
?>

<div id="community-clasificados-wrap" class="cIndex">

	<?php if( $featuredHTML ) { ?>
		<?php echo $featuredHTML; ?><!--call clasificados.featured.php -->
	<?php } ?>

	<div class="row-fluid">
		<div class="span8">
			<div class="cMain">
				<!-- clasificado SORTINGS-->
				<?php echo $sortings; ?>
				<!-- clasificado LISTINGS -->
				<?php echo $clasificadosHTML;?>
			</div>
		</div>
		<div class="span4">
			<div class="cSidebar">
				<!-- START nearby clasificado search -->
				<?php echo $this->view('clasificados')->modClasificadoNearby(); ?>
				<!-- Categories -->
				<?php
				if ( $index && $handler->showCategories() ) :
					echo $this->view('clasificados')->modClasificadoCategories($category, $categories);
				endif;
				?>
				<!-- START clasificado calendar -->
				<?php echo $this->view('clasificados')->modClasificadoCalendar(); ?>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	joms.jQuery(document).ready(function(){
			// Get the Current Location from cookie
			var location =	joms.geolocation.getCookie( 'currentLocation' );
			if( location.length != 0 )
			{
					joms.jQuery('#showNearByClasificadosLoading').show();
					joms.geolocation.showNearByClasificados( location );
			}
			// Check if the browsers support W3C Geolocation API
			// If yes, show the auto-detect link
			if( navigator.geolocation )
			{
				joms.jQuery('#autodetectLocation').show();
			}
	});
	</script>

</div>