# paymentiq_test
Test for sign in flow and cashier

# What do I need?

PHP, composer, ngrok, npm

# How to use?

Create a api client in PIQ backoffice insert the client details (clientId & clientSecret) in SignInController. Start the ngrok client and add it to the uri property in the SignInController.
Add your ngrok url to the api client as redirect url and add /oauth2/callback after it, for example: https://5031-185-139-247-226.ngrok.io/oauth2/callback


Now visit your url: https://localhost:8000/signin to test the sign in flow. Add amount & currency to the url to test signin + deposit flow. 
https://localhost:8000/signin?amount=100&currency=EUR
