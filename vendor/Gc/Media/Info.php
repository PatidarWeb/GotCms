<?php
/**
 * This source file is part of GotCms.
 *
 * GotCms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * GotCms is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with GotCms. If not, see <http://www.gnu.org/licenses/lgpl-3.0.html>.
 *
 * PHP Version >=5.3
 *
 * @category    Gc
 * @package     Library
 * @subpackage  Media
 * @author      Pierre Rambaud (GoT) <pierre.rambaud86@gmail.com>
 * @license     GNU/LGPL http://www.gnu.org/licenses/lgpl-3.0.html
 * @link        http://www.got-cms.com
 */

namespace Gc\Media;

use Gc\Core\Object,
    Zend\Config\Reader\Ini;
/**
 * Manage File, actually only works for Datatypes
 * Need document and property to work
 */
class Info extends Object
{
    /**
     * @var array available array options
     */
    protected $_optionsArray = array('database_compatibility' => 'Database compatibility');

    /**
     * @var array available string options
     */
    protected $_optionsString = array('author' => 'Author', 'date' => 'Date', 'description' => 'Description', 'cms_version' => 'GotCms version', 'version' => 'Version');

    /**
     * @var array available links options
     */
    protected $_optionsLinks = array('website' => 'Website url');

    /**
     * Initialize reader
     *
     * @return void
     */
    public function init()
    {
        $this->setReader(new Ini());
    }

    /**
     * Initialize file from path
     *
     * @param string $file_path
     * @return boolean
     */
    public function fromFile($file_path)
    {
        if(!empty($file_path) and $file_path == $this->getFilename())
        {
            return TRUE;
        }
        elseif($file_path != $this->getFilename() and file_exists($file_path))
        {
            $this->setFilename($file_path);
            $this->setInfos($this->getReader()->fromFile($file_path));

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Render info file to html
     *
     * @return string|FALSE
     */
    public function render()
    {
        $infos = $this->getInfos();
        if(empty($infos) or !is_array($infos))
        {
            return FALSE;
        }

        $translator = \Gc\Registry::get('Translator');
        $escaper = \Gc\Registry::get('Application')->getServiceManager()->get('ViewManager')->getHelperManager()->get('escapehtml');

        $return = '<dl>';
        foreach($infos as $key => $info)
        {
            if(!empty($this->_optionsArray[$key]))
            {
                $return .= sprintf('<dt>%s</dt>', $translator->translate($this->_optionsArray[$key]));
                if(!is_array($info))
                {
                    $info = array($info);
                }

                foreach($info as $value)
                {
                    $return .= sprintf('<dd>%s</dd>', $escaper($value));
                }

            }
            elseif(!empty($this->_optionsString[$key]))
            {
                $return .= sprintf('<dt>%s</dt>', $translator->translate($this->_optionsString[$key]));
                $return .= sprintf('<dd>%s</dd>', $escaper($info));
            }
            elseif(!empty($this->_optionsLinks[$key]))
            {
                $return .= sprintf('<dt>%s</dt>', $translator->translate($this->_optionsLinks[$key]));
                $return .= sprintf('<dd><a href="%s">%s</a></dd>', $escaper($info), $translator->translate($key));
            }
        }

        return $return .= '</dl>';
    }
}