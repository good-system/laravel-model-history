<?php

namespace GoodSystem\ModelHistory;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ModelHistory extends Model
{
    protected $table = 'model_history';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function forModelCreated($user, Model $model)
    {
        $data = $model->getAttributes();

        foreach ($data as $field => $newValue) {
            $data[$field] = ['newValue' => $newValue, 'oldValue' => null];
        }

        return self::getInstance($user, 'Create', get_class($model), $model->getKey(), $data);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $user
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function forFieldChanges($user, Model $model)
    {
        $data = $model->getChanges();
        $originals = $model->getOriginal();

        foreach ($data as $field => $newValue) {
            $data[$field] = ['newValue' => null, 'oldValue' => null];
            if (isset($originals, $field)) {
                $data[$field] = ['newValue' => $newValue, 'oldValue' => array_get($originals, $field)];
            }
        }

        return self::getInstance($user, 'Update', get_class($model), $model->getKey(), $data);
    }

    public static function getInstance($user, $actionName, $modelType, $modelId, $data = [])
    {
        return new static([
            'user_id' => $user ? $user->getKey() : 0,
            'name' => $actionName,
            'model_type' => $modelType,
            'model_id' => $modelId, //
            'data' => json_encode($data),
            'status' => 'finished',
            'exception' => '',
        ]);
    }
}