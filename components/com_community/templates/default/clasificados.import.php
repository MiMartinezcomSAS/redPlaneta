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
<div class="cLayout cClasificados-Import">
	<form class="cForm" name="jsforms-clasificados-import" action="<?php echo CRoute::getURI();?>" method="post" enctype="multipart/form-data">
	<div class="ctitle">
		<h2><?php echo JText::_('COM_COMMUNITY_EVENTS_IMPORT_ICAL_DESCRIPTION');?></h2>
	</div>
		<ul class="cFormList cResetList iCalOptions">
			<li class="jsiCalSel">
				<label for="upload">
					<input class="radio" type="radio" id="upload" name="type" checked="checked" onclick="joms.clasificados.switchImport('file');" />
					<?php echo JText::_('COM_COMMUNITY_EVENTS_INPORT_LOCAL');?>
				</label>
			</li>
			<li class="jsiCalSel">
				<label for="link">
					<input type="radio" id="link" name="type" onclick="joms.clasificados.switchImport('url');" class="radio" />
					<?php echo JText::_('COM_COMMUNITY_EVENTS_IMPORT_EXTERNAL');?>
				</label>
			</li>
			<li class="has-seperator	" id="clasificado-import-file">
				<input type="file" class="input file" name="file" style="width: 320px;" />
				<span class="form-helper"><?php echo JText::_('COM_COMMUNITY_EVENTS_IMPORT_ERROR');?></span>
			</li>
			<li class="has-seperator" id="clasificado-import-url" style="display: none;">
				<input type="text" class="input text" name="url" style="width: 320px;" />
				<span class="form-helper"><?php echo JText::_('COM_COMMUNITY_EVENTS_IMPORT_ERROR');?></span>
			</li>
			<li class="has-seperator">
				<input type="submit" value="<?php echo JText::_('COM_COMMUNITY_EVENTS_IMPORT');?>" class="btn btn-primary" />
				<input type="hidden" value="file" name="type" id="import-type" />
			</li>
		</ul>
	</form>
	<?php if( $clasificados ) { ?>
	<form action="<?php echo $saveimportlink;?>" method="post">
		<div class="ctitle" style="padding-top:30px !important">
			<?php echo JText::_('COM_COMMUNITY_EVENTS_EXPORTED');?>
		</div>
		<p><?php echo JText::_('COM_COMMUNITY_EVENTS_IMPORT_SELECT');?></p>
		<ul class="jsiCal cResetList">
			<?php
			$i	= 1;
			foreach($clasificados as $clasificado){
			?>
			<li>
				<div class="jsiCalHead jsRel">
					<span class="jsAbs">
						<input type="checkbox" name="clasificados[]" id="clasificado-<?php echo $i;?>" value="<?php echo $i;?>" class="input checkbox jsReset" checked />
					</span>
					<label for="clasificado-<?php echo $i;?>"><?php echo $clasificado->getTitle();?></label>
				</div>
							<div class="jsiCalDesc">
				<?php if ( $clasificado->getDescription() ) {?>
					<p><?php echo $clasificado->getDescription();?></p>
				<?php } else { ?>
					<p><?php echo JText::_('COM_COMMUNITY_EVENTS_DESCRIPTION_ERR0R');?></p>
				<?php } ?>
				</div>
				<div class="jsiCalDetail">
					<div class="clrfix">
						<span class="jsiCalLabel jsLft small"><?php echo JText::_('COM_COMMUNITY_EVENTS_START_TIME');?></span>
						<div class="jsiCalData small">: <?php echo $clasificado->getStartDate();?></div>
					</div>
					<div class="clrfix">
						<span class="jsiCalLabel jsLft small"><?php echo JText::_('COM_COMMUNITY_EVENTS_END_TIME');?></span>
						<div class="jsiCalData small">: <?php echo $clasificado->getEndDate();?></div></div>
					<div class="clrfix">
						<span class="jsiCalLabel jsLft small"><?php echo JText::_('COM_COMMUNITY_TIMEZONE');?></span>
						<div class="jsiCalData small">: 
							<select name="clasificado-<?php echo $i; ?>-offset" class="input select">
							<?php
							foreach( $timezones as $offset => $value ){
							?>
								<option value="<?php echo $offset;?>" <?php echo ($offset == 0) ? 'selected' : ''?>><?php echo $value;?></option>
							<?php
							}
							?>
							</select>
						</div>
					</div>
					<div class="clrfix">
						<span class="jsiCalLabel jsLft small"><?php echo JText::_('COM_COMMUNITY_EVENTS_LOCATION');?></span>
						<div class="jsiCalData small">: <?php echo ( $clasificado->getLocation() != '' ) ? $clasificado->getLocation() : JText::_('COM_COMMUNITY_EVENTS_LOCATION_NOT_AVAILABLE');?></div>
					</div>
					<div class="clrfix">
						<span class="jsiCalLabel jsLft small"><?php echo JText::_('COM_COMMUNITY_EVENTS_CATEGORY');?></span>
						<div class="jsiCalData small">
							<select name="clasificado-<?php echo $i;?>-catid" id="clasificado-<?php echo $i;?>-catid" class="required input select">
							<?php
							foreach( $categories as $category )
							{
							?>
								<option value="<?php echo $category->id; ?>"><?php echo JText::_( $this->escape($category->name) ); ?></option>
							<?php
							}
							?>
							</select>
						</div>
					</div>
					<div class="clrfix">
						<span class="jsiCalLabel jsLft small"><?php echo JText::_('COM_COMMUNITY_EVENTS_GUEST_INVITE');?></span>
						<div class="jsiCalData small">
							<input type="radio" class="input radio" name="clasificado-<?php echo $i;?>-invite" id="clasificado-<?php echo $i;?>-invite-allowed" value="1" checked="checked" />
							<label for="clasificado-<?php echo $i;?>-invite-allowed" class="label lblradio"><?php echo JText::_('COM_COMMUNITY_YES');?></label>
							<input type="radio" class="input radio" name="clasificado-<?php echo $i;?>-invite" id="clasificado-<?php echo $i;?>-invite-disallowed" value="0" />
							<label for="clasificado-<?php echo $i;?>-invite-disallowed" class="label lblradio"><?php echo JText::_('COM_COMMUNITY_NO');?></label>
						</div>
					</div>
					<div class="clrfix">
						<span class="jsiCalLabel jsLft small"><?php echo JText::_('COM_COMMUNITY_EVENTS_TYPE'); ?></span>
						<div class="jsiCalData small">
							<input type="radio" class="input radio" name="clasificado-<?php echo $i;?>-permission" id="clasificado-<?php echo $i;?>-permission-open" value="0" checked="checked" />
							<label for="clasificado-<?php echo $i;?>-permission-open" class="label lblradio"><?php echo JText::_('COM_COMMUNITY_EVENTS_OPEN_EVENT');?></label>
							<input type="radio" class="input radio" name="clasificado-<?php echo $i;?>-permission" id="clasificado-<?php echo $i;?>-permission-private" value="1" />
							<label for="clasificado-<?php echo $i;?>-permission-private" class="label lblradio"><?php echo JText::_('COM_COMMUNITY_EVENTS_PRIVATE_EVENT');?></label>
						</div>
					</div>
					<div class="clrfix">
						<span class="jsiCalLabel jsLft small"><?php echo JText::_('COM_COMMUNITY_EVENTS_NO_SEAT'); ?></span>
						<div class="jsiCalData small">
							<input type="text" class="input text" name="clasificado-<?php echo $i;?>-ticket" id="clasificado-<?php echo $i;?>-ticket" value="0" size="10" maxlength="5"/>
						</div>
					</div>
					<div class="clrfix">
						<span class="jsiCalLabel jsLft small"><?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT'); ?></span>
						<div class="jsiCalData small">
							<?php $repeat = $clasificado->getRepeat(); ?>
							<select name="clasificado-<?php echo $i;?>-repeat" class="input select" id="clasificado-<?php echo $i;?>-repeat">
								<option value=""><?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_NONE'); ?></option>
								<option value="daily" <?php echo $repeat == 'daily' ? 'selected' : '';?>><?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_DAILY'); ?></option>
								<option value="weekly" <?php echo $repeat == 'weekly' ? 'selected' : '';?>><?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_WEEKLY'); ?></option>
								<option value="monthly" <?php echo $repeat == 'monthly' ? 'selected' : '';?>><?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_MONTHLY'); ?></option>
							</select>
						</div>
					</div>
					<div class="clrfix">
						<span class="jsiCalLabel jsLft small"><?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_END');?></span>
						<div class="jsiCalData small">: <?php echo $clasificado->getRepeatEnd();?></div>
					</div>
					<div class="clrfix">
						<span class="jsiCalLabel jsLft small"><?php echo JText::_('COM_COMMUNITY_EVENTS_REPEAT_LIMIT'); ?></span>
						<div class="jsiCalData small">
							<input type="text" class="input text" name="clasificado-<?php echo $i;?>-limit" id="clasificado-<?php echo $i;?>-limit" value="<?php echo $clasificado->getRepeatLimit();?>" size="10" maxlength="5"/>
						</div>
					</div>
					<input name="clasificado-<?php echo $i;?>-startdate" value="<?php echo $clasificado->getStartDate();?>" type="hidden" />
					<input name="clasificado-<?php echo $i;?>-enddate" value="<?php echo $clasificado->getEndDate();?>" type="hidden" />
					<input name="clasificado-<?php echo $i;?>-title" value="<?php echo $clasificado->getTitle();?>" type="hidden" />
					<input name="clasificado-<?php echo $i;?>-location" value="<?php echo $this->escape($clasificado->getLocation());?>" type="hidden" />
					<input name="clasificado-<?php echo $i;?>-description" value="<?php echo $this->escape($clasificado->getDescription());?>" type="hidden" />
					<input name="clasificado-<?php echo $i;?>-summary" value="<?php echo $clasificado->getSummary();?>" type="hidden" />
					<input name="clasificado-<?php echo $i;?>-repeatend" value="<?php echo $clasificado->getRepeatEnd();?>" type="hidden" />
				</div>
			</li>
			<?php
				$i++;
			}
			?>
		</ul>
		<div style="text-align: center;margin-top: 10px;"><input type="submit" value="<?php echo JText::_('COM_COMMUNITY_EVENTS_IMPORT');?>" class="button" /></div>
	</form>
	<?php } ?>
</div>