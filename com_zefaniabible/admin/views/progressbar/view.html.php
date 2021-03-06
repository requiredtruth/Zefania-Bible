<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');


require_once JPATH_COMPONENT.'/helpers/zefaniabible.php';
/**
* HTML View class for the Zefaniabible component
*
* @package	Zefaniabible
* @subpackage	Cpanel
*/
class ZefaniabibleViewProgressbar extends JViewLegacy
{
	/**
	* Execute and display a template script.
	*
	* @access	public
	* @param	string	$tpl	The name of the template file to parse; automatically searches through the template paths.
	*
	* @return	mixed	A string if successful, otherwise a JError object.
	*
	* @since	11.1
	*/
	protected $item;
	protected $form;
	protected $state;
		
	public function display($tpl = null)
	{		
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		if ($this->getLayout() == 'modal')
		{
			$this->form->setFieldAttribute('language', 'readonly', 'true');
		}
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
		$item->id 	= $jinput->get('id', 1, 'INT');
		$this->assignRef('item', 		$item);
		parent::display($tpl);
	}
}
