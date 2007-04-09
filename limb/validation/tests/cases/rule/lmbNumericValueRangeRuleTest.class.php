<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id: lmbNumericValueRangeRuleTest.class.php 5584 2007-04-09 10:43:58Z serega $
 * @package    validation
 */
require_once(dirname(__FILE__) . '/lmbValidationRuleTestCase.class.php');
lmb_require('limb/validation/src/rule/lmbNumericValueRangeRule.class.php');

class lmbNumericValueRangeRuleTest extends lmbValidationRuleTestCase
{
  function testInRange()
  {
    $rule = new lmbNumericValueRangeRule('testfield', 0, 5);

    $dataspace = new lmbDataspace();
    $dataspace->set('testfield', 1);

    $this->error_list->expectNever('addError');

    $rule->validate($dataspace, $this->error_list);
  }

  function testLessThanMin()
  {
    $rule = new lmbNumericValueRangeRule('testfield', 1, 5);

    $dataspace = new lmbDataspace();
    $dataspace->set('testfield', -10);

    $this->error_list->expectOnce('addError',
                                  array(lmb_i18n('{Field} must be not less than {value}.', 'validation'),
                                        array('Field' => 'testfield'),
                                        array('value' => 1)));

    $rule->validate($dataspace, $this->error_list);
  }

  function testGreaterThanMax()
  {
    $rule = new lmbNumericValueRangeRule('testfield', 1, 5);

    $dataspace = new lmbDataspace();
    $dataspace->set('testfield', 10);

    $this->error_list->expectOnce('addError',
                                  array(lmb_i18n('{Field} must be not greater than {value}.', 'validation'),
                                        array('Field' => 'testfield'),
                                        array('value' => 5)));

    $rule->validate($dataspace, $this->error_list);
  }

  function testOnlyDigitsAllowedNumeric()
  {
    $rule = new lmbNumericValueRangeRule('testfield', 1, 4);

    $dataspace = new lmbDataspace();
    $dataspace->set('testfield', '4fdfasd');

    $this->error_list->expectOnce('addError',
                                  array(lmb_i18n('{Field} must be a valid number.', 'validation'),
                                        array('Field'=>'testfield'),
                                        array()));

    $rule->validate($dataspace, $this->error_list);
  }

  function testLessThanMinWithCustomError()
  {
    $rule = new lmbNumericValueRangeRule('testfield', 1, 5, 'Custom_Error');

    $dataspace = new lmbDataspace();
    $dataspace->set('testfield', -10);

    $this->error_list->expectOnce('addError',
                                  array('Custom_Error',
                                        array('Field' => 'testfield'),
                                        array('value' => 1)));

    $rule->validate($dataspace, $this->error_list);
  }
}
?>