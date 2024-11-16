# API de Usuarios

Este proyecto es una API RESTful para gestionar usuarios en una aplicación móvil. Está construido usando PHP y se ejecuta en un servidor XAMPP.

## Comenzando

### Requisitos Previos

- XAMPP instalado en tu máquina
- PHP 7.4 o superior
- Composer

### Instalación

1. Clona el repositorio:
    ```sh
    git clone https://github.com/SDDrouet/api-users-moviles
    ```

2. Entra al contenido del directorio y y mueve la carpeta **users-api** a la carpeta htdocs de xampp

3. Inicia el servidor XAMPP y asegúrate de que Apache y MySQL estén en funcionamiento.

4. Instala las dependencias de composer dentro del backend en la carpeta **users-api**
    ```sh
    composer i
    ```

### Configuración

1. Ejecuta el script .sql, para generar la base de datos en mysql de xampp.

2. Configura el .env con los datos de la base de datos creada.
    ```sh
    DB_HOST=localhost
    DB_USER=root
    DB_PASS=
    DB_NAME=usuarios_api
    JWT_SECRET=moviles
    ```

## Uso de la API

### Endpoints

#### Obtener Todos los Usuarios

- **URL:** `/api/users`
- **Método:** `GET`
- **Descripción:** Recupera una lista de todos los usuarios.
- **Header:**
    ```sh
    Authorization: Bearer <token>
    ```
- **Respuesta:**
    ```json
    [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john.doe@example.com"
        },
        ...
    ]
    ```

#### Obtener Usuario por ID

- **URL:** `/api/users/{id}`
- **Método:** `GET`
- **Descripción:** Recupera un usuario por su ID.
- **Header:**
    ```sh
    Authorization: Bearer <token>
    ```
- **Respuesta:**
    ```json
    {
        "id": 1,
        "name": "John Doe",
        "email": "john.doe@example.com"
    }
    ```

#### Crear Usuario

- **URL:** `/api/users`
- **Método:** `POST`
- **Descripción:** Crea un nuevo usuario.
- **Cuerpo de la Solicitud:**
    ```json
    {
        "name": "John Doe",
        "email": "john.doe@example.com",
        "password": "password123"
    }
    ```
- **Respuesta:**
    ```json
    {
        "message": "Usuario creado exitosamente."
    }
    ```

#### Actualizar Usuario

- **URL:** `/api/users/{id}`
- **Método:** `PUT`
- **Descripción:** Actualiza un usuario existente.
- **Header:**
    ```sh
    Authorization: Bearer <token>
    ```
- **Cuerpo de la Solicitud:**
    ```json
    {
        "name": "John Doe",
        "email": "john.doe@example.com",
        "password": "newpassword123"
    }
    ```
- **Respuesta:**
    ```json
    {
        "message": "Usuario actualizado exitosamente."
    }
    ```

#### Eliminar Usuario

- **URL:** `/api/users/{id}`
- **Método:** `DELETE`
- **Descripción:** Elimina un usuario por su ID.
- **Header:**
    ```sh
    Authorization: Bearer <token>
    ```
- **Respuesta:**
    ```json
    {
        "message": "Usuario eliminado exitosamente."
    }
    ```

#### Login

- **URL:** `/api/login`
- **Método:** `POST`
- **Descripción:** Iniciar sesión con un usuario.
- **Cuerpo de la Solicitud:**
    ```json
    {
        "username": "username",
        "password": "password"
    }
    ```
- **Respuesta:**
    ```json
    {
        "message": "Login exitoso",
        "token": "token...."
    }
    ```


### **Nota:**
- La ruta completa del endpoint dependera de la carpeta donde guardes el backend

    #### Ejemplo
    Si guardas la carpeta en esta dirección
    ```sh
    C:\xampp\htdocs\www\api-users-moviles\users-api
    ```
    La URL base es
    ```sh
    http://localhost/www/api-users-moviles/users-api/
    ```
    Podras acceder a los endpoints de la siguiente forma
    ```sh
    http://localhost/www/api-users-moviles/users-api/api/users
    ```


## Agradecimientos

- Gracias a la comunidad de código abierto por sus contribuciones.
