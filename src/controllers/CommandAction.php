<?php
/**
 * HiAPI Yii2 base project for building API
 *
 * @link      https://github.com/hiqdev/hiapi
 * @package   hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\controllers;

use hiqdev\yii\compat\yii;

class CommandAction extends \yii\base\Action
{
    protected $_command;

    public function run()
    {
        return yii::getApp()->get('commandBus')->handle($this->getCommand());
    }

    public function getCommand()
    {
        if (!is_object($this->_command)) {
            $this->_command = yii::createObject($this->_command, [$this->controller->getEntityClass()]);
        }

        return $this->_command;
    }

    public function setCommand($value)
    {
        $this->_command = $value;
    }
}
