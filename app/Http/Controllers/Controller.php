<?php

namespace App\Http\Controllers;

// Mengimpor trait AuthorizesRequests untuk menangani otorisasi (izin akses)
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Mengimpor trait ValidatesRequests untuk menangani validasi input
use Illuminate\Foundation\Validation\ValidatesRequests;

// Mengimpor kelas BaseController sebagai dasar untuk semua controller di Laravel
use Illuminate\Routing\Controller as BaseController;

// Mendefinisikan kelas abstrak Controller yang akan menjadi kelas dasar untuk controller lain
// 'abstract' berarti kelas ini tidak bisa diinstansiasi langsung, hanya bisa diwarisi
abstract class Controller extends BaseController
{
    // Menggunakan trait AuthorizesRequests dan ValidatesRequests
    // Trait ini menambahkan fungsionalitas otorisasi dan validasi ke kelas ini
    use AuthorizesRequests, ValidatesRequests;
}