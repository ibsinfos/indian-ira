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

----

#### Products:

Following files were created / changed:

1. **`app/Category.php`**<br />
    Modified to add the relationship with the Product.

2. **`app/Http/Controllers/Admin/Products/ProductsController.php`**<br />
    Controller that handles the logic of displaying the products along with adding / updating / deleting / destroying.

3. **`app/Product.php`**<br />
    The model for accessing the Products data.

4. **`database/factories/UserFactory.php`**<br />
    Modified to generate the `Product` fake data.

5. **`database/migrations/2018_06_21_101435_create_products_table.php`**<br />
    The table structure where the prodcts data will be stored.

6. **`database/migrations/2018_06_23_112113_create_category_product_table.php`**<br />
    The table structure where the prodcts and categories relationship data will be stored.

7. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    Modified to add the link for the section `Products`.

8. **`resources/views/admin/products/_detailed_information.blade.php`**<br />
    Displaying the form to edit the detailed information of the product.<br />
    Includes description, additional notes and terms.

9. **`resources/views/admin/products/_editing_links.blade.php`**<br />
    Displaying the links that are related to the product for editing purpose.

10. **`resources/views/admin/products/_general.blade.php`**<br />
    Displaying the form to edit the general details of the product.<br />
    Includes categories updation as well.

11. **`resources/views/admin/products/_image.blade.php`**<br />
    Displaying the form to edit the image of the product.<br />

12. **`resources/views/admin/products/_meta_information.blade.php`**<br />
    Displaying the form to edit the meta information of the product.

13. **`resources/views/admin/products/addProduct.blade.php`**<br />
    Displaying the form in a modal window to add a new Product.

14. **`resources/views/admin/products/edit.blade.php`**<br />
    Acting as a master file to display all the edit forms of the product.

15. **`resources/views/admin/products/index.blade.php`**<br />
    Displays all the products on the page including the temporarily deleted products.

16. **`resources/views/admin/products/table.blade.php`**<br />
    Displays all the products in the tabular format.

17. **`routes/web.php`**<br />
    The routes that are defined to to access the `Products` section.

18. **`tests/Feature/Admin/Products/ProductsTest.php`**<br />
    Tests that conforms and validates that the `Products` feature is working as per expectations.

19. **`tests/Feature/Admin/Products/ProductUpdateDetailedInformationTest.php`**<br />
    Tests that conforms and validates that the `Products` feature - updating the detailed information is working as per expectations.

20. **`tests/Feature/Admin/Products/ProductUpdateGeneralDetailsTest.php`**<br />
    Tests that conforms and validates that the `Products` feature - updating the general details is working as per expectations.

21. **`tests/Feature/Admin/Products/ProductUpdateImageTest.php`**<br />
    Tests that conforms and validates that the `Products` feature - updating the image is working as per expectations.

22. **`tests/Feature/Admin/Products/ProductUpdateMetaInformationTest.php`**<br />
    Tests that conforms and validates that the `Products` feature - updating the meta details is working as per expectations.


----

#### Products - Prices and Options:

The prices and options related to a particular product are shown in this section.

Following files were created / changed:

1. **`app/Http/Controllers/Admin/Products/PriceAndOptionsController.php`**<br />
    Controller that handles the logic of displaying the prices and options along with adding / updating / deleting / destroying.

2. **`app/Product.php`**<br />
    Modified to add the relationship between Product and ProductPriceAndOption,<br />
    also the logic of displaying the cart image.

3. **`app/ProductPriceAndOption.php`**<br />
    The model for accessing the `Products Prices and Options` data.

4. **`database/factories/UserFactory.php`**<br />
    Modified to generate the `Products Prices and Options` fake data.

5. **`database/migrations/2018_06_25_065622_create_product_price_and_options_table.php`**<br />
    The table structure where the prodcts prices and options data will be stored.

6. **`resources/views/admin/products-price-and-options/addPriceAndOption.blade.php`**<br />
    Displaying the form to add the prices and/or options in a modal window.

7. **`resources/views/admin/products-price-and-options/editPriceAndOption.blade.php`**<br />
    Displaying the form to edit the prices and/or options in a modal window.

8. **`resources/views/admin/products-price-and-options/index.blade.php`**<br />
    Displays all the prices and/or options related to the product.

9. **`resources/views/admin/products-price-and-options/table.blade.php`**<br />
    Displays all the prices and options in the tabular format, related to the product.

10. **`resources/views/admin/products/_image.blade.php`**<br />
    Modified to add the note of image replacement.<br />

11. **`resources/views/admin/products/table.blade.php`**<br />
    Modified to add a link to add / update the prices and options related to the product.

12. **`routes/web.php`**<br />
    The routes that are defined to to access the `Products Prices and Options` section.

13. **`tests/Feature/Admin/Products/ProductPriceAndOptionsTest.php`**<br />
    Tests that conforms and validates that the `Products Prices and Options` feature is working as per expectations.

----

#### Users - Authentication

The authentication of the user. Registration, Log In and Forgot Password.

Following files were created / changed:

1. **`app/ForgotPassword.php`**<br />
   The model for accessing the `Forgot Password` data.

2. **`app/Http/Controllers/Users/ConfirmRegistrationController.php`**<br />
   Controller that handles the logic of confirming a user's registration.<br />
   Sends a mail on successfully confirmed.

