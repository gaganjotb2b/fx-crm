<?php

namespace App\Services\MT5;
//+------------------------------------------------------------------+
//|                                             MetaTrader 5 Web API |
//|                   Copyright 2000-2020, MetaQuotes Software Corp. |
//|                                        http://www.metaquotes.net |
//+------------------------------------------------------------------+
class MTCommand
{

  public static $commands = [
    'AccountCreate',
    'AccountUpdate',
    'UserDataGet',
    'AccountGetMargin',
    'AccountChangePassword',
    'AccountChangeInvestorPassword',
    'AccountCheckPassword',
    'AccountCheckInvestorPassword',
    'BalanceUpdate',
    'CreditUpdate',
    'GroupGet',
    'DealGetPage'
  ];
  /**
   * @static getCommands to get all commands
   *
   * @param $command
   *
   * @return object
   */
  public static function getCommands()
  {
    return self::$commands;
  }

  /**
   * @static hasCommand to detect command is aviable
   *
   * @param $obj
   *
   * @return string
   */
  public static function hasCommand($command)
  {
    return in_array($command, self::$commands);
  }
}
