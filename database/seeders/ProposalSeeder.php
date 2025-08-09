<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proposal;
use App\Models\TeamMember;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProposalSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->error("Seeder gagal: Tidak ada user di database.");
            return;
        }

        // Pastikan folder storage untuk attachment ada
        Storage::disk('public')->makeDirectory('attachments');

        // Generate 10 proposal dummy
        for ($i = 1; $i <= 10; $i++) {
            $user = $users->random();

            $proposal = Proposal::create([
                'id'          => Str::uuid(),
                'user_id'     => $user->id,
                'title'       => "Proposal Project $i",
                'description' => "Deskripsi lengkap untuk Proposal Project $i.",
                'submitted_at'=> now()->subDays(rand(1, 30)),
                'is_approved' => rand(0, 1),
                'meta_data'   => json_encode([
                    'budget'   => rand(50_000_000, 200_000_000),
                    'category' => fake()->randomElement(['IT', 'Construction', 'Research', 'Marketing']),
                    'priority' => fake()->randomElement(['High', 'Medium', 'Low']),
                ]),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // Tambahkan anggota tim (2-5 orang)
            $teamUsers = $users->random(rand(2, 3));
            foreach ($teamUsers as $teamUser) {
                TeamMember::create([
                    'id'          => Str::uuid(),
                    'name'        => $teamUser->name,
                    'position'    => fake()->jobTitle(),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }

            // Tambahkan attachment (1-3 file PDF dummy)
            for ($k = 1; $k <= rand(1, 3); $k++) {
                $fileName = 'proposal_' . $i . '_file_' . $k . '.pdf';
                $filePath = 'attachments/' . $fileName;

                // Buat file PDF dummy (isi teks biasa)
                Storage::disk('public')->put($filePath, "%PDF-1.4\n% Dummy PDF file for testing");

                Attachment::create([
                    'id'          => Str::uuid(),
                    'proposal_id' => $proposal->id,
                    'file_name'   => $fileName,
                    'file_path'   => $filePath,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        $this->command->info('✅ ProposalSeeder berhasil menambahkan 10 proposal lengkap dengan anggota tim & attachment.');
    }
}