3. **`app/Http/Controllers/Users/DashboardController.php`**<br />
   Controller that handles the logic of displaying the dashboard to the authenticated user.

4. **`app/Http/Controllers/Users/ForgotPasswordController.php`**<br />
   Controller that handles the logic of displaying the form to send the email to the user.<br />
   Sends an E-Mail containing reset token to the given user email address if at all it already exists.

5. **`app/Http/Controllers/Users/LoginController.php`**<br />
   Controller that handles the logic displaying the login form and logging in.

6. **`app/Http/Controllers/Users/RegisterController.php`**<br />
   Controller that handles the logic displaying the register form and registering the user.

7. **`app/Http/Controllers/Users/ResetPasswordController.php`**<br />
   Controller that handles the logic displaying the reset password form and changing the password on submit.

8. **`app/Http/Kernel.php`**<br />
   Modified to include the middleware `UserIsAlreadyLoggedIn`.

9. **`app/Http/Middleware/UserIsAlreadyLoggedIn.php`**<br />
   Middleware to handle the logic of validating that a user is a guest or already logged in.

10. **`app/Mail/Users/ConfirmRegistration.php`**<br />
   Mail that handles the logic of building the confirm registration view.<br />
   Shot when a user clicks on the submit button after getting registered.

11. **`app/Mail/Users/RegistrationSuccessful.php`**<br />
   Mail that handles the logic of building the registration is successful view.<br />
   Shot when a user clicks the link provided in the `ConfirmRegistration` mail.

12. **`app/Mail/Users/ResetPassword.php`**<br />
   Mail that handles the logic of building the forgot password view that inludes the token.<br />
   Shot when a user submits their registered mail id.

13. **`app/User.php`**<br />
   Modified to check that the user is verfied or not.

14. **`database/factories/UserFactory.php`**<br />
   Modified to create te fake / dummy data for `User` and `Forgot Password`.

15. **`database/migrations/2014_10_12_000000_create_users_table.php`**<br />
   Modified to inlude the verification data and contact number.

15. **`database/migrations/2018_06_27_050526_create_forgot_passwords_table.php`**<br />
   The table structure of `Forgot Password` data.

16. **`resources/views/emails/users/confirm_registration.blade.php`**<br />
   The email view that is sent using the logic from `ConfirmRegistration` Mail.

17. **`resources/views/emails/users/forgot_password.blade.php`**<br />
   The email view that is sent using the logic from `ResetPassword` Mail.

18. **`resources/views/emails/users/registration_successful.blade.php`**<br />
   The email view that is sent using the logic from `RegistrationSuccessful` Mail.

19. **`resources/views/users/confirm_registration.blade.php`**<br />
   The view that displays the Confirm Registration on the screen and not the E-Mail.

19. **`resources/views/users/dashboard.blade.php`**<br />
   The view that displays the dashboard to the currently authenticated user.

20. **`resources/views/users/forgot_password.blade.php`**<br />
   The view that displays the form to submit the E-Mail address for resetting the password.

21. **`resources/views/users/login.blade.php`**<br />
   The view that displays the login form to the user.

22. **`resources/views/users/_layout.blade.php`**<br />
   The master file that will be used by the application except the admin section.

23. **`resources/views/users/register.blade.php`**<br />
   The view that displays the registration form to the user.

24. **`resources/views/users/reset_password.blade.php`**<br />
   The view that displays the form to submit New Password for the user.
   On successfully resetting the password, deletes the `ForgotPassword` record.

25. **`routes/web.php`**<br />
   The routes that are defined to access the entire authentication of the user.

26. **`tests/Feature/Users/Authentication/ConfirmRegistrationTest.php`**<br />
   Tests that conforms and validates that the `Confirm Registration` feature is working as per expectations.

27. **`tests/Feature/Users/Authentication/DashboardTest.php`**<br />
   Tests that conforms and validates that the `Dashboard` feature is working as per expectations.

28. **`tests/Feature/Users/Authentication/ForgotPasswordTest.php`**<br />
   Tests that conforms and validates that the `Forgot Password` feature is working as per expectations.

29. **`tests/Feature/Users/Authentication/LoginTest.php`**<br />
   Tests that conforms and validates that the `User Login` feature is working as per expectations.

30. **`tests/Feature/Users/Authentication/RegistrationTest.php`**<br />
   Tests that conforms and validates that the `User Registration` feature is working as per expectations.

31. **`tests/Feature/Users/Authentication/ResetPasswordTest.php`**<br />
   Tests that conforms and validates that the `User Reset Password` feature is working as per expectations.

32. **`tests/Unit/UserTest.php`**<br />
   Single User Tests that conforms and validates that the `User` feature is working as per expectations.

----

#### Products - Import and Export

The (import and export) or (upload and download) via Excel.

Following files were created / changed:

1. **`app/Http/Controllers/Admin/Products/ExportController.php`**<br />
    The controller that handles the logic of Exporting / Downloading the `Products` details.

2. **`app/Http/Controllers/Admin/Products/ImportController.php`**<br />
    The controller that handles the logic of Importing / Uploading the `Products` details.

3. **`resources/views/admin/products/importProduct.blade.php`**<br />
    The view modal file that displays the form to upload the excel file of products data.

4. **`resources/views/admin/products/index.blade.php`**<br />
    Modified to include the `importProduct` modal.

