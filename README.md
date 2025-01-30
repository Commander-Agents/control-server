# What is it ?

This projects allows you to run an "Ansible agents management platform".  
Yes I know, Ansible is supposed to be agent-less. But if you've ever tried to install Ansible on more than 10 Windows hosts, you know how shitty this is on Windows.  

So here is Commander-Agents !  

It allows you to run [lightweight agents (available here)](https://github.com/Commander-Agents/commander-agent) on your hosts (Windows or Linux) and run commands from your server !

*Note: The project is currently in progress, not all features are available and the design is not finished*

## Screenshots
![image](https://github.com/user-attachments/assets/0fd53a34-4175-43ef-abdc-a40f20ac90f5)  
![image](https://github.com/user-attachments/assets/be4e681b-6e61-4dd7-a1d2-a3a4d5e10f0c)  
![image](https://github.com/user-attachments/assets/20d0048a-8f76-4483-b604-73c02270f27d)


# Project setup

Run `cp .env.example .env` to have a fresh `.env` file for Laravel.  
Then modify the `APP_ENV=local` as `APP_ENV=production` in production.

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
