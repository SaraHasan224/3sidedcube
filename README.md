# Laravel 10.10 TheCubeAcademy
This peoject is created using Laravel 10.10. It has Users, and Posts. Protected routes are also added. Protected routes are accessed via Passport access token.

#### Following are the Models
* User
* Post

#### Usage
Clone the project via git clone or download the zip file.

##### .env
Copy contents of .env.example file to .env file. Create a database and connect your database in .env file.
##### Composer Install
cd into the project directory via terminal and run the following  command to install composer packages.
###### `composer install`
##### Generate Key
then run the following command to generate fresh key.
###### `php artisan key:generate`
##### Run Migration
then run the following command to create migrations in the databbase.
###### `php artisan migrate`
##### Passport Install
run the following command to install passport
###### `php artisan passport:install`

##### Database Seeding
finally run the following command to seed the database with dummy content.
###### `php artisan db:seed`

##### Node Install
cd into the project directory via terminal and run the following  command to install node packages.
###### `npm install`
###### `npm run dev`

### Dashboard
My application dashboard  `http://localhost:8000/`

### Registration
My application registration  `http://localhost:8000/register`

### Swagger documentation
Swagger documentation  `http://localhost:8000/v1/api/documentation`

### Real-time error tracking
Used Sentry for real-time error tracking  `https://sentry.io/welcome`

### API EndPoints
##### Auth
* Register POST `http://localhost:8000/v1/register`
* Login POST `http://localhost:8000/v1/login`
##### Customer
* User GET `http://localhost:8000/v1/customer`
##### Post
* Manage Posts - GET All `http://localhost:8000/v1/all-posts` 
* Get post details - GET Single `http://localhost:8000/v1/post/{id}`
* Add New Post - POST Create `http://localhost:8000/v1/post/add`
* Edit Post - POST Update `http://localhost:8000/v1/post/edit/{id}`
* Delete Post - POST destroy `http://localhost:8000/v1/post/delete/{id} `

##### Test
run the following command to execute test cases
###### `php artisan test`


##### Scheduler
run the following command to schedule posts
###### `php artisan publish-scheduled`