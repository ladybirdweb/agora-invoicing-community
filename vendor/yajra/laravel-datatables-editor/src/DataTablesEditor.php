<?php

namespace Yajra\DataTables;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class DataTablesEditor
{
    use ValidatesRequests;

    /**
     * Allowed dataTables editor actions.
     *
     * @var array
     */
    protected $actions = ['create', 'edit', 'remove'];

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = null;

    /**
     * Process dataTables editor action request.
     *
     * @param Request $request
     * @return JsonResponse|mixed
     * @throws DataTablesEditorException
     */
    public function process(Request $request)
    {
        $action = $request->get('action');

        if (! in_array($action, $this->actions)) {
            throw new DataTablesEditorException('Requested action not supported!');
        }

        return $this->{$action}($request);
    }

    /**
     * Process create action request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $instance   = $this->resolveModel();
        $connection = $instance->getConnection();
        $affected   = [];
        $errors     = [];

        $connection->beginTransaction();
        foreach ($request->get('data') as $data) {
            $validator = $this->getValidationFactory()->make($data, $this->createRules(), $this->createMessages(), $this->attributes());
            if ($validator->fails()) {
                foreach ($this->formatErrors($validator) as $error) {
                    $errors[] = $error;
                }

                continue;
            }

            if (method_exists($this, 'creating')) {
                $data = $this->creating($instance, $data);
            }

            if (method_exists($this, 'saving')) {
                $data = $this->saving($instance, $data);
            }

            $model = $instance->newQuery()->create($data);
            $model->setAttribute('DT_RowId', $model->getKey());

            if (method_exists($this, 'created')) {
                $this->created($model, $data);
            }

            if (method_exists($this, 'saved')) {
                $this->saved($model, $data);
            }

            $affected[] = $model;
        }

        if (! $errors) {
            $connection->commit();
        } else {
            $connection->rollBack();
        }

        return $this->toJson($affected, $errors);
    }

    /**
     * Resolve model to used.
     *
     * @return Model
     */
    protected function resolveModel()
    {
        if ($this->model instanceof Model) {
            return $this->model;
        }

        return new $this->model;
    }

    /**
     * Get create action validation rules.
     *
     * @return array
     */
    abstract public function createRules();

    /**
     * Get create validation messages.
     *
     * @return array
     */
    protected function createMessages()
    {
        return [];
    }

    /**
     * @param Validator $validator
     * @return array
     */
    protected function formatErrors(Validator $validator)
    {
        $errors = [];

        collect($validator->errors())->each(function ($error, $key) use (&$errors) {
            $errors[] = [
                'name'   => $key,
                'status' => $error[0],
            ];
        });

        return $errors;
    }

    /**
     * Display success data in dataTables editor format.
     *
     * @param array $data
     * @param array $errors
     * @return JsonResponse
     */
    protected function toJson(array $data, array $errors = [])
    {
        $response = ['data' => $data];
        if ($errors) {
            $response['fieldErrors'] = $errors;
        }

        return new JsonResponse($response, 200);
    }

    /**
     * Process edit action request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Request $request)
    {
        $instance   = $this->resolveModel();
        $connection = $instance->getConnection();
        $affected   = [];
        $errors     = [];

        $connection->beginTransaction();
        foreach ($request->get('data') as $key => $data) {
            $model     = $instance->newQuery()->find($key);
            $validator = $this->getValidationFactory()->make($data, $this->editRules($model), $this->editMessages(), $this->attributes());
            if ($validator->fails()) {
                foreach ($this->formatErrors($validator) as $error) {
                    $errors[] = $error;
                }

                continue;
            }

            if (method_exists($this, 'updating')) {
                $data = $this->updating($model, $data);
            }

            if (method_exists($this, 'saving')) {
                $data = $this->saving($model, $data);
            }

            $model->update($data);

            if (method_exists($this, 'updated')) {
                $this->updated($model, $data);
            }

            if (method_exists($this, 'saved')) {
                $this->saved($model, $data);
            }

            $model->setAttribute('DT_RowId', $model->getKey());
            $affected[] = $model;
        }

        if (! $errors) {
            $connection->commit();
        } else {
            $connection->rollBack();
        }

        return $this->toJson($affected, $errors);
    }

    /**
     * Get edit action validation rules.
     *
     * @param Model $model
     * @return array
     */
    abstract public function editRules(Model $model);

    /**
     * Get edit validation messages.
     *
     * @return array
     */
    protected function editMessages()
    {
        return [];
    }

    /**
     * Process remove action request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function remove(Request $request)
    {
        $instance   = $this->resolveModel();
        $connection = $instance->getConnection();
        $affected   = [];
        $errors     = [];

        $connection->beginTransaction();
        foreach ($request->get('data') as $key => $data) {
            $model     = $instance->newQuery()->find($key);
            $validator = $this->getValidationFactory()
                              ->make($data, $this->removeRules($model), $this->removeMessages(), $this->attributes());
            if ($validator->fails()) {
                foreach ($this->formatErrors($validator) as $error) {
                    $errors[] = $error['status'];
                }

                continue;
            }

            try {
                if (method_exists($this, 'deleting')) {
                    $this->deleting($model, $data);
                }

                $model->delete();

                if (method_exists($this, 'deleted')) {
                    $this->deleted($model, $data);
                }
            } catch (QueryException $exception) {
                $error    = config('app.debug') ? $exception->errorInfo[2] : $this->removeExceptionMessage($exception, $model);
                $errors[] = $error;
            }

            $affected[] = $model;
        }

        if (! $errors) {
            $connection->commit();
        } else {
            $connection->rollBack();
        }

        $response = ['data' => $affected];
        if ($errors) {
            $response['error'] = implode("\n", $errors);
        }

        return new JsonResponse($response, 200);
    }

    /**
     * Get remove action validation rules.
     *
     * @param Model $model
     * @return array
     */
    abstract public function removeRules(Model $model);

    /**
     * Get remove validation messages.
     *
     * @return array
     */
    protected function removeMessages()
    {
        return [];
    }

    /**
     * Get remove query exception message.
     *
     * @param QueryException $exception
     * @param Model          $model
     * @return string
     */
    protected function removeExceptionMessage(QueryException $exception, Model $model)
    {
        return "Record {$model->getKey()} is protected and cannot be deleted!";
    }

    /**
     * Display dataTables editor validation errors.
     *
     * @param Validator $validator
     * @return JsonResponse
     */
    protected function displayValidationErrors(Validator $validator)
    {
        $errors = $this->formatErrors($validator);

        return new JsonResponse([
            'data'        => [],
            'fieldErrors' => $errors,
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }
}
