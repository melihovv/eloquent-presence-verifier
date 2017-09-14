<?php

namespace Melihovv\EloquentPresenceVerifier;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\DatabasePresenceVerifier;

class EloquentPresenceVerifier extends DatabasePresenceVerifier
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * EloquentPresenceVerifier constructor.
     * @param Model $model
     * @param ConnectionResolverInterface $db
     */
    public function __construct(Model $model, ConnectionResolverInterface $db)
    {
        parent::__construct($db);

        $this->model = $model;
    }

    /**
     * Count the number of objects in a collection having the given value.
     *
     * @param  string $collection
     * @param  string $column
     * @param  string $value
     * @param  int $excludeId
     * @param  string $idColumn
     * @param  array $extra
     * @return int
     */
    public function getCount($collection, $column, $value, $excludeId = null, $idColumn = null, array $extra = [])
    {
        $query = $this->setupModel($collection)->where($column, '=', $value);

        if (! is_null($excludeId) && $excludeId !== 'NULL') {
            $query->where($idColumn ?: 'id', '<>', $excludeId);
        }

        return $this->addConditions($query, $extra)->count();
    }

    /**
     * Count the number of objects in a collection with the given values.
     *
     * @param  string $collection
     * @param  string $column
     * @param  array $values
     * @param  array $extra
     * @return int
     */
    public function getMultiCount($collection, $column, array $values, array $extra = [])
    {
        $query = $this->setupModel($collection)->whereIn($column, $values);

        return $this->addConditions($query, $extra)->count();
    }

    /**
     * @param $collection
     * @return Model
     */
    private function setupModel($collection)
    {
        return $this->model
            ->setConnection($this->connection)
            ->setTable($collection)
            ->useWritePdo();
    }
}
