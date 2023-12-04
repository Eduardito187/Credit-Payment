<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Municipality as ModelMunicipality;
use Illuminate\Support\Facades\DB;
use App\Helpers\Text\Translate;

class Municipality extends Seeder
{
    /**
     * @var Translate
     */
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
        if (ModelMunicipality::count() == 0) {
            DB::table($this->translate->getMunicipality())->insert([
                $this->translate->getId() => 1,
                $this->translate->getName() => 'Santa Cruz de la Sierra',
                $this->translate->getIdCity() => 3
            ]);
        }
    }
}
