<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id: lmbInvalidValueRuleTest.class.php 5628 2007-04-11 12:09:20Z pachanga $
 * @package    validation
 */
require_once(dirname(__FILE__) . '/lmbValidationRuleTestCase.class.php');
lmb_require('limb/validation/src/rule/lmbInvalidValueRule.class.php');

class lmbInvalidValueRuleTest extends lmbValidationRuleTestCase
{
  function testInvalidValueRuleOkInt()
  {
    $rule = new lmbInvalidValueRule('testfield', 0);

    $data = new lmbSet();
    $data->set('testfield', 1);

    $this->error_list->expectNever('addError');

    $rule->validate($data, $this->error_list);
  }

  function testInvalidValueRuleOkInt2()
  {
    $rule = new lmbInvalidValueRule('testfield', 0);

    $data = new lmbSet();
    $data->set('testfield', 'whatever');

    $this->error_list->expectNever('addError');

    $rule->validate($data, $this->error_list);
  }

  function testInvalidValueRuleOkNull()
  {
    $rule = new lmbInvalidValueRule('testfield', null);

    $data = new lmbSet();
    $data->set('testfield', 'null');

    $this->error_list->expectNever('addError');

    $rule->validate($data, $this->error_list);

  }

  function testInvalidValueRuleOkBool()
  {
    $rule = new lmbInvalidValueRule('testfield', false);

    $data = new lmbSet();
    $data->set('testfield', 'false');

    $this->error_list->expectNever('addError');

    $rule->validate($data, $this->error_list);

  }

  function testInvalidValueRuleError()
  {
    $rule = new lmbInvalidValueRule('testfield', 1);

    $data = new lmbSet();
    $data->set('testfield', 1);

    $this->error_list->expectOnce('addError',
                                  array(lmb_i18n('{Field} value is wrong', 'validation'),
                                        array('Field' => 'testfield'),
                                        array()));

    $rule->validate($data, $this->error_list);
  }

  function testInvalidValueRuleError2()
  {
    $rule = new lmbInvalidValueRule('testfield', 1);

    $data = new lmbSet();
    $data->set('testfield', '1');

    $this->error_list->expectOnce('addError',
                                  array(lmb_i18n('{Field} value is wrong', 'validation'),
                                        array('Field' => 'testfield'),
                                        array()));

    $rule->validate($data, $this->error_list);
  }

}

?>