## TECHNICAL TEST - RESTFUL API

## RERQUIREMENTS

-   PHP ^8.1
-   composer 2.0

## INSTALLATION

1. clone this repository

```sh
git clone https://github.com/aryumne/be-restful-api.git
```

2. install the packages

```sh
composer install
```

3. duplicate the .env.example file with new filename, .env

```sh
cp .env.example .env
```

4. adjust the database connection inside the .env file
5. run migration and seed

```sh
php artisan migrate --seed
```

6. run the server

```sh
php artisan serve
```

## DUMMY USER

-   SUPERADMIN
    -   email : spa@email.com
    -   password : password
-   SUPERVISOR
    -   email : spv@email.com
    -   password : password
-   USER
    -   email : bayu@email.com
    -   password : password

## API DOCUMENTATION

1. Login

-   request:
    ```
    curl --location --request POST 'http://127.0.0.1:8000/api/login' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --data-raw '{
        "email" : "spv@email.com",
        "password": "password"
    }'
    ```
-   response:
    ```
    {
    "message": "Login success",
    "data": {
    "user": {
    "id": 2,
    "email": "spv@email.com",
    "name": "Supervisor",
    "roleName": null,
    "abilities": [
    "approve-epresence"
    ]
    },
    "token": "8|5Q5ayZeBcqY0w1iXUAaGkJnVUQjh7NMiW4vILPhs2cac04ad"
    },
    "status": true,
    "errors": []
    }
    ```

2. Insert Data (Create Epresence)

-   request:

    ```
    curl --location --request POST 'http://127.0.0.1:8000/api/epresence' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 8|5Q5ayZeBcqY0w1iXUAaGkJnVUQjh7NMiW4vILPhs2cac04ad' \
    --data '{
        "waktu" : "2024-05-31 08:00:00",
        "type": "IN"
    }'
    ```

-   response:

    ```
    {
        "message": "Epresence IN success",
        "data": {
            "id_user": 3,
            "nama_user": "Ananda Bayu",
            "type": "IN",
            "tanggal": "2024-05-31",
            "waktu": "08:00:00"
        },
        "status": true,
        "errors": []
    }
    ```

3. Approve Data (Update Epresence)

-   request:

    ```
    curl --location --request PATCH 'http://127.0.0.1:8000/api/epresence/8' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 7|kTBAxX8hQYcz2J05IsBAaR6vxUPzsOIMDokUZ3xa92b390db' \
    --data '{
        "is_approve" : true
    }'
    ```

-   response:

    ```
    {
        "message": "Update status success",
        "data": null,
        "status": true,
        "errors": []
    }
    ```

4. Get Data epresence user

-   request:

    ```
    curl --location --request GET 'http://127.0.0.1:8000/api/epresence' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 8|5Q5ayZeBcqY0w1iXUAaGkJnVUQjh7NMiW4vILPhs2cac04ad'
    ```

-   response:

    ```
    {
        "message": "Success get data",
        "data": [
            {
                "id_user": 3,
                "nama_user": "Ananda Bayu",
                "tanggal": "2024-05-30",
                "waktu_masuk": "15:11:45",
                "waktu_pulang": "16:13:50",
                "status_masuk": "APPROVE",
                "status_pulang": "REJECT"
            },
            {
                "id_user": 3,
                "nama_user": "Ananda Bayu",
                "tanggal": "2024-05-31",
                "waktu_masuk": "08:00:00",
                "waktu_pulang": null,
                "status_masuk": "REJECT",
                "status_pulang": null
            }
        ],
        "status": true,
        "errors": []
    }
    ```
