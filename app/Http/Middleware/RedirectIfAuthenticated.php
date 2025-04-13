<?php

namespace App\Http\Middleware; // Menempatkan kelas ini di namespace Middleware dalam aplikasi Laravel

use Closure; // Mengimpor kelas Closure untuk menangani fungsi anonim yang digunakan dalam middleware pipeline
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani data yang dikirim melalui HTTP request
use Illuminate\Support\Facades\Auth; // Mengimpor facade Auth untuk mengakses fungsi autentikasi seperti memeriksa status login

class RedirectIfAuthenticated // Nama kelas middleware, sesuai dengan fungsinya untuk mengarahkan pengguna yang sudah login
{
    /**
     * Handle an incoming request.
     *
     * Method ini menangani logika untuk memeriksa apakah pengguna sudah terautentikasi,
     * dan jika ya, mengarahkan mereka ke halaman lain (misalnya dashboard).
     *
     * @param  \Illuminate\Http\Request  $request - Instance request yang masuk
     * @param  \Closure  $next - Fungsi untuk melanjutkan ke langkah berikutnya dalam pipeline
     * @param  string|null  ...$guards - Parameter variadic untuk mendukung multiple authentication guards
     * @return mixed - Mengembalikan redirect atau melanjutkan request
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Memastikan $guards memiliki nilai default jika kosong
        // Jika tidak ada guard yang diberikan sebagai argumen, gunakan [null] sebagai default
        // Guard null berarti menggunakan guard autentikasi default (biasanya 'web' di Laravel)
        $guards = empty($guards) ? [null] : $guards;

        // Iterasi melalui setiap guard yang diberikan (bisa satu atau lebih)
        // Guard adalah mekanisme Laravel untuk mendukung autentikasi berbeda (misalnya 'web', 'api')
        foreach ($guards as $guard) {
            // Memeriksa apakah pengguna sudah terautentikasi untuk guard tertentu
            // 'Auth::guard($guard)' mengembalikan instance guard spesifik
            // 'check()' mengembalikan true jika ada pengguna yang terautentikasi pada guard tersebut
            if (Auth::guard($guard)->check()) {
                // Jika pengguna sudah login, arahkan ke '/dashboard'
                // 'redirect('/dashboard')' membuat respons redirect ke rute atau path '/dashboard'
                // Biasanya digunakan untuk mencegah pengguna yang sudah login mengakses halaman seperti login atau register
                return redirect('/dashboard');
            }
        }

        // Jika pengguna belum terautentikasi pada salah satu guard yang diperiksa,
        // lanjutkan ke langkah berikutnya dalam middleware pipeline atau ke controller tujuan
        // '$next($request)' memanggil middleware berikutnya atau rute yang diminta
        return $next($request);
    }
}