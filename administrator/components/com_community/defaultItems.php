<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

abstract class CommunityDefaultItem
{
	/**
	 * Check if default category exist
	 * @param  [string] $name [table name]
	 * @return [boolean]       [description]
	 */
	static public function checkDefaultCategories($name)
	{
		$db		= JFactory::getDBO();

		$extraquery	= '';
		$tableName	= '#__community_'.$name.'_category';

		switch ($name) {
			case 'userpoints':
				$tableName = '#__community_userpoints';
				$extraquery	= ' WHERE ' . $db->quoteName('system') .' = 1';
				break;
			case 'fields':
				$tableName = '#__community_fields';
				break;
		}

		$query	= 'SELECT COUNT(*) FROM ' . $db->quoteName( $tableName ) . $extraquery;
		$db->setQuery( $query );

		$result	= ( $db->loadResult() > 0 ) ? false : true;

		return $result;
	}

	/**
	 * Add default Event Categories
	 */
	static public function addDefaultEventsCategories()
	{
		$db		= JFactory::getDBO();

		$query	= 'INSERT INTO '.$db->quoteName("#__community_events_category").'(' . $db->quoteName('name') .', ' . $db->quoteName('description') .' ) VALUES ' .
					'( ' . $db->Quote('General') .', ' . $db->Quote('Eventos generales') .'),'.
					'( ' . $db->Quote('Cumpleaños') .', ' . $db->Quote('Eventos sobre Cumpleaños') .'),'.
					'( ' . $db->Quote('Fiestas') .', ' . $db->Quote('Eventos sobre Fiestas de cualquier tipo') .')';

		$db->setQuery( $query );

		$db->query();

		if($db->getErrorNum())
		{
			return false;
		}
		return true;
	}

	/**
	 * Add defautl Group Categories
	 */
	static public function addDefaultGroupCategories()
	{
		$db 	= JFactory::getDBO();

		$query 	= 'INSERT INTO ' . $db->quoteName('#__community_groups_category') . ' (' . $db->quoteName('id') .', ' . $db->quoteName('name') .', ' . $db->quoteName('description') .') VALUES ' .
					'(' . $db->Quote('1') .', ' . $db->Quote('General') .', ' . $db->Quote('Categoría general del grupo.') .'),'.
					'(' . $db->Quote('2') .', ' . $db->Quote('Internet') .', ' . $db->Quote('Categoría sobre Grupos de Internet.') . '),'.
					'(' . $db->Quote('3') .', ' . $db->Quote('Empresas') .', ' . $db->Quote('Categoría sobre temas de Empresas') .'),'.
					'(' . $db->Quote('4') .', ' . $db->Quote('Automotivación') .', ' . $db->Quote('Categoría para grupos de Automotivación') .'),'.
					'(' . $db->Quote('5') .', ' . $db->Quote('Música') .', ' . $db->Quote('Categoría para Grupos amantes a la música') .')';

		$db->setQuery( $query );

		$db->query();

		if($db->getErrorNum())
		{
			return false;
		}

		return true;
	}

