<?php

namespace Database\Seeders;

use App\Models\CateringOrder;
use App\Models\CateringPackage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CateringOrderSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil atau buat paket catering
        $package = CateringPackage::first();
        if (!$package) {
            $package = CateringPackage::create([
                'name'          => 'Paket Prasmanan Lengkap',
                'description'   => 'Paket catering prasmanan lengkap dengan 5 menu utama',
                'price_per_pax' => 35000,
                'min_pax'       => 50,
                'max_pax'       => 500,
                'is_active'     => true,
            ]);
        }

        // Ambil user non-admin
        $user = User::where('role', '!=', 'admin')->first();
        if (!$user) {
            $user = User::first();
        }

        $namaAcara = [
            'Pernikahan Budi & Ani',
            'Syukuran Rumah Baru',
            'Ulang Tahun Perusahaan',
            'Arisan Keluarga Besar',
            'Rapat Dinas Kecamatan',
            'Khitanan Anak',
            'Reuni Angkatan 2010',
            'Launching Produk Baru',
            'Gathering Karyawan',
            'Acara Amal Yayasan',
        ];

        $tanggalHari = [1, 4, 7, 10, 13, 16, 19, 22, 25, 28];
        $paxValues   = [50, 75, 100, 60, 120, 80, 150, 90, 200, 70];
        $totalValues = [1750000, 2625000, 3500000, 2100000, 4200000, 2800000, 5250000, 3150000, 7000000, 2450000];

        $bulanIni = Carbon::now();

        foreach ($tanggalHari as $i => $tgl) {
            $createdAt = $bulanIni->copy()->day($tgl)->setTime(rand(8, 16), rand(0, 59));

            // Jangan buat pesanan di masa depan
            if ($createdAt->greaterThan(now())) {
                continue;
            }

            CateringOrder::create([
                'user_id'              => $user->id,
                'catering_package_id'  => $package->id,
                'nama_acara'           => $namaAcara[$i],
                'tanggal_acara'        => $createdAt->copy()->addDays(rand(3, 14)),
                'jumlah_pax'           => $paxValues[$i],
                'lokasi_acara'         => 'Jl. Contoh No. ' . ($i + 1) . ', Kota Bandung',
                'detail_lokasi_acara'  => 'Gedung Serbaguna Lt. ' . rand(1, 3),
                'jam_acara'            => sprintf('%02d:00', rand(9, 14)),
                'jam_pengantaran'      => sprintf('%02d:00', rand(7, 12)),
                'nama_pemesan'         => $user->name,
                'no_telepon'           => '08' . rand(1000000000, 9999999999),
                'total'                => $totalValues[$i],
                'status'               => 'selesai',
                'paid_at'              => $createdAt->copy()->addHours(1),
                'payment_type'         => 'bank_transfer',
                'created_at'           => $createdAt,
                'updated_at'           => $createdAt,
            ]);
        }
    }
}
