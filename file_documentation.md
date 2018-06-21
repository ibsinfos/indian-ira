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

------

#### Global Settings - Bank Details

The bank details is required for the buyers to make payment while checking out if they choose the 'Offline' payment option as their preferred payment type. The buyer will make payment to the mentioned bank details.

Following files were made / changed:

1. **`app/GlobalSettingBankDetail.php`**<br />
    The model for accessing the bank data.

2. **`app/Http/Controllers/Admin/GlobalSettings/BankDetailsController.php`**<br />
    Controller that handles the logic of displaying the the bank details to the Super Administrator and displaying the same in the form, if they want to update it.

3. **`database/factories/UserFactory.php`**<br />
    Modified to generate the `GlobalSettingBankDetail` fake data.

5. **`database/migrations/2018_06_09_051026_create_global_setting_bank_details_table.php`**<br />
    The table structure where the bank's data will be stored.

6. **`resources/views/admin/global-settings/bank-details.blade.php`**<br />
    The view file that displays the bank details in the form, if the Super Administrator wants to update.

7. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    Modified to add the link for the section `Global Settings > Bank Details`.

7. **`routes/web.php`**<br />
    The routes that are defined to to access the `Global Settings > Bank Details` section.

8. **`tests/Feature/Admin/GlobalSettings/BankDetailsTest.php`**<br />
    Tests that conforms and validates that the `Global Settings > Bank Details` feature is working as per expectations.

9. **`tests/TestCase.php`**<br />
    Modified to add method `signInSuperAdministrator`.

-----

#### Global Settings - Payment Options:

Payment Options will help the buyer to choose their preferred payment type while checking out.

Following files were created / changed:

1. **`app/GlobalSettingPaymentOption.php`**<br />
    The model for accessing the payment options data.

2. **`app/Http/Controllers/Admin/GlobalSettings/PaymentOptionsController.php`**<br />
    Controller that handles the logic of provisioning the payment options and displaying the same, if the Super Administrator wants to update it.

3. **`database/factories/UserFactory.php`**<br />
    Modified to generate the `GlobalSettingPaymentOption` fake data.

4. **`database/migrations/2018_06_11_044957_create_global_setting_payment_options_table.php`**<br />
    The table structure where the payment options data will be stored.

5. **`resources/views/admin/global-settings/payment-options.blade.php`**<br />
    The view file that displays the bank details in the form, if the Super Administrator wants to update.

6. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    Modified to add the link for the section `Global Settings > Payment Options`.

7. **`routes/web.php`**<br />
    The routes that are defined to to access the `Global Settings > Bank Details` section.

8. **`tests/Feature/Admin/GlobalSettings/PaymentOptionsTest.php`**<br />
    Tests that conforms and validates that the `Global Settings > Payment Options` feature is working as per expectations.

-----

#### Global Settings - COD Charges:

COD Charges amount will get added in the cart if the buyer chooses COD as their preferred payment option while checking out.

Following files were created / changed:

1. **`app/GlobalSettingCodCharge.php`**<br />
    The model for accessing the cod charges data.

2. **`app/Http/Controllers/Admin/GlobalSettings/CodChargesController.php`**<br />
    Controller that handles the logic of provisioning the cod charges and displaying the same, if the Super Administrator wants to update it.

3. **`database/factories/UserFactory.php`**<br />
    Modified to generate the `GlobalSettingCodCharge` fake data.

4. **`database/migrations/2018_06_11_044957_create_global_setting_cod_charges_table.php`**<br />
    The table structure where the cod charges data will be stored.

5. **`resources/views/admin/global-settings/payment-options.blade.php`**<br />
    The view file that displays the cod charges in the form, if the Super Administrator wants to update.

6. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    Modified to add the link for the section `Global Settings > COD Charges`.

7. **`routes/web.php`**<br />
    The routes that are defined to to access the `Global Settings > COD Charges` section.

8. **`tests/Feature/Admin/GlobalSettings/PaymentOptionsTest.php`**<br />
    Tests that conforms and validates that the `Global Settings > COD Charges` feature is working as per expectations.

----

#### Shipping Rates:

The shipping amount that will get applied based on the cart weight as and when the buyer adds the product(s) into the cart.

Following files were created / changed:

1. **`app/Http/Controllers/Admin/ShippingRatesController.php`**<br />
    Controller that handles the logic of displaying the shipping rates along with adding / updating / deleting / destroying shipping rates.

2. **`app/ShippingRate.php`**<br />
    The model for accessing the Shipping Rates data.

3. **`database/factories/UserFactory.php`**<br />
    Modified to generate the `ShippingRate` fake data.

