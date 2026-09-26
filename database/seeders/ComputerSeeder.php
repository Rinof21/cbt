<?php

namespace Database\Seeders;

use App\Models\Computer;
use Illuminate\Database\Seeder;

class ComputerSeeder extends Seeder
{
    public function run(): void
    {
        $pcNumber = 1;
        $rows = 8;
        $cols = 11;
        $computers = [];

        for ($row = 1; $row <= $rows; $row++) {
            if ($row % 2 !== 0) {
                for ($col = 1; $col <= $cols; $col++) {
                    $computers[] = [
                        "pc_number"    => $pcNumber,
                        "row_position" => $row,
                        "col_position" => $col,
                        "brand_model"  => "Dell OptiPlex 3000",
                        "monitor_model"=> "Dahua 22 Inch",
                        "ip_address"   => "192.168.1." . ($pcNumber + 10),
                        "status"       => "available",
                        "total_usage_minutes"  => 0,
                        "total_sessions_count" => 0,
                        "created_at"   => now(),
                        "updated_at"   => now(),
                    ];
                    $pcNumber++;
                }
            } else {
                for ($col = $cols; $col >= 1; $col--) {
                    $computers[] = [
                        "pc_number"    => $pcNumber,
                        "row_position" => $row,
                        "col_position" => $col,
                        "brand_model"  => "Dell OptiPlex 3000",
                        "monitor_model"=> "Dahua 22 Inch",
                        "ip_address"   => "192.168.1." . ($pcNumber + 10),
                        "status"       => "available",
                        "total_usage_minutes"  => 0,
                        "total_sessions_count" => 0,
                        "created_at"   => now(),
                        "updated_at"   => now(),
                    ];
                    $pcNumber++;
                }
            }
        }

        Computer::insert($computers);
        $this->command->info("Seeded " . ($pcNumber - 1) . " computers in snake pattern.");
    }
}