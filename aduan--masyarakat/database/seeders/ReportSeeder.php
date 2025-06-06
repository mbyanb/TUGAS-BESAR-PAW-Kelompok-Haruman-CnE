<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        
        if ($users->count() > 0) {
            $sampleReports = [
                [
                    'title' => 'Jalan Rusak di Jl. Merdeka',
                    'description' => 'Jalan berlubang besar yang membahayakan pengendara. Sudah beberapa minggu tidak diperbaiki dan semakin parah.',
                    'category' => 'infrastruktur',
                    'location' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                    'status' => 'pending',
                ],
                [
                    'title' => 'Sampah Menumpuk di TPS Kelurahan',
                    'description' => 'Sampah tidak diangkut selama 3 hari berturut-turut. Menimbulkan bau tidak sedap dan mengundang lalat.',
                    'category' => 'lingkungan',
                    'location' => 'TPS Kelurahan Sukamaju, Jakarta Timur',
                    'status' => 'in-progress',
                ],
                [
                    'title' => 'Lampu Jalan Mati',
                    'description' => 'Lampu penerangan jalan sudah mati sejak seminggu yang lalu. Membuat jalan gelap dan tidak aman di malam hari.',
                    'category' => 'infrastruktur',
                    'location' => 'Jl. Sudirman Raya, Jakarta Selatan',
                    'status' => 'resolved',
                ],
                [
                    'title' => 'Pelayanan KTP Lambat',
                    'description' => 'Proses pembuatan KTP memakan waktu lebih dari 2 minggu. Petugas kurang responsif.',
                    'category' => 'pelayanan',
                    'location' => 'Kantor Kelurahan Menteng, Jakarta Pusat',
                    'status' => 'pending',
                ],
                [
                    'title' => 'Banjir di Perumahan',
                    'description' => 'Setiap hujan deras, perumahan selalu banjir karena drainase yang buruk.',
                    'category' => 'lingkungan',
                    'location' => 'Perumahan Griya Asri, Jakarta Barat',
                    'status' => 'in-progress',
                ],
            ];

            foreach ($sampleReports as $index => $reportData) {
                $user = $users[$index % $users->count()];
                
                Report::updateOrCreate(
                    [
                        'title' => $reportData['title'],
                        'user_id' => $user->id
                    ],
                    array_merge($reportData, ['user_id' => $user->id])
                );
            }
        }
    }
}