4. **`database/migrations/2018_06_18_033825_create_shipping_rates_table.php`**<br />
    The table structure where the shipping rates data will be stored.

5. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    Modified to add the link for the section `Shipping Rates`.

6. **`resources/views/admin/shipping-rates/addShippingRate.blade.php`**<br />
    Displaying the form to add New Shipping Rate.

7. **`resources/views/admin/shipping-rates/editShippingRate.blade.php`**<br />
    Displaying the pre-populated form to update an existing Shipping Rate.

8. **`resources/views/admin/shipping-rates/index.blade.php`**<br />
    Displaying the index of shipping rates. Super admin can add, update, temporarily delete, permanently destroy the shipping rate.

9. **`resources/views/admin/shipping-rates/table.blade.php`**<br />
    Displaying all the shipping rates (including temporarily deleted) in tabular format.

10. **`routes/web.php`**<br />
    The routes that are defined to to access the `Shipping Rates` section.

8. **`tests/Feature/Admin/ShippingCompanyAndRatesTest.php`**<br />
    Tests that conforms and validates that the `Shipping Rates` feature is working as per expectations.

----

#### Tags:

The tags help to search and/or sort the products via a tag. For instance, if the product is in Apparels category and it has red color in it, then tag can be created as `apparels-red` or `red`.

Following files were created / changed:

1. **`app/Http/Controllers/Admin/TagsController.php`**<br />
    Controller that handles the logic of displaying the tags along with adding / updating / deleting / destroying tags.

2. **`app/Tag.php`**<br />
    The model for accessing the Tags data.

3. **`database/factories/UserFactory.php`**<br />
    Modified to generate the `Tag` fake data.

4. **`database/migrations/2018_06_18_084241_create_tags_table.php`**<br />
    The table structure where the tags data will be stored.

5. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    Modified to add the link for the section `Tags`.

6. **`resources/views/admin/shipping-rates/addTag.blade.php`**<br />
    Displaying the form to add New Tag.

7. **`resources/views/admin/shipping-rates/editTag.blade.php`**<br />
    Displaying the pre-populated form to update an existing Tag.

8. **`resources/views/admin/shipping-rates/index.blade.php`**<br />
    Displaying the index of tags. Super admin can add, update, temporarily delete, permanently destroy the tag.

9. **`resources/views/admin/shipping-rates/table.blade.php`**<br />
    Displaying all the tags (including temporarily deleted) in tabular format.

10. **`routes/web.php`**<br />
    The routes that are defined to to access the `Tags` section.

11. **`tests/Feature/Admin/TagsTest.php`**<br />
    Tests that conforms and validates that the `Tags` feature is working as per expectations.


----

#### Categories:

Only 3 levels of category can be added. It should be like `Category > Sub Category > Sub Sub Category`.

Following files were created / changed:

1. **`app/Category.php`**<br />
    The model for accessing the Categories data.

2. **`app/Http/Controllers/Admin/Categories/CategoriesController.php`**<br />
    Controller that handles the logic of displaying the categories along with adding / updating / deleting / destroying categories.

3. **`app/Http/Controllers/Admin/Categories/ExportController.php`**<br />
    Controller that handles the logic of exporting / downloading all the categories (including temporarily deleted).

4. **`app/Http/Controllers/Admin/Categories/ImportController.php`**<br />
    Controller that handles the logic of importing / uploading all the categories (including temporarily deleted).

5. **`database/factories/UserFactory.php`**<br />
    Modified to generate the `Category` fake data.

6. **`database/migrations/2018_06_20_071934_create_categories_table.php`**<br />
    The table structure where the categories data will be stored.

7. **`resources/views/admin/categories/addCategory.blade.php`**<br />
    Displaying the form to add New Category.

8. **`resources/views/admin/categories/editCategory.blade.php`**<br />
    Displaying the pre-populated form to update an existing Category.

9. **`resources/views/admin/categories/importCategory.blade.php`**<br />
    Displaying the form to import / upload New Categories in excel format.

10. **`resources/views/admin/categories/index.blade.php`**<br />
    Displaying the index of tags. Super admin can add, update, temporarily delete, permanently destroy, download / export, upload / import the category.

11. **`resources/views/admin/categories/table.blade.php`**<br />
    Displaying all the categories (including temporarily deleted) in tabular format.

12. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    Modified to add the link for the section `Categories`.

13. **`routes/web.php`**<br />
    The routes that are defined to to access the `Categories` section.

14. **`tests/Feature/Admin/CategoriesTest.php`**<br />
    Tests that conforms and validates that the `Categories` feature is working as per expectations.

15. **`tests/Unit/CategoryTest.php`**<br />
    Unit tests that conforms and validates that the `Categories` feature is working as per expectations.
