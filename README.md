
# Stripe Learning Project

This project aims to learn how to use stripe checkout sessions with Laravel.

## Postman Collection

<https://documenter.getpostman.com/view/32746829/2sAXxJgZUJ>

## Installation

To clone and run this project:

- Open your cmd where you want to clone this project and run:

`git clone git@github.com:Philip-Droubi/Stripe_Laravel_API.git`

- install required packages:

`composer install`

- After that, you need to have an account on [Stripe](https://stripe.com/) to get the publishable and secret keys from the developer dashboard

After you get these keys put them in the project .env file:

```bash
STRIPE_PUBLISHABLE_TEST_KEY=pk_test_*********
STRIPE_SECRET_TEST_KEY=sk_test_*********
```

- Create a database.

- Run `php artisan migrate --seed`

## Ready to use account in the system

email: philip@email.com
password: phil1234

## Stripe form data for testing

- Card Number: 4242 4242 4242 4242
- Date of expire: 12/28
- CVC:123
- Cardholder name: any name

Now you are ready to go 😃

Happy codding! 💻🎉
