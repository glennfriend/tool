<?php

$replaces[] = [
    'pattern' => '/hello/',
    'text'    => 'hello and Hello',
    'desc'    => '基本的替換',
];
$replaces[] = [
    'pattern' => '/hello/i',
    'text'    => 'hello and Hello',
    'desc'    => ' /i 的替換 不分大小寫',
];
$replaces[] = [
    'pattern' => '/\bhi all\b/i',
    'text'    => $match_a_word_boundary = 'hi all , _hi all , _hi all_ , hi all_',
    'desc'    => '如果擔心邊界問題, 可以使用 \\b 來防止異外的替換',
];
$replaces[] = [
    'pattern' => '/\bhi\b/i',
    'text'    => 'hi hhii high hi_light hi-light',
    'desc'    => '同上',
];
$replaces[] = [
    'pattern' => '/\bba[a-z]*/i',
    'text'    => 'ba , banana , hiba',
    'desc'    => '只要開頭有符合的, 都進行替換',
];


//
$show = '';
$tag = '<span>●</span>';
foreach ($replaces as $replace) {
    $replacement = $replace['replacement'] ?? $tag;
    $description = $replace['desc'] ?? '';
    $result = preg_replace($replace['pattern'], $replacement, $replace['text']);


    $show .= <<<"EOD"
    <tr>
        <td>{$replace['pattern']}</td>
        <td>{$replace['text']}</td>
        <td>{$result}</td>
        <td>{$description}</td>
    </tr>
EOD;
}

include 'header.html';
echo <<<"EOD"
<table>
    <tr>
        <th>Partten</th>
        <th>Replacement</th>
        <th>Text</th>
        <th>Description</th>
    </tr>
{$show}
</table>
EOD;