5. **`routes/web.php`**<br />
    The routes that are defined to access the importing and exporting of products data.

----

#### Products - Tags

The tags feature for the product at the time of updating and at Import / Export.

Following files were created / changed:

1. **`app/Http/Controllers/Admin/Products/ExportController.php`**<br />
    Add the tags data.

2. **`app/Http/Controllers/Admin/Products/ImportController.php`**<br />
    Modified to add the tags and attach the same to the product.

3. **`app/Http/Controllers/Admin/Products/ProductsController.php`**<br />
    Modified to add the tags in General Section of the product editing.

4. **`app/Product.php`**<br />
    Modified to add the relationship between `Product` and `Tag`.

5. **`app/Tag.php`**<br />
    Modified to add the relationship between `Product` and `Tag`.

6. **`database/migrations/2018_06_28_074706_create_product_tag_table.php`**<br />
    The table structure to add / update the mapping of product and tag.

7. **`resources/views/admin/products/_general.blade.php`**<br />
    Modified to include the tags feature in the product.

8. **`resources/views/admin/products/edit.blade.php`**<br />
      Modified to update the viewing of default tags attached to the product.

9. **`resources/views/admin/products/index.blade.php`**<br />
      Modified to notify the user.

10. **`tests/Feature/Admin/Products/ProductUpdateGeneralDetailsTest.php`**<br />
    Modified to add the test of tags associating to the product feature.


----

#### User - Billing Address

The billing address is required for us to know where to send the generated invoice and the product(s) when you place an order of purchasing.

Following files were created / changed:

1. **`app/Http/Controllers/Users/BillingAddressController.php`**<br />
    Controller that handles the logic of displaying the billing address data in the form and updating the same.

2. **`app/User.php`**<br />
    Modified to add the relationship between `User` and `User Billing Address`.

3. **`app/UserBillingAddress.php`**<br />
    Modified to add the relationship between `User` and `User Billing Address`.

4. **`database/migrations/2018_06_28_121411_create_user_billing_addresses_table.php`**<br />
    The table structure for storing the billing address of the user.

5. **`resources/views/users/billing_address.blade.php`**<br />
    Displays the billing address details in the form.

6. **`resources/views/users/partials/_navigation.blade.php`**<br />
    Modified to add the link for `Billing Address`.

7. **`routes/web.php`**<br />
    Modified to add the routes that access the Billing Address section of the user.

8. **`tests/Feature/Users/BillingAddressTest.php`**<br />
    Tests that conforms to the feature of `Billing Address` is working as per expectations.

----

#### User - General Settings

Following files were created / changed:

1. **`app/Http/Controllers/Users/Settings/GeneralSettingsController.php`**<br />
    Controller that handles the logic of displaying the general details data in the form and updating the same.

2. **`resources/views/users/partials/_navigation.blade.php`**<br />
    Modified to add the link for `Settings - General`.

3. **`resources/views/users/settings/general.blade.php`**<br />
    Displays the general user details in the form to update.

4. **`routes/web.php`**<br />
    Modified to add the routes that access the `Settings - General` section of the user.

5. **`tests/Feature/Users/Settings/GeneralSettingsTest.php`**<br />
    Tests that conforms to the feature of `Settings - General` is working as per expectations.

----

#### User - Change Password

Following files were created / changed:

1. **`app/Http/Controllers/Users/Settings/ChangePasswordController.php`**<br />
    Controller that handles the logic of updating the password and displaying the form.

2. **`resources/views/users/partials/_navigation.blade.php`**<br />
    Modified to add the link for `Settings - Change Password`.

3. **`resources/views/users/settings/change_password.blade.php`**<br />
    Displays the change password form.

4. **`routes/web.php`**<br />
    Modified to add the routes that access the `Settings - Change Password` section of the user.

5. **`tests/Feature/Users/Settings/ChangePasswordTest.php`**<br />
    Tests that conforms to the feature of `Settings - Change Password` is working as per expectations.

-----

#### Coupons:

The coupons will be used by the buyers to add the discount in the cart which will reduce the cart total payable amount after deducting the coupon discount.

Following files were created / changed:

1. **`app/Coupon.php`**<br />
    The model for accessing the Coupons data.

2. **`app/Http/Controllers/Admin/CouponsController.php`**<br />
    Controller that handles the logic of displaying the coupons along with adding / updating / deleting / destroying coupons.

3. **`database/factories/UserFactory.php`**<br />
    Modified to generate the `Coupon` fake data.

4. **`database/migrations/2018_06_30_060124_create_coupons_table.php`**<br />
    The table structure where the coupons data will be stored.

5. **`resources/views/admin/coupons/addCoupon.blade.php`**<br />
    Displaying the form to add New Coupon.

6. **`resources/views/admin/coupons/editCoupon.blade.php`**<br />
    Displaying the pre-populated form to update an existing Coupon.

7. **`resources/views/admin/coupons/index.blade.php`**<br />
    Displaying the index of tags. Super admin can add, update, temporarily delete, permanently destroy, download / export, upload / import the category.

8. **`resources/views/admin/coupons/table.blade.php`**<br />
    Displaying all the coupons (including temporarily deleted) in tabular format.

9. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    Modified to add the link for the section `Coupons`.

10. **`routes/web.php`**<br />
    The routes that are defined to to access the `Coupons` section.

