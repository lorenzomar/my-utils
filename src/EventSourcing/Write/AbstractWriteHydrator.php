<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventSourcing\Write;

use Zend\Hydrator\NamingStrategy\ArrayMapNamingStrategy;
use Zend\Hydrator\Reflection;
use Zend\Hydrator\Strategy\StrategyInterface;
use ZendHydratorUtilities\Strategy\DateTimeFormatterStrategy;
use ZendHydratorUtilities\Strategy\UuidStrategy;

/**
 * Class AbstractWriteHydrator
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
abstract class AbstractWriteHydrator extends Reflection
{
    /**
     * init.
     *
     * @param array               $namingStrategies
     * @param StrategyInterface[] $strategies key => value where key is the field name and the value its strategy
     *
     * @return static
     */
    public static function init(array $namingStrategies = [], array $strategies = [])
    {
        $s = new static();

        if (!empty($namingStrategies)) {
            $s->setNamingStrategy(new ArrayMapNamingStrategy(array_merge(
                [
                    'createdAt'      => 'created_at',
                    'streamId'       => 'stream_id',
                    'streamCategory' => 'stream_category',
                ],
                $namingStrategies
            )));
        }

        $strategies = array_merge(
            [
                'id'         => new UuidStrategy(),
                'created_at' => new DateTimeFormatterStrategy('Y-m-d H:i:s.u', new \DateTimeZone("UTC")),
            ],
            $strategies
        );

        foreach ($strategies as $field => $strategy) {
            $s->addStrategy($field, $strategy);
        }

        return $s;
    }

    public function extract($object)
    {
        $e            = parent::extract($object);
        $e['payload'] = $this->buildPayload($e);

        return $e;
    }

    /**
     * buildPayload.
     * Questo metodo è usato dagli hydrator dei singoli eventi per costruire il payload da salvare a DB
     *
     * @param array $extractedData
     *
     * @return array
     */
    abstract protected function buildPayload(array $extractedData);
    
    protected function extractFields($source, array $fields, $removeFromSource = true)
    {
        $ret = [];

        foreach ($fields as $field) {
            if(isset($source[$field])) {
                $ret[$field] = $source[$field];
            }

            if($removeFromSource) {
                unset($source[$field]);
            }
        }
    }
}