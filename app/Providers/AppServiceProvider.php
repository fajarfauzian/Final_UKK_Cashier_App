<?php

namespace App\Providers; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa kelas ini berada di folder App/Providers

use Illuminate\Support\ServiceProvider; // Mengimpor kelas ServiceProvider dari Laravel sebagai dasar untuk penyedia layanan

class AppServiceProvider extends ServiceProvider // Mendefinisikan kelas AppServiceProvider yang mewarisi ServiceProvider
{
    /**
     * Register any application services.
     * 
     * Method ini digunakan untuk mendaftarkan layanan atau binding ke dalam container aplikasi Laravel.
     * Layanan yang didaftarkan di sini akan tersedia di seluruh aplikasi.
     */
    public function register(): void
    {
        // Mengikat konfigurasi khusus untuk 'dompdf.options' ke dalam container Laravel
        // Ini memungkinkan Anda mengakses opsi Dompdf yang sudah dikonfigurasi di mana saja dalam aplikasi
        $this->app->bind('dompdf.options', function () {
            // Mengembalikan instance baru dari Dompdf\Options dengan pengaturan kustom
            return new \Dompdf\Options([
                'isHtml5ParserEnabled' => true, // Mengaktifkan parser HTML5 untuk mendukung tag HTML modern
                'isRemoteEnabled' => true, // Mengizinkan Dompdf mengakses sumber daya eksternal (misalnya gambar dari URL)
                'defaultFont' => 'sans-serif' // Menetapkan font default untuk dokumen PDF yang dihasilkan
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     * 
     * Method ini digunakan untuk melakukan konfigurasi atau inisialisasi layanan setelah semua layanan didaftarkan.
     * Biasanya digunakan untuk hal-hal seperti event listener atau konfigurasi tambahan.
     */
    public function boot(): void
    {
        // Saat ini kosong, tetapi bisa diisi dengan logika bootstrap jika diperlukan
        //
    }
}