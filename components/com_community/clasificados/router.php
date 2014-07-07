<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

defined('_JEXEC') or die('Restricted access');

class CClasificadosTrigger
{

	public function __call($name, $arguments)
	{
        // Note: value of $name is case sensitive.
		switch($name)
		{
			case 'onAfterConfigCreate':
				include_once (COMMUNITY_COM_PATH.'/clasificados/config.trigger.php');
				$plgObj = new CConfigTrigger();
				call_user_func_array(array(&$plgObj, $name), $arguments);
				break;
			/* profile event */
			case 'onAfterProfileUpdate':
				include_once (COMMUNITY_COM_PATH.'/clasificados/profile.trigger.php');
				$plgObj = new CProfileTrigger();
				call_user_func_array(array(&$plgObj, $name), $arguments);
				break;
			case 'onProfileStatusUpdate':
				include_once (COMMUNITY_COM_PATH.'/clasificados/profile.trigger.php');
				$plgObj = new CProfileTrigger();
				call_user_func_array(array(&$plgObj, $name), $arguments);
				break;
			/* Group event*/
			case 'onGroupCreate':
				include_once( COMMUNITY_COM_PATH .'/clasificados/groups.trigger.php' );
				$plgObj	= new CGroupsTrigger();
				call_user_func_array( array(&$plgObj , $name) , $arguments );
				break;
			case 'onGroupJoin':
				include_once( COMMUNITY_COM_PATH .'/clasificados/groups.trigger.php' );
				$plgObj	= new CGroupsTrigger();
				call_user_func_array( array(&$plgObj , $name) , $arguments );
				break;
			case 'onDiscussionDisplay':
				include_once( COMMUNITY_COM_PATH .'/clasificados/groups.trigger.php' );
				$plgObj	= new CGroupsTrigger();
				call_user_func_array( array(&$plgObj , $name) , $arguments );
				break;
			case 'onBulletinDisplay':
				include_once( COMMUNITY_COM_PATH .'/clasificados/groups.trigger.php' );
				$plgObj	= new CGroupsTrigger();
				call_user_func_array( array(&$plgObj , $name) , $arguments );
				break;

			/* Events */
			case 'onClasificadoCreate':
				include_once (COMMUNITY_COM_PATH.'/clasificados/clasificados.trigger.php');
				$plgObj = new CClasificadosTrigger();
				call_user_func_array(array(&$plgObj, $name), $arguments);
				break;

			/* Friends */
			case 'onFriendApprove':
				include_once (COMMUNITY_COM_PATH.'/clasificados/friends.trigger.php');
				$plgObj = new CFriendsTrigger();
				call_user_func_array(array(&$plgObj, $name), $arguments);
				break;

			/* Photos */
			case 'onAfterPhotoDelete':
				include_once (COMMUNITY_COM_PATH.'/clasificados/photos.trigger.php');
				$plgObj = new CPhotosTrigger();
				call_user_func_array(array(&$plgObj, $name), $arguments);
				break;

			/* Wall */
			case 'onWallDisplay':
				include_once (COMMUNITY_COM_PATH.'/clasificados/wall.trigger.php');
				$plgObj = new CWallTrigger();
				call_user_func_array(array(&$plgObj, $name), $arguments);
				break;
			case 'onAfterWallDelete':
				include_once (COMMUNITY_COM_PATH.'/clasificados/wall.trigger.php');
				$plgObj = new CWallTrigger();
				call_user_func_array(array(&$plgObj, $name), $arguments);
				break;
			/* Messaging */
			case 'onMessageDisplay':
				include_once (COMMUNITY_COM_PATH.'/clasificados/inbox.trigger.php');
				$plgObj = new CInboxTrigger();
				call_user_func_array(array(&$plgObj, $name), $arguments);
				break;
			default:
				// do nothing
		}
    }

}