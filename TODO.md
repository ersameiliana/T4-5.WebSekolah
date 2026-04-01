# TODO: Fix Admin Login by Role

## Approved Plan Steps (No DB changes)
1. [ ] Edit app/Controllers/Auth.php: In admin login case, fetch `jenis_admin` from AdminModel, set session `role_admin` and `nama_admin`, redirect to `/dashboard/admin`.
2. [ ] Add to app/Controllers/Dashboard.php: `admin()` method with role=='admin' check, load 'dashboard/admin' view.
3. [ ] Check/update app/Config/Routes.php: Ensure route for `dashboard/admin` → Dashboard::admin.
4. [ ] Test login for different `jenis_admin` values → correct dashboard menus/data.
5. [ ] Enhance controllers (future): Role-based access (no delete for admin_administrasi).

Progress will be updated after each step.

