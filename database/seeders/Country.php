<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country as ModelCountry;
use Illuminate\Support\Facades\DB;
use App\Helpers\Text\Translate;

class Country extends Seeder
{
    protected $translate;

    public function __construct() {
        $this->translate = new Translate();
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (ModelCountry::count() == 0) {
            DB::table($this->translate->getCountry())->insert([
                $this->translate->getId() => 1,
                $this->translate->getName() => 'Bolivia'
            ]);
        }
    }
}
