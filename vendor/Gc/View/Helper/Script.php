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
 * @subpackage  View\Helper
 * @author      Pierre Rambaud (GoT) <pierre.rambaud86@gmail.com>
 * @license     GNU/LGPL http://www.gnu.org/licenses/lgpl-3.0.html
 * @link        http://www.got-cms.com
 */

namespace Gc\View\Helper;

use Zend\View\Helper\AbstractHelper,
    Gc\Script\Model as ScriptModel,
    Gc\View\Stream;
/**
 * Retrieve script from identifier
 * @example In view: $this->script('identifier');
 */
class Script extends AbstractHelper
{
    /**
     * Script parameter
     * @var array
     */
    protected $_params = array();

    /**
     * Returns script from identifier.
     *
     * @param string $identifier
     * @param array $params
     * @return mixte
     */
    public function __invoke($identifier, $params = array())
    {
        $existed = in_array('gc.script', stream_get_wrappers());
        if(!$existed)
        {
            stream_wrapper_register('gc.script', 'Gc\View\Stream');
        }

        $script =  ScriptModel::fromIdentifier($identifier);
        if(empty($script))
        {
            return FALSE;
        }

        $this->_params = $params;

        file_put_contents('gc.script://' . $identifier, $script->getContent());

        return (include('gc.script://' . $identifier)) === TRUE ? TRUE : FALSE;
    }

    /**
     * Returns param from name.
     *
     * @param string $name
     * @return mixte
     */
    public function getParam($name)
    {
        if(!empty($this->_params[$name]))
        {
            return $this->_params[$name];
        }

        return NULL;
    }

    /**
     * Get Http Request instance.
     *
     * @return \Zend\Http\PhpEnvironment\Request
     */
    public function getRequest()
    {
        return \Gc\Registry::get('Application')->getRequest();
    }

    /**
     * Get plugin instance
     *
     * @param  string $name Name of plugin to return
     * @param  null|array $options Options to pass to plugin constructor (if not already instantiated)
     * @return mixed
     */
    public function plugin($name, array $options = NULL)
    {
        return \Gc\Registry::get('Application')->getServiceManager()->get('controllerpluginbroker')->get($name, $options);
    }

    /**
     * Method overloading: return/call plugins
     *
     * If the plugin is a functor, call it, passing the parameters provided.
     * Otherwise, return the plugin instance.
     *
     * @param  string $method
     * @param  array  $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        $plugin = $this->plugin($method);
        if (is_callable($plugin))
        {
            return call_user_func_array($plugin, $params);
        }

        return $plugin;
    }
}
