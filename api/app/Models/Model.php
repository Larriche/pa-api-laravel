<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    /**
     * Fields to be protected from mass assignment
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The list of all relations the model has. We cannot
     * detect this automatically so we need to manually enter them.
     *
     * @var array
     */
    protected $relations = [];

     /**
     * Fields that a keyword search should be carried on
     *
     * @var array
     */
    protected $searchFields = ['name'];

    /**
     * The name field for the table.
     *
     * @var string|null
     */
    public $name_field = 'name';

    /**
     * Returns the id of a resource by comparing its name
     *
     * @param string $value
     * @return int|null
     */
    public static function getResourceId($value)
    {
        try {
            $test = self::where('name', $value)->first();
            return is_null($test) ? null : $test->id;
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Get the list of relations this model has.
     *
     * @return array
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Get the fields of this model that are searchable
     *
     * @return array Fields of this model
     */
    public function getSearchFields()
    {
        return $this->searchFields;
    }

    /**
     * Get the primary key of a model
     *
     * @return string
     */
    public static function getPrimaryKey()
    {
        return (new static)->getKeyName();
    }

    /**
     * Get the table name of a model
     *
     * @return string
     */
    public static function getTableName()
    {
        return (new static)->getTable();
    }
}