11. **`tests/Feature/Admin/CouponsTest.php`**<br />
    Tests that conforms and validates that the `Coupons` feature is working as per expectations.

----


#### Carousels:

The carousels are a special way of saying that these products are of best quality. A section on a page which acts as a Merry-Go Round.

Following files were created / changed:

1. **`app/Carousel.php`**<br />
    The model for accessing the Carousel data.

2. **`app/Http/Controllers/Admin/CarouselsController.php`**<br />
    Controller that handles the logic of displaying the carousels along with adding / updating / deleting / destroying carousels.

3. **`app/Product.php`**<br />
    Modified to add the relationship with Carousel.

4. **`database/factories/UserFactory.php`**<br />
    Modified to generate the `Carousel` fake data.

5. **`database/migrations/2018_07_02_062228_create_carousels_table.php`**<br />
    The table structure where the carousels data will be stored.

6. **`database/migrations/2018_07_02_064511_create_carousel_product_table.php`**<br />
    The table structure where the carousels and the products mapping will be stored.

7. **`resources/views/admin/carousels/addCarousel.blade.php`**<br />
    Displaying the form to add New Carousel in a modal window.

8. **`resources/views/admin/carousels/editCarousel.blade.php`**<br />
    Displaying in the modal window, the pre-populated form to update an existing Carousel.

9. **`resources/views/admin/carousels/index.blade.php`**<br />
    Displaying the index of carousels. Super admin can add, update, temporarily delete, permanently destroy the carousels.

10. **`resources/views/admin/carousels/table.blade.php`**<br />
    Displaying all the carousels (including temporarily deleted) in tabular format.

11. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    Modified to add the link for the section `Carousel`.

12. **`routes/web.php`**<br />
    The routes that are defined to to access the `Carousel` section.

13. **`tests/Feature/Admin/CarouselsTest.php`**<br />
    Tests that conforms and validates that the `Carousel` feature is working as per expectations.

----

#### Super Administrator - User:

The list of all users who have registered. If permanently deleting a user, their billing address will also get deleted.

Following files were created / changed:

1. **`app/Http/Controllers/Admin/Users/UsersController.php`**<br />
    Controller that handles the logic of displaying the users along with adding / updating / deleting / destroying user.

2. **`database/factories/UserFactory.php`**<br />
    Modified to generate the `User Billing Address` fake data.

3. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    Modified to add the link for the section `Users`.

4. **`resources/views/admin/users/_billing.blade.php`**<br />
    Displays the form to update the `Billing Address` of the user.

5. **`resources/views/admin/users/_editing_links.blade.php`**<br />
    Displays the links to `General Details`, `Billing Address` and `Change Password`.

6. **`resources/views/admin/users/_general.blade.php`**<br />
    Displays the form to update the `General Details` of the user.

7. **`resources/views/admin/users/_password.blade.php`**<br />
    Displays the form to update the `Password` of the user.

8. **`resources/views/admin/users/edit.blade.php`**<br />
    The master file for editing the user getails.

9. **`resources/views/admin/users/index.blade.php`**<br />
    Displays the list of all the registered users.

10. **`resources/views/admin/users/index.blade.php`**<br />
    The table that displays the user data in tabular format.

11. **`routes/web.php`**<br />
    Modified to add the routes that access the `User` section.

12. **`tests/Feature/Admin/Users/UpdateBillingAddressTest.php`**<br />
    Tests that conforms and validates that the `User - Billing Address` feature is working as per expectations.

13. **`tests/Feature/Admin/Users/UpdateGeneralDetailsTest.php`**<br />
    Tests that conforms and validates that the `User - General Details` feature is working as per expectations.

14. **`tests/Feature/Admin/Users/UsersTest.php`**<br />
    Tests that conforms and validates that the `Users` feature is working as per expectations.

15. **`tests/Feature/Admin/Users/VerifiesUserTest.php`**<br />
    Tests that conforms and validates that the `Users - Verification` feature is working as per expectations.

----

#### Home Page

Contains Carousels of products which are `Enabled` from various categories.

Following files were created / changed:

1. **`app/Http/Controllers/HomeController.php`**<br />
    Controller that handles the logic of displaying the home and the carousels.

2. **`app/Product.php`**<br />
    Modified to add the default `Not Available` Image in different image dimensions.

3. **`resources/views/partials/_carousels.blade.php`**<br />
    Displays all the carousels that have been `Enabled` along with the products.

4. **`resources/views/partials/_slider.blade.php`**<br />
    Displays the full width slider (responsive).

5. **`resources/views/welcome.blade.php`**<br />
    Modified to remove the slider and carousel into separate files altogether.

6. **`routes/web.php`**<br />
    Modified to include the `HomeController` instead of Closure.

----

#### Categories - On Navigation Menu

Feature of displaying the categor(y/ies) in the navigation menu.

Following files were created / changed:

1. **`app/Category.php`**<br />
    Modified to add the `display_in_menu` field.

2. **`app/Http/Controllers/Admin/Categories/CategoriesController.php`**<br />
    Modified to include the validation for `display_in_menu` field.

3. **`database/factories/UserFactory.php`**<br />
    Modified to add the `display_in_menu` field while generating dummy data.

4. **`database/migrations/2018_06_20_071934_create_categories_table.php`**<br />
    Modified to include the column `display_in_menu` in the table.