	/**
	 * Add default videos categories
	 */
	static public function addDefaultVideosCategories()
	{
		$db		= JFactory::getDBO();

		$query	= 'INSERT INTO '.$db->quoteName('#__community_videos_category') .'(' . $db->quoteName('id') .', ' . $db->quoteName('name') .', ' . $db->quoteName('description') .', ' . $db->quoteName('published') .' )'.
					' VALUES ( NULL , ' . $db->Quote('General') .', ' . $db->Quote('Canal de videos generales') .', ' . $db->Quote('1') .')';

		$db->setQuery( $query );

		$db->query();

		if($db->getErrorNum())
		{
			return false;
		}
		return true;
	}
	/**
	 * Add default Userpoints
	 */
	static public function addDefaultUserPoints()
	{
		$db 	= JFactory::getDBO();

		$query = "INSERT INTO `#__community_userpoints` (`rule_name`, `rule_description`, `rule_plugin`, `action_string`, `component`, `access`, `points`, `published`, `system`) VALUES
					('Add Application', 'Give points when registered user add new application.', 'com_community', 'application.add', '', 1, 0, 0, 1),
					('Remove Application', 'Deduct points when registered user remove application.', 'com_community', 'application.remove', '', 1, 0, 0, 1),
					('Upload Photo', 'Give points when registered user upload photos.', 'com_community', 'photo.upload', '', 1, 0, 1, 1),
					('Add New Group', 'Give points when registered user created new group.', 'com_community', 'group.create', '', 1, 3, 1, 1),
					('Add New Group''s Discussion', 'Give points when registered user created new discussion on group.', 'com_community', 'group.discussion.create', '', 1, 2, 1, 1),
					('Leave Group', 'Deduct points when registered user leave a group.', 'com_community', 'group.leave', '', 1, -1, 1, 1),
					('Approve Friend Request', 'Give points when registered user approved friend request.', 'com_community', 'friends.request.approve', '', 1, 1, 1, 1),
					('Add New Photo Album', 'Give points when registered user added new photo album.', 'com_community', 'album.create', '', 1, 1, 1, 1),
					('Post Group Wall', 'Give points when registered user post wall on group.', 'com_community', 'group.wall.create', '', 1, 1, 1, 1),
					('Join Group', 'Give points when registered user joined a group.', 'com_community', 'group.join', '', 1, 1, 1, 1),
					('Reply Group''s Discussion', 'Give points when registered user replied on group''s discussion.', 'com_community', 'group.discussion.reply', '', 1, 1, 1, 1),
					('Post Wall', 'Give points when registered user post a wall on profile.', 'com_community', 'profile.wall.create', '', 1, 1, 1, 1),
					('Profile Status Update', 'Give points when registered user update their profile status.', 'com_community', 'profile.status.update', '', 1, 1, 1, 1),
					('Profile Update', 'Give points when registered user update their profile.', 'com_community', 'profile.save', '', 1, 1, 1, 1),
					('Update group', 'Give points when registered user update the group.', 'com_community', 'group.updated', '', 1, 1, 1, 1),
					('Upload group avatar', 'Give points when registered user upload avatar to group.', 'com_community', 'group.avatar.upload', '', 1, 0, 1, 1),
					('Create Group''s News', 'Give points when registered user add group''s news.', 'com_community', 'group.news.create', '', 1, 1, 1, 1),
					('Post Wall for photos', 'Give points when registered user post wall at photos.', 'com_community', 'photos.wall.create', '', 1, 1, 1, 1),
					('Remove Friend', 'Deduct points when registered user remove a friend.', 'com_community', 'friends.remove', '', 1, 0, 1, 1),
					('Upload profile avatar', 'Give points when registered user upload profile avatar.', 'com_community', 'profile.avatar.upload', '', 1, 0, 1, 1),
					('Update privacy', 'Give points when registered user updated privacy.', 'com_community', 'profile.privacy.update', '', 1, 0, 1, 1),
					('Reply Messages', 'Give points when registered user reply a message.', 'com_community', 'inbox.message.reply', '', 1, 0, 1, 1),
					('Send Messages', 'Give points when registered user send a message.', 'com_community', 'inbox.message.send', '', 1, 0, 1, 1),
					('Remove Group member', 'Deduct points when registered user remove a group memeber.', 'com_community', 'group.member.remove', '', 1, 0, 1, 1),
					('Delete news', 'Deduct points when registered user remove a news.', 'com_community', 'group.news.remove', '', 1, 0, 1, 1),
					('Remove Wall', 'Deduct points to original poster when registered user remove a wall.', 'com_community', 'wall.remove', '', 1, 0, 1, 1),
					('Remove Photo album', 'Deduct points when registered user remove a photo album.', 'com_community', 'album.remove', '', 1, 0, 1, 1),
					('Remove photos', 'Deduct points when registered user remove a photo.', 'com_community', 'photo.remove', '', 1, 0, 1, 1),
	                ('Update Event', 'Give points when registered user update the event.', 'com_community', 'events.update', '', 1, 1, 1, 1)";

		$db->setQuery( $query );
		$db->query();

		if($db->getErrorNum())
		{
			return false;
		}
		return true;
	}

