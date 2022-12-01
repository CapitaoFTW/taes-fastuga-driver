<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    private $numberOfDays = 100;
    private $avgOrdersDay = [260, 50, 65, 65, 120, 170, 200]; // Domingo, Segunda, terça, ...

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DatabaseSeeder::$seedType == "full") {
            $this->numberOfDays = 2 * 365;   // 2 ANOS
        } else {
            $this->numberOfDays = 60;      // 2 meses
        }

        $this->command->info("Order seeder - Start");

        $faker = \Faker\Factory::create('pt_PT');

        $today = Carbon::today();
        $this->start_date = $today->copy();
        $this->start_date->subDays($this->numberOfDays);
        $d = $this->start_date->copy();

        $i = 0;
        while ($d->lessThanOrEqualTo($today)) {
            if ($i % 20 == 0) { /// 20 em 20 dias escreve no log
                $this->command->info("Order for day " . $d->format('d-m-Y'));
            }
            if ($i % 100 == 0) { /// 100 em 100 dias negócio cresce (ou diminui)
                for ($j = 0; $j < count($this->avgOrdersDay); $j++)
                    foreach ($this->avgOrdersDay as $avg) {
                        $fatorCrescimento = rand(-3, 5);
                        $this->avgOrdersDay[$j] += $this->avgOrdersDay[$j] * $fatorCrescimento / 100;
                    }
            }
            $totalOrdersDay = intval($this->avgOrdersDay[$d->dayOfWeek] + $this->avgOrdersDay[$d->dayOfWeek] * rand(-20, 20) / 100);
            $totalOrdersDay = $totalOrdersDay < 0 ? 0 : $totalOrdersDay;
            $ordersDay = [];
            for ($num = 0; $num < $totalOrdersDay; $num++) {
                $ordersDay[] = $this->createOrderArray($faker, $d, $num);
            }
            DB::table('orders')->insert($ordersDay);
            DB::table('orders')->where('date', $d->format('Y-m-d'))->pluck('id')->toArray();
            $i++;
            $d->addDays(1);
        }

        $this->command->info("All Orders were created");
        $this->command->info("---- END ----");
    }

    private function createOrderArray($faker, $day, $orderNumberOfDay)
    {
        $inicio = $day->copy()->addSeconds(rand(39600, 78000));
        $fim = $inicio->copy()->addSeconds(rand(100, 900));

        return [
            'status' => $faker->randomElement(['P','R','C']),
            'ticket_number' => $orderNumberOfDay % 99 + 1,
            'total_price' => $faker->randomFloat(2, 0, 50),
            'street_address' => $faker->address(),
            'quantity' => $faker->randomNumber(2, false),
            'delivered_by' => NULL,

            'date' =>  $day->format('Y-m-d'),
            'created_at' => $inicio,
            'updated_at' => $fim
        ];
    }
}

