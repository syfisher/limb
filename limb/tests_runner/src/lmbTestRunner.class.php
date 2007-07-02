<?php
/*
 * Limb PHP Framework
 *
 * @link http://limb-project.com
 * @copyright  Copyright &copy; 2004-2007 BIT(http://bit-creative.com)
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 */

/**
 * class lmbTestRunner.
 *
 * @package tests_runner
 * @version $Id$
 */
class lmbTestRunner
{
  protected $reporter;
  protected $coverage;
  protected $coverage_reporter;
  protected $coverage_include;
  protected $coverage_exclude;
  protected $coverage_report_dir;
  protected $start_time = 0;
  protected $end_time = 0;

  function setReporter($reporter)
  {
    $this->reporter = $reporter;
  }

  function useCoverage($coverage_include, $coverage_exclude, $coverage_report_dir)
  {
    if(is_string($coverage_include))
      $this->coverage_include = explode(';', $this->coverage_include);

    if(is_string($coverage_exclude))
      $this->coverage_exclude = explode(';', $this->coverage_exclude);

    $this->coverage_report_dir = $coverage_report_dir;
  }

  function run($root_node, $path='/')
  {
    require_once(dirname(__FILE__) . '/../simpletest.inc.php');

    $this->_startTimer();
    $this->_startCoverage();

    $res = $this->_doRun($root_node, $path);

    $this->_endCoverage();
    $this->_stopTimer();
    return $res;
  }

  protected function _doRun($node, $path)
  {
    if(!$sub_node = $node->findChildByPath($path))
      throw new Exception("Test node '$path' not found!");

    $test = $sub_node->createTestCase();
    return $test->run($this->_getReporter());
  }

  protected function _startTimer()
  {
    $this->start_time = microtime(true);
  }

  protected function _stopTimer()
  {
    $this->end_time = microtime(true);
  }

  function getRunTime()
  {
    return round($this->end_time - $this->start_time, 3);
  }

  protected function _startCoverage()
  {
    if(!$this->coverage_include)
      return;

    @define('__PHPCOVERAGE_HOME', dirname(__FILE__) . '/../lib/spikephpcoverage/src/');
    require_once(__PHPCOVERAGE_HOME . '/CoverageRecorder.php');
    require_once(__PHPCOVERAGE_HOME . '/reporter/HtmlCoverageReporter.php');

    $this->coverage_reporter = new HtmlCoverageReporter("Code Coverage Report", "",
                                                        $this->coverage_report_dir);

    $this->coverage = new CoverageRecorder($this->coverage_include, $this->coverage_exclude, $this->coverage_reporter);
    $this->coverage->startInstrumentation();
  }

  protected function _endCoverage()
  {
    if($this->coverage)
    {
      $this->coverage->stopInstrumentation();
      $this->coverage->generateReport();
      $this->coverage_reporter->printTextSummary();
    }
  }

  protected function _getReporter()
  {
    if(!$this->reporter)
    {
      if($this->_simpleTestDefaultReporterInstalled())
      {
        require_once(dirname(__FILE__) . '/lmbTestShellReporter.class.php');
        SimpleTest :: prefer(new lmbTestShellReporter());
      }
      return clone(SimpleTest :: preferred(array('SimpleReporter', 'SimpleReporterDecorator')));
    }
    else
      return clone($this->reporter);
  }

  protected function _simpleTestDefaultReporterInstalled()
  {
    $reporter = SimpleTest :: preferred(array('SimpleReporter', 'SimpleReporterDecorator'));
    return get_class($reporter) == 'DefaultReporter';
  }
}

?>