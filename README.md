## About Framework

The framework, built on Laravel 9, seamlessly integrates Permission management, providing a comprehensive RBAC solution that covers functionalities like login, registration, and user management. Additionally, it offers a RESTful response return format, enhancing the API interaction experience.

## Deployment
1. Update .env Configuration:
    ```
    APP_DEBUG=false
   ```
2. Optimize Autoloading:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
3. Reset JWT:
   ```bash
   php artisan jwt:secret
   ```
4. Initialize User Roles and Permissions:
   ```bash
   php artisan db:seed RoleAndPermissionSeeder
   ```
5. Seed Departments:
   ```bash
   php artisan db:seed DepartmentsTableSeeder
   ```
> Note: Make sure to review and test each step before deploying to a production environment.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