5. **`database/seeds/DatabaseSeeder.php`**<br />
    Modified to create the dummy data on executing `php artisan migrate:refresh --seed`.

6. **`resources/views/admin/categories/addCategory.blade.php`**<br />
    Modified to add `display_in_menu` field.

7. **`resources/views/admin/categories/editCategory.blade.php`**<br />
    Modified to add `display_in_menu` field.

8. **`resources/views/admin/categories/index.blade.php`**<br />
    Modified to add `display_in_menu` field.

9. **`resources/views/admin/categories/table.blade.php`**<br />
    Modified to view the `display_in_menu` value.

10. **`tests/Feature/Admin/CategoriesTest.php`**<br />
    Modified to test the functionality of `display_in_menu` field.

11. **`tests/Feature/Unit/CategoryTest.php`**<br />
    Modified to test the functionality of `display_in_menu` field.

----

#### Cart - Back end

Products quantity added / updated / deleted in the cart. Only the backend, no front end code added.

Following files were created / changed:

1. **`app/Http/Controllers/CartController.php`**<br />
    Controller that handles the processing of request of adding / updating / deleting / emptying the product quantity in the cart.

2. **`app/Utilities/Cart.php`**<br />
    The file that handles the logic of adding / updating / deleting / emptying the product quantity in the cart.

3. **`routes/web.php`**<br />
    The routes that access the functionality of the cart.

4. **`tests/Feature/CartTest.php`**<br />
    Tests that conforms and validates that the `Cart` feature is working as per expectations.

----

#### Cart - Everything

The entire process of adding products in to the cart from the application.

Following files were created / changed:

1. **`app/Http/Controllers/Cart/CartController.php`**<br />
    Moved and modified the controller that handles the processing of request of adding / updating / deleting / emptying the product quantity in the cart.

2. **`app/Http/Controllers/Cart/CouponsController.php`**<br />
    Controller that handles the logic of applying and removing the coupon in the cart.

3. **`app/Utilities/Cart.php`**<br />
    Modified to add the amounts totals.

4. **`resources/views/cart/index.blade.php`**<br />
    The index page of the cart.

5. **`resources/views/cart/table.blade.php`**<br />
    The table of the products that are added in the cart by the user.

6. **`resources/views/partials/_carousels.blade.php`**<br />
    Modified to add the link of adding the product into the cart.

7. **`resources/views/welcome.blade.php`**<br />
    Modified to add the jQuery ajax functionality of adding the product into the cart.

8. **`routes/web.php`**<br />
    Modified to access the routes of cart functionality from the application.

9. **`tests/Feature/CartTest.php`**<br />
    Tests that conforms and validates that the `Cart` feature is working as per expectations.

----

#### Checkout - Authentication

The authentication process at the time of checkout. The single page checkout is incomplete at the time of this commit.

Following files were created / changed:

1. **`app/Http/Controllers/Checkout/CheckoutController.php`**<br />
    Controller that handles the logic of displaying the authentication page which includes registration and login section.

2. **`app/Http/Controllers/Checkout/LoginController.php`**<br />
    Controller that handles the logic of processing the login credentials.

3. **`app/Http/Controllers/Checkout/RegisterController.php`**<br />
    Controller that handles the logic of processing the registration data and registering the user.

4. **`resources/views/cart/index.blade.php`**<br />
    Modified to add the checkout link.

5. **`resources/views/checkout/_login.blade.php`**<br />
    View partial file that displays the login form for the user.

6. **`resources/views/checkout/_register.blade.php`**<br />
    View partial file that displays the register form for the user.

7. **`resources/views/checkout/authentication.blade.php`**<br />
    The file that displays both login and registration forms to the user.

8. **`resources/views/checkout/single_page.blade.php`**<br />
    Single page checkout form.

9. **`routes/web.php`**<br />
    Modified to add the checkout routes.

10. **`tests/Feature/Checkout/CheckoutTest.php`**<br />
    Tests that conforms and validates that `Checkout` feature is working as per expectations.

11. **`tests/Feature/Checkout/LoginTest.php`**<br />
    Tests that conforms and validates that `Checkout - Login` feature is working as per expectations.

11. **`tests/Feature/Checkout/RegisterTest.php`**<br />
    Tests that conforms and validates that `Checkout - Register` feature is working as per expectations.

----

#### Shipping Rate - Locations

Added the `location_type` and `location_name` fields.

Following files were created / changed:

1. **`app/Http/Controllers/Admin/ShippingRatesController.php`**<br />
    Modified to add the validation on the `location_type` and `location_name` field.

2. **`app/ShippingRate.php`**<br />
    Modified to add the columns in `$fillable` property.

3. **`database/factories/UserFactory.php`**<br />
    Modified to add the fake data for the `location_type` and `location_name` fields.

4. **`database/migrations/2018_06_18_033825_create_shipping_rates_table.php`**<br />
    Modified to include the `location_type` and `location_name` in table structure.

5. **`resources/views/admin/shipping-rates/addShippingRate.blade.php`**<br />
    Modified to include the fields `location_type` and `location_name`.

6. **`resources/views/admin/shipping-rates/editShippingRate.blade.php`**<br />
    Modified to include the fields `location_type` and `location_name`.

