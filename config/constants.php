<?php
return [

    //Statuses
    'statuses' => [
        'active' => 'Active',
        'pending' => 'Pending',
        'inactive' => 'Inactive',
    ],

    //Work Location Types
    'work_location_types' => [
        'office' => 'Office',
        'stockyard' => 'Stockyard',
        'factory' => 'Factory',
        'package' => 'Package Center',
    ],

    //Approvals
    'approvals' => [
        'approved' => 'Approved',
        'pending' => 'Pending',
    ],

    // ACL Resources
    'acl_resources' => [
        'user' => 'User Management',
        'player' => 'Player Management',
    ],

    //ACL Permissions
    'acl_permissions' => [
        'user' => ['list', 'add', 'edit', 'delete', 'acl', 'change-password'],
        'player' => ['list', 'add', 'edit', 'delete', 'view', 'approve'],
    ],

    //Indian states

    'states' => [
        "Andhra Pradesh" => "Andhra Pradesh",
        "Arunachal Pradesh" => "Arunachal Pradesh",
        "Assam" => "Assam",
        "Bihar" => "Bihar",
        "Chhattisgarh" => "Chhattisgarh",
        "Goa" => "Goa",
        "Gujarat" => "Gujarat",
        "Haryana" => "Haryana",
        "Himachal Pradesh" => "Himachal Pradesh",
        "Jammu and Kashmir" => "Jammu and Kashmir",
        "Jharkhand" => "Jharkhand",
        "Karnataka" => "Karnataka",
        "Kerala" => "Kerala",
        "Madhya Pradesh" => "Madhya Pradesh",
        "Maharashtra" => "Maharashtra",
        "Manipur" => "Manipur",
        "Meghalaya" => "Meghalaya",
        "Mizoram" => "Mizoram",
        "Nagaland" => "Nagaland",
        "Odisha" => "Odisha",
        "Punjab" => "Punjab",
        "Rajasthan" => "Rajasthan",
        "Sikkim" => "Sikkim",
        "Tamil Nadu" => "Tamil Nadu",
        "Telangana" => "Telangana",
        "Tripura" => "Tripura",
        "Uttarakhand" => "Uttarakhand",
        "Uttar Pradesh" => "Uttar Pradesh",
        "West Bengal" => "West Bengal",
        "Andaman and Nicobar Islands" => "Andaman and Nicobar Islands",
        "Chandigarh" => "Chandigarh",
        "Dadra and Nagar Haveli" => "Dadra and Nagar Haveli",
        "Daman and Diu" => "Daman and Diu",
        "Delhi" => "Delhi",
        "Lakshadweep" => "Lakshadweep",
        "Puducherry" => "Puducherry",
    ],
    'factory_of' => [
        "Prashanthi" => "Prashanthi",
        "Outside - Job Work" => "Outside - Job Work",
    ],
    'processing_types' => [
        'Sizering' => 'Sizering',
        'Boiling' => 'Boiling',
        'Cutting' => 'Cutting',
        'Roasting' => 'Roasting',
        'Borma' => 'Borma',
        'Machine Peeling' => 'Machine Peeling',
        'Sorting' => 'Sorting',
        'Peeling Pass' => 'Peeling Pass',
        'Grading' => 'Grading',
    ],
    //User groups
    'user_groups' => [
        'admin' => 'Administrator',
        'manager' => 'Manager',
        'clerk' => 'Clerk',
        'accounts' => 'Accounts',
        'front-office' => 'Front Office',
    ],

    //Job Cateogory
    'job_categories' => [
        'roaster' => 'Roaster',
        'boiler' => 'Boiler',
        'driver' => 'Driver',
        'loading' => 'Loading',
    ],

    //Religion
    'religions' => [
        'christian' => 'Christian',
        'hindu' => 'Hindu',
        'muslim' => 'Muslim',
    ],

    //RCN Marks
    'rcn_marks' => [
        'hana' => 'Ghana',
        'ivory-cost' => 'Ivory cost',
        'nigeeria' => 'Nigeeria',
        'senegal' => 'Senegal ',
        'bissau' => 'Bissau',
        'gambia' => 'Gambia',
        'madagascar' => 'Madagascar',
        'conagry' => 'Conagry',
        'cdjkl' => 'Cdjkl',
        'kenya' => 'Kenya',
        'mombassa' => 'Mombassa',
        'dsm' => 'DSM',
        'mozambique' => 'Mozambiqu',
        'indonesia' => 'Indonesia',
        'local' => 'Local(India)',
        'un_defined' => '',
    ],

    //Grades
    'grades' => [
        'A+' => 'aplus',
        'A1' => 'a1',
        'A2' => 'a2',
        'B1' => 'b1',
        'B2' => 'b2',
        'C1' => 'c1',
        'C2' => 'c2',
        'D1' => 'd1',
        'D2' => 'd2',
    ],

    'mix' => [
       
        'RCN Bags' => 'rcn_bags',
        'RCN Weight' => 'rcn_weight',
    
    ],

    
];