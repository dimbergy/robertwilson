<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Helper
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 * @uses        KConfig
 */
class ComDefaultTemplateHelperListbox extends KTemplateHelperListbox
{    
    /**
     * Generates an HTML enabled listbox
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function enabled( $config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name'      => 'enabled',
            'attribs'   => array(),
            'deselect'  => true,
            'prompt'    => '- '.$this->translate('Select').' -',
        ))->append(array(
            'selected'  => $config->{$config->name}
        ));

        $options = array();

        if($config->deselect) {
            $options[] = $this->option(array('text' => $config->prompt, 'value' => ''));
        }

        $options[] = $this->option(array('text' => $this->translate( 'Enabled' ) , 'value' => 1 ));
        $options[] = $this->option(array('text' => $this->translate( 'Disabled' ), 'value' => 0 ));

        //Add the options to the config object
        $config->options = $options;

        return $this->optionlist($config);
    }

    /**
     * Generates an HTML published listbox
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function published($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name'      => 'enabled',
            'attribs'   => array(),
            'deselect'  => true,
            'prompt'    => '- '.$this->translate('Select').' -'
        ))->append(array(
            'selected'  => $config->{$config->name}
        ));
    
        $options = array();
    
        if ($config->deselect) {
            $options[] = $this->option(array('text' => $config->prompt, 'value' => ''));
        }
    
        $options[] = $this->option(array('text' => $this->translate('Published'), 'value' => 1 ));
        $options[] = $this->option(array('text' => $this->translate('Unpublished') , 'value' => 0 ));
    
        //Add the options to the config object
        $config->options = $options;
    
        return $this->optionlist($config);
    }

    /**
     * Generates an HTML access listbox
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function access($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name'      => 'access',
            'attribs'   => array(),
            'deselect'  => true,
            'prompt'    => '- '.$this->translate('Select').' -'
        ))->append(array(
            'selected'  => $config->{$config->name}
        ));
        
        if (version_compare(JVERSION, '1.6', '<')) {
            $html = parent::access();
        } else {
            $prompt = false;
            if ($config->deselect) {
                $prompt = array((object) array('value' => '', 'text'  => $config->prompt));
            }
            
            $html = JHtml::_('access.level', $config->name, $config->selected, $config->attribs->toArray(), $prompt);
        }
    
        return $html;
    }
}