	/**
	 * Check if JomSocial Menu type exits
	 * @return [boolean] [return true/false]
	 */
	static public function menuTypesExist()
	{
		$db		= JFactory::getDBO();

		$query	= 'SELECT COUNT(*) FROM ' . $db->quoteName( '#__menu_types' ) . ' '
				. 'WHERE ' . $db->quoteName( 'menutype' ) . ' = ' .  $db->Quote( 'jomsocial') . ' '
				. 'AND ' . $db->quoteName('title') . ' = ' . $db->Quote('JomSocial toolbar');

		$db->setQuery( $query );

		$needUpdate	= ( $db->loadResult() >= 1 ) ? true : false;

		return $needUpdate;
	}

	/**
	 * Add Default Menu types
	 */
	static public function addDefaultMenuTypes()
	{
		$db		= JFactory::getDBO();
		$query	= 'INSERT INTO ' . $db->quoteName( '#__menu_types' ) . ' (' . $db->quoteName('menutype') .',' . $db->quoteName('title') .',' . $db->quoteName('description') .') VALUES '
		    			. '( ' . $db->Quote( 'jomsocial' ) . ',' . $db->Quote( 'JomSocial toolbar' ) . ',' . $db->Quote( 'Toolbar items for JomSocial toolbar') . ')';
		$db->setQuery( $query );
		$db->Query();
		if ($db->getErrorNum())
		{
			return false;
		}
		return true;
	}

	/**
	 * Check if menu exist
	 * @return [boolean] [return true/false]
	 */
	static public function menuExist()
	{
		$db		= JFactory::getDBO();

		$query	= 'SELECT COUNT(*) FROM ' . $db->quoteName( '#__menu' ) . ' '
				. 'WHERE ' . $db->quoteName( 'link' ) . ' LIKE ' .  $db->Quote( '%option=com_community%') . ' '
				. 'AND ' . $db->quoteName('menutype') . ' != ' . $db->Quote('main');

		$db->setQuery( $query );

		$needUpdate	= ( $db->loadResult() >= 1 ) ? true : false;

		return $needUpdate;
	}

