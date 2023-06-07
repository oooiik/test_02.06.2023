# TEST 02.06.2023

## Installation

1. Clone the repository.

2. skip this step if you like the settings in `.env.example` .
   If you don't like the settings here, copy the file (``` make create-env ```) and adjust it to your needs.

   here are the basic settings:
    ```dotenv
    COMPOSE_PROJECT_NAME=TEST # Project name
    COMPOSE_PROJECT_NETWORK=172.2.0 # Start the network with 3 numbers
    COMPOSE_USER_NAME=user # username in the build (recommend writing for windows)
    COMPOSE_USER_UID=1000 # user id in the build
    ```

3. Docker compose build and up
    ```bash
    make build
    make up
    ```
   or
    ```bash
    make up
    ```

4. Open a browser and go to Go to the address (default: http://172.2.0.2/api/docs) shown in the terminal.
   Or run the command and the browser will open automatically.
    ```bash
    make open-server
    ```

5. To stop the server, run the command
    ```bash
    make stop
    ```
   Start the server again with the command
    ```bash
    make start
    ```

6. To remove the server, run the command
    ```bash
    make down
    ```

7. Enjoy the work of the server. 😁

