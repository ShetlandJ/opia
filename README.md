# Phone number validator

1. run `composer install` in the terminal
2. rename the `.env.example` file to just `.env`
3. update the environment variables with the appropriate information from the Twilio service
4. run `php app.php phone_numbers.txt` in the CLI
5. check the `outputs` directory for your file. The naming convenention is: `yyyy_mm_dd__hh_mm__ss` eg 2019_02_25__20_45_13