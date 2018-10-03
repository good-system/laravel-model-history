# Laravel Model History

## Installation
```
composer require good-system/laravel-model-history
composer migrate
```

## Usage

### Use Generic `ModelObserver`

If model history logging/recording for "created" and "updated" are the only things you need to do in a model observer, simply add something like the following to your service provider class:
```
class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Donation::observe(\GoodSystem\ModelHistory\ModelObserver::class);
    }
}
``` 

And two model actions will be logged in database table `model_history`

- new donation created
- donation updated
  
### Use Trait `ModelChangeEventTraits`

If your model observer needs to observe additional model events, but for "created" and "updated" events, model history logging/recording is the only thing needed, trait `ModelChangeEventTraits` could be added to observer class, like this

```
class YOUR_MODEL_Observer
{
    use \GoodSystem\ModelHistory\ModelChangeEventTraits;

    // other model events such as deleted, saved, etc.
}
```

### Use `ModelFieldHistory` model static methods directly

Model class `ModelFieldHistory` provides two additional methods
 
- `forModelCreated()` - record newly created model
- `forFieldChanges()` - record changes made to the model  

If your model observer's "created" or "updated" event needs to do history logging/recording, plus other things, these two methods can be called from the observer directly, like this:  
```
class YOUR_MODEL_Observer
{
    public function updated(YOUR_MODEL $modelObject)
    {
        ModelHistory::forFieldChanges(auth()->user(), $modelObject)->save();
        // Other things that need to be done upon model record update
    }
    
    public function created(YOUR_MODEL $modelObject)
    {
        ModelHistory::forModelCreated(auth()->user(), $modelObject)->save();
        // Other things that need to be done upon model record creation
    }
}
```

### Other Usage

No other usage at this point.  Note that the database table is not limited to accommodate these two changes.  
