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

require_once( JPATH_ROOT .'/components/com_community/libraries/core.php' );
//CFactory::load( 'libraries' , 'comment' );

class CClasificados implements
	CCommentInterface, CStreamable
{

	static public function sendCommentNotification( CTableWall $wall , $message )
	{
		//CFactory::load( 'libraries' , 'notification' );
		$clasificado	= JTable::getInstance( 'Clasificado' , 'CTable' );
		$clasificado->load($wall->contentid);
		$my			= CFactory::getUser();
		$targetUser	= CFactory::getUser( $wall->post_by );
		$url		= 'index.php?option=com_community&view=clasificados&task=viewclasificado&clasificadoid=' . $wall->contentid;
		$params 	= $targetUser->getParams();

		$params		= new CParameter( '' );
		$params->set( 'url' , $url );
		$params->set( 'message' , $message );
		$params->set( 'clasificado' , $clasificado->title );
		$params->set( 'clasificado_url' , $url );

		CNotificationLibrary::add( 'clasificados_submit_wall_comment' , $my->id , $targetUser->id , JText::sprintf('PLG_WALLS_WALL_COMMENT_EMAIL_SUBJECT' , $my->getDisplayName() ) , '' , 'clasificados.wallcomment' , $params );
		return true;
	}

	/**
	 * Return an array of valid 'app' code to fetch from the stream
	 * @return array
	 */
	static public function getStreamAppCode(){
		return array('clasificados.wall', 'clasificado.attend');
	}


	static public function getActivityContentHTML($act)
	{
		// Ok, the activity could be an upload OR a wall comment. In the future, the content should
		// indicate which is which
		$html 	 = '';
		$param 	 = new CParameter( $act->params );
		$action  = $param->get('action' , false);

		if( $action == 'clasificados.create'  )
		{
			return CClasificados::getClasificadoSummary($act->cid, $param);
		}
		else if( $action == 'clasificado.join' || $action ==  'clasificado.attendence.attend' )
		{
			return CClasificadoss::getClasificadoSummary($act->cid, $param);
		}
		else if( $action == 'clasificado.wall.create' || $action == 'clasificados.wall.create')
		{


			$wallid = $param->get('wallid' , 0);
			$html = CWallLibrary::getWallContentSummary($wallid);
			return $html;
		}

		return $html;
	}

	static public function getClasificadoSummary($clasificadoid, $param)
	{
		$config = CFactory::getConfig();
		$model  =CFactory::getModel( 'clasificados' );
		$clasificado	= JTable::getInstance( 'Clasificado' , 'CTable' );
		$clasificado->load( $clasificadoid );

		// Add tagging code
		/*
		$tagsHTML = '';
		if($config->get('tags_clasificados') && $config->get('tags_show_in_stream')){

			$tags = new CTags();
			$tagsHTML = $tags->getHTML('clasificados', $clasificadoid, false);
		}*/

		$tmpl	= new CTemplate();
		$tmpl->set( 'clasificado'		, $clasificado );
		$tmpl->set( 'param'		, $param );

		return $tmpl->fetch( 'activity.clasificados.update' );
	}

	/**
	 * Return array of rss-feed compatible data
	 */
	public function getFEED($maxEntry=20, $userid=null)
	{

		$clasificados   = array();

        //CFactory::load( 'helpers' , 'owner' );
		//CFactory::load( 'models' , 'clasificados' );

		$model    = new CommunityModelClasificados();
        $clasificadoObjs= $model->getClasificados( null, $userid );

		if( $clasificadoObjs )
		{
			foreach( $clasificadoObjs as $row )
			{
				$clasificado	= JTable::getInstance( 'Clasificado' , 'CTable' );
				$clasificado->load( $row->id );
				$clasificados[]	= $clasificado;
			}
			unset($clasificadoObjs);
		}

		return $clasificados;
	}

	/**
	 * Return HTML formatted stream for clasificados
	 * @param type $clasificadoid
	 * @deprecated use CActivities directly instead
	 */
	public function getStreamHTML( $clasificado )
	{
		$activities = new CActivities();
		$streamHTML = $activities->getOlderStream(1000000000,'active-clasificado', $clasificado->id);

		// $streamHTML = $activities->getAppHTML(
		// 			array(
		// 				'app' => CActivities::APP_EVENTS,
		// 				'clasificadoid' => $clasificado->id,
		// 				'apptype' => 'clasificado'
		// 			)
		// 		);

		return $streamHTML;
	}

	/**
	 * Return true is the user can post to the stream
	 **/
	public function isAllowStreamPost( $userid, $options )
	{
		// Guest cannot post.
		if( $userid == 0){
			return false;
		}

		// Admin can comment on any post
		if(COwnerHelper::isCommunityAdmin()){
			return true;
		}

		$clasificado	= JTable::getInstance( 'Clasificado' , 'CTable' );
-		$clasificado->load( $options['clasificadoid'] );
		return $clasificado->isMember($userid);
	}

        public static function getClasificadoMemberHTML( $clasificadoId )
        {
            //CFactory::load( 'libraries' , 'tooltip' );
            //CFactory::load( 'helpers' , 'clasificado' );
        	$my = CFactory::getUser();
            $clasificado                              = JTable::getInstance( 'Clasificado' , 'CTable' );
            $clasificado->load($clasificadoId);
            $clasificadoMembers			= $clasificado->getMembers( COMMUNITY_EVENT_STATUS_ATTEND, 12 , CC_RANDOMIZE );
            $clasificadoMembersCount		= $clasificado->getMembersCount( COMMUNITY_EVENT_STATUS_ATTEND );

            for( $i = 0; ($i < count($clasificadoMembers)); $i++)
            {
			$row	=  $clasificadoMembers[$i];
			$clasificadoMembers[$i]	= CFactory::getUser( $row->id );
            }
            $handler	= CClasificadoHelper::getHandler( $clasificado );
            
            $isClasificadoGuest	= $clasificado->isMember( $my->id );
            $isMine			= ($my->id == $clasificado->creator);
			$isAdmin		= $clasificado->isAdmin( $my->id );

            $tmpl	= new CTemplate();
            $tmpl->set('isClasificadoGuest',$isClasificadoGuest);
            $tmpl->set( 'isMine'				, $isMine );
			$tmpl->set( 'isAdmin'			, $isAdmin );
            $tmpl->set( 'clasificadoMembers',    $clasificadoMembers );
            $tmpl->set( 'clasificadoMembersCount',    $clasificadoMembersCount );
            $tmpl->set( 'handler',    $handler );
            $tmpl->set( 'clasificadoId',  $clasificadoId);
            $tmpl->set( 'clasificado',  $clasificado);

            return $tmpl->fetch( 'clasificados.members.html' );
        }



	/**
	 * Return clasificado recurring save HTML.
	 **/
	static public function getClasificadoRepeatSaveHTML($selected = "")
	{
		$message	= JText::_('COM_COMMUNITY_EVENTS_REPEAT_MESSAGE');

		$message   .= '<br/><br/><input type="radio" id="repeatcurrent" name="repeattype" value="current" checked><strong>&nbsp;&nbsp;' . JText::_('COM_COMMUNITY_EVENTS_REPEAT_MESSAGE_ONLY_THIS') .'</strong><br/>';
		$message   .= '<div style="padding-left:18px">'.JText::_('COM_COMMUNITY_EVENTS_REPEAT_MESSAGE_ONLY_THIS_DESC') . '</div>';

		$selectfuture = $selected == 'future' ? 'checked' : '';
		$message   .= '<br/><input type="radio" id="repeatfuture" name="repeattype" value="future" ' .$selectfuture. '><strong>&nbsp;&nbsp;' . JText::_('COM_COMMUNITY_EVENTS_REPEAT_MESSAGE_FOLLOWING') .'</strong><br />';
		$message   .=  '<div style="padding-left:18px">'.JText::_('COM_COMMUNITY_EVENTS_REPEAT_MESSAGE_FOLLOWING_DESC') . '</div><br/><br/>';

		return $message;
	}

	/**
	 * Add stream for new created event.
         * @since 2.6
	 **/
    public static function addClasificadoStream($clasificado)
    {
        //CFactory::load( 'helpers' , 'event' );
        $handler = CClasificadoHelper::getHandler( $clasificado );
        $my	     = CFactory::getUser();

        //CFactory::load( 'helpers' , 'clasificado' );
        $handler = CClasificadoHelper::getHandler( $clasificado );

        // Activity stream purpose if the clasificado is a public clasificado
        $action_str = 'clasificados.create';
        if( $handler->isPublic() && $clasificado->isPublished())
        {
            $actor		= $clasificado->creator;
            $target		= 0;
            $content	= '';
            $cid		= $clasificado->id;
            $app		= 'clasificados';
            $act		= $handler->getActivity( 'clasificados.create' , $actor, $target , $content , $cid , $app );
            $url		= $handler->getFormattedLink( 'index.php?option=com_community&view=clasificados&task=viewclasificado&clasificadoid=' . $clasificado->id , false , true , false );

            // Set activity group id if the clasificado is in group
            $act->groupid	= ($clasificado->type == 'group') ? $clasificado->contentid : null;
            $act->clasificadoid	= $clasificado->id;
            $act->location	= $clasificado->location;

            $act->comment_id   = $clasificado->id;
            $act->comment_type = 'clasificados';

            $act->like_id	= $clasificado->id;
            $act->like_type	= 'clasificados';

            $params		= new CParameter('');
            $cat_url        = $handler->getFormattedLink( 'index.php?option=com_community&view=clasificados&task=display&categoryid=' . $clasificado->catid , false , true , false );
            $params->set( 'action', $action_str );
            $params->set( 'clasificado_url', $url );
            $params->set( 'clasificado_category_url', $cat_url );

            // Add activity logging

            CActivityStream::add( $act, $params->toString() );
        }
    }


	/**
	 * Add notifcation to group's member for new created clasificado.
         * @since 2.6
	 **/
    public static function addGroupNotification($clasificado)
    {


        if($clasificado->type == CClasificadoHelper::GROUP_TYPE && $clasificado->contentid != 0 && $clasificado->isPublished()){



            $my = CFactory::getUser();

            $group = JTable::getInstance( 'Group' , 'CTable' );
            $group->load( $clasificado->contentid );

            $modelGroup    = CFactory::getModel( 'groups' );
            $groupMembers  = array();
            $groupMembers  = $modelGroup->getMembersId($clasificado->contentid, true );

            // filter clasificado creator.
            if ($key = array_search($clasificado->creator, $groupMembers))
            {
                unset($groupMembers[$key]);
            }

            $subject       = JText::sprintf('COM_COMMUNITY_GROUP_NEW_EVENT_NOTIFICATION', $my->getDisplayName(), $group->name );
            $params	       = new CParameter( '' );
            $params->set( 'title' , $clasificado->title );
            $params->set('group' , $group->name );
            $params->set('group_url' , 'index.php?option=com_community&view=groups&task=viewgroup&groupid='.$clasificado->contentid );
            $params->set('clasificado' , $clasificado->title );
            $params->set('clasificado_url' , 'index.php?option=com_community&view=clasificados&task=viewclasificado&groupid='.$clasificado->contentid.'&clasificadoid='.$clasificado->id );
            $params->set( 'url', 'index.php?option=com_community&view=clasificados&task=viewclasificado&clasificadoid='.$clasificado->id);
            CNotificationLibrary::add( 'groups_create_clasificado' , $my->id , $groupMembers , JText::sprintf('COM_COMMUNITY_GROUP_NEW_EVENT_NOTIFICATION') , '' , 'groups.clasificado' , $params);
        }
    }

	/**
	 * Return true is the user is a group admin
	 **/
	public function isAdmin($userid,$clasificadoid)
	{
		$clasificado	= JTable::getInstance( 'Clasificado' , 'CTable' );
		$clasificado->load( $clasificadoid );
		return $clasificado->isAdmin($userid);
	}
}