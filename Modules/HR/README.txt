# HR Module (Skeleton)

- Namespace: `Modules\HR\...`
- Layout: uses `layouts.user_type.auth`
- AJAX-first Employees sub-module.

## Install
1) Copy `Modules/HR` into your project root.
2) Register provider in `config/app.php`:
```php
'providers' => [
    // ...
    Modules\HR\Providers\HRServiceProvider::class,
],
```
3) Run migrations:
```bash
php artisan migrate
```
4) Add sidebar link:
```blade
<li class="nav-item">
  <a class="nav-link" href="{{ route('hr.employees.index') }}">
    <i class="ni ni-single-02"></i> <span class="nav-link-text ms-1">Employees</span>
  </a>
</li>
```
5) Ensure `storage:link` exists for public documents:
```bash
php artisan storage:link
```

## Routes
- `GET /hr` HR dashboard
- `GET /hr/employees` index
- `GET /hr/employees/table` table partial
- `POST /hr/employees` create
- `GET /hr/employees/{employee}` show (JSON)
- `PUT /hr/employees/{employee}` update
- `DELETE /hr/employees/{employee}` delete
- `POST /hr/employees/{employee}/documents` upload
- `DELETE /hr/employees/{employee}/documents/{doc}` delete
- `GET /hr/employees/export/csv` export CSV
