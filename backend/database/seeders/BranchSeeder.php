<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

/**
 * Branch names and regions as published on
 * https://xtremewaycorp.com/gamextreme/participating-branches.php (Oct 2026).
 * The source lists no street addresses, so `address` is left null.
 */
class BranchSeeder extends Seeder
{
    public const BRANCHES = [
        'Metro Manila' => [
            'SM City Makati', 'Robinsons Place Manila', 'SM City San Lazaro', 'SM City Sta. Mesa',
            'Ayala Malls UP Town Center', 'Lucky Chinatown', 'SM City Megamall', 'Greenhills Mall',
            'SM City Fairview', 'SM City North EDSA', 'Fisher Mall QC', 'Festival Mall',
            'SM City Southmall', 'SM City Sucat', 'SM City BF Parañaque', 'Vistamall Taguig',
            'Venice Grand Canal', 'SM Mall of Asia', 'Ayala Malls Feliz', 'Sta. Lucia Grandmall',
            'SM City East Ortigas', 'SM City San Mateo', 'SM City Masinag', 'VMall',
            'Bridgeway 1',
        ],
        'North Luzon' => [
            'Ayala Marquee Mall', 'SM City Clark', 'Ayala Malls Harbor Point', 'SM City Telebastagan',
            'SM City Urdaneta', 'Robinsons Place La Union', 'SM City Baliwag', 'SM City Cabanatuan',
            'Robinsons Santiago',
        ],
        'South Luzon' => [
            'SM City Bacoor', 'SM City Dasmarinas', 'SM City Tanza', 'SM City Sto. Tomas',
            'Ayala Malls Vermosa', 'Robinsons Place Lipa',
        ],
        'Visayas & Mindanao' => [
            'Gaisano Mall Davao',
        ],
    ];

    public function run(): void
    {
        $order = 0;

        foreach (self::BRANCHES as $region => $names) {
            foreach ($names as $name) {
                Branch::updateOrCreate(
                    ['name' => $name],
                    ['region' => $region, 'is_online' => false, 'sort_order' => ++$order, 'is_active' => true]
                );
            }
        }

        // "Website - GameXtreme" option on the original registration form (gamextreme.ph).
        Branch::updateOrCreate(
            ['name' => 'GameXtreme Website'],
            ['region' => 'Website', 'address' => 'https://gamextreme.ph/', 'is_online' => true, 'sort_order' => ++$order, 'is_active' => true]
        );

        // Stores taken out of the list above are switched off, not deleted, so past registrations
        // and winners keep their branch. They disappear from the form and the registration rules.
        Branch::whereNotIn('name', collect(self::BRANCHES)->flatten()->push('GameXtreme Website'))
            ->update(['is_active' => false]);
    }
}
