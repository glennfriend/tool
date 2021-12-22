<?php
declare(strict_types=1);
date_default_timezone_set("Asia/Taipei");

$rows = json_decode(file_get_contents('origin.json'), true);
$mergeRows = json_decode(file_get_contents('values.json'), true);

// [0] -> "FullName+BGC | BrD | AllDev | SEED #0 | 210525" total have 8333 adGroups
foreach ($rows as $index => $row) {
    foreach ($mergeRows as $mergeRow) {
        $rows[$index]['currentValue'] ??= 0;

        //
        if ($row['campaignId'] == $mergeRow['id']) {
            $rows[$index]['currentValue'] = $mergeRow['totalAdGroups'];
        }
    }
}

//
// view
//
echo '--<pre>';
// var_export($rows);

foreach ($rows as $row) {
    echo sprintf(
        "#%-3s - %4s/%4s\n",
        $row['index'], $row['currentValue'], $row['total']
    );
}

