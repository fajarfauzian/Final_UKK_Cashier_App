<?php

namespace App\Http\Middleware; // Menempatkan kelas ini di namespace Middleware dalam aplikasi Laravel

use Closure; // Mengimpor kelas Closure untuk menangani fungsi anonim yang digunakan dalam middleware pipeline
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani data yang dikirim melalui HTTP request
use Illuminate\Support\Facades\Auth; // Mengimpor facade Auth untuk mengakses fungsi autentikasi seperti memeriksa status login

class Authenticate // Nama kelas middleware, biasanya sesuai dengan nama file (Authenticate.php)
{
    // Method ini adalah inti dari middleware, menangani logika autentikasi sebelum request diteruskan ke tujuan
    // Parameter $request adalah instance dari Illuminate\Http\Request, berisi detail request saat ini
    // Parameter $next adalah Closure yang mewakili langkah berikutnya dalam middleware pipeline atau controller
    public function handle(Request $request, Closure $next)
    {
        // Memeriksa apakah pengguna saat ini sudah login
        // 'Auth::check()' mengembalikan true jika ada pengguna yang terautentikasi, false jika tidak
        // '!' membalikkan logika, sehingga blok ini dijalankan jika pengguna BELUM login
        if (!Auth::check()) {
            // Jika pengguna belum login, arahkan ke halaman login
            // 'redirect('/login')' membuat respons redirect ke rute atau path '/login'
            // 'withErrors' menyimpan pesan error ke session dalam format array asosiatif
            // Pesan ini bisa diakses di view login untuk ditampilkan kepada pengguna
            return redirect('/login')->withErrors(['access' => 'You must be logged in to access this page.']);
        }

        // Jika pengguna sudah login, lanjutkan ke langkah berikutnya dalam pipeline
        // '$next($request)' memanggil middleware berikutnya atau controller tujuan, meneruskan $request
        // Ini memungkinkan request melanjutkan perjalanan ke rute yang diminta
        return $next($request);
    }
}