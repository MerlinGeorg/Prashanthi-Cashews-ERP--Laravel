<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Str;
use \App\Models\Permission;
use \App\Models\Resource;

class AclSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Resource::truncate();
        Permission::truncate();

        foreach ($this->resources() as $resources) {

            foreach ($resources as $resource_name => $resource) {
                $resource_slug = $resource['work_location_type'] . '-' . Str::slug($resource_name);

                $r = Resource::updateOrCreate(['slug' => $resource_slug], [
                    'resource_name' => $resource_name,
                    'slug' => $resource_slug,
                    'work_location_type' => $resource['work_location_type'],
                ]);

                foreach ($resource['permissions'] as $permission) {
                    $permission_slug = $resource_slug . '-' . Str::slug($permission);

                    Permission::updateOrCreate([
                        'slug' => $permission_slug,
                    ], [
                        'name' => $permission,
                        'slug' => $permission_slug,
                        'resource_slug' => $resource_slug,
                        'work_location_type' => $resource['work_location_type'],
                        'guard_name' => 'web',
                    ]);
                }
            }
        }
    }

    public function resources()
    {

        /**
         * O F F I C E
         */
        yield [
            'Office' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];
        yield [
            'Account' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];
        yield [
            'Stockyard' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];
        yield [
            'Factory' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];
        yield [
            'Package Center' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];
        yield [
            'Shipper Details' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];
        yield [
            'Staff' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View', 'Change Password'],
            ],
        ];
        yield [
            'Employee' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];
        yield [
            'Role' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];
        yield [
            'Resource' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];
        yield [
            'Permission' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];
        yield [
            'Role Privilege' => [
                'work_location_type' => 'office',
                'permissions' => ['View', 'Change'],
            ],
        ];
        yield [
            'User Group' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];
        yield [
            'Job Category' => [
                'work_location_type' => 'office',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        /**
         * S T O C K  Y A R D
         */
        yield [
            'Employee' => [
                'work_location_type' => 'stockyard',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'RCN' => [
                'work_location_type' => 'stockyard',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'Inward RCN' => [
                'work_location_type' => 'stockyard',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'Outward RCN' => [
                'work_location_type' => 'stockyard',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        /**
         * F A C T O R Y
         */
        yield [
            'Employee' => [
                'work_location_type' => 'factory',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'Inward RCN' => [
                'work_location_type' => 'factory',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'RCN' => [
                'work_location_type' => 'factory',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'Sizering' => [
                'work_location_type' => 'factory',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'Boiling' => [
                'work_location_type' => 'factory',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'Cutting' => [
                'work_location_type' => 'factory',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'Borma' => [
                'work_location_type' => 'factory',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'Machine Peeling' => [
                'work_location_type' => 'factory',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'Sorting' => [
                'work_location_type' => 'factory',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'Peeling Pass' => [
                'work_location_type' => 'factory',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

        yield [
            'Grading' => [
                'work_location_type' => 'factory',
                'permissions' => ['Add', 'Edit', 'Delete', 'View'],
            ],
        ];

    }

}