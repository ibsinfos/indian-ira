# Indian Ira File Documentation

All the files that were created and/or updated while developing the project are listed here. Note that the core framework files are not updated. However, few of them were updated for the application to function properly.

----

This file documentation will be based on feature wise. Meaning, the files that were created / updated will be seen here and not alphabetically.

----

#### Generate Super Administrator (S. A.):

The application should not function without Super Administrator.

When a new application is made, it should first, force the user to generate the S. A., no matter which / what route the user is accessing.

On successful generating the S. A., send an E-Mail to the given E-Mail address along with the user credentials.

Provide message above the form that an E-Mail will be sent to the given E-Mail address.

Once generated, take them to Home Page section instead of Admin Dashboard.

Following files were made / changed:

1. **`app/Http/Controllers/Admin/GenerateController.php`**<br />
    Contains the methods that are required for Generating the Super Administrator.
    On successful generation, sends an E-Mail to the given E-Mail address.

2. **`app/Mail/AdminGenerated.php`**<br />
    Mail that will be sent to the Generated Super Administrator.

3. **`app/User.php`**<br />
    User Model which is accessed to create / update / delete the user of the application.

    Uses traits: Illuminate\Database\Eloquent\SoftDeletes

4. **`database/factories/UserFactory.php`**<br />
    Generating a random user.

5. **`database/migrations/2014_10_12_000000_create_users_table.php`**<br />
    The users table structure.

6. **`resources/views/admin/generate.blade.php`**<br />
    The view file that displays the form for generating the Super Administrator.

7. **`resources/views/admin/partials/_layout.blade.php`**<br />
    The master layout file for super administrator that will be used by other files.

8. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    The navigation links for the Super Administrator.

9. **`resources/views/emails/super_admin_generated.blade.php`**<br />
    The view file that will be used for rendering the E-Mail Content of Generating the Super Administrator.

10. **`routes/web.php`**<br />
    The routes that are defined to access the Generating of Super Administrator.

11. **`tests/Feature/Admin/GenerateAdministratorTest.php`**<br />
    The tests that conforms Generating of Super Administrator functions as per expectations.

12. **`app/Http/Kernel.php`**<br />
    Added the file `SuperAdminAlreadyExists` which is a middleware in the application that checks if there already exists the Super Administrator in the application.

13. **`app/Http/Middleware/SuperAdministratorExists.php`**<br />
    The file that checks if there already exists the Super Administrator in the application.

14. **`routes/web.php`**<br />
    The routes that are defined to access the Generating of Super Administrator.

15. **`tests/Feature/Admin/SuperAdminAlreadyExistsTest.php`**<br />
    The tests that conforms that Super Administrator Already Exists in the application.

15. **`tests/TestCase.php`**<br />
    Generating the Super Administrator for testing purposes

------

#### Log In and Log Out Super Administrator (S. A.):

Following files were made / changed:

1. **`app/Http/Controllers/Admin/DashboardController.php`**<br />
    Controller that handles the logic of displaying the Admin Dashboard and logging out the administrator.

2. **`app/Http/Controllers/Admin/LoginController.php`**<br />
    Controller that handles the logic of displaying the Admin login form and logging in the administrator on submitting the login credentials.

    Uses trait: app\Utilities\ProcessLoginCredentials

3. **`app/Http/Kernel.php`**<br />
    Registering the middleware.

4. **`app/Http/Middleware/SuperAdminAlreadyLoggedIn.php`**<br />
    Logic to validate whether the super administrator is already logged in.

5. **`app/Utilities/ProcessLoginCredentials.php`**<br />
    A trait used to check the logic of validation of logging in the user.

6. **`resources/views/admin/dashboard.blade.php`**<br />
    View file that displays the dashboard of the Super Administrator.

7. **`resources/views/admin/login.blade.php`**<br />
    View file that displays the login form of Super Administrator.

8. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    View file that displays the navigation on multiple files

9. **`routes/web.php`**<br />
    The routes that are defined to access the logging in and logging out the Super Administrator.

10. **`tests/Feature/Admin/AlreadyLoggedInTest.php`**<br />
    Test that proves and conforms that Super Administrator is already logged in.

11. **`tests/Feature/Admin/LoginTest.php`**<br />
    Test that proves and conforms that Super Administrator may log in.
