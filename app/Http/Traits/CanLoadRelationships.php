<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder as EloquetBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// IMPORTANT!!!
// THIS TRAIT IS SOMETHING THAT IS JUST A WAY OF HOW CAN YOU SOLVE THOSE SITUATIONS WHERE YOU MIGHT WANT TO LOAD SOME ADDITIONAL RELATIONSHIPS. --
// -- YOU DONT HAVE ALWAYS TO DO THAT, LIKE ADDING THE loadRelationships() in the Controllers but if you feel the need to do that or you have this kind of requirement, that could be a solution of how can you do that and how can you do that in a way that's reusable for every of your API controllers
// Manually Created the Traits folder and CanLoadRelationships.php file and its contents code
trait CanLoadRelationships
{
     // $for we will load the relationships for something either modal or a query builder
    public function loadRelationships( 
        Model|QueryBuilder|EloquetBuilder|HasMany $for, 
        ?array $relations = null // This is a nullable arguemnt, but lets also add a default value otherwise, even if its if this argument is nullable you still have to specify it. This $relations array parameter is only useful if you would like to customize what relations can be loaded for every single action within controller. So we wnat to make things customizable if they are about to be reusable
        ) :  Model|QueryBuilder|EloquetBuilder|HasMany // Make sure the return type $for is also the same as the paraemters here ->  Model|QueryBuilder|EloquetBuilder
    {
        // This will decide where do we get those relationships from. So if you want to specify the relations parameter, it would automatically use the value of the field.
        $relations = $relations ?? $this->relations ?? []; // If the relations argument in the first left is null or empty it will take the second argument which is $this->relations
        // We use the $relations if the parameter is passed. If its not passed we use the $this->relations which is an array of default relations or defined inside the class where this trade is used. 
        // We dont expect the $this->relations on the trait. It has to be added to class. So where we would use this trait, in this case this would be in the EventController or alternatively an empty array which when you use for each loop this would just not load anything because that's an empty array.

        // We are iterating over all the relations which would be passed now as an argument to this method. -> array $relations
           // Supplementing this query with something optionally. we used foreach loop to over all the relations that we have inside our array.
        // 
        foreach($relations as $relation){
            // Bot models and query builder instances has this when() method.
            $for->when(  //Every query builder instance has this when method. When the first argument passed to this, when method is true, it will run the second function which can alter the query. 
                $this->shouldIncludeRelation($relation), // If this true the call the arrow function fn()
                fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation) // If this $for variable is an instance of model, we do $for -> load() because the MODEL is already LOADED, so it wont have the width method available. If not we use $q -> with() for a QUERY BUILDER
                        // The instanceof is a build in keyword of PHP. This would let you check what is the class type of a specific object which is this one is $for
                        // $q is acutally passing a QUERY BUILDER INSTANCE. so in this case if we want to use $for->load($relation) method, we should just use this passed $for variable because this is a model.
                    );
        }

        return $for; // This function should return the $for so we can build or we can have a fluent interface.
        // SO WITH THIS THE INCLUDE PARAMETER IN THE URL WILL BE WORK WITH BASICALLY EVERY SINGLE ENDPOINT INSIDE EACH CONTROLLER. LIKE THIS CASE EVENTCONTROLLER , ATTENDEECONTROLLER AND OTHER CONTROLLER.
    }

    protected function shouldIncludeRelation(string $relation) : bool
    {
        // We will get the request query 
        // In laravel you can get the current request using the request function
        // We are uusing the request() function which lets you add a global access to the current request VARs. We dont have to pass the request object to this method ->shouldIncludeRelation()
        $include = request()->query('include');

        // If the parameter is null or well emplty we can check if its true or false.
        // So it will return false if include would be null
        if(!$include){
            return false;
        }
        
        // We'll use the built in PHP explode function that lets you convert a string to an array using a specific separator
        // IN THIS CASE we will use the comma as the separator ','
        // the array_map will make a run through every results in the url in ?include in url that explode would generate through a trim function.
        //  
        $relations = array_map('trim', explode(',', $include)); // trim is a built in php function that will remove all the starting leading spaces and all the ending spaces from any string.

        // dd($relations);
        return in_array($relation, $relations); // So it checks if a specific relation that's passed to this method is inside relations array.
    }
}
