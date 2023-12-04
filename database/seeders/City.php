<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City as ModelCity;
use Illuminate\Support\Facades\DB;
use App\Helpers\Text\Translate;

class City extends Seeder
{
    protected $translate;

    public function __construct()
    {
        $this->translate = new Translate();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (ModelCity::count() == 0) {
            DB::table($this->translate->getCity())->insert([
                $this->translate->getId() => 1,
                $this->translate->getName() => 'Pando'
            ]);

            DB::table($this->translate->getCity())->insert([
                $this->translate->getId() => 2,
                $this->translate->getName() => 'Beni'
            ]);

            DB::table($this->translate->getCity())->insert([
                $this->translate->getId() => 3,
                $this->translate->getName() => 'Santa Cruz'
            ]);

            DB::table($this->translate->getCity())->insert([
                $this->translate->getId() => 4,
                $this->translate->getName() => 'La Paz'
            ]);

            DB::table($this->translate->getCity())->insert([
                $this->translate->getId() => 5,
                $this->translate->getName() => 'Cochabamba'
            ]);

            DB::table($this->translate->getCity())->insert([
                $this->translate->getId() => 6,
                $this->translate->getName() => 'Oruro'
            ]);

            DB::table($this->translate->getCity())->insert([
                $this->translate->getId() => 7,
                $this->translate->getName() => 'Potosi'
            ]);

            DB::table($this->translate->getCity())->insert([
                $this->translate->getId() => 8,
                $this->translate->getName() => 'Chuquisaca'
            ]);

            DB::table($this->translate->getCity())->insert([
                $this->translate->getId() => 9,
                $this->translate->getName() => 'Tarija'
            ]);
        }
    }
}
