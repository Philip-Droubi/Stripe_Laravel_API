
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

- After that, you need to have an account on [Stripe](https://stripe.com/) to get the publishable and secret keys from the developer dashboard.

When you get these keys copy and paste them into the project .env file

```bash
STRIPE_PUBLISHABLE_TEST_KEY=pk_test_*********
STRIPE_SECRET_TEST_KEY=sk_test_*********
STRIPE_WEBHOOK_SECRET_KEY=whsec_*********
```

- Create a database.

- Run `php artisan migrate --seed`

## How to use Stripe webhook locally?

1. Download `Stripe CLI` from [HERE](https://https://github.com/stripe/stripe-cli/releases).
2. Once downloaded, open the cmd in the stripe.exe folder and run `stripe login`.
3. Copy the given link and paste it into the browser.
4. Verify your connection using the email sent by Stripe to your email address.
5. Compare the pairing code displayed in your cmd with the one in the browser UI.
6. If they match, click "allow" in the browser.
7. In the cmd, enter the following command: `stripe listen --forward-to http://127.0.0.1:8000/webhook`
8. Copy the webhook signing secret and paste it in the `.env` file `STRIPE_WEBHOOK_SECRET_KEY=whsec_*********`.

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
