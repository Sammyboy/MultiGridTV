<?php defined('IN_MANAGER_MODE') or die('<h1>ERROR:</h1><p>Please use the MODx Content Manager instead of accessing this file directly.</p>');

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//
//  Configuration file for the
//  MultiGridTV for MODx Evolution
//
//  ( = ̱Multi̱Grid ̱Plugin ̱Configuration)
//
//  @version    0.1.0
//  @license    http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
//  @author     sam (sam@gmx-topmail.de)
//
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


/*
// Example:
//==============================================================================
// treeTV
//==============================================================================
$c = count($MGPC);

$MGPC[$c]['input']['tv_id']            = 14;                          // ID of template variable to be used with the CSS ID is then treeBox_tv29
$MGPC[$c]['input']['tpl_ids']          = "5";                           // ID of template used
$MGPC[$c]['input']['role']             = false;                         // 1 = Administrator, 2 = Editor, 3 = Publisher
$MGPC[$c]['input']['columnNames']      = "column1,column2,column3";     // Names of the columns

*/

//==============================================================================
// MultiGridTV
//==============================================================================
$c = count($MGPC);

$MGPC[$c]['tv_id']             = 32;
$MGPC[$c]['tpl_ids']           = "5";
$MGPC[$c]['roles']             = false;
$MGPC[$c]['columnNames']       = "SpalteA,SpalteB";

//==============================================================================
// anotherGridTV
//==============================================================================
$c = count($MGPC);

$MGPC[$c]['tv_id']             = 14;
$MGPC[$c]['tpl_ids']           = "5";
$MGPC[$c]['roles']             = false;
$MGPC[$c]['columnNames']       = "Spalte 1,Spalte 2,Spalte 3,Spalte 4";

?>
