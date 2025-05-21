<?php

use App\Constants\ExceptionMessages;
use App\Constants\Resources;

if (!function_exists('findByIdOrFail')) {
    /**
     * Find a model instance by ID and return it or throw a not found exception
     *
     * @param string $model
     * @param int $modelId
     * @param string $resource
     * @param string $type
     * @param array $where
     * @param array $with
     * @param bool $withTrashed
     * @param array|null $selectedColumns
     * @param bool $asQuery
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
     */
    function findByIdOrFail($model, $modelId, $resource = Resources::ITEM, $type = 'male', $where = [], $with = [], $withTrashed = false, $selectedColumns = null, $asQuery = false)
    {
        $modelInstance = null;
        $query = $withTrashed ? $model::withTrashed() : $model::query();

        if (isset($selectedColumns)) {
            $query->select($selectedColumns);
        }

        if (!empty($where)) {
            $query->where($where);
        }

        if (!empty($with)) {
            $query->with($with);
        }

        if (!empty($queries)) {
            $query->$queries;
        }

        $modelInstance = $query->find($modelId);

        if (!$modelInstance) {
            $notFoundMessage = '';
            if ($type == 'female') {
                $notFoundMessage = ExceptionMessages::MSG_RESOURCE_NOT_FOUNDF;
            } else {
                $notFoundMessage = ExceptionMessages::MSG_RESOURCE_NOT_FOUND;
            }
            notFoundFailure(null, __($notFoundMessage, ['resource' => __($resource)]));
        }
        if ($asQuery)
            return $query->where('id', $modelId);
        return $modelInstance;
    }
}

if (!function_exists('generateUniqueResourceNumber')) {

    function generateUniqueResourceNumber($model, $resourceAttribute, $prefix)
    {
        $lastResource = $model::orderBy('id', 'desc')->first();
        if ($lastResource) {
            $numberPart = substr($lastResource->{$resourceAttribute}, 2); // Extracting the numeric part
            $numberPartLength = strlen($numberPart);

            $nextNumber = (int) $numberPart + 1;
            $nextNumberLength = strlen($nextNumber);

            if ($nextNumberLength > $numberPartLength) {
                $nextNumber = 1;
                $numberPartLength++;
            }

            $nextNumber = str_pad($nextNumber, $numberPartLength, '0', STR_PAD_LEFT); // Incrementing and padding

            return $prefix . '_' . $nextNumber;
        } else {
            return $prefix . '_001'; // If there are no employees yet, start from 001
        }
    }
}

if (!function_exists('getModelInstancesDependingOnIds')) {
    function getModelInstancesDependingOnIds($model, $modelIds)
    {
        $modelInstances = collect();
        foreach ($modelIds as $modelId) {
            $modelInstance = $model::find($modelId);
            $modelInstances->push($modelInstance);
        }

        return $modelInstances;
    }
}