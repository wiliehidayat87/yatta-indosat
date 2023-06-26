<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class my_jqgrid
{
    public $columnName;

    public function __construct() {
        $this->columnName = array();
    }

    public function buildColumnModel($columnName, $globalParameters, $customParameters, $thousandSeparatorColumn = array()) {
        $columnModel = array();

        for ($i = 0; $i < count($columnName); $i++) {
            $this->columnName[] = $currentColumnName = strtolower(str_replace(' ', '_', $columnName[$i]));

            $default = array(
                'index' => $currentColumnName,
                'name' => $currentColumnName,
            );

            if (false !== in_array($columnName[$i], $thousandSeparatorColumn)) {
                $default = array_merge($default, array(
                    'formatter' => 'integer',
                    'formatoptions' => array(
                        'thousandsSeparator' => '.',
                        'defaultValue' => '0'
                    )
                ));
            }

            if (false !== array_key_exists($columnName[$i], $customParameters)) {
                $default = array_merge($default, $customParameters[$columnName[$i]]);
            }

            $currentGlobalParameters = $globalParameters;
            if (false !== array_key_exists('width', $default)) {
                unset($currentGlobalParameters['width']);
            }

            $columnModel[] = array_merge($default, $currentGlobalParameters);
        }

        return $columnModel;
    }

    public function buildColumnData($mandatoryData, $optionalData) {
        $columnData = array();

        for ($i = 0; $i < count($mandatoryData); $i++) {
            $index = $this->columnName;

            if (true !== empty($optionalData)) {
                for ($j = 0; $j < count($mandatoryData[$i]); $j++) {
                    $data = array_merge($mandatoryData[$i][$j], $optionalData[$i][$j]);
                    $columnData[] = array_combine($index, $data);
                }
            }
            else {
                $columnData[] = array_combine($index, $mandatoryData[$i]);
            }
        }

        return $columnData;
    }
}