	/**
	 * Update Menu Item
	 * @return [boolean] [return true/false]
	 */
	static public function updateMenuItems()
	{
		// Get new component id.
		$component		= JComponentHelper::getComponent('com_community');
		$component_id	= 0;
		if (is_object($component) && isset($component->id)){
			$component_id 	= $component->id;
		}

		if ($component_id > 0)
		{
			// Update the existing menu items.
			$db 	= JFactory::getDBO();

			$query 	= 'UPDATE ' . $db->quoteName( '#__menu' ) . ' '
					. 'SET '.$db->quoteName(JOOMLA_MENU_COMPONENT_ID).'=' . $db->Quote( $component_id ) . ' '
					. 'WHERE ' . $db->quoteName('link') .' LIKE ' . $db->Quote('%option=com_community%');

			$db->setQuery( $query );
			$db->query();

			if($db->getErrorNum())
			{
				return false;
			}


			$query 	= 'UPDATE ' . $db->quoteName( '#__menu' ) . ' '
					. 'SET '.$db->quoteName('level').'=' . $db->Quote( 1 ) . ' '
					. 'WHERE ' . $db->quoteName('link') .' LIKE ' . $db->Quote('%option=com_community%')
					. 'AND '.$db->quoteName( 'level' ).'= 0';

			$db->setQuery( $query );
			$db->query();

			if($db->getErrorNum())
			{
				return false;
			}
		}
		return true;
	}
	/**
	 * Add Menu items
	 */
	static public function addMenuItems()
	{
		$db = JFactory::getDBO();

		// Get new component id.
		$component    = JComponentHelper::getComponent('com_community');
		$component_id = 0;

		if (is_object($component) && isset($component->id))
		{
			$component_id = $component->id;
		}

		$column_name = JOOMLA_MENU_NAME;
		$column_cid  = JOOMLA_MENU_COMPONENT_ID;

		// Get the default menu type
		// 2 Joomla bugs occur in /Administrator mode
		// Bug 1: JFactory::getApplication('site') failed. It always return id = 'administrator'.
		// Bug 2: JMenu::getDefault('*') failed. JAdministrator::getLanguageFilter() doesn't exist.
		// If these 2 bugs are fixed, we can call the following syntax:
		// $defaultMenuType	= JFactory::getApplication('sites')->getMenu()->getDefault()->menutype;
		jimport('joomla.application.application');
		$defaultMenuType = JApplication::getInstance('site')->getMenu()->getDefault('workaround_joomla_bug')->menutype;

		// Update the existing menu items.
		$row				= JTable::getInstance ( 'menu', 'JTable' );
		$row->menutype		= $defaultMenuType;
		$row->$column_name	= 'JomSocial';
		$row->alias			= 'JomSocial';
		$row->access		= 1;
		$row->link			= 'index.php?option=com_community&view=frontpage';
		$row->type			= 'component';
		$row->published		= '1';
		$row->$column_cid	= $component_id;
		$row->id			= null; //new item
		$row->language		= '*';

		$row->check();

		if ( !$row->store() )
		{
			return false;
		}

		$query = 'UPDATE '. $db->quoteName( '#__menu' )
				 . ' SET `parent_id` = ' .$db->quote(1)
				 . ', `level` = ' . $db->quote(1)
				 . ' WHERE `id` = ' . $db->quote($row->id) ;
		$db->setQuery( $query );
		$db->query();

		if ($db->getErrorNum())
		{
			return false;
		}

		if ( ! self::addDefaultToolbarMenus())
		{
			return false;
		}

		// update memu items with component id
		if ( ! self::updateMenuItems())
		{
			return false;
		}

		return true;
	}
	/**
	 * Add default customs Fields
	 */
	static public function addDefaultCustomFields()
	{
		$db		= JFactory::getDBO();
		$query	= 'INSERT INTO ' . $db->quoteName('#__community_fields') . ' (' . $db->quoteName('id') . ', ' . $db->quoteName('type') . ', ' . $db->quoteName('ordering') . ', ' . $db->quoteName('published') . ', ' . $db->quoteName('min') . ', ' . $db->quoteName('max') . ', ' . $db->quoteName('name') . ', ' . $db->quoteName('tips') . ', ' . $db->quoteName('visible') . ', ' . $db->quoteName('required') . ', ' . $db->quoteName('searchable') . ', ' . $db->quoteName('options') . ', ' . $db->quoteName('fieldcode') . ') VALUES '.
					'(' . $db->Quote('1') . ', ' . $db->Quote('group') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('10') . ', ' . $db->Quote('100') . ', ' . $db->Quote('Información Básica') . ', ' . $db->Quote('Información básica del usuario') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('') . ', ' . $db->Quote('') . '),' .
					'(' . $db->Quote('2') . ', ' . $db->Quote('select') . ', ' . $db->Quote('2') . ', ' . $db->Quote('1') .', ' . $db->Quote('10') . ', ' . $db->Quote('100') . ', ' . $db->Quote('Sexo') . ', ' . $db->Quote('Selecciona tu sexo') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('Male'."\n".'Female') . ', ' . $db->Quote('FIELD_GENDER') . '),' .
					'(' . $db->Quote('3') . ', ' . $db->Quote('birthdate') .', ' . $db->Quote('3') .', ' . $db->Quote('1') .', ' . $db->Quote('10') .', ' . $db->Quote('100') .', ' . $db->Quote('Cumpleaños') .', ' . $db->Quote('Ingresa la fecha de tu cumpleaños para que los miembros puedan felicitarte') .', ' . $db->Quote('1') .', ' . $db->Quote('0') .', ' . $db->Quote('1') .', ' . $db->Quote('') .', ' . $db->Quote('FIELD_BIRTHDATE') .'),' .
					'(' . $db->Quote('4') . ', ' . $db->Quote('textarea') . ', ' . $db->Quote('4') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('800') . ', ' . $db->Quote('Acerca de mí') . ', ' . $db->Quote('Cuéntanos más sobre tí') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('') . ', ' . $db->Quote('FIELD_ABOUTME') . '),' .
					'(' . $db->Quote('5') . ', ' . $db->Quote('group') . ', ' . $db->Quote('5') . ', ' . $db->Quote('1') . ', ' . $db->Quote('10') . ', ' . $db->Quote('100') . ', ' . $db->Quote('Información de Contacto') . ', ' . $db->Quote('Especifica tus datos de Contacto') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('') . ', ' . $db->Quote('') . '),' .
					'(' . $db->Quote('6') . ', ' . $db->Quote('text') . ', ' . $db->Quote('6') . ', ' . $db->Quote('1') . ', ' . $db->Quote('10') . ', ' . $db->Quote('100') . ', ' . $db->Quote('Teléfono Móvil') .', ' . $db->Quote('Número de Telf. Móvil para que otros usuarios puedan contactarse contigo.') . ', ' . $db->Quote('1') . ', ' . $db->Quote('0') . ', ' . $db->Quote('1') . ', ' . $db->Quote('') . ', ' . $db->Quote('FIELD_MOBILE') . '),' .
					'(' . $db->Quote('7') . ', ' . $db->Quote('text') . ', ' . $db->Quote('7') . ', ' . $db->Quote('1') . ', ' . $db->Quote('10') . ', ' . $db->Quote('100') . ', ' . $db->Quote('Teléfono Fijo') . ', ' . $db->Quote('Número de Telf. Fijo para que otros usuarios contacten contigo.') . ', ' . $db->Quote('1') . ', ' . $db->Quote('0') . ', ' . $db->Quote('1') . ', ' . $db->Quote('') . ', ' . $db->Quote('FIELD_LANDPHONE') . '),' .
					'(' . $db->Quote('8') . ', ' . $db->Quote('textarea') . ', ' . $db->Quote('8') . ', ' . $db->Quote('1') . ', ' . $db->Quote('10') . ', ' . $db->Quote('100') . ', ' . $db->Quote('Dirección') .', ' . $db->Quote('La dirección donde vives') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('') . ', ' . $db->Quote('FIELD_ADDRESS') . '),' .
					'(' . $db->Quote('9') . ', ' . $db->Quote('text') . ', ' . $db->Quote('9') . ', ' . $db->Quote('1') . ', ' . $db->Quote('10') . ', ' . $db->Quote('100') . ', ' . $db->Quote('Estado / Provincia') . ', ' . $db->Quote('El Estado / Provincia donde vives') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('1') . ', ' . $db->Quote('') . ', ' . $db->Quote('FIELD_STATE') . '),' .
					'(' . $db->Quote('10') . ', ' . $db->Quote('text') . ', ' . $db->Quote('10') .', ' . $db->Quote('1') .', ' . $db->Quote('10') .', ' . $db->Quote('100') .', ' . $db->Quote('Ciudad / Pueblo') .', ' . $db->Quote('El nombre de tu ciudad') .', ' . $db->Quote('1') .', ' . $db->Quote('1') .', ' . $db->Quote('1') .', ' . $db->Quote('') .', ' . $db->Quote('FIELD_CITY') . '),' .
					'(' . $db->Quote('11') . ', ' . $db->Quote('country') .', ' . $db->Quote('11') .', ' . $db->Quote('1') .', ' . $db->Quote('10') .', ' . $db->Quote('100') .', ' . $db->Quote('País') .', ' . $db->Quote('Tu país') .', ' . $db->Quote('1') .', ' . $db->Quote('1') .', ' . $db->Quote('1') .', ' . $db->Quote('Afghanistan'."\n".'Albania'."\n".'Algeria'."\n".'American Samoa'."\n".'Andorra'."\n".'Angola'."\n".'Anguilla'."\n".'Antarctica'."\n".'Antigua and Barbuda'."\n".'Argentina'."\n".'Armenia'."\n".'Aruba'."\n".'Australia'."\n".'Austria'."\n".'Azerbaijan'."\n".'Bahamas'."\n".'Bahrain'."\n".'Bangladesh'."\n".'Barbados'."\n".'Belarus'."\n".'Belgium'."\n".'Belize'."\n".'Benin'."\n".'Bermuda'."\n".'Bhutan'."\n".'Bolivia'."\n".'Bosnia and Herzegovina'."\n".'Botswana'."\n".'Bouvet Island'."\n".'Brazil'."\n".'British Indian Ocean Territory'."\n".'Brunei Darussalam'."\n".'Bulgaria'."\n".'Burkina Faso'."\n".'Burundi'."\n".'Cambodia'."\n".'Cameroon'."\n".'Canada'."\n".'Cape Verde'."\n".'Cayman Islands'."\n".'Central African Republic'."\n".'Chad'."\n".'Chile'."\n".'China'."\n".'Christmas Island'."\n".'Cocos (Keeling) Islands'."\n".'Colombia'."\n".'Comoros'."\n".'Congo'."\n".'Cook Islands'."\n".'Costa Rica'."\n".'Cote D\'Ivoire (Ivory Coast)'."\n".'Croatia (Hrvatska)'."\n".'Cuba'."\n".'Cyprus'."\n".'Czechoslovakia (former)'."\n".'Czech Republic'."\n".'Denmark'."\n".'Djibouti'."\n".'Dominica'."\n".'Dominican Republic'."\n".'East Timor'."\n".'Ecuador'."\n".'Egypt'."\n".'El Salvador'."\n".'Equatorial Guinea'."\n".'Eritrea'."\n".'Estonia'."\n".'Ethiopia'."\n".'Falkland Islands (Malvinas)'."\n".'Faroe Islands'."\n".'Fiji'."\n".'Finland'."\n".'France'."\n".'France, Metropolitan'."\n".'French Guiana'."\n".'French Polynesia'."\n".'French Southern Territories'."\n".'Gabon'."\n".'Gambia'."\n".'Georgia'."\n".'Germany'."\n".'Ghana'."\n".'Gibraltar'."\n".'Great Britain (UK)'."\n".'Greece'."\n".'Greenland'."\n".'Grenada'."\n".'Guadeloupe'."\n".'Guam'."\n".'Guatemala'."\n".'Guinea'."\n".'Guinea-Bissau'."\n".'Guyana'."\n".'Haiti'."\n".'Heard and McDonald Islands'."\n".'Honduras'."\n".'Hong Kong'."\n".'Hungary'."\n".'Iceland'."\n".'India'."\n".'Indonesia'."\n".'Iran'."\n".'Iraq'."\n".'Ireland'."\n".'Israel'."\n".'Italy'."\n".'Jamaica'."\n".'Japan'."\n".'Jordan'."\n".'Kazakhstan'."\n".'Kenya'."\n".'Kiribati'."\n".'Korea, North'."\n".'South Korea'."\n".'Kuwait'."\n".'Kyrgyzstan'."\n".'Laos'."\n".'Latvia'."\n".'Lebanon'."\n".'Lesotho'."\n".'Liberia'."\n".'Libya'."\n".'Liechtenstein'."\n".'Lithuania'."\n".'Luxembourg'."\n".'Macau'."\n".'Macedonia'."\n".'Madagascar'."\n".'Malawi'."\n".'Malaysia'."\n".'Maldives'."\n".'Mali'."\n".'Malta'."\n".'Marshall Islands'."\n".'Martinique'."\n".'Mauritania'."\n".'Mauritius'."\n".'Mayotte'."\n".'Mexico'."\n".'Micronesia'."\n".'Moldova'."\n".'Monaco'."\n".'Mongolia'."\n".'Montserrat'."\n".'Morocco'."\n".'Mozambique'."\n".'Myanmar'."\n".'Namibia'."\n".'Nauru'."\n".'Nepal'."\n".'Netherlands'."\n".'Netherlands Antilles'."\n".'Neutral Zone'."\n".'New Caledonia'."\n".'New Zealand'."\n".'Nicaragua'."\n".'Niger'."\n".'Nigeria'."\n".'Niue'."\n".'Norfolk Island'."\n".'Northern Mariana Islands'."\n".'Norway'."\n".'Oman'."\n".'Pakistan'."\n".'Palau'."\n".'Panama'."\n".'Papua New Guinea'."\n".'Paraguay'."\n".'Peru'."\n".'Philippines'."\n".'Pitcairn'."\n".'Poland'."\n".'Portugal'."\n".'Puerto Rico'."\n".'Qatar'."\n".'Reunion'."\n".'Romania'."\n".'Russian Federation'."\n".'Rwanda'."\n".'Saint Kitts and Nevis'."\n".'Saint Lucia'."\n".'Saint Vincent and the Grenadines'."\n".'Samoa'."\n".'San Marino'."\n".'Sao Tome and Principe'."\n".'Saudi Arabia'."\n".'Senegal'."\n".'Seychelles'."\n".'S. Georgia and S. Sandwich Isls.'."\n".'Sierra Leone'."\n".'Singapore'."\n".'Slovak Republic'."\n".'Slovenia'."\n".'Solomon Islands'."\n".'Somalia'."\n".'South Africa'."\n".'Spain'."\n".'Sri Lanka'."\n".'St. Helena'."\n".'St. Pierre and Miquelon'."\n".'Sudan'."\n".'Suriname'."\n".'Svalbard and Jan Mayen Islands'."\n".'Swaziland'."\n".'Sweden'."\n".'Switzerland'."\n".'Syria'."\n".'Taiwan'."\n".'Tajikistan'."\n".'Tanzania'."\n".'Thailand'."\n".'Togo'."\n".'Tokelau'."\n".'Tonga'."\n".'Trinidad and Tobago'."\n".'Tunisia'."\n".'Turkey'."\n".'Turkmenistan'."\n".'Turks and Caicos Islands'."\n".'Tuvalu'."\n".'Uganda'."\n".'Ukraine'."\n".'United Arab Emirates'."\n".'United Kingdom'."\n".'United States'."\n".'Uruguay'."\n".'US Minor Outlying Islands'."\n".'USSR (former)'."\n".'Uzbekistan'."\n".'Vanuatu'."\n".'Vatican City State (Holy Sea)'."\n".'Venezuela'."\n".'Viet Nam'."\n".'Virgin Islands (British)'."\n".'Virgin Islands (U.S.)'."\n".'Wallis and Futuna Islands'."\n".'Western Sahara'."\n".'Yemen'."\n".'Yugoslavia'."\n".'Zaire'."\n".'Zambia'."\n".'Zimbabwe') .', ' . $db->Quote('FIELD_COUNTRY'). '),' .
					'(' . $db->Quote('12') . ', ' . $db->Quote('url') . ', ' . $db->Quote('12') .', ' . $db->Quote('1') .', ' . $db->Quote('10') .', ' . $db->Quote('100') .', ' . $db->Quote('Página WEB') .', ' . $db->Quote('La URL de tu sitio web o Blog') .', ' . $db->Quote('1') .', ' . $db->Quote('1') .', ' . $db->Quote('1') .', ' . $db->Quote('') .', ' . $db->Quote('FIELD_WEBSITE') . '),' .
					'(' . $db->Quote('13') . ', ' . $db->Quote('group') . ', ' . $db->Quote('13') .', ' . $db->Quote('1') . ', ' . $db->Quote('10') .', ' . $db->Quote('100') .', ' . $db->Quote('Educación') .', ' . $db->Quote('Datos sobre tu educación') .', ' . $db->Quote('1') .', ' . $db->Quote('1') .', ' . $db->Quote('1') .', ' . $db->Quote('') . ', ' . $db->Quote('') .'),' .
					'(' . $db->Quote('14') . ', ' . $db->Quote('text') .', ' . $db->Quote('14') .', ' . $db->Quote('1') .', ' . $db->Quote('10') .', ' . $db->Quote('200') .', ' . $db->Quote('Colegio / Instituto / Universidad') .', ' . $db->Quote('El nombre de tu colegio, instituto o universidad') .', ' . $db->Quote('1') .', ' . $db->Quote('1') .', ' . $db->Quote('1') .', ' . $db->Quote('') .', ' . $db->Quote('FIELD_COLLEGE') . '),' .
					'(' . $db->Quote('15') . ', ' . $db->Quote('text') . ', ' . $db->Quote('15') .', ' . $db->Quote('1') .', ' . $db->Quote('5') .', ' . $db->Quote('100') .', ' . $db->Quote('Año de Graduación') .', ' . $db->Quote('En que año te graduaste o te vas a graduar') .', ' . $db->Quote('1') .', ' . $db->Quote('1') .', ' . $db->Quote('1') .', ' . $db->Quote('') .', ' . $db->Quote('FIELD_GRADUATION') .')';

		$db->setQuery( $query );
		$db->query();

		if($db->getErrorNum())
		{
			return false;
		}
		return true;
	}
	/**
	 * Add default toolbar  menu
	 */
	static public function addDefaultToolbarMenus()
	{
		$db				= JFactory::getDBO();
		$file			= JPATH_ROOT . '/administrator/components/com_community/toolbar.xml';
		$menu_name		= JOOMLA_MENU_NAME;
		$menu_parent	= JOOMLA_MENU_PARENT;
		$menu_level		= JOOMLA_MENU_LEVEL;
		$items			= new SimpleXMLElement( $file , NULL , true );
		$items			= $items->items;

		$i	= 1;
		foreach( $items->children() as $item )
		{
			$obj				= new stdClass();
			$obj->$menu_name	= (string) $item->name;
			$obj->alias			= (string) $item->alias;
			$obj->link			= (string) $item->link;
			$obj->access		= (string) $item->access;
			$obj->menutype		= 'jomsocial';
			$obj->type			= 'component';
			$obj->published		= 1;
			$obj->$menu_parent	= JOOMLA_MENU_ROOT_PARENT;
			$obj->language		= '*';

			$childs	= $item->childs;
			//J1.6: menu item ordering follow lft and rgt
			$query 	= 'SELECT ' . $db->quoteName( 'rgt' ) . ' '
					. 'FROM ' . $db->quoteName( '#__menu' ) . ' '
					. 'ORDER BY ' . $db->quoteName( 'rgt' ) . ' DESC LIMIT 1';
			$db->setQuery( $query );
			$obj->lft 	= $db->loadResult() + 1;
			$totalchild = $childs?count($childs->children()):0;
			$obj->rgt	= $obj->lft + $totalchild * 2 + 1;

			$db->insertObject( '#__menu' , $obj );

			if ($db->getErrorNum()) {
				return false;
			}

			$parentId		= $db->insertid();

			if( $childs )
			{
				$x	= 1;
				foreach( $childs->children() as $child )
				{
					$childObj		= new stdClass();

					$childObj->$menu_name	= (string) $child->name;
					$childObj->alias		= (string) $child->alias;
					$childObj->link			= (string) $child->link;
					$childObj->access		= (string) $item->access;
					$childObj->menutype		= 'jomsocial';
					$childObj->type			= 'component';
					$childObj->published	= 1;
					$childObj->$menu_parent	= $parentId;
					$childObj->$menu_level	= JOOMLA_MENU_LEVEL_PARENT + 1;
					$childObj->language		= '*';
					//J1.6: menu item ordering follow lft and rgt
					$childObj->lft			= $obj->lft + ($x - 1)* 2 + 1;
					$childObj->rgt			= $childObj->lft + 1;

					$db->insertObject( '#__menu' , $childObj );
					if ($db->getErrorNum()) {
						return false;
					}

					$x++;
				}
			}
			$i++;
		}
		return true;
	}
}