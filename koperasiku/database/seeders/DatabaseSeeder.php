<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Akun contoh UNTUK LOKAL SAJA. Ganti password sebelum produksi. */
    public function run(): void
    {
        foreach ([
            ['Admin', 'admin@koperasiku.test', 'admin'],
            ['Pengurus', 'pengurus@koperasiku.test', 'pengurus'],
            ['Bendahara', 'bendahara@koperasiku.test', 'bendahara'],
        ] as [$name, $email, $role]) {
            $u = User::firstOrNew(['email' => $email]);
            $u->name = $name;
            $u->password = 'password123'; // di-hash oleh cast 'hashed'
            $u->role = $role;
            $u->is_active = true;
            $u->save();
        }
    }
}
