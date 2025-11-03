# 🛡️ Standar Keamanan Laravel Berdasarkan OWASP

## 📘 Pendahuluan
OWASP (Open Web Application Security Project) adalah standar global untuk keamanan aplikasi web. Berikut panduan lengkap untuk menerapkan standar OWASP pada aplikasi **Laravel 12+** agar aman terhadap serangan umum seperti SQL Injection, XSS, CSRF, dan lain-lain.

---

## 🧱 OWASP Top 10 (2021–2025)

| Kode | Risiko Utama | Fokus Laravel |
|------|---------------|----------------|
| A01 | Broken Access Control | Middleware, Policy, Gate |
| A02 | Cryptographic Failures | Hash, Encrypt, HTTPS |
| A03 | Injection | Query Builder, Validation |
| A04 | Insecure Design | Architecture Pattern, MVC |
| A05 | Security Misconfiguration | Config Cache, Header, .env |
| A06 | Vulnerable Components | Composer Audit |
| A07 | Identification & Authentication Failures | Auth, Session, Password Hash |
| A08 | Software & Data Integrity Failures | Signed URL, CSRF |
| A09 | Security Logging & Monitoring Failures | Laravel Log, Telescope |
| A10 | Server-Side Request Forgery (SSRF) | HTTP Client Whitelist |

---

## 🔐 Implementasi Standar Laravel + OWASP

### A01. Broken Access Control
Gunakan Middleware dan Policy untuk kontrol akses pengguna.
```php
Route::middleware(['auth', 'can:view,App\Models\Post'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```
Gunakan Policy:
```bash
php artisan make:policy PostPolicy
```

### A02. Cryptographic Failures
Pastikan `APP_KEY` aman dan gunakan enkripsi Laravel.
```php
use Illuminate\Support\Facades\Crypt;

$encrypted = Crypt::encryptString('secret');
$decrypted = Crypt::decryptString($encrypted);
```
Gunakan HTTPS:
```php
if (app()->environment('production')) {
    URL::forceScheme('https');
}
```

### A03. Injection (SQL / XSS)
Gunakan Query Builder & Validasi Input.
```php
public function rules() {
    return ['email' => 'required|email|max:255'];
}
```
Escape output di Blade:
```blade
{{ $user->name }}
```

### A04. Insecure Design
Gunakan rate limit dan role-based access control.
```php
Route::middleware('throttle:10,1')->post('/login');
```

### A05. Security Misconfiguration
Tambahkan Security Header Middleware.
```php
$response->headers->set('X-Frame-Options', 'SAMEORIGIN');
$response->headers->set('X-Content-Type-Options', 'nosniff');
$response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
$response->headers->set('X-XSS-Protection', '1; mode=block');
```

### A06. Vulnerable Components
Gunakan audit untuk mendeteksi library rentan.
```bash
composer audit
npm audit
```

### A07. Identification & Authentication Failures
Gunakan Laravel Breeze / Jetstream dan password hashing.
```php
Hash::make('password123');
```
`.env`:
```env
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
```

### A08. Software & Data Integrity Failures
Gunakan Signed URL dan CSRF protection.
```php
URL::temporarySignedRoute('download', now()->addMinutes(30), ['file' => $id]);
```
Form Blade:
```blade
<form method="POST">@csrf</form>
```

### A09. Security Logging & Monitoring
Gunakan Laravel Log dan Telescope.
```php
Log::warning('Login gagal', ['user' => $request->email]);
```

### A10. SSRF (Server-Side Request Forgery)
Batasi domain yang dapat diakses.
```php
$url = $request->input('url');
if (!Str::startsWith($url, ['https://trusted.com', 'https://api.unilak.ac.id'])) {
    abort(403, 'Untrusted domain');
}
Http::get($url);
```

---

## 🧤 Middleware Keamanan Laravel

Buat middleware `SecurityHeadersMiddleware`:
```php
public function handle($request, Closure $next)
{
    $response = $next($request);
    $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->headers->set('Content-Security-Policy', "default-src 'self' data: https:");
    $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=()');
    return $response;
}
```
Daftarkan di `Kernel.php`:
```php
protected $middleware = [
    \App\Http\Middleware\SecurityHeaders::class,
];
```

---

## ✅ Checklist Laravel OWASP Security Ready

| Area | Status | Implementasi |
|-------|---------|--------------|
| Autentikasi | ✅ | Laravel Breeze / Sanctum |
| Authorization | ✅ | Policy & Gate |
| HTTPS Enforcement | ✅ | `URL::forceScheme('https')` |
| CSRF | ✅ | Laravel default |
| XSS Filter | ✅ | Blade escape |
| SQL Injection | ✅ | Query Builder |
| Rate Limit | ✅ | Middleware `throttle` |
| Security Header | ✅ | Custom middleware |
| Error Handling | ✅ | `APP_DEBUG=false` |
| Logging | ✅ | Laravel Log / Telescope |
| Dependency Audit | ✅ | `composer audit` |

---

## 📜 Penutup
Dengan menerapkan standar OWASP ini, aplikasi Laravel akan memiliki tingkat keamanan tinggi terhadap ancaman umum. Pastikan selalu **update dependency**, **nonaktifkan debug di production**, dan **audit keamanan secara rutin**.
