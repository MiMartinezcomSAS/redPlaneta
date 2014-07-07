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
<li>
	<div class="cIndex-Box clearfix">
		
		<a href="<?php echo $clasificado->getLink();?>" class="cIndex-Avatar cFloat-L">
			<img src="<?php echo $clasificado->getThumbAvatar();?>" alt="<?php echo $this->escape($clasificado->title); ?>" class="cAvatar" />
			<?php if( $isExpired || CClasificadoHelper::isPast($clasificado) ) { ?>
				<b class="cStatus-Past"><?php echo JText::_('COM_COMMUNITY_EVENTS_PAST'); ?></b>
			<?php } else if(CClasificadoHelper::isToday($clasificado)) { ?>
				<b class="cStatus-OnGoing"><?php echo JText::_('COM_COMMUNITY_EVENTS_ONGOING'); ?></b>
			<?php } ?>
		</a>


		<div class="cIndex-content">
			<h3 class="cIndex-Name cResetH">
				<a href="<?php echo $clasificado->getLink();?>"><strong><?php echo $this->escape($clasificado->title); ?></strong></a>
			</h3>
			<div class="cIndex-Status">
				<div class="cIndex-Date"><b><?php echo CClasificadoHelper::formatStartDate($clasificado, $config->get('clasificadodateformat') ); ?></b></div>
				<i class="cIndex-Location"><?php echo $this->escape($clasificado->location);?></i>
				<div class="cIndex-Time"><?php echo JText::sprintf('COM_COMMUNITY_EVENTS_DURATION', CTimeHelper::getFormattedTime($clasificado->startdate, $timeFormat), CTimeHelper::getFormattedTime($clasificado->enddate, $timeFormat)); ?></div>
			</div>
			<div class="cIndex-Actions">
				<div class="action">
					<?php if( $isExpired || CClasificadoHelper::isPast($clasificado) ) { ?>
					<a href="<?php echo $clasificado->getGuestLink( COMMUNITY_EVENT_STATUS_ATTEND );?>"><?php echo JText::sprintf((cIsPlural($clasificado->confirmedcount)) ? 'COM_COMMUNITY_EVENTS_COUNT_MANY_PAST':'COM_COMMUNITY_EVENTS_COUNT_PAST', $clasificado->confirmedcount);?></a>
					<?php } else { ?>
					<a href="<?php echo $clasificado->getGuestLink( COMMUNITY_EVENT_STATUS_ATTEND );?>"><?php echo JText::sprintf((cIsPlural($clasificado->confirmedcount)) ? 'COM_COMMUNITY_EVENTS_MANY_GUEST_COUNT':'COM_COMMUNITY_EVENTS_GUEST_COUNT', $clasificado->confirmedcount);?></a>
					<?php } ?>
				</div>
				<?php
				if( $isCommunityAdmin && $showFeatured ) {
					if( !in_array($clasificado->id, $featuredList) )
					{
				?>
				<div class="action">
					<a onclick="joms.featured.add('<?php echo $clasificado->id;?>','clasificados');" href="javascript:void(0);"><?php echo JText::_('COM_COMMUNITY_MAKE_FEATURED'); ?></a>
				</div>
				<?php			
					}
				}
				?>
			</div>
		</div>
	</div>
</li>