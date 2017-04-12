<?php

/**
 * This file is part of the MyUtils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace MyUtils;

/**
 * Class RepositoryUtils.
 *
 * @package MyUtils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class RepositoryUtils
{
    protected function optimizeUuid1Fields(array $data, array $fields, $setOriginals = false)
    {
        $isIterative = MyUtils::arr()->isAssoc($data) ? false : true;
        $data        = $isIterative ? $data : [$data];

        foreach ($data as $dataI => $datum) {
            foreach ($fields as $k => $fieldToOptimize) {
                $fieldName  = is_numeric($k) ? $fieldToOptimize : $k;
                $isMultiple = is_string($k) && (bool)$fieldToOptimize;

                if (!isset($datum[$fieldName])) {
                    continue;
                }

                $fieldValues       = $isMultiple ? $datum[$fieldName] : [$datum[$fieldName]];
                $datum[$fieldName] = [];

                foreach ($fieldValues as $i => $uuid1) {
                    $datum["original_$fieldName"][$i] = $uuid1;
                    $datum[$fieldName][$i]            = MyUtils::uuid()->mysqlOptimizeUuid1($uuid1);
                }

                $datum[$fieldName]            = $isMultiple ? $datum[$fieldName] : array_shift($datum[$fieldName]);
                $datum["original_$fieldName"] = $isMultiple ? $datum["original_$fieldName"] : array_shift($datum["original_$fieldName"]);

                if (!$setOriginals) {
                    unset($datum["original_$fieldName"]);
                }
            }

            $data[$dataI] = $datum;
        }

        return $isIterative ? $data : array_shift($data);
    }

    protected function unoptimizeUuid1Fields(array $data, array $fields)
    {
        $isIterative = MyUtils::arr()->isAssoc($data) ? false : true;
        $data        = $isIterative ? $data : [$data];

        foreach ($data as $dataI => $datum) {
            foreach ($fields as $k => $fieldToOptimize) {
                $fieldName  = is_numeric($k) ? $fieldToOptimize : $k;
                $isMultiple = is_string($k) && (bool)$fieldToOptimize;

                if (!isset($datum[$fieldName])) {
                    continue;
                }

                $fieldValues       = $isMultiple ? $datum[$fieldName] : [$datum[$fieldName]];
                $datum[$fieldName] = [];

                foreach ($fieldValues as $i => $uuid1) {
                    $datum[$fieldName][$i] = MyUtils::uuid()->mysqlUnoptimizeUuid1($uuid1);
                }

                $datum[$fieldName] = $isMultiple ? $datum[$fieldName] : array_shift($datum[$fieldName]);
            }

            $data[$dataI] = $datum;
        }

        return $isIterative ? $data : array_shift($data);
    }
}