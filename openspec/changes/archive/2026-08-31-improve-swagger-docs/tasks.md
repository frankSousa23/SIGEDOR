## 1. DTO Schemas Definition

- [x] 1.1 Create `app/Virtual/Models/Teacher.php` with `@OA\Schema` defining all attributes of a Teacher and verify the class loads correctly.
- [x] 1.2 Create `app/Virtual/Models/Report.php` with `@OA\Schema` defining all attributes of a Report and verify the class loads correctly.
- [x] 1.3 Create `app/Virtual/Resources/PaginationMeta.php` (or similar component in `Controller.php`) to define Laravel's pagination meta schema and verify syntax.
- [x] 1.4 Create `app/Virtual/Resources/ErrorResponse.php` defining the structure for 404, 422, and 500 errors and verify syntax.

## 2. API Controllers Update

- [x] 2.1 Update `@OA\Get` annotations in `TeacherApiController.php` to use the `Teacher` schema, including pagination, and document 404, 422, 500 error responses, and verify syntax.
- [x] 2.2 Update `@OA\Get` annotations in `ReportApiController.php` to use the `Report` schema, including pagination, and document error responses, and verify syntax.

## 3. Swagger Generation & Validation

- [x] 3.1 Run `php artisan l5-swagger:generate` to regenerate the documentation and verify the command finishes successfully without errors.
- [x] 3.2 Open Swagger UI (or inspect the generated `swagger.json`) to manually verify that Schemas appear correctly and error codes are visible for both endpoints.
