<?php
/**
 * MultiGrid
 *
 * @category 	plugin
 * @version 	1.1
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @author		Jako (thomas.jakobi@partout.info)
 * based on a lot of code of Temus (temus3@gmail.com)
 *
 * @internal    @plugin code: include(MODX_BASE_PATH.'assets/plugins/multigrid/MultiGrid.plugin.php');
 * @internal	@properties: &tvids=TV IDs;text; &tpl=Template;text; &role=Role;text; &columnNames=Column Names;text;
 * @internal	@events: OnDocFormRender
 */
 
if (IN_MANAGER_MODE != 'true') {
    die('<h1>ERROR:</h1><p>Please use the MODx Content Manager instead of accessing this file directly.</p>');
}
global $content,$default_template;

$tvids = isset($tvids) ? explode(',', $tvids) : array('1');
$tpl = isset($tpl) ? explode(',', $tpl) : false;
$role = isset($role) ? explode(',', $role) : false;
$columnNames = isset($columnNames) ? $columnNames : 'key,value';

if (!class_exists('gridChunkie')) {
    include (MODX_BASE_PATH.'assets/plugins/multigrid/includes/chunkie.class.inc.php');
}

$columns = explode(',', $columnNames);
$columnCount = count($columns);
$curTpl = isset($_POST['template']) ? $_POST['template'] : isset($content['template']) ? $content['template'] : $default_template;
$curRole = $_SESSION['mgrRole'];
$tvids = "['tv".implode("', 'tv", $tvids)."']";

if (($tpl && !in_array($curTpl, $tpl)) || ($role && !in_array($curRole, $role))) {
    return;
}

$headRow = $bodyRow = $elements = $values = array();
$i = 0;
foreach ($columns as $column) {
    $headRow[] = "this.th('".$column."', '".((!$i) ? 'first' : '')."')";
    $bodyRow[] = "this.td(grid".$column.", '".((!$i) ? 'first' : '')."')";
    $elements[] = "        var grid".$column." = new Element('input', {
            'type': 'text',
            'class': 'gridVal',
            'value': values[".$i."],
            'events': {
                'keyup': function(){
                    this.setEditor();
                    documentDirty = true;
                }.bind(this)
            }
        });";
    $values[] = "''";
    $i++;
}

$headRow = implode(', ', $headRow);
$bodyRow = implode(', ', $bodyRow);
$elements = implode("\r\n", $elements);
$values = implode(", ", $values);


$script = '<style type="text/css">'."\r\n";
$parser = new gridChunkie('@FILE:assets/plugins/multigrid/MultiGrid.template.css');
$script .= $parser->Render();
$script .= '</style>'."\r\n";

$script .= '<script type="text/javascript">'."\r\n";
$parser = new gridChunkie('@FILE:assets/plugins/multigrid/MultiGrid.template.js');
$parser->AddVar('tvids', $tvids);
$parser->AddVar('headRow', $headRow);
$parser->AddVar('bodyRow', $bodyRow);
$parser->AddVar('elements', $elements);
$parser->AddVar('values', $values);
$script .= $parser->Render();
$script .= '</script>'."\r\n";

$e = &$modx->Event;
if ($e->name == 'OnDocFormRender') {
    $output = $script;
    $e->output($output);
}
?>
