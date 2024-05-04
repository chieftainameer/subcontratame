<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Category;
use App\Models\LegalForm;
use Illuminate\Support\Str;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Admin User Default
        $user = \App\Models\User::where('email', 'admin@admin.com')->first();
        if (!$user) {
            \App\Models\User::create([
                'image' => 'https://picsum.photos/200/300',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'role' => 1,
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'status' => 1,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]);
        }

        

        $autonomousCommunitys = [
            0 => ['name' => 'Andalucia'],
            1 => ['name' => 'Aragón'],
            2 => ['name' => 'Asturias'],
            3 => ['name' => 'Baleares'],
            4 => ['name' => 'Canarias'],
            5 => ['name' => 'Cantabria'],
            6 => ['name' => 'Castilla - La Mancha'],
            7 => ['name' => 'Castilla y León'],
            8 => ['name' => 'Cataluña'],
            9 => ['name' => 'Comunidad Valenciana'],
            10 => ['name' => 'Extremadura'],
            11 => ['name' => 'Galacia'],
            12 => ['name' => 'Madrid'],
            13 => ['name' => 'Murcia'],
            14 => ['name' => 'Navarra'],
            15 => ['name' => 'País Vasco'],
            16 => ['name' => 'La Rioja']
        ];

        foreach ($autonomousCommunitys as $key => $data) {
            \App\Models\AutonomousCommunity::create($data);
        }

        $provinces = [
            // Andalucia
            0 => ['name' => 'Almería', 'autonomous_community_id' => 1],
            1 => ['name' => 'Cádiz', 'autonomous_community_id' => 1],
            2 => ['name' => 'Córdoba', 'autonomous_community_id' => 1],
            3 => ['name' => 'Granada', 'autonomous_community_id' => 1],
            4 => ['name' => 'Vuelva', 'autonomous_community_id' => 1],
            5 => ['name' => 'Jáen', 'autonomous_community_id' => 1],
            6 => ['name' => 'Málaga', 'autonomous_community_id' => 1],
            7 => ['name' => 'Sevilla', 'autonomous_community_id' => 1],
            // Aragón
            8 => ['name' => 'Huesca', 'autonomous_community_id' => 2],
            9 => ['name' => 'Teruel', 'autonomous_community_id' => 2],
            10 => ['name' => 'Zaragoza', 'autonomous_community_id' => 2],
            // Asturias
            11 => ['name' => 'Oviedo', 'autonomous_community_id' => 3],
            // Baleares
            12 => ['name' => 'Palma de Mallorca', 'autonomous_community_id' => 4],
            // Canarias
            13 => ['name' => 'Santa Cruz de Tenerife', 'autonomous_community_id' => 5],
            14 => ['name' => 'Las Palmas de Gran Canaria', 'autonomous_community_id' => 5],
            // Cantabria
            15 => ['name' => 'Santander', 'autonomous_community_id' => 6],
            // Castilla - La Mancha
            16 => ['name' => 'Albacete', 'autonomous_community_id' => 7],
            17 => ['name' => 'Ciudad Real', 'autonomous_community_id' => 7],
            18 => ['name' => 'Cuenca', 'autonomous_community_id' => 7],
            19 => ['name' => 'Guadalajara', 'autonomous_community_id' => 7],
            20 => ['name' => 'Toledo', 'autonomous_community_id' => 7],
            // Castilla y León
            21 => ['name' => 'Ávila', 'autonomous_community_id' => 8],
            22 => ['name' => 'Burgos', 'autonomous_community_id' => 8],
            23 => ['name' => 'León', 'autonomous_community_id' => 8],
            24 => ['name' => 'Salamanca', 'autonomous_community_id' => 8],
            25 => ['name' => 'Segovia', 'autonomous_community_id' => 8],
            26 => ['name' => 'Soria', 'autonomous_community_id' => 8],
            27 => ['name' => 'Valladolid', 'autonomous_community_id' => 8],
            28 => ['name' => 'Zamora', 'autonomous_community_id' => 8],
            // Cataluña
            29 => ['name' => 'Barcelona', 'autonomous_community_id' => 9],
            30 => ['name' => 'Gerona', 'autonomous_community_id' => 9],
            31 => ['name' => 'Lérida', 'autonomous_community_id' => 9],
            32 => ['name' => 'Tarragona', 'autonomous_community_id' => 9],
            // Comunidad Valenciana
            33 => ['name' => 'Alicante', 'autonomous_community_id' => 10],
            34 => ['name' => 'Castellón de la Plana', 'autonomous_community_id' => 10],
            35 => ['name' => 'Valencia', 'autonomous_community_id' => 10],
            // Extremadura
            36 => ['name' => 'Badajoz', 'autonomous_community_id' => 11],
            37 => ['name' => 'Cáceres', 'autonomous_community_id' => 11],
            // Galacia
            38 => ['name' => 'La Coruña', 'autonomous_community_id' => 12],
            39 => ['name' => 'Lugo', 'autonomous_community_id' => 12],
            40 => ['name' => 'Orense', 'autonomous_community_id' => 12],
            41 => ['name' => 'Pontevedra', 'autonomous_community_id' => 12], 
            // Madrid
            42 => ['name' => 'Madrid', 'autonomous_community_id' => 13],
            // Murcia
            43 => ['name' => 'Murcia', 'autonomous_community_id' => 14],
            // Navarra
            44 => ['name' => 'Pamplona', 'autonomous_community_id' => 15],
            // País Vasco
            45 => ['name' => 'Vilbao', 'autonomous_community_id' => 16],
            46 => ['name' => 'San Sebastian', 'autonomous_community_id' => 16],
            47 => ['name' => 'Vitoria', 'autonomous_community_id' => 16],
            // La Rioja
            48 => ['name' => 'Logroño', 'autonomous_community_id' => 17],
        ];

        foreach ($provinces as $key => $province) {
            \App\Models\Province::create($province);
        }
        
        $legal_forms = [
            0 => ['name' => 'Empresario Individual'],
            1 => ['name' => 'Sociedad Anónima'],
            2 => ['name' => 'Sociedad Limitada'],
            3 => ['name' => 'Sociedad Colectiva'],
            4 => ['name' => 'Sociedad Comanditaria'],
            5 => ['name' => 'Comunidad de Bienes'],
            6 => ['name' => 'Cooperativa'],
            7 => ['name' => 'Asociación'],
            8 => ['name' => 'Corporación Local'],
            9 => ['name' => 'Organismo Público'],
            10 => ['name' => 'Organismos de la Administración'],
            11 => ['name' => 'Comunidad de Propietarios'],
            12 => ['name' => 'Sociedad Civil'],
            13 => ['name' => 'Unión Temporal de Empresas'],
            14 => ['name' => 'Otros Tipos No Definidos'],
            15 => ['name' => 'Congregación e Inst. Religiosa'],
            16 => ['name' => 'Entidad Extranjera'],
            17 => ['name' => 'Ent. No Residente en España'],
            18 => ['name' => 'Otro'],
        ];
        foreach ($legal_forms as $key => $legal_form) {
            LegalForm::create($legal_form);
        }

        $categories = [
            0 => ['name' => 'Construcción'],
            1 => ['name' => 'Potable'],
            2 => ['name' => 'Saneamiento'],
        ];
        foreach ($categories as $category) {
          Category::create($category);  
        }

        $payment_methods = [
            0 => ['name' => 'Confirming'],
            1 => ['name' => '50% - 50%'],
        ];
        foreach ($payment_methods as $payment_method) {
          PaymentMethod::create($payment_method);  
        }

        // User Normal #1
        $user = \App\Models\User::create([
            'image' => 'https://picsum.photos/200/300',
            'first_name' => 'Pablo',
            'last_name' => 'Alvarado',
            'role' => 2,
            'email' => 'pablo.alvarado@gmail.com',
            'email_verified_at' => now(),
            'status' => 1,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'cellphone' => '+584246091437',
            'company_name' => 'Empresa Petrolera',
            'tax_residence' => 'Av 70',
            'nif' => 'F565869',
            'legal_form_id' => 3,
            'company_description' => 'Descripción de la empresa',
            'position' => 'CEO',
            'province_id' => 12,
            'key_words' => 'Techo,Piso,Madera'
        ]);
        // Categories to User
        $user->categories()->attach(1);
        $user->categories()->attach(3);

        // Payment Methods to User
        $user->payment_methods()->attach(1);
        $user->payment_methods()->attach(2);

        // User Normal #2
        $user = \App\Models\User::create([
            'image' => 'https://picsum.photos/200/300',
            'first_name' => 'Ernesto',
            'last_name' => 'Avila',
            'role' => 2,
            'email' => 'ernesto.avila@gmail.com',
            'email_verified_at' => now(),
            'status' => 1,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'cellphone' => '+584246091435',
            'company_name' => 'Empresa Constructora',
            'tax_residence' => 'Av 70',
            'nif' => 'F565869',
            'legal_form_id' => 3,
            'company_description' => 'Descripción de la empresa',
            'position' => 'CEO',
            'province_id' => 12,
            'key_words' => 'Techo,Piso,Madera'
        ]);

        // Categories to User
        $user->categories()->attach(1);
        $user->categories()->attach(2);

        // Payment Methods to User
        $user->payment_methods()->attach(1);
        $user->payment_methods()->attach(2);

        // User Normal #3
        $user = \App\Models\User::create([
            'image' => 'https://picsum.photos/200/300',
            'first_name' => 'Sofia',
            'last_name' => 'Vergara',
            'role' => 2,
            'email' => 'sofia.vergara@gmail.com',
            'email_verified_at' => now(),
            'status' => 1,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'cellphone' => '+584246091436',
            'company_name' => 'Empresa de Textil',
            'tax_residence' => 'Av 70',
            'nif' => 'F565869',
            'legal_form_id' => 3,
            'company_description' => 'Descripción de la empresa',
            'position' => 'CEO',
            'province_id' => 12,
            'key_words' => 'Techo,Piso,Textil'
        ]);

        // Categories to User
        $user->categories()->attach(1);
        $user->categories()->attach(3);

        // Payment Methods to User
        $user->payment_methods()->attach(1);
        $user->payment_methods()->attach(2);

        // Settings
        Setting::create([
            'price_departure' => 10,
            'price_variable' => 5
        ]);
     }
}