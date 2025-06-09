<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    // Nama tabel
    protected $table = 'users';

    // kolom CRUD
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'password',
        'role',
    ];

    // kolom database => kolom tampilan tabel
    public static function Labelling()
    {
        return [
            'firstname' => 'Nama Awal',
            'lastname' => 'Nama Akhir',
            'username' => 'Username',
            'password' => 'Password',
            'role' => 'Pengguna'
        ];
    }

     // Peraturan kolom database => peraturannya
    public static $rules = [
        'firstname' => 'required|max:20',
        'lastname' => 'required|max:20',
        'username' => 'required|min:8|unique:users,username',
        'password' => 'required',
    ];

    // jika melanggar aturan => menampilkan pesan
    public static $messages = [
        'firstname.required' => 'Nama Awal Wajib Diisi!',
        'firstname.max' => 'Nama Awal Maksimal 20 Karakter!',

        'lastname.required' => 'Nama Akhir Wajib Diisi!',
        'lastname.max' => 'Nama Akhir Maksimal 20 Karakter!',

        'username.required' => 'Username Wajib Diisi!',
        'username.min' => 'Username Minimal 8 Karakter!',
        'username.unique' => 'Tolong Gunakan Username Lain!',

        'password.required' => 'password wajib diisi!',
    ];







     /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
