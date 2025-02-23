<p align="center">
    <a href="https://www.linkedin.com/in/evandrojlle/" target="_blank">
        <img src="https://media.licdn.com/dms/image/v2/D4D03AQGwE1Sw5gAPXg/profile-displayphoto-shrink_200_200/profile-displayphoto-shrink_200_200/0/1718302942023?e=1746057600&v=beta&t=F0RJ1v7UzintcnID3JwPtppNZQvbVUsns_7TaufR5qQ" width="120" alt="Evandro de Oliveira">
    </a>
</p>

## About Project

Our clients operate in the real estate sector, managing multiple buildings within their accounts. We need to provide a tool that allows our owners to create tasks for their teams to perform within each building and add comments to their tasks for tracking progress.. These tasks should be assignable to any team member and have statuses such as Open, In Progress, Completed, or Rejected.

### Technical Requirements: 

- Develop an application using Laravel 10 with REST architecture.
- Implement GET endpoint for listing tasks of a building along with their comments. 
- Implement POST endpoint for creating a new task.
- Implement POST endpoint for creating a new comment for a task.
- Define the payload structure for task and comment creation, considering necessary relationships and information for possible filters.
- Implement filtering functionality, considering at least three filters such as date range of creation and assigned user, or task status and the building it belongs to.

### Expected Deliverables: 
- Provide the application in a public GitHub repository 
- Include migrations for table construction. 
- Organize code with clear separation of responsibilities. 
- Implement unit tests to ensure code reliability. 
- Provide detailed installation instructions in the readme. 
- Ensure adherence to coding standards, specifically PSR-12.

### Bonus:
- Containerize the application using Docker. 
- Type methods and parameters for improved code clarity. 
- Include descriptive PHPDoc in the methods.

## Development

### Develop an application using Laravel 10 with REST architecture.
- According to the project's premises, Laravel version 10.x was used to develop the APIs.

### Download the project from the GitHub repository.
- The project must be downloaded from the Github repository using the command below, through your terminal:
```git
git clone https://github.com/evandrojlle/proprli.git
```
or
```git
git clone git@github.com:evandrojlle/proprli.git
```

### Download laravel dependencies.
- After downloading the project to the local machine, still through the terminal, the project's root directory must be accessed and composer must be run to install Laravel's dependencies.
```composer
composer install
```

### Configuration
- After installing the Laravel dependencies, rename the .env.example file to just .env . Edit this file and check and if necessary correct the settings according to your work environment.
- The Mysql database was used.

```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proprli
DB_USERNAME=root
DB_PASSWORD=
```

### Migrations
- To create tables in the `proprli` database, the commands below must be executed:
```php
php artisan migrate:install
php artisan migrate
```

### Load Data
- To add the test data to the database, the command below can be run:
```php
php artisan db:seed
```

### Rodando o projeto.
- After configuration, it's time to get the project running. Run the command below in your terminal:
```
php artisa serve
```
- This command will show the address that the application is responding to. Copy and paste the url into the browser.

```
 INFO  Server running on [http://127.0.0.1:8000].
```

### Routes.
Routes are configured in the routes/api.php file and can be executed through POSTMAN, as follows:
- [Create Task](http://127.0.0.1:8000/api/tasks/store)
- [Update task](http://127.0.0.1:8000/api/tasks/update)
- [List Tasks](http://127.0.0.1:8000/api/tasks/list/6)
- [List Tasks Filter](http://127.0.0.1:8000/api/tasks/filters/initial=2025-02-22&&final=2025-02-28)
- [Create Comment](http://127.0.0.1:8000/api/comments/store)
- [Update Comment](http://127.0.0.1:8000/api/comments/update)
- [Get Comment](http://127.0.0.1:8000/api/comments/id/2)
- [Get Comment By Task](http://127.0.0.1:8000/api/comments/task/3)

