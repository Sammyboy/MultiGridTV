<?php
/**
 * MultiGrid
 *
 * @category 	plugin
 * @version 	1.1.4
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @author		Jako (thomas.jakobi@partout.info)
 * based on a lot of code of Temus (temus3@gmail.com)
 * modified by sam (sam@gmx-topmail.de)
 *
 * @internal    @plugin code: include(MODX_BASE_PATH.'assets/plugins/multigrid/MultiGrid.plugin.php');
 * @internal	@properties: &tvids=TV IDs:;text; &tpl=Template IDs:;text; &roles=Roles:;text; &columnNames=Column Names:;text;key,value
 * @internal	@events: OnDocFormRender
 */
 
if (IN_MANAGER_MODE != 'true') {
    die('<h1>ERROR:</h1><p>Please use the MODx Content Manager instead of accessing this file directly.</p>');
}
global $content,$default_template;

if (!isset($pluginPath)) $pluginPath = 'assets/plugins/multigrid/';

if (!strlen($tvids)) include MODX_BASE_PATH.$pluginPath."MultiGrid.config.php";
else {
    $MGPC           = array();
    $tvids          = explode("||", $tvids);
    $tpl            = explode("||", $tpl);
    $roles          = explode("||", $roles);
    $columnNames    = explode("||", $columnNames);

    foreach ($tvids as $i => $tvid) {
        $MGPC[$i]['tv_id']          = intval(trim($tvid));
        $MGPC[$i]['tpl_ids']        = isset($tpl[$i]) ? str_replace(" ", "", $tpl[$i]) : false;
        $MGPC[$i]['roles']          = isset($roles[$i]) ? str_replace(" ", "", $roles[$i]) : false;
        $MGPC[$i]['columnNames']    = isset($columnNames[$i]) ? str_replace(", ", ",", $columnNames[$i]) : "key,value";
    }
}

if (!class_exists('gridChunkie')) {
    include (MODX_BASE_PATH.$pluginPath.'includes/chunkie.class.inc.php');
}
if (!class_exists('TransAlias')) {
    include (MODX_BASE_PATH.'assets/plugins/transalias/transalias.class.php');
}

$curTpl = isset($_POST['template']) ? $_POST['template'] : isset($content['template']) ? $content['template'] : $default_template;
$curRole = $_SESSION['mgrRole'];
$tvids = $columnNames = array();

foreach ($MGPC as $opt_num => $option) {
    $tvids[$opt_num]    = "'tv".$option['tv_id']."'";
    $roles              = $option['roles'] ? explode(",",$option['roles']) : false;
    $tpl_ids            = $option['tpl_ids'] ? explode(",", $option['tpl_ids']) : false;
    $columns            = explode(',', $option['columnNames']);
    $columnCount        = count($columns);

    if (($tpl_ids && !in_array($curTpl, $tpl_ids)) || ($roles && !in_array($curRole, $roles))) return;

    foreach ($columns as $i => $column) {
	    $column = trim($column);
	    $trans = new TransAlias($modx); 
        $columns[$i] = $trans->stripAlias($column, 'lowercase alphanumeric', 'underscore');
    }
    $columnNames[$opt_num] = "new Array('".implode("','", $columns)."')";
}
$tvids = 'new Array('.implode(',', $tvids).')';
$columnNames = 'new Array('.implode(',', $columnNames).')';

$script = '<style type="text/css">'."\r\n";
$parser = new gridChunkie('@FILE:'.$pluginPath.'MultiGrid.template.css');
$script .= $parser->Render();
$script .= '</style>'."\r\n";

$script .= '<script type="text/javascript">'."\r\n";
$parser = new gridChunkie('@FILE:'.$pluginPath.'MultiGrid.template.js');
$parser->AddVar('tvids', $tvids);
$parser->AddVar('columnNames', $columnNames);

$script .= $parser->Render();
$script .= '</script>'."\r\n";

$e = &$modx->Event;
if ($e->name == 'OnDocFormRender') {
    $output = $script;
    $e->output($output);
}
?>
