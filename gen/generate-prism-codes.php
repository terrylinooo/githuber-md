<?php
    $contents = file_get_contents(dirname(__DIR__) . '/assets/vendor/prism/components.json');
    $data = json_decode($contents, true);

    foreach($data['languages'] as $name => $values) {

        $name = str_replace('\'', '\\\'', $name);
        $require = "";

        if (array_key_exists('require', $values)) {
            if (is_array($values['require'])) {
                $require = $values['require'];
            } else {
                $require = array($values['require']);
            }
        }

        if (array_key_exists('title', $values)) {
            $prism_codes[$name] = $values['title'];
            if (!empty($require)) {
                $prism_component_parent[$name] = $require;
            }
        }

        if (array_key_exists('alias', $values)) {
            if (is_array($values['alias'])) {
                foreach($values['alias'] as $aliasValues) {
                    $prism_codes[$aliasValues] = $values['title'];
                    if (!empty($require)) {
                        $prism_component_parent[$aliasValues] = $require;
                    }
                }
            } else {
                $prism_codes[$values['alias']] = $values['title'];
                if (!empty($require)) {
                    $prism_component_parent[$values['alias']] = $require;
                }
            }
        }

        if (array_key_exists('aliasTitles', $values)) {
            foreach($values['aliasTitles'] as $aliasTitlesKey => $aliasTitlesValue) {
                $prism_codes[$aliasTitlesKey] = $aliasTitlesValue;
                if (!empty($require)) {
                    $prism_component_parent[$aliasTitlesKey] = $require;
                }
            }
        }
    }
    
    ksort($prism_codes);
    ksort($prism_component_parent);

    echo "	// This is what Prism.js uses. generated using sh/generate-prism-codes.php\n";
    $result1 = '	public static $prism_codes = array('. "\n";
    foreach ($prism_codes as $key => $value) {
        $result1 = $result1 . "	    '" . $key . "' => '" . $value . "',\n";
    }
    $result1 = $result1 . '	);';
    echo $result1 . "\n";
    echo "	// The below codes need a parent component being loaded before. generated using sh/generate-prism-codes.php\n";
    $result2 = '	public static $prism_component_parent = array('. "\n";

    foreach ($prism_component_parent as $key => $values) {
        $result2 = $result2 . "	    '" . $key . "' => ";
        if (sizeof($values) > 1) {
            $result2 = $result2 . "array(";
            for($i = 0; $i < sizeof($values); $i++) {
                if ($i === sizeof($values) - 1) {
                    $result2 = $result2 . "'" . $values[$i] . "'";
                } else {
                    $result2 = $result2 . "'" . $values[$i] . "',";
                }
            }
            $result2 = $result2 . "),\n";
        } else {
            $result2 = $result2 ."array('" . $values[0] . "'),\n";
        }
    }
    $result2 = $result2 . '	);';
    echo $result2 . "\n";
    echo PHP_EOL;

?>