7. **`resources/views/admin/shipping-rates/index.blade.php`**<br />
    Modified so that the `location_type` and `location_name` fields are pre-populated while editing shipping rates.

8. **`tests/Feature/Admin/ShippingCompanyAndRatesTest.php`**<br />
    Tests that conforms and validates that `Shipping Rate - Location` feature works as per expectations.

----

#### Cart - Shipping Rate Calculation

Calculating the shipping amount in the cart.

Following files were created / changed:

1. **`app/Http/Controllers/Cart/CartController.php`**<br />
    Modified to add the logic of `Shipping Rate` calculation.

2. **`app/Utilities/Cart.php`**<br />
    Modified to add the logic of selecting the correct `Shipping Rate` based on the location chosen.

3. **`resources/views/cart/index.blade.php`**<br />
    Modified to add the modal view file.

4. **`resources/views/cart/selectLocationModal.blade.php`**<br />
    The view file that displays the form to select the location where the products shall be shipped which calculates the shipping amount based on the selected location.

5. **`resources/views/cart/table.blade.php`**<br />
    Modified to add the button link for opening the `selectLocationModal` window.

6. **`routes/web.php`**<br />
    Modified to access the route for calculation of shipping amount.

----

#### Footer

The footer that will be used by the application

Following files were created / changed:

1. **`resources/views/partials/footer.blade.php`**<br />
    The footer view of the application.

2. **`resources/views/partials/_layout.blade.php`**<br />
    The master layout file wherein the footer file is integrated.

----

#### Categories in Menu

The super parent categories that will be seen in the navigation menu.

Following files were created / changed:

1. **`app/Category.php`**<br />
    Modified to include the `pageUrl()` method.

2. **`app/Providers/AppServiceProvider.php`**<br />
    Modified to include the categories result at every request.

3. **`app/Utilities/PaginateCollection.php`**<br />
    Added the trait to paginate the collection results.

4. **`resources/views/partials/_layout.blade.php`**<br />
    Modified to make the categories link dropdown - the javascript behaviour.

5. **`resources/views/partials/_navigation.blade.php`**<br />
    Modified to make the categories link dropdown.

----

#### Categories Page

Displaying of all the products that are available in the category.

Following files were created / changed:

1. **`app/Category.php`**<br />
    Modified to include only the `Enabled` products.

2. **`app/Http/Controllers/CategoriesController.php`**<br />
    Controller that handles the logic of displaying the products in the given category.

3. **`app/Product.php`**<br />
    Modified to include only the `Enabled` products.

4. **`routes/web.php`**<br />
    The routes that access the categories page.

5. **`tests/Feature/CategoriesPageTest.php`**<br />
    Tests that conform and validates that the categories pages are functioning as per expectations.

----

#### Products Page

Displaying of products on the product page.

Following files were created / changed:

1. **`app/Http/Controllers/ProductsController.php`**<br />
    Controller that handles the logic of displaying the products in the given category.

2. **`app/Product.php`**<br />
    Modified to include the `pageUrl()` and `canonicalPageUrl()` methods.

3. **`app/ProductPriceAndOption.php`**<br />
    Modified to include the `zoomedImage()` method.

4. **`resources/views/products/_detailed_info.blade.php`**<br />
    The view file that displays only the `description`, `additional_notes` and `terms` data of the product.

5. **`resources/views/products/_options_setting.blade.php`**<br />
    The view file that displays the `options` data of the product.

6. **`resources/views/products/show.blade.php`**<br />
    The view file that displays the `product` the product itself.

7. **`routes/web.php`**<br />
    The routes that are required to access the product page.

8. **`tests/Feature/CategoriesPageTest.php`**<br />
    Modified to include the tests for the products page from the category section.<br />
    The tests that conforms and validates that the `Product` page is working as per expectations.

----

#### Offline Order Placing

The entire process of placing an offline order.

Following files were created / changed:

1. **`app/Http/Controllers/Checkout/OfflineController.php`**<br />
    The controller that handles the logic of placing an Offline Order.

2. **`app/Mail/OrderPlaced.php`**<br />
    The mail that will be sent to buyer.

3. **`app/Mail/OrderReceived.php`**<br />
    The mail that will be sent to the super administrator.

4. **`app/Order.php`**<br />
    The model for accessing the Orders data.

5. **`app/OrderAddress.php`**<br />
    The model for accessing the Order Address data.

6. **`app/Utilities/Cart.php`**<br />
    Modified to include the methods `gstAmount()` and `netAmount()`.

7. **`database/migrations/2018_07_14_061048_create_orders_table.php`**<br />
    The table structure for orders.

8. **`database/migrations/2018_07_14_071053_create_order_addresses_table.php`**<br />
    The table structure for orders address.

9. **`resources/views/checkout/_shipping.blade.php`**<br />
    Modified to change the maxlength from 100 to 50

10. **`resources/views/checkout/single_page.blade.php`**<br />
    Modified to include the link for placing of offline order.

11. **`resources/views/emails/orders_placed.blade.php`**<br />
    The view file that will be used for rendering the email sent to the buyer.

12. **`resources/views/emails/orders_received.blade.php`**<br />
    The view file that will be used for rendering the email sent to the super administrator.

13. **`resources/views/orders/placed_offline_success.blade.php`**<br />
    The thank you page displayed after making an offline order.

14. **`routes/web.php`**<br />
    The routes that are required to access the placing of offline order.

