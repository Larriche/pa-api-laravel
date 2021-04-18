<?php

namespace App\Services;

use Schema;

class QueryBuilder
{
    /**
     * The model to be used for this service.
     *
     * @var \App\Models\Model
     */
    protected $model;

    /**
     * Show the resource with all its relations
     *
     * @var bool
     */
    protected $show_with_relations = false;

    /**
     * Default pagination to use for item listings
     *
     * @var bool
     */
    protected $pagination = 20;

    /**
     * Default ordering
     *
     * @var bool
     */
    protected $ranking = 'DESC';

    /**
     * Specifies service relations
     *
     * @var array
     */
    protected $relations = [];

    /**
     * Name of the field which identifies the name of the model
     *
     * @var string
     */
    protected $name_field = 'name';

    /**
     * List of fields that should be searched when a keyword is supplied
     *
     * @var array
     */
    protected $search_fields = ['name'];

    /**
     * Get a listing of resource based on the request made
     *
     * @param \Illuminate\Http\Request $request The HTTP request
     */
    public function execute($request)
    {
        $data = $request->all();

        $params = $this->getQueryParams($data);

        /* New implementations of show with relations */
        if ($this->show_with_relations) {
            if (isset($data['exempted_relations'])) {
                $exempted = explode(',', $data['exempted_relations']);

                $this->relations = array_diff($this->relations, $exempted);
            } elseif (isset($data['relations'])) {
                $this->relations = explode(',', $data['relations']);
            }

            $query = $this->model()->with($this->relations);
        } else {
            $query = $this->model();
        }

        if (isset($params['where'])) {
            $where = $params['where'];

            foreach ($where as $field => $value) {
                $query = $query->where($field, $value);
            }
        }

        // Search filter
        if (isset($data['keyword']) && $data['keyword']) {
            $query = $this->search($query, $data['keyword']);
        }

        $query = $this->applyFilters($query, $data);

        if (isset($params['order'])) {
            $order = $params['order'];

            foreach ($order as $field => $ranking) {
                if (Schema::hasColumn($this->model()->getTable(), $field)) {
                    $query = $query->orderBy($this->model()->getTable() . '.' . $field, $ranking);
                } else {
                    // For custom fields, we expect a custom order-by function defined for it by the model
                    // The function name should be like sortByFieldName and it will handle requests to order by
                    // field_name
                    $sortFunction = 'sortBy' . join(array_map('ucfirst', explode('_', $field)));

                    if (method_exists($this->model(), $sortFunction)) {
                        $query = $this->model()->$sortFunction($query, $ranking);
                    }
                }
            }
        }

        // Add default ordering fields
        $defaultOrderings = [];

        if ($field = $this->name_field) {
            $defaultOrderings[$field] = 'ASC';
        }

        $defaultOrderings['created_at'] = 'DESC';

        foreach ($defaultOrderings as $field => $rank) {
            if (!isset($params['order'][$field]) && Schema::hasColumn($this->model()->getTable(), $field)) {
                $query = $query->orderBy($this->model()->getTable() . '.' . $field, $rank);
            }
        }

        if (isset($params['view_by'])) {
            if ($params['view_by'] == 'all') {
                $query = $query->withTrashed();
            } elseif ($params['view_by'] == 'deleted') {
                $query = $query->onlyTrashed();
            }
        }

        if (isset($params['paginate'])) {
            $results = $query->paginate($params['paginate']);
        } else {
            $results = $query->get();
        }

        return $results;
    }

    /**
     * Get base params for query purpose
     *
     * @param array $requestData Data from request
     * @return array $params Query params
     */
    protected function getQueryParams($requestData)
    {
        $params = [];
        $fields = Schema::getColumnListing($this->model()->getTable());

        // Set pagination options
        if (isset($requestData['paginate']) && ($requestData['paginate'] === true || $requestData['paginate'] === 'true')) {
            $pagination = (isset($requestData['per_page']) && $requestData['per_page']) ?
                $requestData['per_page'] : $this->pagination;
            $params['paginate'] = $pagination;
        }

        // Set ordering options
        if (isset($requestData['order_field']) && $requestData['order_field']) {
            $orderField = $requestData['order_field'];
            $ranking = (isset($requestData['order_ranking']) && $requestData['order_ranking']) ?
                $requestData['order_ranking'] : $this->ranking;

            $params['order'] = [$orderField => $ranking];
        }

        $params['where'] = [];

        // Set field-value pairs for use in an ANDed query for items
        foreach ($fields as $field) {
            if (isset($requestData[$field]) && $requestData[$field]) {
                $params['where'][$field] = $requestData[$field];
            }
        }

        // Instantiate extra filters option
        $params['filters'] = [];

        if (isset($requestData['view_by']) && $requestData['view_by']) {
            $params['view_by'] = $requestData['view_by'];
        }

        return $params;
    }

    /**
     * Do further querying on the current query object
     * Will be overriden by service classes with more complicated filtering requirements
     *
     * @param \Illuminate\Database\Builder $query
     * @param array $data Data for filtering query
     * @param \Illuminate\Database\Builder
     */
    public function applyFilters($query, $data)
    {
        return $query;
    }

    /**
     * A filter for querying name with a search
     *
     * @param \Illuminate\Database\QueryBuilder $query Current query builder
     * @param $data Data from request
     * @return \Illuminate\Database\QueryBuilder $query Updated query
     */
    public function search($query, $keyword)
    {
        // Truncate contiguous spaces to only a single space for
        // explode to work desirably
        $keyword = preg_replace('/\s+/', ' ', trim($keyword));
        $keywordParts = explode(" ", $keyword);
        $fields = $this->search_fields;

        $query = $query->where(function ($query) use ($fields, $keywordParts) {
            if (count($keywordParts)) {
                foreach ($fields as $field) {
                    $query = $query->orWhere(function ($query) use ($keywordParts, $field) {
                        // $query->where($field, 'LIKE', '%'.$keywordParts[0].'%');

                        foreach ($keywordParts as $part) {
                            $query = $query->where($field, 'LIKE', '%'.$part.'%');
                        }
                    });
                }
            }
        });

        return $query;
    }

    /**
     * Get a new instance of the model used by this service.
     *
     * @return \App\Models\Model|null
     */
    public function model()
    {
        return $this->model ? new $this->model : null;
    }
}
