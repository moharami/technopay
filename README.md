
## Query String Filter for Laravel

This is a Laravel application code that provides a mechanism to filter Eloquent queries based on query string parameters. It follows a simple and extensible approach to define custom filters for your models.

### Overview

The code consists of the following components:

1. **Filterable Trait**: This trait is used in your Eloquent models to enable query string filtering.
2. **FilterQuery Class**: This class handles the logic of applying filters to the query based on the provided request and the available filter classes.
3. **Filter Classes**: These classes define the actual filtering logic for specific fields of your model. They should be placed in the `App\Filters\YourModel` namespace.
4. **FailedToFindFilter Exception**: This exception is thrown when a filter class cannot be found for a given query string parameter.


## Setup

1. Clone the repository.
2. Run `composer install` to install dependencies.
3. Copy the `.env.example` file to `.env` and configure your database settings.
4. Run `php artisan key:generate` to generate the app key.
5. Run `php artisan migrate` to migrate the database.
6. Start the development server by running `php artisan serve`.



### Usage

1. In your model, use the `Filterable` trait:

```php
class Order extends Model
{
    use HasFactory, Filterable;

    // ...
}
```

2. Create a filter class for each filter you want to apply to your model. Place these filter classes in the `App\Filters\YourModel` namespace. For example, to create a filter for the `price` field, create a file `App\Filters\Order\PriceFilter.php`:

```php
namespace App\Filters\Order;

use YourNamespace\QueryStringFilter\Filter;

class PriceFilter extends Filter
{
    public function apply($value)
    {
        return $this->query->where('price', $value);
    }
}
```

3. In your controller, you can now filter your queries based on the query string parameters:

```php
public function index(OrderFilterRequest $request)
{
    return OrderResource::collection(Order::filter($request)->get());
}
```

### Exception Handling

The `FailedToFindFilter` exception is thrown when a filter class cannot be found for a given query string parameter. You can customize the behavior of this exception by listening to the `FilterClassNotFoundExceptionOccurred` event in your `EventServiceProvider`:

```php
protected $listen = [
    FilterClassNotFoundExceptionOccurred::class => [
        SendEmailNotification::class,
        SendSmsNotification::class,
    ],
];
```

This will trigger the `SendEmailNotification` and `SendSmsNotification` listeners when the `FilterClassNotFoundExceptionOccurred` event is dispatched.




## Code Structure

- `app/Models/Order.php`: Contains the `Order` model with the `Filterable` trait.
- `app/Traits/Filterable.php`: Defines the `Filterable` trait used by the `Order` model.
- `app/Services/FilterQuery.php`: Implements the logic for applying dynamic filters.
- `app/Exceptions/FailedToFindFilter.php`: Represents an exception class for missing filter implementations.
- `app/Listeners/SendEmailNotification.php`: Sends email notifications for `FilterClassNotFoundExceptionOccurred`.
- `app/Listeners/SendSmsNotification`: Sends SMS notifications for `FilterClassNotFoundExceptionOccurred`.


## Testing

Proper tests have been written to ensure the functionality of the query string filtering mechanism. The tests can be found in the `tests/` directory and can be run using the `php artisan test` command.

## API Documentation

The API endpoints are documented using Swagger. You can access the Swagger in public/Doc folder