15. **`tests/Feature/PlaceOfflineOrdersTest.php`**<br />
    Modified to include the tests for making an offline order.<br />
    The tests that conforms and validates that the `Placement of Offline Order` is working as per expectations.

----

#### COD Order Placing

The entire process of placing an offline order.

Following files were created / changed:

1. **`app/Http/Controllers/Checkout/CodController.php`**<br />
    The controller that handles the logic of placing an COD Order.

2. **`resources/views/orders/placed_cod_success.blade.php`**<br />
    The thank you page displayed after making an cod order.

3. **`routes/web.php`**<br />
    The routes that are required to access the placing of cod order.

4. **`tests/Feature/PlaceCodOrdersTest.php`**<br />
    The tests that conforms and validates that the `Placement of COD Order` is working as per expectations.

----

#### Admin - Order Viewing

The viewing of order by the super administrator.

Following files were created / changed:

1. **`app/Http/Controllers/Admin/OrdersController.php`**<br />
    The controller that handles the logic of viewing the order details for the given order code.

2. **`app/Order.php`**<br />
    Modified to add the relationship with `Order Address` and `User`.

3. **`app/OrderAddress.php`**<br />
    Modified to add the relationship with `Order`.

4. **`app/User.php`**<br />
    Modified to add the relationship with `Order`.

5. **`database/factories/UserFactory.php`**<br />
    Modified to generate the dummy data for the `Order`.

6. **`resources/views/admin/orders/_address_table.blade.php`**<br />
    The view file that displays the address details of that particular order.

7. **`resources/views/admin/orders/_products_table.blade.php`**<br />
    The view file that displays the products details of that particular order.

8. **`resources/views/admin/orders/index.blade.php`**<br />
    The view file that displays all the orders grouped by order code.

9. **`resources/views/admin/orders/_show_address.blade.php`**<br />
    The view file that displays the address in tabular format.

10. **`resources/views/admin/orders/_show_products.blade.php`**<br />
    The view file that displays the products in tabular format.

11. **`resources/views/admin/orders/table.blade.php`**<br />
    The view file that displays the `order` details in tabular format.

12. **`resources/views/admin/partials/_navigation.blade.php`**<br />
    Modified to add the link for `Order`.

13. **`routes/web.php`**<br />
    Modified to add the access routes for `Order` section in admin.

14. **`tests/Feature/Admin/Orders/OrdersTest.php`**<br />
    Tests that conforms and validates that accessing the `Order` in admin is working as per expectations.

----

#### Order History

The order history that will be updated by the super administrator. Default will be Processing.

Following files were created / changed:

1. **`app/Http/Controllers/Checkout/CodController.php`**<br />
    Modified to add the `Order History` when the `Order` is made.

2. **`app/Http/Controllers/Checkout/OfflineController.php`**<br />
    Modified to add the `Order History` when the `Order` is made.

3. **`app/OrderHistory.php`**<br />
    Model that is required for accessing the `Order History` data.

4. **`app/Utilities/Cart.php`**<br />
    Modified to add chosen `Shipping Rate` in session.

5. **`database/migrations/2018_07_17_054452_create_order_histories_table.php`**<br />
    The table structure for storing the order's history.

6. **`tests/Feature/Checkout/PlaceCodOrdersTest.php`**<br />
    Modified to test that conforms and validates the `Order History` is working as per expectations.

7. **`tests/Feature/Checkout/PlaceOfflineOrdersTest.php`**<br />
    Modified to test that conforms and validates the `Order History` is working as per expectations.

----

#### Admin - Order History

The order history that will be updated by the super administrator.

Following files were created / changed:

1. **`app/Http/Controllers/Admin/OrdersController.php`**<br />
    Modified to add the logic of updating the order history and displaying the same.

2. **`app/Order.php`**<br />
    Modified to add the relationship with `Order History`.

3. **`app/OrderHistory.php`**<br />
    Modified to add the relationship with `Order`.

4. **`database/factories/UserFactory.php`**<br />
    Modified to generate the dummy data for `Order History`.

5. **`resources/views/admin/orders/_history_table.blade.php`**<br />
    The view file that displays the history in tabular format.

6. **`resources/views/admin/orders/addOrderHistoryModal.blade.php`**<br />
    The modal window that displays the form to add `Order History`.

7. **`resources/views/admin/orders/show_address.blade.php`**<br />
    Modified to add the link of `Order History`.

8. **`resources/views/admin/orders/show_history.blade.php`**<br />
    The view file for displaying the history for the given order.

9. **`resources/views/admin/orders/show_products.blade.php`**<br />
    Modified to add the link of `Order History`.

10. **`routes/web.php`**<br />
    Modified to add the link of `Order History`.

11. **`tests/Feature/Admin/Orders/OrdersTest.php`**<br />
    Modified to tests that conforms and validates the `Order History` in admin section is working as per expectations.

----

#### Search Products.

Following files were created / changed:

1. **`app/Http/Controllers/SearchProductsController.php`**<br />
    The controller that handles the logic of searching the products.

2. **`resources/views/partials/_layout.blade.php`**<br />
    Modified to add the ajax functionality for searching of products.

3. **`routes/web.php`**<br />
    Modified to add the routes for searching of products.

----

#### Users - Orders

The orders that will be viewed by the user themselves.

