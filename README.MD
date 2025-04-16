# API Documentation

## Description
This API allows managing projects and associated files, and users with registration and login, using token-based authentication. It includes functionalities to create, list, update, and delete projects, as well as upload and manage files related to the projects.

---

## Requirements
- **PHP** >= 7.4
- **Composer** for dependency management
- Configured web server (Apache, Nginx, etc.)
- MySQL database

---

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/sebasmzg/php-freelance-flow
   cd php-freelance-flow
   ```

2. Install dependencies with Composer:
   ```bash
   composer install
   ```

3. Configure environment variables in a `.env` file:
   ```env
   SECRET_KEY=your_secret_key
   DB_HOST=database_host
   DB_NAME=database_name
   DB_USERNAME=username
   DB_PASSWORD=password
   ```

4. Set write permissions for the `uploads` directory (if necessary):
   ```bash
   chmod -R 0777 uploads
   ```

5. Start the server:
   ```bash
   php -S localhost:8000 -t public
   ```

---

## Endpoints

### **Authentication**
#### `POST /register`
- **Description**: Registers a new user.
- **Body**:
  ```json
  {
    "name": "string",
    "email": "string",
    "password": "string"
  }
  ```
- **Response**:
  - `201 Created`: User successfully registered.
  - `400 Bad Request`: Invalid data.

#### `POST /login`
- **Description**: Logs in and retrieves an authentication token.
- **Body**:
  ```json
  {
    "email": "string",
    "password": "string"
  }
  ```
- **Response**:
  - `200 OK`: Returns the token.
  ```json
  {
  "message": "Login successful",
  "token": "authentication_token"
  }
  ```
  - Token content:
  ```json
    {
  "iss": "http://localhost",
  "aud": "http://localhost",
  "iat": "issued_at_time",
  "exp": "expiration_time",
  "user": {
    "id": 2,
    "name": "John Doe",
    "email": "johndoe@example.com"
    }
  }
  ```
  - `401 Unauthorized`: Invalid credentials.

---

### **Projects**
#### `POST /projects/create`
- **Description**: Creates a new project.
- **Headers**:
  - `Authorization: Bearer <token>`
- **Body**:
  ```json
  {
    "title": "string",
    "description": "string",
    "start_date": "YYYY-MM-DD",
    "delivery_date": "YYYY-MM-DD",
    "state": "enum: ['pending', 'in_progress', 'completed']",
    "user_id": "string"
  }
  ```
- **Response**:
  - `201 Created`: Project successfully created.
  - `400 Bad Request`: Missing or invalid data.

#### `GET /projects`
- **Description**: Lists the projects of the authenticated user.
- **Headers**:
  - `Authorization: Bearer <token>`
- **Response**:
  - `200 OK`: Returns the list of projects.
  - `500 Internal Server Error`: Error retrieving projects.

#### `GET /projects/get/{id}`
- **Description**: Retrieves a project by its ID.
- **Headers**:
  - `Authorization: Bearer <token>`
- **Response**:
  - `200 OK`: Returns the project.
  - `404 Not Found`: Project not found.

#### `PUT /projects/update/{id}`
- **Description**: Updates an existing project.
- **Headers**:
  - `Authorization: Bearer <token>`
- **Body**:
  ```json
  {
    "title": "string",
    "description": "string",
    "start_date": "YYYY-MM-DD",
    "delivery_date": "YYYY-MM-DD",
    "state": "enum: ['pending', 'in_progress', 'completed']"
  }
  ```
- **Response**:
  - `200 OK`: Project successfully updated.
  - `400 Bad Request`: Missing or invalid data.

#### `DELETE /projects/delete/{id}`
- **Description**: Deletes a project by its ID.
- **Headers**:
  - `Authorization: Bearer <token>`
- **Response**:
  - `200 OK`: Project successfully deleted.
  - `500 Internal Server Error`: Error deleting the project.

---

### **Files**
#### `POST /projects/{projectId}/files/upload`
- **Description**: Uploads a file to a project.
- **Headers**:
  - `Authorization: Bearer <token>`
- **Body**:
  - **form-data**:
    - `file`: File to upload.
- **Response**:
  - `201 Created`: File successfully uploaded.
  - `500 Internal Server Error`: Error saving the file.

#### `GET /projects/{projectId}/files`
- **Description**: Lists the files of a project.
- **Headers**:
  - `Authorization: Bearer <token>`
- **Response**:
  - `200 OK`: Returns the list of files.
  - `500 Internal Server Error`: Error retrieving files.

#### `GET /projects/{projectId}/files/{fileId}`
- **Description**: Retrieves a file by its ID.
- **Headers**:
  - `Authorization: Bearer <token>`
- **Response**:
  - `200 OK`: Returns the file.
  - `404 Not Found`: File not found.

#### `DELETE /projects/{projectId}/files/{fileId}`
- **Description**: Deletes a file by its ID.
- **Headers**:
  - `Authorization: Bearer <token>`
- **Response**:
  - `200 OK`: File successfully deleted.
  - `500 Internal Server Error`: Error deleting the file.

---

## Security
- **Authentication**: Token-based JWT.
- **Validation**: All protected routes require the `Authorization` header.

---

## Project Structure
```
.
├── public
│   └── index.php
├── src
│   ├── controllers
│   ├── middleware
│   ├── models
│   ├── core
│   └── routes.php
├── uploads
├── vendor
├── docs
└──composer.json

```
## Postman Collection

You can import this collection into Postman to test the API:
[freelance-flow-api.postman_collection.json](./docs/freelance-flow.postman_collection.json)

---

## License
This project is licensed under the [MIT](LICENSE) license.
```