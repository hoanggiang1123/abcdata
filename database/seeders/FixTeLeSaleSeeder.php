<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeleSaleAgent;
use App\Models\TeleSaleLine;
use App\Models\TeleSale;

class FixTeLeSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teles = TeleSale::whereNotNull('agent')->get();
        foreach ($teles as $val) {
            $name = $val->agent;
            $line_id = $val->line_id;
            $agent = TeleSaleAgent::where('name', $name)->first();
            if (!$agent) $agent = TeleSaleAgent::create(['name' => $name, 'line_id' => $line_id]);

            $val->agent_id = $agent->id;
            
            $val->save();
        }
    }
}
