<?php
/**
 * @author     mediahof, Kiel-Germany
 * @link       http://www.mediahof.de
 * @copyright  Copyright (C) 2013 - 2014 mediahof. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');

class JFormFieldDoc extends JFormFieldText
{

    protected $type = 'Doc';

    protected function getInput()
    {
        return JHtml::_('link', $this->element['href'], JText::_('Documentation'), array('target' => '_blank'));
    }

    protected function getLabel()
    {
        return '';
    }
}
