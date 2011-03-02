<?php
$tvOutput = $modx->getTemplateVarOutput(array($options));
$tvOutput = trim($tvOutput[$options]);
$condition[] = intval($tvOutput != '[]' && $tvOutput != '');
?>