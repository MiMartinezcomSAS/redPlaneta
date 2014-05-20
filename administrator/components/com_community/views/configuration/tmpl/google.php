<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_GOOGLE' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="::<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_STREET_FIELD_CODE_TIPS'); ?>">
						<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_STREET_FIELD_CODE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getFieldCodes( 'fieldcodestreet' , $this->config->get('fieldcodestreet') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="::<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_CITY_FIELD_CODE_TIPS'); ?>">
						<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_CITY_FIELD_CODE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getFieldCodes( 'fieldcodecity' , $this->config->get('fieldcodecity') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="::<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_STATE_FIELD_CODE_TIPS'); ?>">
						<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_STATE_FIELD_CODE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getFieldCodes( 'fieldcodestate' ,  $this->config->get('fieldcodestate') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="::<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_COUNTRY_FIELD_CODE_TIPS'); ?>">
						<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_COUNTRY_FIELD_CODE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getFieldCodes( 'fieldcodecountry' , $this->config->get('fieldcodecountry') ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>