Following files were created / changed:

1. **`app/Http/Controllers/Users/OrdersController.php`**<br />
    Modified to add the logic of updating the order history and displaying the same.

2. **`resources/views/users/orders/_history_table.blade.php`**<br />
    The view file that displays the history in tabular format.

3. **`resources/views/users/orders/_address_table.blade.php`**<br />
    The view that will display the address in tabular format.

4. **`resources/views/users/orders/_history_table.blade.php`**<br />
    The view that will display the order history in tabular format.

5. **`resources/views/users/orders/_products_table.blade.php`**<br />
    The view that will display the products details in tabular format.

6. **`resources/views/users/orders/index.blade.php`**<br />
    The view that will display all orders placed by the user.

7. **`resources/views/users/orders/show_address.blade.php`**<br />
    The view file that will display the address.

8. **`resources/views/users/orders/show_history.blade.php`**<br />
    The view file that will display the order history.

9. **`resources/views/users/orders/show_products.blade.php`**<br />
    The view file that will display the products details.

10. **`resources/views/users/orders/table.blade.php`**<br />
    The view file that will display all the orders in tabular format.

11. **`resources/views/users/partials/_navigation.blade.php`**<br />
    Modified to add the link for `Order`.

12. **`routes/web.php`**<br />
    Modified to add the access routes for `Order` placed by the user.

13. **`tests/Feature/Users/Orders/OrdersTest.php`**<br />
    Tests that conforms and validates that the `Order` section for the user works as per expectations.

----

#### Product multiple images - Backend

The products' images that will be shown on the products page only which shall change on changing the options.

Following files were created / changed:

1. **`app/Http/Controllers/Admin/Products/PriceAndOptionsController.php`**<br />
    Added the validation and logic for adding multiple product images.

2. **`app/ProductPriceAndOption.php`**<br />
    Modified to add the columns in for multiple product images.

3. **`database/factories/UserFactory.php`**<br />
    Modified to include the multiple product images default values.

4. **`database/migrations/2018_06_25_065622_create_product_price_and_options_table.php`**<br />
    Modified to add the columns of multiple product images.

5. **`resources/views/admin/products-price-and-options/addPriceAndOption.blade.php`**<br />
    Modified to upload the file via form while adding new price and option.

6. **`resources/views/admin/products-price-and-options/editPriceAndOption.blade.php`**<br />
    Modified to upload the file via form while editing new price and option.

7. **`tests/Feature/Admin/Products/OptionGalleryImagesTest.php`**<br />
    Tests that conforms and validates that the `Product multiple images` section works as per expectations.

----

#### Product Sorting - General - Backend

The sorting of products based on the number provided.

Following files were created / changed:

1. **`app/Http/Controllers/Admin/Products/ProductsController.php`**<br />
    Added the validation for `sort_number`.

2. **`app/Product.php`**<br />
    Modified to add the column `sort_number`.

3. **`database/factories/UserFactory.php`**<br />
    Modified to include the `sort_number`.

4. **`database/migrations/2018_06_21_101435_create_products_table.php`**<br />
    Modified to add the column `sort_number`.

5. **`resources/views/admin/products-price-and-options/addPriceAndOption.blade.php`**<br />
    Modified to upload the file via form while adding new price and option.

6. **`resources/views/admin/products/_general.blade.php`**<br />
    Modified to include the field `sort_number`.

7. **`tests/Feature/Admin/Products/ProductUpdateGeneralDetailsTest.php`**<br />
    Tests that conforms and validates that the `Product sort_number` works as per expectations.

----

#### Product Sorting - Price and Options - Backend

The sorting of products' price and options based on the number provided.

Following files were created / changed:

1. **`app/Http/Controllers/Admin/Products/ProductPriceAndOptionsTest.php`**<br />
    Modified to qdd the validation for `sort_number`.

2. **`app/ProductPriceAndOption.php`**<br />
    Modified to add the column `sort_number`.

3. **`database/factories/UserFactory.php`**<br />
    Modified to include the `sort_number`.

4. **`database/migrations/2018_06_25_065622_create_product_price_and_options_table.php`**<br />
    Modified to add the column `sort_number`.

5. **`resources/views/admin/products-price-and-options/addPriceAndOption.blade.php`**<br />
    Modified to add the form field `sort_number` while adding new price and option.

6. **`resources/views/admin/products-price-and-options/editPriceAndOption.blade.php`**<br />
    Modified to add the form field `sort_number` while editing existing price and option.

7. **`resources/views/admin/products-price-and-options/index.blade.php`**<br />
    Modified to include the field `sort_number` in the `editPriceAndOption` modal.

8. **`tests/Feature/Admin/Products/ProductPriceAndOptionsTest.php`**<br />
    Tests that conforms and validates that the `Product Price and Options sort_number` works as per expectations.

----

#### Product Sorting - Price and Options - Front End

The sorting of products based on the sort number provided. Sorting is done in Ascending order.

Following files were created / changed:

1. **`app/Http/Controllers/CategoriesController.php.php`**<br />
    Modified to add the logic of displaying the products even when parent category is selected.

2. **`resources/views/categories/_sub_categories_link.blade.php`**<br />
    Added the nesting of sub categories.

3. **`resources/views/categories/show.blade.php`**<br />
    Modified to display the products and it's options based on the `sort_number` in ascending order.
