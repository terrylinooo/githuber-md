<?php
/**
 * Update theme from CodeMirror official site.
 * 
 * @author Terry Lin
 * @packeage WP Githuber MD
 * @version 1.0
 */

exit;

$content = file_get_contents('https://codemirror.net/theme/');

$dom = new DOMDocument();
$dom->loadHTML($content);

$hrefs = $dom->getElementsByTagName('a');

$array = [];

foreach ($hrefs as $a) {
    foreach ($a->attributes as $attr) {
        $name = $attr->nodeName;
        $value = $attr->nodeValue;

        if (strpos($value, '.css') !== false) {
            $array[$value] = str_replace('.css', '', $value);
            $f = file_get_contents('https://codemirror.net/theme/' . $value);
            file_put_contents($value, $f);
            unset($f);
        }
    }
}

foreach ($array as $k => $v) {
    echo "'" . $v . "' => '" . ucwords(str_replace('-', ' ', $v)) . "',\n";
}