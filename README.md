# Thrivecart products

> A lite system to process products order creation, which supports delivery fees and offers to them

---

## Table of Contents

- [About the Project](#about-the-project)
- [Tech Stack](#tech-stack)
- [Setup Instructions](#setup-instructions)
- [Structure & Usage](#structure--usage)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

---

## About the Project

As an interview test, this project reflects the requirements of [this document](https://www.dropbox.com/scl/fi/n8x764do2iy0t1pff787b/architech-labs-code-test.pdf?rlkey=uynfzjm445ejhlq7ijf5x5yvu&e=1&dl=0)

The project is structure to be used as a REST API. It exposes a set of routes which accepts JSON requests and responses,
and can be plugged/connected to any front end project done in Vue JS, React, vanilla Javascript or any other front end
library/framework that the team wishes.

---

## Tech Stack

- PHP 8.4 & Laravel version 12: As the latest version of both the language and the framework, the code is completely up to date with current standards
- Database: MySQL, but easy to configure to any major DB engines in the market
- PHPUnit: Main classes tested and covered
- REST API structure: In order to use it separately from any UI
- Dependency Injection: Provided out of the box via Laravel
- Design Patterns: Factory & Strategy ones customs, the rest out of the obx via Laravel
- Source control: Git used with branches and PRs
- Small interfaces: The offers are processed via interface implementations, to follow OPEN/CLOSED principle and proper design
- Separation of concerns: Each class performs a specific task/responsibility

---

## Setup Instructions

### Prerequisites

- PHP >= 8.0 (or your project’s version)
- Composer
- Database server (MySQL)

### Steps

1. **Clone the repository**

    ```bash
    git clone https://github.com/jmares79/thrivecart.git
    cd <repo-where-cloned>
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Copy the environment file**

    ```bash
    cp .env.example .env
    ```

4. **Generate the application key**

    ```bash
    php artisan key:generate
    ```

5. **Configure `.env`**

    - Set your database connection details in the `DB_CONNECTION` & `DB_*` variables
    

6. **Run database migrations**

    ```bash
    php artisan migrate
    ```

7. **Seed the database**

    ```bash
    php artisan db:seed
    ```

9. **Start the development server**

    ```bash
    php artisan serve
    ```

---

## Structure & Usage

Open Postman or any REST client and point the base URL to the equivalent of `http://localhost:8000` or wherever the 
site has been pointed to.

### Routes

- Orders: It handles everything related to order creation and fetching (For a single order)
- Products: It handles products CRUD
- Basket/Order total: It handles the calculation of the total value of a single order, applying offers and delivery fees

Eg: Order creation should be:

    HTTP POST `api/orders` 

with the required payload exposed in OrderCreationRequest file

> Every route endpoint has a request file where it exposes the data payload to be sent vua Postman. For example, for
> order creation the payload consist of an array with the shape [description, [products => [id, amount]]

`
{
    "description": "Dummy description",
    "products": [
        {
            "id": 1,
            "amount": 4
        },
        ...
    ]
}
`

> The products must be already created or seeded for the order creation to work

For a full list of the available routes, execute:

    php artisan route:list

And it will print the list of all routes with the HTTP verb, canonic route and name in the project.

### Architecture

As explained, the project consist of a standard Laravel structure, where the `app` folder is the entry point of the 
child folders where the system classes resides.

#### Controllers

- Order controller: Handles all actions for creating and showing an order
- Product controller: CRUD a product
- Basket controller: Calculates the total amount for a specific order, applying delivery and offers

#### Requests

This folder contains all request classes, which validates the payloads sent to the controller methods

#### Logic

This folder contains the Basket logic class itself, which calculates the total of the order and the delivery fee for the 
order, the offer creation factory and the strategies used to process the individual offers.

#### Factories

This is an offer creation factory, where we should encapsulate the offer type creation when processing 
a product in the order. It uses an Enum PHP standard class to limit the offer type to process, so every time a new offer
is created, it has to be updated there.

#### Strategy

This folder contains all the concrete offer processing strategies, which should be one per offer type, for example 
`ONE_RED_WIDGET_SECOND_HALF_PRICE`. 

The way a concrete offer strategy is instantiated is through the calculate offer total (In BasketLogic). In that method 
we fetch an offer for every product being processed by product code. Then the Offer factory will create a concrete offer
according to the code saved in the offer table and it will return it to be used in the mentioned logic.

In this way, it will be easy to create and add as many offers as needed after product deployment.

#### Interfaces

This folder contains the interface that every offer strategy **MUST** implement in order to be used in the factory. This
interface provides the method to be implemented into any new offer processing strategy, making them extensible and
flexible enough for the system to be expanded.

The steps to add a new offer strategy are:

1. Create a new records in 'offers' table with a unique **code** and a product code, which indicates the product to 
apply the offer to.
2. Create a new entry in `OfferTypes` enum class, to map the new offer, with the new Offer type code.
3. Create a new strategy class similar to `OneRedWidgetSecondHalfPrice`, implementing the interface, which implements 
the concrete logic of the new offer.
4. Update the Offer factory class `OfferProcessingFactory` match map to return the new offer when the code is queried. 

In this way, we keep the system flexible to add new offers via strategies.

### Example usage

1. After seeding the DB (This is mandatory as creates products) create an order with the payload explained in the request
2. Process the order via the BasketController endpoint to see the full order with its products, the subtotal of the order and the delivery fees.

---

## Potential improvements

1. Caching most popular products when creating orders to avoid DB access
2. Caching most popular offers
3. Create a pivot table structure to include a list of products into a single offer, to allow "Mix and match" offers
4. Check MySQL queries indexes
5. Install Laravel Telescope to check slow queries and requests
6. Use something similar to Laravel Octane

---
## Testing

- Run tests with:

    ```bash
    php artisan test
    ```

- Or with PHPUnit directly:

    ```bash
    ./vendor/bin/phpunit
    ```

---

## Disclaimer

Although I added everything I could think of, if some parts of this document are unclear or confusing, or if simply
needs some help setting it up, please send me an email to `jmares79@gmail.com`
