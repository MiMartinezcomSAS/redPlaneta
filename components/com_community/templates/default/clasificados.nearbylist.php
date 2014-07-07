<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die( 'Restricted Access' );
?>


<p><?php echo JText::sprintf('COM_COMMUNITY_EVENTS_NEARBY_RADIUS', $location, $radius, $measurement); ?></p>
<div class="app-box-content">
	<ul class="cClasificadoNearby cResetList">
		<?php if( $clasificados ){ ?>
		<?php
		for( $i=0; $i<count( $clasificados ); $i++ ){

		    $clasificado	=&  $clasificados[$i];
		    $creator	=   CFactory::getUser($clasificado->creator);

		?>
		<li>
			<a href="<?php echo CRoute::_( 'index.php?option=com_community&view=clasificados&task=viewclasificado&clasificadoid=' . $clasificado->id );?>"><?php echo $this->escape($clasificado->title); ?></a>
			<div class="small">
				<?php echo JText::sprintf('COM_COMMUNITY_ORGANIZED_BY', $clasificado->location, $creator->getDisplayName(), CRoute::_('index.php?option=com_community&view=profile&userid=' . $creator->id)); ?>
			</div>
		</li>
		<?php } ?>
		<?php }else{ echo JText::_('COM_COMMUNITY_EVENTS_NO_NEARBY'); }?>
	</ul>
</div>


