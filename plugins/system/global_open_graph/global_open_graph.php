<?php
/**
 * @author     mediahof, Kiel-Germany
 * @link       http://www.mediahof.de
 * @copyright  Copyright (C) 2013 -2014 mediahof. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class plgSystemGlobal_Open_Graph extends JPlugin
{

    private $tags = array(
        'og' => '<meta property="%s" content="%s" />',
        'fb' => '<meta property="%s" content="%s" />',
        'twitter' => '<meta name="%s" content="%s" />',
    );

    public function onBeforeRender()
    {
        if (JFactory::getApplication()->isAdmin()) {
            return;
        }

        $document = JFactory::getDocument();

        if (!method_exists($document, 'addCustomTag')) {
            return;
        }

        foreach ($this->params->toArray() as $tag => $value) {
            if (!empty($value)) {
                $set = explode(':', $tag);
                $document->addCustomTag(sprintf($this->tags[$set[0]], $tag, htmlspecialchars($value)));
            }
        }
    }
}