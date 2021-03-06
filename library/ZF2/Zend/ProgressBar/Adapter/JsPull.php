<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_ProgressBar
 */

namespace Zend\ProgressBar\Adapter;

use Zend\Json\Json;

/**
 * Zend_ProgressBar_Adapter_JsPull offers a simple method for updating a
 * progressbar in a browser.
 *
 * @category  Zend
 * @package   Zend_ProgressBar
 */
class JsPull extends AbstractAdapter
{
    /**
     * Wether to exit after json data send or not
     *
     * @var boolean
     */
    protected $exitAfterSend = true;

    /**
     * Set wether to exit after json data send or not
     *
     * @param  boolean $exitAfterSend
     * @return \Zend\ProgressBar\Adapter\JsPull
     */
    public function setExitAfterSend($exitAfterSend)
    {
        $this->exitAfterSend = $exitAfterSend;
    }

    /**
     * Defined by Zend\ProgressBar\Adapter\AbstractAdapter
     *
     * @param  float   $current       Current progress value
     * @param  float   $max           Max progress value
     * @param  float   $percent       Current percent value
     * @param  integer $timeTaken     Taken time in seconds
     * @param  integer $timeRemaining Remaining time in seconds
     * @param  string  $text          Status text
     * @return void
     */
    public function notify($current, $max, $percent, $timeTaken, $timeRemaining, $text)
    {
        $arguments = array(
            'current'       => $current,
            'max'           => $max,
            'percent'       => ($percent * 100),
            'timeTaken'     => $timeTaken,
            'timeRemaining' => $timeRemaining,
            'text'          => $text,
            'finished'      => false
        );

        $data = Json::encode($arguments);

        // Output the data
        $this->_outputData($data);
    }

    /**
     * Defined by Zend\ProgressBar\Adapter\AbstractAdapter
     *
     * @return void
     */
    public function finish()
    {
        $data = Json::encode(array('finished' => true));

        $this->_outputData($data);
    }

    /**
     * Outputs given data the user agent.
     *
     * This split-off is required for unit-testing.
     *
     * @param  string $data
     * @return void
     */
    protected function _outputData($data)
    {
        echo $data;

        if ($this->exitAfterSend) {
            exit;
        }
    }
}
