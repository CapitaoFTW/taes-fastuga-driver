<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UsersSeeder extends Seeder
{
    private $photoPath = 'public/fotos';
    private $numberOfUsers = 200;
    private $files_M = [];
    private $files_F = [];
    static public $allUsers = [];
    public static $used_emails = [];

    public function run()
    {
        if (DatabaseSeeder::$seedType == "full") {
            $this->numberOfUsers = 1500;
        }
        $this->command->table(['Users table seeder notice'], [
            ['Photos will be stored on path ' . storage_path('app/' . $this->photoPath)]
        ]);

        $this->limparFicheirosFotos();
        $this->preencherNomesFicheirosFotos();

        $faker = \Faker\Factory::create('pt_PT');

        $totalUsersOfType = $this->numberOfUsers;

        for ($i = 1; $i <= $totalUsersOfType; $i++) {
            $userNumber = $i;
            $userRow = $this->newFakerUser($faker, $userNumber);
            $userInfo = $this->insertUser($faker, $userRow);
            $this->updateFoto($userInfo);
        }
    }

    private function limparFicheirosFotos()
    {
        Storage::deleteDirectory($this->photoPath);
        Storage::makeDirectory($this->photoPath);
    }

    private function preencherNomesFicheirosFotos()
    {
        $allFiles = collect(File::files(database_path('seeders/profile_photos')));
        foreach ($allFiles as $f) {
            if (strpos($f->getPathname(), 'M_')) {
                $this->files_M[] = $f->getPathname();
            } else {
                $this->files_F[] = $f->getPathname();
            }
        }
    }

    private function newFakerUser($faker, $userByNumber = 0)
    {
        $fullname = "";
        $email = "";
        $gender = "";
        static::randomName($faker, $gender, $fullname, $email);

        $createdAt = $faker->dateTimeBetween('-10 years', '-3 months');
        $email_verified_at = $faker->dateTimeBetween($createdAt, '-2 months');
        $updatedAt = $faker->dateTimeBetween($email_verified_at, '-1 months');

        return [
            'name' => $fullname,
            'email' =>  $email,
            'email_verified_at' => $email_verified_at,
            'password' => bcrypt('123'),
            'remember_token' => $faker->asciify('**********'), //str_random(10),
            'phone_number' => $faker->mobileNumber(),
            'license_plate' => strtoupper($faker->unique()->bothify('??-##-??')),
            'nif' => $faker->randomNumber($nbDigits = 9, $strict = true),
            'balance' => 0,
            'photo_url' => null,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'deleted_at' => null,
            'blocked' => false,
            'gender' => $gender,
        ];
    }

    private function insertUser($faker, $user)
    {
        $userInfo = new \ArrayObject($user);
        $gender = $user['gender'];
        unset($user['gender']);
        $newId = DB::table('users')->insertGetId($user);
        $userInfo['id'] = $newId;
        $userInfo['gender'] = $gender;

        UsersSeeder::$allUsers[$newId] = $userInfo;

        return $userInfo;
    }

    private function gravarFoto($id, $file)
    {
        $targetDir = storage_path('app/' . $this->photoPath);
        //$sourceDir = database_path('seeds/fotos');
        $newfilename = $id . "_" . uniqid() . '.jpg';
        File::copy($file, $targetDir . '/' . $newfilename);
        DB::table('users')->where('id', $id)->update(['photo_url' => $newfilename]);
        $this->command->info("Updated Photo of User $id. File $file copied as $newfilename");
    }

    private function updateFoto($userInfo)
    {
        $fileName = null;
        if ($userInfo['gender'] == 'M') {
            if (count($this->files_M)) {
                $fileName = array_shift($this->files_M);
            }
        } else {
            if (count($this->files_F)) {
                $fileName = array_shift($this->files_F);
            }
        }
        if ($fileName) {
            $this->gravarFoto($userInfo['id'], $fileName);
        }
        return $fileName;
    }

    private static function stripAccents($stripAccents)
    {
        $from = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ';
        $to =   'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY';
        $keys = array();
        $values = array();
        preg_match_all('/./u', $from, $keys);
        preg_match_all('/./u', $to, $values);
        $mapping = array_combine($keys[0], $values[0]);
        return strtr($stripAccents, $mapping);
    }

    public static function randomName($faker, &$gender, &$fullname, &$email)
    {
        $gender = $faker->randomElement(['male', 'female']);
        $firstname = $faker->firstName($gender);
        $lastname = $faker->lastName();
        $secondname = $faker->numberBetween(1, 3) == 2 ? "" : " " . $faker->firstName($gender);
        $number_middlenames = $faker->numberBetween(1, 6);
        $number_middlenames = $number_middlenames == 1 ? 0 : ($number_middlenames >= 5 ? $number_middlenames - 3 : 1);
        $middlenames = "";
        for ($i = 0; $i < $number_middlenames; $i++) {
            $middlenames .= " " . $faker->lastName();
        }
        $fullname = $firstname . $secondname . $middlenames . " " . $lastname;
        $email = strtolower(UsersSeeder::stripAccents($firstname) . "." . UsersSeeder::stripAccents($lastname) . "@mail.pt");
        $i = 2;
        while (in_array($email, UsersSeeder::$used_emails)) {
            $email = strtolower(UsersSeeder::stripAccents($firstname) . "." . UsersSeeder::stripAccents($lastname) . "." . $i . "@mail.pt");
            $i++;
        }
        UsersSeeder::$used_emails[] = $email;
        $gender = $gender == 'male' ? 'M' : 'F';
    }
}
