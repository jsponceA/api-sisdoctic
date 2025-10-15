<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $correo = fake()->unique()->email;
        // 1️⃣ Descargar imagen aleatoria (desde LoremFlickr o ThisPersonDoesNotExist)
        $url = "https://i.pravatar.cc/250?u=mail@{$correo}";
        $imageContents = Http::get($url)->body(); // Obtener la imagen

        // 2️⃣ Generar un nombre único y guardarlo en `dashboard/usuarios`
        $fileName = Str::random(10) . '.jpg';
        Storage::put("dashboard/usuarios/{$fileName}", $imageContents);

        return [
            'username' => fake()->unique()->userName,
            'password' => bcrypt(123456),
            'nombres' => fake()->firstName,
            'apellidos' => fake()->lastName,
            'correo' => $correo,
            'estado' => 1,
            'foto' => $fileName,
        ];
    }


}
