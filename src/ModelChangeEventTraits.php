<?php
namespace GoodSystem\ModelHistory;

use GoodSystem\ModelHistory\ModelHistory;
use Illuminate\Database\Eloquent\Model;

trait ModelChangeEventTraits
{
    public function updated(Model $model)
    {
        ModelHistory::forFieldChanges(auth()->user(), $model)->save();
    }

    public function created(Model $model)
    {
        ModelHistory::forModelCreated(auth()->user(), $model)->save();
    }
}
