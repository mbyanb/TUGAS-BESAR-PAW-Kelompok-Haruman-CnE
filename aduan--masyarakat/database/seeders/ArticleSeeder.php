<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use App\Models\Report;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        
        if ($admin) {
            $sampleArticles = [
                [
                    'title' => 'Perbaikan Jalan Merdeka Telah Dimulai',
                    'content' => 'Tim perbaikan jalan telah memulai pekerjaan perbaikan di Jl. Merdeka. Diperkirakan selesai dalam 2 minggu. Masyarakat diharapkan bersabar dan menggunakan jalur alternatif selama proses perbaikan berlangsung.',
                    'category' => 'update',
                ],
                [
                    'title' => 'Cara Melaporkan Masalah Lingkungan',
                    'content' => 'Panduan lengkap untuk melaporkan masalah lingkungan melalui SiADU dengan efektif. Pastikan Anda menyertakan foto, lokasi yang jelas, dan deskripsi detail masalah yang dihadapi.',
                    'category' => 'edukasi',
                ],
                [
                    'title' => 'Pengumuman Jadwal Pengangkutan Sampah',
                    'content' => 'Jadwal pengangkutan sampah untuk wilayah Jakarta Timur telah diperbarui. Silakan cek jadwal terbaru dan pastikan sampah dikeluarkan sesuai waktu yang ditentukan.',
                    'category' => 'pengumuman',
                ],
                [
                    'title' => 'Tips Melaporkan Keluhan Pelayanan Publik',
                    'content' => 'Beberapa tips untuk melaporkan keluhan pelayanan publik agar lebih efektif: 1) Sertakan bukti yang jelas, 2) Tulis kronologi lengkap, 3) Cantumkan nama petugas jika ada.',
                    'category' => 'edukasi',
                ],
            ];

            foreach ($sampleArticles as $articleData) {
                Article::updateOrCreate(
                    [
                        'title' => $articleData['title'],
                        'user_id' => $admin->id
                    ],
                    array_merge($articleData, [
                        'user_id' => $admin->id,
                        'views' => rand(50, 500)
                    ])
                );
            }
        }
    }
}
