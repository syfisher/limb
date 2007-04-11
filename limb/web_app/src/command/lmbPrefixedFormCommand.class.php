<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id: lmbPrefixedFormCommand.class.php 5628 2007-04-11 12:09:20Z pachanga $
 * @package    web_app
 */
lmb_require('limb/web_app/src/command/lmbFormCommand.class.php');

class lmbPrefixedFormCommand extends lmbFormCommand
{
  function getRequestData()
  {
    return $this->request->getArray($this->form_id);
  }

  function isSubmitted()
  {
    return !is_null($this->request->getPost($this->form_id));
  }

  protected function _validate()
  {
    $this->validator->setErrorList($this->error_list);
    $this->validator->validate(new lmbSet($this->getRequestData()));
  }
}
?>
