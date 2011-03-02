<?php
/**
 * MultiGrid
 * @category 	snippet
 * @version 	1.0
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @author		Jako (thomas.jakobi@partout.info)
 *
 * @internal    @snippet code: return include(MODX_BASE_PATH.'assets/plugins/multigrid/MultiGrid.snippet.php');
 */

if (MODX_BASE_PATH == '') {
    die('<h1>ERROR:</h1><p>Please use do not access this file directly.</p>');
}

$tvName = isset($tvName) ? $tvName : '';
$columnNames = isset($columnNames) ? $columnNames : 'key,value';
$outerTpl = isset($outerTpl) ? $outerTpl : '@CODE:<select name="'.$tvName.'">[+wrapper+]</select>';
$rowTpl = isset($rowTpl) ? $rowTpl : '@CODE:<option value="[+value+]">[+key+]</option>';

$columns = explode(',', $columnNames);
$columnCount = count($columns);
$tvOutput = $modx->getTemplateVarOutput(array($tvName));
$tvOutput = $tvOutput[$tvName];
$tvOutput = json_decode($tvOutput);

if (!count($tvOutput))
    return;

if (!class_exists('gridChunkie')) {
    include (MODX_BASE_PATH.'assets/plugins/multigrid/includes/chunkie.class.inc.php');
}

$wrapper = '';
foreach ($tvOutput as $value) {
    $parser = new gridChunkie($rowTpl);
	for ($i = 0; $i < $columnCount; $i++) {
    $parser->AddVar($columns[$i], $value[$i]);
	}
    $wrapper .= $parser->Render();
}

$parser = new gridChunkie($outerTpl);
$parser->AddVar('wrapper', $wrapper);
$output = $parser->Render();

return $output.'test';
?>
