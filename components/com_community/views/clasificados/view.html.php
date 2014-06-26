<?php

/**
 * @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author iJoomla.com <webmaster@ijoomla.com>
 * @url https://www.jomsocial.com/license-agreement
 * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at https://www.jomsocial.com/license-agreement
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

include_once JPATH_BASE.'/components/com_community/helpers/clasificado.php';

class CommunityViewClasificados extends CommunityView {

    public function _addSubmenu() {
        //CFactory::load( 'helpers' , 'clasificado' );
        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;
        $id = $jinput->request->get('clasificadoid', '', 'INT'); //JRequest::getVar( 'clasificadoid' , '' , 'REQUEST' );
        $clasificado = JTable::getInstance('Clasificado', 'CTable');
        $clasificado->load($id);

        CClasificadoHelper::getHandler($clasificado)->addSubmenus($this);
    }

    public function showSubmenu() {
        $this->_addSubmenu();
        parent::showSubmenu();
    }

    /**
     * Application full view
     * */
    public function appFullView() {
        $document = JFactory::getDocument();
        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;

        $this->showSubmenu();

        $applicationName = JString::strtolower($jinput->get->get('app', '', 'STRING'));

        if (empty($applicationName)) {
            JError::raiseError(500, 'COM_COMMUNITY_APP_ID_REQUIRED');
        }

        if (!$this->accessAllowed('registered')) {
            return;
        }

        $output = '';

        //@todo: Since group walls doesn't use application yet, we process it manually now.
        if ($applicationName == 'walls') {
            //CFactory::load( 'libraries' , 'wall' );
            //$jConfig	= JFactory::getConfig();
            $limit = JRequest::getInt('limit', 5, 'REQUEST');
            $limitstart = JRequest::getInt('limitstart', 0, 'REQUEST');
            $clasificadoId = JRequest::getInt('clasificadoid', '', 'GET');
            $my = CFactory::getUser();
            $config = CFactory::getConfig();

            $clasificadosModel = CFactory::getModel('Clasificados');
            $clasificado = JTable::getInstance('Clasificado', 'CTable');
            $clasificado->load($clasificadoId);
            $config = CFactory::getConfig();
            $document->setTitle(JText::sprintf('COM_COMMUNITY_EVENTS_WALL_TITLE', $clasificado->title));
            //CFactory::load( 'helpers' , 'owner' );

            $guest = $clasificado->isMember($my->id);
            $waitingApproval = $clasificado->isPendingApproval($my->id);
            $status = $clasificado->getUserStatus($my->id, 'clasificados');
            $responded = (($status == COMMUNITY_EVENT_STATUS_ATTEND) || ($status == COMMUNITY_EVENT_STATUS_WONTATTEND) || ($status == COMMUNITY_EVENT_STATUS_MAYBE));

            if (!$config->get('lockclasificadowalls') || ($config->get('lockclasificadowalls') && ($guest) && !($waitingApproval) && $responded) || COwnerHelper::isCommunityAdmin()) {
                $output .= CWallLibrary::getWallInputForm($clasificado->id, 'clasificados,ajaxSaveWall', 'clasificados,ajaxRemoveWall');

                // Get the walls content
                $output .='<div id="wallContent">';
                $output .= CWallLibrary::getWallContents('clasificados', $clasificado->id, $clasificado->isAdmin($my->id), $limit, $limitstart, 'wall.content', 'clasificados,clasificados');
                $output .= '</div>';

                jimport('joomla.html.pagination');
                $wallModel = CFactory::getModel('wall');
                $pagination = new JPagination($wallModel->getCount($clasificado->id, 'clasificados'), $limitstart, $limit);

                $output .= '<div class="cPagination">' . $pagination->getPagesLinks() . '</div>';
            }
        } else {
            //CFactory::load( 'libraries' , 'apps' );
            $model = CFactory::getModel('apps');
            $applications = CAppPlugins::getInstance();
            $applicationId = $model->getUserApplicationId($applicationName);

            $application = $applications->get($applicationName, $applicationId);

            if (!$application) {
                JError::raiseError(500, 'COM_COMMUNITY_APPS_NOT_FOUND');
            }

            // Get the parameters
            $manifest = CPluginHelper::getPluginPath('community', $applicationName) . '/' . $applicationName . '/' . $applicationName . '.xml';

            $params = new CParameter($model->getUserAppParams($applicationId), $manifest);

            $application->params = $params;
            $application->id = $applicationId;

            $output = $application->onAppDisplay($params);
        }

        echo $output;
    }

    public function display($tpl = null) {
        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;

        $document = JFactory::getDocument();
        $config = CFactory::getConfig();
        $my = CFactory::getUser();

        $script = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
        $document->addCustomTag($script);

        $groupId = $jinput->get->get('groupid', '', 'INT');
        $clasificadoparent = $jinput->get->get('parent', '', 'INT');

        if (!empty($groupId)) {
            $group = JTable::getInstance('Group', 'CTable');
            $group->load($groupId);

            // @rule: Test if the group is unpublished, don't display it at all.
            if (!$group->published) {
                echo JText::_('COM_COMMUNITY_GROUPS_UNPUBLISH_WARNING');
                return;
            }

            // Set pathway for group videos
            // Community > Groups > Group Name > Events
            $this->addPathway(JText::_('COM_COMMUNITY_GROUPS'), CRoute::_('index.php?option=com_community&view=groups'));
            $this->addPathway($group->name, CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupId));
        }

        //page title
        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS'), CRoute::_('index.php?option=com_community&view=clasificados'));

        // Get category id from the query string if there are any.
        $categoryId = JRequest::getInt('categoryid', 0);
        $limitstart = $jinput->get('limitstart', 0, 'INT'); //JRequest::getVar( 'limitstart' , 0 );
        $category = JTable::getInstance('ClasificadoCategory', 'CTable');
        $category->load($categoryId);

        if (isset($category) && $category->id != 0) {
            $document->setTitle(JText::sprintf('COM_COMMUNITY_GROUPS_CATEGORY_NAME', str_replace('&amp;', '&', JText::_($this->escape($category->name)))));
        } else {
            $document->setTitle(JText::_('COM_COMMUNITY_EVENTS'));
        }


        $this->showSubmenu();

        $feedLink = CRoute::_('index.php?option=com_community&view=clasificados&format=feed');
        $feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('COM_COMMUNITY_SUBSCRIBE_ALL_EVENTS_FEED') . '" href="' . $feedLink . '"/>';
        $document->addCustomTag($feed);

        $data = new stdClass();
        $sorted = $jinput->get->get('sort', 'startdate', 'STRING'); //JRequest::getVar( 'sort' , 'startdate' , 'GET' );

        /* begin: UNLIMITED LEVEL BREADCRUMBS PROCESSING */
        if ($category->parent == COMMUNITY_NO_PARENT) {
            $this->addPathway(JText::_($this->escape($category->name)), CRoute::_('index.php?option=com_community&view=clasificados&task=display&categoryid=' . $category->id));
        } else {
            // Parent Category
            $parentsInArray = array();
            $n = 0;
            $parentId = $category->id;

            $parent = JTable::getInstance('ClasificadoCategory', 'CTable');

            do {
                $parent->load($parentId);
                $parentId = $parent->parent;

                $parentsInArray[$n]['id'] = $parent->id;
                $parentsInArray[$n]['parent'] = $parent->parent;
                $parentsInArray[$n]['name'] = $parent->name;

                $n++;
            } while ($parent->parent > COMMUNITY_NO_PARENT);

            for ($i = count($parentsInArray) - 1; $i >= 0; $i--) {
                $this->addPathway($parentsInArray[$i]['name'], CRoute::_('index.php?option=com_community&view=clasificados&task=display&categoryid=' . $parentsInArray[$i]['id']));
            }
        }
        /* end: UNLIMITED LEVEL BREADCRUMBS PROCESSING */

        $data->categories = $this->_cachedCall('_getClasificadosCategories', array($category->id), '', array(COMMUNITY_CACHE_TAG_EVENTS_CAT));

        $model = CFactory::getModel('clasificados');

        // Get event in category and it's children.
        $categories = $model->getAllCategories();
        $categoryIds = CCategoryHelper::getCategoryChilds($categories, $category->id);
        if ($category->id > 0) {
            $categoryIds[] = (int) $category->id;
        }
	    //die('asd');
        //CFactory::load( 'helpers' , 'event' );
        $clasificado = JTable::getInstance('Clasificado', 'CTable');
        $handler = CClasificadoHelper::getHandler($clasificado);

        // It is safe to pass 0 as the category id as the model itself checks for this value.
        $data->clasificados = $model->getClasificados($categoryIds, null, $sorted, null, true, false, null, array('parent' => $clasificadoparent), $handler->getContentTypes(), $handler->getContentId());

        // Get pagination object
        $data->pagination = $model->getPagination();

        $clasificadosHTML = $this->_cachedCall('_getClasificadosHTML', array($data->clasificados, false, $data->pagination), '', array(COMMUNITY_CACHE_TAG_EVENTS));
        //Cache Group Featured List
        $featuredClasificados = $this->_cachedCall('getClasificadosFeaturedList', array(), '', array(COMMUNITY_CACHE_TAG_FEATURED));
        $featuredHTML = $featuredClasificados['HTML'];

        //no Featured Event headline slideshow on Category filtered page
        if (!empty($categoryId))
            $featuredHTML = '';

        $sortItems = array(
            'latest' => JText::_('COM_COMMUNITY_EVENTS_SORT_CREATED'),
            'startdate' => JText::_('COM_COMMUNITY_EVENTS_SORT_COMING'));

        //CFactory::load( 'helpers' , 'owner' );

        $tmpl = new CTemplate();
        echo $tmpl->set('handler', $handler)
                ->set('featuredHTML', $featuredHTML)
                ->set('index', true)
                ->set('categories', $data->categories)
                ->set('clasificadosHTML', $clasificadosHTML)
                ->set('config', $config)
                ->set('category', $category)
                ->set('isCommunityAdmin', COwnerHelper::isCommunityAdmin())
                ->set('sortings', CFilterBar::getHTML(CRoute::getURI(), $sortItems, 'startdate'))
                ->set('my', $my)
                ->fetch('clasificados.index');
    }

    /**
     * List All FEATURED EVENTS
     * @ since 2.4
     * */
    public function getClasificadosFeaturedList() {
        $featClasificados = $this->_getClasificadosFeaturedList();

        if ($featClasificados) {
            $featuredHTML['HTML'] = $this->_getFeatHTML($featClasificados);
        } else {
            $featuredHTML['HTML'] = null;
        }

        return $featuredHTML;
    }

    /**
     * 	Generate Featured Events HTML
     *
     * 	@param		array	Array of events objects
     * 	@return		string	HTML
     * 	@since		2.4
     */
    private function _getFeatHTML($clasificados) {
        //CFactory::load( 'helpers' , 'owner' );
        //CFactory::load( 'libraries', 'events' );
        $my = CFactory::getUser();
        $config = CFactory::getConfig();
        $clasificado = JTable::getInstance('Clasificado', 'CTable');
        // Get the formated date & time
        $format = ($config->get('clasificadoshowampm')) ? JText::_('COM_COMMUNITY_EVENTS_TIME_FORMAT_12HR') : JText::_('COM_COMMUNITY_EVENTS_TIME_FORMAT_24HR');

        $startDate = $clasificado->getStartDate(false);
        $endDate = $clasificado->getEndDate(false);
        $allday = false;

        if (($startDate->format('%Y-%m-%d') == $endDate->format('%Y-%m-%d')) && $startDate->format('%H:%M:%S') == '00:00:00' && $endDate->format('%H:%M:%S') == '23:59:59') {
            $format = JText::_('COM_COMMUNITY_EVENT_TIME_FORMAT_LC1');
            $allday = true;
        }

        $tmpl = new CTemplate();
        return $tmpl->set('clasificados', $clasificados)
                        ->set('showFeatured', $config->get('show_featured'))
                        ->set('isCommunityAdmin', COwnerHelper::isCommunityAdmin())
                        ->set('my', $my)
                        ->set('allday', $allday)
                        ->fetch('clasificados.featured');
    }

    /**
     * Display invite form
     * */
    public function invitefriends() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_COMMUNITY_EVENTS_INVITE_FRIENDS_TO_EVENT_TITLE'));

        if (!$this->accessAllowed('registered')) {
            return;
        }

        $this->showSubmenu();

        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;
        $my = CFactory::getUser();
        $clasificadoId = $jinput->get->get('clasificadoid', '', 'INT'); //JRequest::getVar( 'eventid' , '' , 'GET' );
        $this->_addClasificadoInPathway($clasificadoId);
        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS_INVITE_FRIENDS_TO_EVENT_TITLE'));

        $friendsModel = CFactory::getModel('Friends');
        $model = CFactory::getModel('Clasificados');
        $clasificado = JTable::getInstance('Clasificado', 'CTable');
        $clasificado->load($clasificadoId);

        $tmpFriends = $friendsModel->getFriends($my->id, 'name', false);

        $friends = array();

        for ($i = 0; $i < count($tmpFriends); $i++) {
            $friend = $tmpFriends[$i];
            $clasificadoMember = JTable::getInstance('ClasificadoMembers', 'CTable');
            $keys = array('clasificadoId' => $clasificadoId, 'memberId' => $friend->id);
            $clasificadoMember->load($keys);


            if (!$clasificado->isMember($friend->id) && !$clasificadoMember->exists()) {
                $friends[] = $friend;
            }
        }
        unset($tmpFriends);

        $tmpl = new CTemplate();
        echo $tmpl->set('friends', $friends)
                ->set('clasificado', $clasificado)
                ->fetch('clasificados.invitefriends');
    }

    public function pastclasificados() {
        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;
        $document = JFactory::getDocument();
        $config = CFactory::getConfig();
        $my = CFactory::getUser();

        $groupId = $jinput->get->get('groupid', '', 'INT'); //JRequest::getVar('groupid','', 'GET');
        if (!empty($groupId)) {
            $group = JTable::getInstance('Group', 'CTable');
            $group->load($groupId);

            // Set pathway for group videos
            // Community > Groups > Group Name > Events
            $this->addPathway(JText::_('COM_COMMUNITY_GROUPS'), CRoute::_('index.php?option=com_community&view=groups'));
            $this->addPathway($group->name, CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupId));
        } else {
            $this->addPathway(JText::_('COM_COMMUNITY_EVENTS'), CRoute::_('index.php?option=com_community&view=clasificados'));
            $this->addPathway(JText::_('COM_COMMUNITY_EVENTS_PAST_TITLE'), '');
        }

        $document->setTitle(JText::_('COM_COMMUNITY_EVENTS_PAST_TITLE'));

        $this->showSubmenu();

        $feedLink = CRoute::_('index.php?option=com_community&view=clasificados&task=pastclasificados&format=feed');
        $feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('COM_COMMUNITY_SUBSCRIBE_EXPIRED_EVENTS_FEED') . '"  href="' . $feedLink . '"/>';
        $document->addCustomTag($feed);

        // loading neccessary files here.
        //CFactory::load( 'libraries' , 'filterbar' );
        //CFactory::load( 'helpers' , 'event' );
        //CFactory::load( 'helpers' , 'owner' );
        //CFactory::load( 'models' , 'events');
        //$event		= JTable::getInstance( 'Event' , 'CTable' );

        $data = new stdClass();
        $sorted = $jinput->get->get('sort', 'latest', 'STRING');
        $model = CFactory::getModel('clasificados');

        //CFactory::load( 'helpers' , 'event' );
        $clasificado = JTable::getInstance('Clasificado', 'CTable');
        $handler = CClasificadoHelper::getHandler($clasificado);

        // It is safe to pass 0 as the category id as the model itself checks for this value.
        $data->clasificados = $model->getClasificados(null, null, $sorted, null, false, true, null, null, $handler->getContentTypes(), $handler->getContentId());

        // Get pagination object
        $data->pagination = $model->getPagination();

        // Get the template for the group lists
        $clasificadosHTML = $this->_cachedCall('_getClasificadosHTML', array($data->clasificados, true, $data->pagination), '', array(COMMUNITY_CACHE_TAG_EVENTS));

        $sortItems = array(
            'latest' => JText::_('COM_COMMUNITY_EVENTS_SORT_CREATED'),
            'startdate' => JText::_('COM_COMMUNITY_EVENTS_SORT_START_DATE'));

        $tmpl = new CTemplate();
        echo $tmpl->set('clasificadosHTML', $clasificadosHTML)
                ->set('config', $config)
                ->set('isCommunityAdmin', COwnerHelper::isCommunityAdmin())
                ->set('sortings', CFilterBar::getHTML(CRoute::getURI(), $sortItems, 'startdate'))
                ->set('my', $my)
                ->fetch('clasificados.pastclasificados');
    }

    /*
     * @since 2.4
     * To retrieve nearby events
     */

    public function modClasificadoNearby() {
        return $this->_getNearbyClasificado();
    }

    /*
     * @since 2.4
     */

    public function _getNearbyClasificado() {
        $tmpl = new CTemplate();
        echo $tmpl->fetch('clasificados.nearbysearch');
    }

    /*
     * @since 3.0
     * To get event category
     */

    public function modClasificadoCategories($category, $categories) {
        return $this->_getClasificadoCategories($category, $categories);
    }

    /*
     * @since 3.0
     */

    public function _getClasificadoCategories($category, $categories) {
        $tmpl = new CTemplate();
        echo $tmpl->set('category', $category)
                ->set('categories', $categories)
                ->fetch('modules/clasificados/categories');
    }

    /*
     * @since 2.4
     * To retrieve events on calendar
     */

    public function modClasificadoCalendar() {
        return $this->_getClasificadoCalendar();
    }

    /*
     * @since 2.4
     */

    private function _getClasificadoCalendar() {
        $tmpl = new CTemplate();
        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;

        //@since 2.6 if there is group id assigned, only display group's events.
        $gid = $jinput->request->get('groupid', '', 'INT'); //only display

        echo $tmpl->set('group_id', $gid)
                ->fetch('clasificados.clasificadocalendar');
    }

    /*
     * @since 2.4
     * To retrieve event pending list
     */

    public function modClasificadoPendingList() {
        $my = CFactory::getUser();
        return $this->_getPendingListHTML($my);
    }

    public function myclasificados() {
        if (!$this->accessAllowed('registered')) {
            return;
        }

        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;
        $document = JFactory::getDocument();
        $config = CFactory::getConfig();
        $my = CFactory::getUser();
        $userid = $jinput->get('userid', $my->id, 'INT');
        $currentUser = CFactory::getUser($userid);

        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS'), CRoute::_('index.php?option=com_community&view=clasificados'));
        $this->addPathway(JText::sprintf('COM_COMMUNITY_USER_EVENTS', $currentUser->getDisplayName()), '');

        $document->setTitle(JText::sprintf('COM_COMMUNITY_USER_EVENTS', $currentUser->getDisplayName()));

        $this->showSubmenu();

        $feedLink = CRoute::_('index.php?option=com_community&view=clasificados&userid=' . $userid . '&format=feed');
        $feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('COM_COMMUNITY_SUBSCRIBE_MY_EVENTS_FEED') . '" href="' . $feedLink . '"/>';
        $document->addCustomTag($feed);

        $data = new stdClass();
        $sorted = $jinput->get->get('sort', 'startdate', 'STRING'); //JRequest::getVar( 'sort' , 'startdate' , 'GET' );
        $model = CFactory::getModel('clasificados');

        // It is safe to pass 0 as the category id as the model itself checks for this value.
        $data->clasificados = $model->getClasificados(null, $userid, $sorted);

        // Get pagination object
        $data->pagination = $model->getPagination();

        // Get the template for the group lists
        $clasificadosHTML = $this->_cachedCall('_getClasificadosHTML', array($data->clasificadis, false, $data->pagination), '', array(COMMUNITY_CACHE_TAG_EVENTS));

        $tmpl = new CTemplate();

        $sortItems = array(
            'latest' => JText::_('COM_COMMUNITY_EVENTS_SORT_CREATED'),
            'startdate' => JText::_('COM_COMMUNITY_EVENTS_SORT_COMING'));

        echo $tmpl->set('clasificadosHTML', $clasificadosHTML)
                ->set('config', $config)
                ->set('isCommunityAdmin', COwnerHelper::isCommunityAdmin())
                ->set('sortings', CFilterBar::getHTML(CRoute::getURI(), $sortItems, 'startdate'))
                ->set('my', $my)
                ->fetch('clasificados.myclasificados');
    }

    public function myinvites() {
        if (!$this->accessAllowed('registered')) {
            return;
        }

        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;
        $document = JFactory::getDocument();
        $config = CFactory::getConfig();
        $my = CFactory::getUser();
        $userid = JRequest::getCmd('userid', null);

        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS'), CRoute::_('index.php?option=com_community&view=clasificados'));
        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS_PENDING_INVITATIONS'), '');

        $document->setTitle(JText::_('COM_COMMUNITY_EVENTS_PENDING_INVITATIONS'));

        $this->showSubmenu();

        $feedLink = CRoute::_('index.php?option=com_community&view=clasificados&userid=' . $userid . '&format=feed');
        $feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('COM_COMMUNITY_SUBSCRIBE_TO_PENDING_INVITATIONS_FEED') . '"  href="' . $feedLink . '"/>';
        $document->addCustomTag($feed);


        //CFactory::load( 'libraries' , 'filterbar' );
        //CFactory::load( 'helpers' , 'event' );
        //CFactory::load( 'helpers' , 'owner' );
        //CFactory::load( 'models' , 'events');

        $sorted = $jinput->get->get('sort', 'startdate', 'STRING'); //JRequest::getVar( 'sort' , 'startdate' , 'GET' );
        $model = CFactory::getModel('clasificados');
        $pending = COMMUNITY_EVENT_STATUS_INVITED;

        // It is safe to pass 0 as the category id as the model itself checks for this value.
        $rows = $model->getClasificados(null, $my->id, $sorted, null, true, false, $pending);
        $pagination = $model->getPagination();
        $count = count($rows);
        $sortItems = array('latest' => JText::_('COM_COMMUNITY_EVENTS_SORT_CREATED'),
            'startdate' => JText::_('COM_COMMUNITY_EVENTS_SORT_COMING'));

        $clasificados = array();

        if ($rows) {
            foreach ($rows as $row) {
                $clasificado = JTable::getInstance('Clasificado', 'CTable');
                $clasificado->bind($row);
                $clasificados[] = $clasificado;
            }
            unset($clasificadoObjs);
        }

        $tmpl = new CTemplate();
        echo $tmpl->set('clasificados', $clasificados)
                ->set('pagination', $pagination)
                ->set('config', $config)
                ->set('isCommunityAdmin', COwnerHelper::isCommunityAdmin())
                ->set('sortings', CFilterBar::getHTML(CRoute::getURI(), $sortItems, 'startdate'))
                ->set('my', $my)
                ->set('count', $count)
                ->fetch('clasificados.myinvites');
    }

    /**
     * Method to display the create / edit event's form.
     * Both views share the same template file.
     * */
    public function _displayForm($clasificado) {
        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;
        $my = CFactory::getUser();
        $config = CFactory::getConfig();
        $model = CFactory::getModel('clasificados');
        $categories = $model->getCategories();
        $now = JFactory::getDate();

        //J1.6 returns timezone as string, not integer offset.

        $systemOffset = new JDate('now', $mainframe->getCfg('offset'));
        $systemOffset = $systemOffset->getOffsetFromGMT(true);

        $editorType = ($config->get('allowhtml') ) ? $config->get('htmleditor', 'none') : 'none';

        $editor = new CEditor($editorType);
        $totalClasificadoCount = $model->getClasificadosCreationCount($my->id);

        if ($clasificado->catid == NULL)
            $clasificado->catid = JRequest::getInt('categoryid', 0, 'GET');

        $clasificado->startdatetime = $jinput->post->get('startdatetime', '00:01', 'NONE');
        $clasificado->enddatetime = $jinput->post->get('enddatetime', '23:59', 'NONE');

        $timezones = CTimeHelper::getTimezoneList();

        $helper = CClasificadoHelper::getHandler($clasificado);

        $startDate = $clasificado->getStartDate(false);
        $endDate = $clasificado->getEndDate(false);
        $repeatEndDate = $clasificado->getRepeatEndDate();

        $dateSelection = CClasificadoHelper::getDateSelection($startDate, $endDate);

        // Load category tree
        $cTree = CCategoryHelper::getCategories($categories);
        $lists['categoryid'] = CCategoryHelper::getSelectList('clasificados', $cTree, $clasificado->catid);

        $app = CAppPlugins::getInstance();
        $appFields = $app->triggerClasificado('onFormDisplay', array('createClasificado'));
        $beforeFormDisplay = CFormElement::renderElements($appFields, 'before');
        $afterFormDisplay = CFormElement::renderElements($appFields, 'after');

        $tmpl = new CTemplate();
        echo $tmpl->set('startDate', $startDate)
                ->set('endDate', $endDate)
                ->set('enableRepeat', $my->authorise('community.view', 'clasificados.repeat'))
                ->set('repeatEndDate', $repeatEndDate)
                ->set('startHourSelect', $dateSelection->startHour)
                ->set('endHourSelect', $dateSelection->endHour)
                ->set('startMinSelect', $dateSelection->startMin)
                ->set('endMinSelect', $dateSelection->endMin)
                ->set('startAmPmSelect', $dateSelection->startAmPm)
                ->set('endAmPmSelect', $dateSelection->endAmPm)
                ->set('timezones', $timezones)
                ->set('config', $config)
                ->set('systemOffset', $systemOffset)
                ->set('lists', $lists)
                ->set('categories', $categories)
                ->set('clasificado', $clasificado)
                ->set('editor', $editor)
                ->set('helper', $helper)
                ->set('now', $now->format('%Y-%m-%d'))
                ->set('clasificadoCreated', $totalClasificadoCount)
                ->set('clasificadocreatelimit', $config->get('clasificadocreatelimit'))
                ->set('beforeFormDisplay', $beforeFormDisplay)
                ->set('afterFormDisplay', $afterFormDisplay)
                ->fetch('clasificados.forms');
    }

    /**
     * Display the form of the event import and the listing of events users can import
     * from the calendar file.
     * */
    public function import($clasificados) {
        $groupId = JRequest::getInt('groupid', 0, 'GET');
        $groupLink = $groupId > 0 ? '&groupid=' . $groupId : '';
        $saveImportLink = CRoute::_('index.php?option=com_community&view=clasificados&task=saveImport' . $groupLink);


        if (!$this->accessAllowed('registered')) {
            return;
        }

        $document = JFactory::getDocument();
        $config = CFactory::getConfig();
        $document->setTitle(JText::_('COM_COMMUNITY_EVENTS_IMPORT_ICAL'));

        $this->showSubmenu();
        $model = CFactory::getModel('clasificados');
        $categories = $model->getCategories();

        //CFactory::load( 'helpers' , 'time' );
        $timezones = CTimeHelper::getTimezoneList();

        $tmpl = new CTemplate();
        echo $tmpl->set('clasificados', $clasificados)
                ->set('categories', $categories)
                ->set('timezones', $timezones)
                ->set('saveimportlink', $saveImportLink)
                ->fetch('clasificados.import');
    }

    /**
     * Displays the create event form
     * */
    public function create($clasificado) {
        if (!$this->accessAllowed('registered')) {
            return;
        }

        $document = JFactory::getDocument();
        $config = CFactory::getConfig();
        $mainframe = JFactory::getApplication();
        //CFactory::load( 'helpers' , 'owner' );
        //CFactory::load( 'helpers' , 'event' );
        $handler = CClasificadoHelper::getHandler($clasificado);

        if (!$handler->creatable()) {
            $document->setTitle('');
            $mainframe->enqueueMessage(JText::_('COM_COMMUNITY_EVENTS_DISABLE_CREATE'), 'error');
            return;
        }

        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS'), CRoute::_('index.php?option=com_community&view=clasificados'));
        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS_CREATE_TITLE'), '');
        $document->setTitle(JText::_('COM_COMMUNITY_EVENTS_CREATE_TITLE'));

        $js = 'assets/validate-1.5.min.js';
        CAssets::attach($js, 'js');

        $this->showSubmenu();
        $this->_displayForm($clasificado);
        return;
    }

    public function edit($clasificado) {
        if (!$this->accessAllowed('registered'))
            return;
        $document = JFactory::getDocument();
        $config = CFactory::getConfig();
        $document->setTitle(JText::_('COM_COMMUNITY_EVENTS_EDIT_TITLE'));

        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS'), CRoute::_('index.php?option=com_community&view=clasificados'));
        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS_EDIT_TITLE'), '');

        $file = 'assets/validate-1.5.min.js';

        CAssets::attach($file, 'js');


        if (!$this->accessAllowed('registered')) {
            echo JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN');
            return;
        }

        $this->showSubmenu();
        $this->_displayForm($clasificado);
        return;
    }

    public function printpopup($clasificado) {
        $config = CFactory::getConfig();
        $my = CFactory::getUser();
        // We need to attach the javascirpt manually

        $js = JURI::root() . 'components/com_community/assets/joms.jquery-1.8.1.min.js';
        $script = '<script type="text/javascript" src="' . $js . '"></script>';

        $js = JURI::root() . 'components/com_community/assets/script-1.2.min.js';

        $script .= '<script type="text/javascript" src="' . $js . '"></script>';

        $creator = CFactory::getUser($clasificado->creator);
        $creatorUtcOffset = $creator->getUtcOffset();
        $creatorUtcOffsetStr = CTimeHelper::getTimezone($clasificado->offset);

        // Get the formated date & time
        $format = ($config->get('clasificadoshowampm')) ? JText::_('COM_COMMUNITY_DATE_FORMAT_LC2_12H') : JText::_('COM_COMMUNITY_DATE_FORMAT_LC2_24H');
        $clasificado->startdateHTML = CTimeHelper::getFormattedTime($clasificado->startdate, $format);
        $clasificado->enddateHTML = CTimeHelper::getFormattedTime($clasificado->enddate, $format);

        // Output to template
        $tmpl = new CTemplate();
        echo $tmpl->set('clasificado', $clasificado)
                ->set('script', $script)
                ->set('creatorUtcOffsetStr', $creatorUtcOffsetStr)
                ->fetch('clasificados.print');
    }

    /**
     * Responsible for displaying the clasificado page.
     * */
    public function viewclasificado() {
        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;
        $document = JFactory::getDocument();
        $config = CFactory::getConfig();
        $my = CFactory::getUser();

        CWindow::load();

        $clasificadoLib = new CClasificados();
        $clasificadoid = JRequest::getInt('clasificadoid', 0);
        $clasificadoModel = CFactory::getModel('clasificados');
        $clasificado = JTable::getInstance('Clasificado', 'CTable');

        $handler = CClasificadoHelper::getHandler($clasificado);

        $clasificado->load($clasificadoid);

        if (empty($clasificado->id)) {
            return JError::raiseWarning(404, JText::_('COM_COMMUNITY_EVENTS_NOT_AVAILABLE_ERROR'));
        }

        if (!$handler->exists()) {
            $mainframe->enqueueMessage(JText::_('COM_COMMUNITY_EVENTS_NOT_AVAILABLE_ERROR'), 'error');
            return;
        }

        if (!$handler->browsable()) {
            echo JText::_('COM_COMMUNITY_NOT_ALLOWED_TO_ACCESS_SECTION');
            return;
        }

        // @rule: Test if the group is unpublished, don't display it at all.
        if (!$clasificado->isPublished()) {
            echo JText::_('COM_COMMUNITY_EVENTS_UNDER_MODERATION');
            return;
        }
        $this->showSubmenu();
        $clasificado->hit();

        // Basic page presentation
        if ($clasificado->type == 'group') {
            $groupId = $clasificado->contentid;
            $group = JTable::getInstance('Group', 'CTable');
            $group->load($groupId);

            // Set pathway for group videos
            // Community > Groups > Group Name > Events
            $this->addPathway(JText::_('COM_COMMUNITY_GROUPS'), CRoute::_('index.php?option=com_community&view=groups'));
            $this->addPathway($group->name, CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupId));
        }

        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS'), CRoute::_('index.php?option=com_community&view=clasificados'));
        $this->addPathway($clasificado->title);

        /* set head meta */
        CHeadHelper::setTitle(JText::sprintf('COM_COMMUNITY_EVENT_PAGE_TITLE', $clasificado->title));

        // Permissions and privacies

        $isClasificadoGuest = $clasificado->isMember($my->id);
        $isMine = ($my->id == $clasificado->creator);
        $isAdmin = $clasificado->isAdmin($my->id);
        $isCommunityAdmin = COwnerHelper::isCommunityAdmin();

        // Get Event Admins
        $clasificadoAdmins = $clasificado->getAdmins(12, CC_RANDOMIZE);
        $adminsInArray = array();

        // Attach avatar of the admin
        for ($i = 0; ($i < count($clasificadoAdmins)); $i++) {
            $row = $clasificadoAdmins[$i];
            $admin = CFactory::getUser($row->id);
            array_push($adminsInArray, '<a href="' . CUrlHelper::userLink($admin->id) . '">' . $admin->getDisplayName() . '</a>');
        }

        $adminsList = ltrim(implode(', ', $adminsInArray), ',');

        // Get Attending Event Guests
        $clasificadoMembers = $clasificado->getMembers(COMMUNITY_EVENT_STATUS_ATTEND, 12, CC_RANDOMIZE);
        $clasificadoMembersCount = $clasificado->getMembersCount(COMMUNITY_EVENT_STATUS_ATTEND);

        // Attach avatar of the admin
        // Pre-load multiple users at once
        $userids = array();
        foreach ($clasificadoMembers as $uid) {
            $userids[] = $uid->id;
        }
        CFactory::loadUsers($userids);

        for ($i = 0; ($i < count($clasificadoMembers)); $i++) {
            $row = $clasificadoMembers[$i];
            $clasificadoMembers[$i] = CFactory::getUser($row->id);
        }


        // Pre-load multiple users at once

        $waitingApproval = $clasificado->isPendingApproval($my->id);
        $waitingRespond = false;

        $myStatus = $clasificado->getUserStatus($my->id);

        $hasResponded = (($myStatus == COMMUNITY_EVENT_STATUS_ATTEND) || ($myStatus == COMMUNITY_EVENT_STATUS_WONTATTEND) || ($myStatus == COMMUNITY_EVENT_STATUS_MAYBE));

        // Get Bookmark HTML
        $bookmarks = new CBookmarks(CRoute::getExternalURL('index.php?option=com_community&view=clasificados&task=viewclasificado&clasificadoid=' . $clasificado->id));
        $bookmarksHTML = $bookmarks->getHTML();

        // Get Reporting HTML
        //$report		= new CReportingLibrary();
        //$reportHTML	= $report->getReportingHTML( JText::_('COM_COMMUNITY_EVENTS_REPORT') , 'events,reportEvent' , array( $event->id ) );
        // Get the Wall
        $wallContent = CWallLibrary::getWallContents('clasificados', $clasificado->id, $isAdmin, 10, 0, 'wall.content', 'clasificados,clasificados');
        $wallCount = CWallLibrary::getWallCount('clasificados', $clasificado->id);
        $viewAllLink = false;

        if ($jinput->request->get('task', '', 'STRING') != 'app') {
            $viewAllLink = CRoute::_('index.php?option=com_community&view=clasificados&task=app&clasificadoid=' . $clasificado->id . '&app=walls');
        }

        $wallContent .= CWallLibrary::getViewAllLinkHTML($viewAllLink, $wallCount);

        $wallForm = '';

        // Construct the RVSP radio list
        $arr = array(
            JHTML::_('select.option', COMMUNITY_EVENT_STATUS_ATTEND, JText::_('COM_COMMUNITY_EVENTS_YES')),
            JHTML::_('select.option', COMMUNITY_EVENT_STATUS_WONTATTEND, JText::_('COM_COMMUNITY_EVENTS_NO')),
            JHTML::_('select.option', COMMUNITY_EVENT_STATUS_MAYBE, JText::_('COM_COMMUNITY_EVENTS_MAYBE'))
        );
        $status = $clasificado->getMemberStatus($my->id);
        $radioList = JHTML::_('select.radiolist', $arr, 'status', '', 'value', 'text', $status, false);

        $unapprovedCount = $clasificado->inviteRequestCount();
        //...
        $editClasificado = $jinput->get->get('edit', false, 'NONE');
        $editClasificado = ( $editClasificado == 1 ) ? true : false;

        // Am I invited in this event?
        $isInvited = false;
        $join = '';
        $friendsCount = 0;
        $isInvited = $clasificadoModel->isInvitedMe(0, $my->id, $clasificado->id);

        // If I was invited, I want to know my invitation informations
        if ($isInvited) {
            $invitor = CFactory::getUser($isInvited[0]->invited_by);
            $join = '<a href="' . CUrlHelper::userLink($invitor->id) . '">' . $invitor->getDisplayName() . '</a>';

            // Get users friends in this group
            $friendsCount = $clasificadoModel->getFriendsCount($my->id, $clasificado->id);
        }

        // Get like
        $likes = new CLike();
        $isUserLiked = false;

        if ($isLikeEnabled = $likes->enabled('clasificados')) {
            $isUserLiked = $likes->userLiked('clasificados', $clasificado->id, $my->id);
        }
        $totalLikes = $likes->getLikeCount('clasificados', $clasificado->id);

        // Is this clasificado is a past clasificado?
        $now = new JDate();
        $isPastClasificado = ( $clasificado->getEndDate(false)->toSql() < $now->toSql(true) ) ? true : false;

        // Get the formated date & time
        $format = ($config->get('clasificadoshowampm')) ? JText::_('COM_COMMUNITY_EVENTS_TIME_FORMAT_12HR') : JText::_('COM_COMMUNITY_EVENTS_TIME_FORMAT_24HR');

        $startDate = $clasificado->getStartDate(false);
        $endDate = $clasificado->getEndDate(false);
        $allday = false;

        if (($startDate->format('%Y-%m-%d') == $endDate->format('%Y-%m-%d')) && $startDate->format('%H:%M:%S') == '00:00:00' && $endDate->format('%H:%M:%S') == '23:59:59') {
            $format = JText::_('COM_COMMUNITY_EVENT_TIME_FORMAT_LC1');
            $allday = true;
        }

        $clasificado->startdateHTML = CTimeHelper::getFormattedTime($clasificado->startdate, $format);
        $clasificado->enddateHTML = CTimeHelper::getFormattedTime($clasificado->enddate, $format);

        $inviteHTML = CInvitation::getHTML(null, 'clasificados,inviteUsers', $clasificado->id, CInvitation::SHOW_FRIENDS, CInvitation::SHOW_EMAIL);

        $status = new CUserStatus($clasificado->id, 'clasificados');

        $tmpl = new CTemplate();
        $creator = new CUserStatusCreator('message');
        $creator->title = ($isMine) ? JText::_('COM_COMMUNITY_STATUS') : JText::_('COM_COMMUNITY_MESSAGE');
        $creator->html = $tmpl->fetch('status.message');
        $status->addCreator($creator);

        // Upgrade wall to stream @since 2.5
        $clasificado->upgradeWallToStream();

        // Add custom stream
        $streamHTML = $clasificadoLib->getStreamHTML($clasificado);

        if ($clasificado->getMemberStatus($my->id) == COMMUNITY_EVENT_STATUS_ATTEND)
            $RSVPmessage = JText::_('COM_COMMUNITY_EVENTS_ATTENDING_EVENT_MESSAGE');
        else if ($clasificado->getMemberStatus($my->id) == COMMUNITY_EVENT_STATUS_WONTATTEND)
            $RSVPmessage = JText::_('COM_COMMUNITY_EVENTS_NOT_ATTENDING_EVENT_MESSAGE');
        else
            $RSVPmessage = JText::_('COM_COMMUNITY_EVENTS_NOT_RESPOND_RSVP_MESSAGE');

        // Get recurring event series
        $clasificadoSeries = null;
        $seriesCount = 0;
        if ($clasificado->isRecurring()) {
            $advance = array('expired' => false,
                'return' => 'object',
                'limit' => COMMUNITY_EVENT_SERIES_LIMIT,
                'exclude' => $clasificado->id,
                'published' => 1);
            $tempseries = $clasificadoModel->getClasificadoChilds($clasificado->parent, $advance);
            foreach ($tempseries as $series) {
                $table = JTable::getInstance('Clasificado', 'CTable');
                $table->bind($series);
                $clasificadoSeries[] = $table;
            }
            $seriesCount = $clasificadoModel->getClasificadoChildsCount($clasificado->parent);
        }

        /* Opengraph */
        CHeadHelper::addOpengraph('og:image', $clasificado->getAvatar('avatar'), true);
        CHeadHelper::addOpengraph('og:image', $clasificado->getCover(), true);

        // Output to template
        echo $tmpl->setMetaTags('clasificado', $clasificado)
                ->set('status', $status)
                ->set('streamHTML', $streamHTML)
                ->set('timezone', CTimeHelper::getTimezone($clasificado->offset))
                ->set('handler', $handler)
                ->set('isUserLiked', $isUserLiked)
                ->set('totalLikes', $totalLikes)
                ->set('inviteHTML', $inviteHTML)
                ->set('guestStatus', $clasificado->getUserStatus($my->id))
                ->set('clasificado', $clasificado)
                ->set('radioList', $radioList)
                ->set('bookmarksHTML', $bookmarksHTML)
                ->set('isLikeEnabled', $isLikeEnabled)
                ->set('isClasificadoGuest', $isClasificadoGuest)
                ->set('isMine', $isMine)
                ->set('isAdmin', $isAdmin)
                ->set('isCommunityAdmin', $isCommunityAdmin)
                ->set('unapproved', $unapprovedCount)
                ->set('waitingApproval', $waitingApproval)
                ->set('wallContent', $wallContent)
                ->set('clasificadoMembers', $clasificadoMembers)
                ->set('clasificadoMembersCount', $clasificadoMembersCount)
                ->set('editClasificado', $editClasificado)
                ->set('my', $my)
                ->set('memberStatus', $myStatus)
                ->set('waitingRespond', $waitingRespond)
                ->set('isInvited', $isInvited)
                ->set('join', $join)
                ->set('friendsCount', $friendsCount)
                ->set('isPastClasificado', $isPastClasificado)
                ->set('adminsList', $adminsList)
                ->set('RSVPmessage', $RSVPmessage)
                ->set('allday', $allday)
                ->set('clasificadoSeries', $clasificadoSeries)
                ->set('seriesCount', $seriesCount)
                ->fetch('clasificados.viewclasificado');
    }

    /**
     * Responsible to output the html codes for the task viewguest.
     * Outputs html codes for the viewguest page.
     *
     * @return	none.
     * */
    public function viewguest() {
        if (!$this->accessAllowed('registered')) {
            return;
        }

        $mainframe = JFactory::getApplication();
        $document = JFactory::getDocument();
        $config = CFactory::getConfig();
        $my = CFactory::getUser();
        $id = JRequest::getInt('clasificadoid', 0);
        $type = JRequest::getCmd('type');
        $approval = JRequest::getCmd('approve');

        $clasificado = JTable::getInstance('Clasificado', 'CTable');
        $clasificado->load($id);

        $handler = CClasificadoHelper::getHandler($clasificado);
        $types = array(COMMUNITY_EVENT_ADMINISTRATOR, COMMUNITY_EVENT_STATUS_INVITED, COMMUNITY_EVENT_STATUS_ATTEND, COMMUNITY_EVENT_STATUS_BLOCKED, COMMUNITY_EVENT_STATUS_REQUESTINVITE);

        if (!in_array($type, $types)) {
            JError::raiseError('500', JText::_('Invalid status type'));
        }

        // Set the guest type for the title purpose
        switch ($type) {
            case COMMUNITY_EVENT_ADMINISTRATOR:
                $guestType = JText::_('COM_COMMUNITY_ADMINS');
                break;
            case COMMUNITY_EVENT_STATUS_INVITED:
                $guestType = JText::_('COM_COMMUNITY_EVENTS_PENDING_MEMBER');
                break;
            case COMMUNITY_EVENT_STATUS_ATTEND:
                $guestType = JText::_('COM_COMMUNITY_EVENTS_CONFIRMED_GUESTS');
                break;
            case COMMUNITY_EVENT_STATUS_BLOCKED:
                $guestType = JText::_('COM_COMMUNITY_EVENTS_BLOCKED');
                break;
            case COMMUNITY_EVENT_STATUS_REQUESTINVITE:
                $guestType = JText::_('COM_COMMUNITY_REQUESTED_INVITATION');
                break;
        }

        // Then we load basic page presentation
        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS'), CRoute::_('index.php?option=com_community&view=clasificados'));
        $this->addPathway(JText::sprintf('COM_COMMUNITY_EVENTS_TITLE_LABEL', $clasificado->title), '');

        // Set the specific title
        $document->setTitle(JText::sprintf('COM_COMMUNTIY_EVENTS_GUESTLIST', $clasificado->title, $guestType));


        //CFactory::load( 'helpers' , 'owner' );
        $status = $clasificado->getUserStatus($my->id);
        $allowed = array(COMMUNITY_EVENT_STATUS_INVITED, COMMUNITY_EVENT_STATUS_ATTEND, COMMUNITY_EVENT_STATUS_WONTATTEND, COMMUNITY_EVENT_STATUS_MAYBE);
        $accessAllowed = ( ( in_array($status, $allowed) ) && $status != COMMUNITY_EVENT_STATUS_BLOCKED ) ? true : false;

        if ($handler->hasInvitation() && ( ( $accessAllowed && $clasificado->allowinvite ) || $clasificado->isAdmin($my->id) || COwnerHelper::isCommunityAdmin() )) {
            $this->addSubmenuItem('javascript:void(0)', JText::_('COM_COMMUNITY_TAB_INVITE'), "joms.invitation.showForm('', 'clasificados,inviteUsers','" . $clasificado->id . "','1','1');", SUBMENU_RIGHT);
        }
        $this->showSubmenu();

        $isSuperAdmin = COwnerHelper::isCommunityAdmin();

        // status = unsure | noreply | accepted | declined | blocked
        // permission = admin | guest |

        if ($type == COMMUNITY_EVENT_ADMINISTRATOR) {
            $guestsIds = $clasificado->getAdmins(0);
        } else {
            $guestsIds = $clasificado->getMembers($type, 0, false, $approval);
        }

        $guests = array();

        // Pre-load multiple users at once
        $userids = array();
        foreach ($guestsIds as $uid) {
            $userids[] = $uid->id;
        }
        CFactory::loadUsers($userids);

        for ($i = 0; $i < count($guestsIds); $i++) {
            $guests[$i] = CFactory::getUser($guestsIds[$i]->id);
            $guests[$i]->friendsCount = $guests[$i]->getFriendCount();
            $guests[$i]->isMe = ( $my->id == $guests[$i]->id ) ? true : false;
            $guests[$i]->isAdmin = $clasificado->isAdmin($guests[$i]->id);
            $guests[$i]->statusType = $guestsIds[$i]->statusCode;
        }

        $pagination = $clasificado->getPagination();

        // Output to template
        $tmpl = new CTemplate();
        echo $tmpl->set('clasificado', $clasificado)
                ->set('handler', $handler)
                ->set('guests', $guests)
                ->set('clasificadoid', $clasificado->id)
                ->set('isMine', $clasificado->isCreator($my->id))
                ->set('isSuperAdmin', $isSuperAdmin)
                ->set('pagination', $pagination)
                ->set('my', $my)
                ->set('config', $config)
                ->fetch('clasificados.viewguest');
    }

    public function search() {
        // Get the document object and set the necessary properties of the document
        $document = JFactory::getDocument();
        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS'), CRoute::_('index.php?option=com_community&view=clasificados'));
        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS_SEARCH'), '');
        $document->setTitle(JText::_('COM_COMMUNITY_SEARCH_EVENTS_TITLE'));

        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;
        $script = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
        $document->addCustomTag($script);

        $config = CFactory::getConfig();

        // Display the submenu
        $this->showSubmenu();

        //New search features
        $model = CFactory::getModel('clasificados');
        $categories = $model->getCategories();

        // input filtered to remove tags
        $search = trim($jinput->get('search', '', 'STRING'));

        // Input for advance search
        $catId = JRequest::getInt('catid', '');
        $unit = $jinput->get('unit', $config->get('clasificadoradiusmeasure'), 'NONE');

        $category = JTable::getInstance('ClasificadoCategory', 'CTable');
        $category->load($catId);

        $advance = array();
        $advance['startdate'] = $jinput->get('startdate', '', 'NONE');
        $advance['enddate'] = $jinput->get('enddate', '', 'NONE');
        $advance['radius'] = $jinput->get('radius', '', 'NONE');
        $advance['fromlocation'] = $jinput->get('location', '', 'NONE');

        if ($unit === COMMUNITY_EVENT_UNIT_KM) { //COM_COMMUNITY_EVENTS_MILES
            // Since our searching need a value in Miles unit, we need to convert the KM value to Miles
            // 1 kilometre	=   0.621371192 miles
            // 1 mile = 1.6093 km
            $advance['radius'] = $advance['radius'] * 0.621371192;
        }

        $clasificados = '';
        $pagination = null;
        $posted = JRequest::getInt('posted', '');
        $count = 0;
        $clasificadosHTML = '';

        // Test if there are any post requests made
        if (!empty($search) || !empty($catId) || (!empty($advance['startdate']) || !empty($advance['enddate']) || !empty($advance['radius']) || !empty($advance['fromlocation']))) {
            // Check for request forgeries
            JRequest::checkToken('get') or jexit(JText::_('COM_COMMUNITY_INVALID_TOKEN'));

            //CFactory::load( 'libraries' , 'apps' );
            $appsLib = CAppPlugins::getInstance();
            $saveSuccess = $appsLib->triggerClasificado('onFormSave', array('jsform-clasificados-search'));

            if (empty($saveSuccess) || !in_array(false, $saveSuccess)) {
                $clasificados = $model->getClasificados($category->id, null, null, $search, null, null, null, $advance);
                $pagination = $model->getPagination();
                $count = $model->getClasificadosSearchTotal();
            }
        }

        // Get the template for the events lists
        $clasificadosHTML = $this->_getClasificadosHTML($clasificados, false, $pagination);

        $app = CAppPlugins::getInstance();
        $appFields = $app->triggerClasificado('onFormDisplay', array('jsform-clasificados-search'));
        $beforeFormDisplay = CFormElement::renderElements($appFields, 'before');
        $afterFormDisplay = CFormElement::renderElements($appFields, 'after');

        $searchLinks = parent::getAppSearchLinks('clasificados');

        // Revert back the radius value
        $advance['radius'] = $jinput->get('radius', '', 'NONE');

        $tmpl = new CTemplate();
        echo $tmpl->set('beforeFormDisplay', $beforeFormDisplay)
                ->set('afterFormDisplay', $afterFormDisplay)
                ->set('posted', $posted)
                ->set('clasificadosCount', $count)
                ->set('clasificadosHTML', $clasificadosHTML)
                ->set('search', $search)
                ->set('catId', $category->id)
                ->set('categories', $categories)
                ->set('advance', $advance)
                ->set('unit', $unit)
                ->set('searchLinks', $searchLinks)
                ->fetch('clasificados.search');
    }

    /**
     * An clasificado has just been created, should we just show the album ?
     */
    public function created() {

        $clasificadoid = JRequest::getInt('clasificadoid', 0);

        //CFactory::load( 'models' , 'clasificados');
        $clasificado = JTable::getInstance('Clasificado', 'CTable');

        $clasificado->load($clasificadoid);
        $document = JFactory::getDocument();
        $document->setTitle($clasificado->title);

        $uri = JURI::base();
        $this->showSubmenu();

        $tmpl = new CTemplate();
        echo $tmpl->set('link', CRoute::_('index.php?option=com_community&view=clasificados&task=viewclasificado&clasificadoid=' . $clasificado->id))
                ->set('linkUpload', CRoute::_('index.php?option=com_community&view=clasificados&task=uploadavatar&clasificadoid=' . $clasificado->id))
                ->set('linkEdit', CRoute::_('index.php?option=com_community&view=clasificados&task=edit&clasificadoid=' . $clasificado->id))
                ->set('linkInvite', CRoute::_('index.php?option=com_community&view=clasificados&task=invitefriends&clasificadoid=' . $clasificado->id))
                ->fetch('clasificados.created');
    }

    public function sendmail() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_COMMUNITY_EVENTS_EMAIL_SEND'));
        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS'), CRoute::_('index.php?option=com_community&view=clasificados'));
        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS_EMAIL_SEND'));

        if (!$this->accessAllowed('registered')) {
            return;
        }

        // Display the submenu
        $this->showSubmenu();
        $clasificadoId = JRequest::getInt('clasificadoid', '');

        //CFactory::load( 'helpers', 'owner' );
        //CFactory::load( 'models' , 'clasificados' );
        $clasificado = JTable::getInstance('Clasificado', 'CTable');
        $clasificado->load($clasificadoId);

        if (empty($clasificadoId) || empty($clasificado->title)) {
            echo JText::_('COM_COMMUNITY_INVALID_ID_PROVIDED');
            return;
        }

        $my = CFactory::getUser();
        $config = CFactory::getConfig();
        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;

        //CFactory::load( 'libraries' , 'editor' );
        $editor = new CEditor($config->get('htmleditor'));

        //CFactory::load( 'helpers' , 'clasificado' );
        $handler = CClasificadoHelper::getHandler($clasificado);
        if (!$handler->manageable()) {
            $this->noAccess();
            return;
        }

        $message = JRequest::getVar('message', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $title = $jinput->get('title', '', 'STRING'); //JRequest::getVar( 'title'	, '' );

        $tmpl = new CTemplate();
        echo $tmpl->set('editor', $editor)
                ->set('clasificado', $clasificado)
                ->set('message', $message)
                ->set('title', $title)
                ->fetch('clasificados.sendmail');
    }

    public function uploadAvatar() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_COMMUNITY_EVENTS_AVATAR'));

        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;

        $clasificadoid = $jinput->get('clasificadoid', '0', 'INT');
        $this->_addClasificadoInPathway($clasificadoid);
        $this->addPathway(JText::_('COM_COMMUNITY_EVENTS_AVATAR'));

        $this->showSubmenu();
        $clasificado = JTable::getInstance('Clasificado', 'CTable');
        $clasificado->load($clasificadoid);

        //CFactory::load( 'helpers' , 'clasificado' );
        $handler = CClasificadoHelper::getHandler($clasificado);
        if (!$handler->manageable()) {
            $this->noAccess();
            return;
        }

        $config = CFactory::getConfig();
        $uploadLimit = (double) $config->get('maxuploadsize');
        $uploadLimit .= 'MB';

        //CFactory::load( 'models' , 'clasificados' );
        $clasificado = JTable::getInstance('Clasificado', 'CTable');
        $clasificado->load($clasificadoid);

        //CFactory::load( 'libraries' , 'apps' );
        $app = CAppPlugins::getInstance();
        $appFields = $app->triggerClasificado('onFormDisplay', array('jsform-clasificados-uploadavatar'));
        $beforeFormDisplay = CFormElement::renderElements($appFields, 'before');
        $afterFormDisplay = CFormElement::renderElements($appFields, 'after');

        $tmpl = new CTemplate();
        echo $tmpl->set('beforeFormDisplay', $beforeFormDisplay)
                ->set('afterFormDisplay', $afterFormDisplay)
                ->set('clasificadoId', $clasificadoid)
                ->set('avatar', $clasificado->getAvatar('avatar'))
                ->set('thumbnail', $clasificado->getThumbAvatar())
                ->set('uploadLimit', $uploadLimit)
                ->fetch('clasificados.uploadavatar');
    }

    public function _addClasificadoInPathway($clasificadoId) {
        //CFactory::load( 'models' , 'clasificados' );
        $clasificado = JTable::getInstance('Clasificado', 'CTable');
        $clasificado->load($clasificadoId);

        $this->addPathway($clasificado->title, CRoute::_('index.php?option=com_community&view=clasificados&task=viewclasificado&clasificadoid=' . $clasificado->id));
    }

    public function _getClasificadosHTML($clasificadoObjs, $isExpired = false, $pagination = NULL) {
        $clasificados = array();

        $config = CFactory::getConfig();
        $format = ($config->get('clasificadoshowampm')) ? JText::_('COM_COMMUNITY_DATE_FORMAT_LC2_12H') : JText::_('COM_COMMUNITY_DATE_FORMAT_LC2_24H');

        if ($clasificadoObjs) {
            foreach ($clasificadoObjs as $row) {
                $clasificado = JTable::getInstance('Clasificado', 'CTable');
                $clasificado->bind($row);
                $clasificados[] = $clasificado;
            }
            unset($clasificadoObjs);
        }

        $featured = new CFeatured(FEATURED_EVENTS);
        $featuredList = $featured->getItemIds();

        $tmpl = new CTemplate();
        return $tmpl->set('showFeatured', $config->get('show_featured'))
                        ->set('featuredList', $featuredList)
                        ->set('isCommunityAdmin', COwnerHelper::isCommunityAdmin())
                        ->set('clasificados', $clasificados)
                        ->set('isExpired', $isExpired)
                        ->set('pagination', $pagination)
                        ->set('timeFormat', $format)
                        ->fetch('clasificados.list');
    }

    public function _getClasificadosCategories($categoryId) {
        $model = CFactory::getModel('clasificados');

        $categories = $model->getCategoriesCount();



        $categories = CCategoryHelper::getParentCount($categories, $categoryId);

        return $categories;
    }

    public function _getPendingListHTML($user) {
        //CFactory::load( 'models', 'clasificados' );
        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;
        $model = CFactory::getModel('clasificados');
        $sorted = $jinput->get->get('sort', 'startdate', 'STRING'); //JRequest::getVar( 'sort' , 'startdate' , 'GET' );
        $pending = COMMUNITY_EVENT_STATUS_INVITED;
        $rows = $model->getClasificados(null, $user->id, $sorted, null, true, false, $pending);
        $clasificados = array();

        if ($rows) {
            foreach ($rows as $row) {
                $clasificado = JTable::getInstance('Clasificado', 'CTable');
                $clasificado->bind($row);
                $clasificados[] = $clasificado;
            }
        }

        $tmpl = new CTemplate();
        return $tmpl->set('clasificados', $clasificados)
                        ->fetch('clasificados.pendinginvitelist');
    }

    public function _getClasificadosFeaturedList() {
        //CFactory::load( 'libraries' , 'featured' );
        $featured = new CFeatured(FEATURED_EVENTS);
        $featuredClasificados = $featured->getItemIds();
        $featuredList = array();
        $now = new JDate();

        foreach ($featuredClasificados as $clasificado) {
            $table = JTable::getInstance('Clasificado', 'CTable');
            $table->load($clasificado);
            $expiry = new JDate($table->enddate);
            if ($expiry->toUnix() >= $now->toUnix()) {
                $featuredList[] = $table;
            }
        }

        if (!empty($featuredList)) {
            foreach ($featuredList as $key => $row) {
                $orderByDate[$key] = strtotime($row->startdate);
            }

            array_multisort($orderByDate, SORT_ASC, $featuredList);
        }

        return $featuredList;
    }

}
