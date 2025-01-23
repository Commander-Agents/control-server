# Project setup

Modify the `APP_ENV=local` as `APP_ENV=production` in production.

Don't forget to configure the database connection in `.env` with your database credentials :
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

And if you don't use the provided Redis Docker container, you must configure also those variables
```bash
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

```bash
cd src
php artisan storage:link
php artisan key:generate
php artisan migrate

php artisan user:create Admin Admin admin@example.com --admin --password=securePassword

npm run build
```

Then you must generate a RSA key pair and place it in `./storage/app/private/keys/server/` and name them `private_key.pem` & `public_key.pem` with 4096 bits


## MQTT Setup

1. Start the docker compose `docker compose up -d`
2. Generate the password for the user `docker exec -it commander-mqtt mosquitto_ctrl dynsec init /mosquitto/config/dynamic-security.json laravel_admin`, then you will be prompted to enter your user password for client `laravel_admin`. **Please choose a secure password** (you can change the username)
3. Go manually in the file `mqtt/config/dynamic-security.json`, identify the `admin` role and add this to his permissions
```bash
{
    "acltype":	"publishClientSend",
    "topic":	"agents/#",
    "priority":	0,
    "allow":	true
}, {
    "acltype":	"publishClientReceive",
    "topic":	"agents/#",
    "priority":	0,
    "allow":	true
}, {
    "acltype":	"subscribePattern",
    "topic":	"agents/#",
    "priority":	0,
    "allow":	true
}
```
4. Restart the docker compose `docker compose restart`
5. Update the Laravel's `.env` file with your credentials
```bash
MQTT_HOST=127.0.0.1
MQTT_PORT=1883
MQTT_USERNAME=laravel_admin
MQTT_PASSWORD=MY_SECURE_PASSWORD
MQTT_CLIENT_ID=laravel_client
```


Perfect, now you server setup is completed !  
You can now register your agents (refer to the README of the agent) !