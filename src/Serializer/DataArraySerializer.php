<?php

namespace JoBins\LaravelRepository\Serializer;

use League\Fractal\Serializer\ArraySerializer;

/**
 * Class DataArraySerializer
 *
 * @package JoBins\LaravelRepository\Serializer
 */
class DataArraySerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        if ( $resourceKey ) {
            return [$resourceKey => $data];
        }

        return $data;
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return $data;
    }

    /**
     * Serialize null resource.
     *
     * @return array
     */
    public function null()
    {
        return [];
    }
}
