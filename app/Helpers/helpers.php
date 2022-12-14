<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Config;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Helper
{
    public static function applClasses()
    {
        $data = config('custom.custom');

        // default data array
        $DefaultData = [
            'mainLayoutType' => 'vertical',
            'theme' => 'light',
            'sidebarCollapsed' => false,
            'navbarColor' => '',
            'horizontalMenuType' => 'floating',
            'verticalMenuNavbarType' => 'floating',
            'footerType' => 'static', //footer
            'layoutWidth' => 'boxed',
            'showMenu' => true,
            'bodyClass' => '',
            'pageClass' => '',
            'pageHeader' => true,
            'contentLayout' => 'default',
            'blankPage' => false,
            'defaultLanguage' => 'en',
            'direction' => env('MIX_CONTENT_DIRECTION', 'ltr'),
        ];

        // if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
        $data = array_merge($DefaultData, $data);

        // All options available in the template
        $allOptions = [
            'mainLayoutType' => array('vertical', 'horizontal'),
            'theme' => array('light' => 'light', 'dark' => 'dark-layout', 'bordered' => 'bordered-layout', 'semi-dark' => 'semi-dark-layout'),
            'sidebarCollapsed' => array(true, false),
            'showMenu' => array(true, false),
            'layoutWidth' => array('full', 'boxed'),
            'navbarColor' => array('bg-primary', 'bg-info', 'bg-warning', 'bg-success', 'bg-danger', 'bg-dark'),
            'horizontalMenuType' => array('floating' => 'navbar-floating', 'static' => 'navbar-static', 'sticky' => 'navbar-sticky'),
            'horizontalMenuClass' => array('static' => '', 'sticky' => 'fixed-top', 'floating' => 'floating-nav'),
            'verticalMenuNavbarType' => array('floating' => 'navbar-floating', 'static' => 'navbar-static', 'sticky' => 'navbar-sticky', 'hidden' => 'navbar-hidden'),
            'navbarClass' => array('floating' => 'floating-nav', 'static' => 'navbar-static-top', 'sticky' => 'fixed-top', 'hidden' => 'd-none'),
            'footerType' => array('static' => 'footer-static', 'sticky' => 'footer-fixed', 'hidden' => 'footer-hidden'),
            'pageHeader' => array(true, false),
            'contentLayout' => array('default', 'content-left-sidebar', 'content-right-sidebar', 'content-detached-left-sidebar', 'content-detached-right-sidebar'),
            'blankPage' => array(false, true),
            'sidebarPositionClass' => array('content-left-sidebar' => 'sidebar-left', 'content-right-sidebar' => 'sidebar-right', 'content-detached-left-sidebar' => 'sidebar-detached sidebar-left', 'content-detached-right-sidebar' => 'sidebar-detached sidebar-right', 'default' => 'default-sidebar-position'),
            'contentsidebarClass' => array('content-left-sidebar' => 'content-right', 'content-right-sidebar' => 'content-left', 'content-detached-left-sidebar' => 'content-detached content-right', 'content-detached-right-sidebar' => 'content-detached content-left', 'default' => 'default-sidebar'),
            'defaultLanguage' => array('en' => 'en', 'fr' => 'fr', 'de' => 'de', 'pt' => 'pt'),
            'direction' => array('ltr', 'rtl'),
        ];

        //if mainLayoutType value empty or not match with default options in custom.php config file then set a default value
        foreach ($allOptions as $key => $value) {
            if (array_key_exists($key, $DefaultData)) {
                if (gettype($DefaultData[$key]) === gettype($data[$key])) {
                    // data key should be string
                    if (is_string($data[$key])) {
                        // data key should not be empty
                        if (isset($data[$key]) && $data[$key] !== null) {
                            // data key should not be exist inside allOptions array's sub array
                            if (!array_key_exists($data[$key], $value)) {
                                // ensure that passed value should be match with any of allOptions array value
                                $result = array_search($data[$key], $value, 'strict');
                                if (empty($result) && $result !== 0) {
                                    $data[$key] = $DefaultData[$key];
                                }
                            }
                        } else {
                            // if data key not set or
                            $data[$key] = $DefaultData[$key];
                        }
                    }
                } else {
                    $data[$key] = $DefaultData[$key];
                }
            }
        }

        //layout classes
        $layoutClasses = [
            'theme' => $data['theme'],
            'layoutTheme' => $allOptions['theme'][$data['theme']],
            'sidebarCollapsed' => $data['sidebarCollapsed'],
            'showMenu' => $data['showMenu'],
            'layoutWidth' => $data['layoutWidth'],
            'verticalMenuNavbarType' => $allOptions['verticalMenuNavbarType'][$data['verticalMenuNavbarType']],
            'navbarClass' => $allOptions['navbarClass'][$data['verticalMenuNavbarType']],
            'navbarColor' => $data['navbarColor'],
            'horizontalMenuType' => $allOptions['horizontalMenuType'][$data['horizontalMenuType']],
            'horizontalMenuClass' => $allOptions['horizontalMenuClass'][$data['horizontalMenuType']],
            'footerType' => $allOptions['footerType'][$data['footerType']],
            'sidebarClass' => '',
            'bodyClass' => $data['bodyClass'],
            'pageClass' => $data['pageClass'],
            'pageHeader' => $data['pageHeader'],
            'blankPage' => $data['blankPage'],
            'blankPageClass' => '',
            'contentLayout' => $data['contentLayout'],
            'sidebarPositionClass' => $allOptions['sidebarPositionClass'][$data['contentLayout']],
            'contentsidebarClass' => $allOptions['contentsidebarClass'][$data['contentLayout']],
            'mainLayoutType' => $data['mainLayoutType'],
            'defaultLanguage' => $allOptions['defaultLanguage'][$data['defaultLanguage']],
            'direction' => $data['direction'],
        ];
        // set default language if session hasn't locale value the set default language
        if (!session()->has('locale')) {
            app()->setLocale($layoutClasses['defaultLanguage']);
        }

        // sidebar Collapsed
        if ($layoutClasses['sidebarCollapsed'] == 'true') {
            $layoutClasses['sidebarClass'] = "menu-collapsed";
        }

        // blank page class
        if ($layoutClasses['blankPage'] == 'true') {
            $layoutClasses['blankPageClass'] = "blank-page";
        }

        return $layoutClasses;
    }

    public static function updatePageConfig($pageConfigs)
    {
        $demo = 'custom';
        $fullURL = request()->fullurl();
        if (App()->environment() === 'production') {
            for ($i = 1; $i < 7; $i++) {
                $contains = Str::contains($fullURL, 'demo-' . $i);
                if ($contains === true) {
                    $demo = 'demo-' . $i;
                }
            }
        }
        if (isset($pageConfigs)) {
            if (count($pageConfigs) > 0) {
                foreach ($pageConfigs as $config => $val) {
                    Config::set('custom.' . $demo . '.' . $config, $val);
                }
            }
        }
    }

    public static function generateAnnualResetNumber($lot_number, $prefix) {
        $date = Carbon::now();

        $reset_day = env('FINANCIAL_YEAR_END');

        $curr_date = $date->toDateString();

        $curr_year = Carbon::createFromFormat('Y-m-d', $curr_date)->format('Y');

        $reset_date = $curr_year. "-".$reset_day;
        
        $curr_date = Carbon::createFromFormat('Y-m-d', $curr_date);
        $reset_date = Carbon::createFromFormat('Y-m-d', $reset_date);

        $fin_year_chk = $curr_date->eq($reset_date);
        
        if ($fin_year_chk != true ) {
            if (isset($lot_number) && $lot_number != '') {
                $lot_number_parts = explode('-', $lot_number);
                $removed_char = substr($lot_number_parts[1], 1);
                $generated_lot_number = $stpad = str_pad($removed_char + 1, 5, "0", STR_PAD_LEFT);
            } else {
                $generated_lot_number = str_pad(1, 5, "0", STR_PAD_LEFT);
            }
        } else {
            $generated_lot_number = str_pad(1, 5, "0", STR_PAD_LEFT);
        }

        return Str::lower($prefix).'-'.$generated_lot_number;
    }
    
    public static function isMenuEnabled($section,$name){
       
        $section_menu = env(strtoupper($section)."_MENU");
        if($section_menu) {
            $arr_section_menu = explode(",",$section_menu);

            return in_array($name,$arr_section_menu);
        }

        return true;
    }

    public static function activeMenuBySlug($slug){
          
        $arrMenuSteps = explode('.',\Route::currentRouteName());   
        $arrSlugSteps = explode('.',$slug);
        $arrMenuStepsNew = $arrSlugStepsNew = [];
                    
        if(end($arrSlugSteps) == '*') {
            array_pop($arrSlugSteps); 
            unset($arrMenuSteps[count($arrSlugSteps)]);             
        }
       
        if($arrMenuSteps == $arrSlugSteps || $arrMenuSteps == $arrSlugStepsNew)
            return true;
       
        else
            return false;
    }

    public static function userAccess($str_slug) {
        if(!$str_slug)
            return true;
        
        $slugs = explode(',',$str_slug);

        foreach($slugs as $slug) {
           
            $user_permissions = \Auth::user()->getAllPermissions()->pluck('slug')->toArray();
           
            if(in_array($slug,$user_permissions) || \Auth::user()->isSuperAdmin())
                return true;
            
        }    
        
        return false;
    }

    public static function inWardOutWardStatusWithBadge($status = 0){
        return ($status == 0 ? '<span class="badge badge-light-danger"> Scheduled</span>' : 
            ($status == 1 ? '<span class="badge badge-light-warning"> Dispatched</span>' :
            ($status == 2 ? '<span class="badge badge-light-success"> Received</span>':'')));
    }

    public static function inWardOutWardConditionalStatus($current_status = '') {
        if($current_status == 0) {
            return [
                0 => 'Schedule',
                1 => 'Dispatch'
            ];
        } else if($current_status == 1) {
            return [                
                1 => 'Dispatch',
                2 => 'Received'
            ];
        } else if($current_status == 2) {
            return [            
                2 => 'Received'
            ];
        } else {
            return [
                0 => 'Schedule',
                1 => 'Dispatch',
            ];
        }
    }
    public static function rcnJobWorkWithBadge($stock_yard_account, $work_location_account) {
        
        return $stock_yard_account != $work_location_account ? 
                '<span class="badge rounded-pill bg-success float-end" title ="Job Work">J</span>':'';
    }

    public static function rcnBorrowWithBadge() {
        
        return '<span class="badge rounded-pill bg-danger float-end" title ="Stock Borrow">B</span>';
    }
    public static function rcnStockSplitBadge() {
        
        return '<span class="badge rounded-pill bg-warning float-end" title ="Split Stock">S</span>';
    }

    public static function rcnStockMixBadge() {
        
        return '<span style="background-color:teal;" class="badge rounded-pill  float-end" title ="Rcn Mixed Stock">M</span>';
    }

    public static function rcnStockCombineBadge() {
        
        return '<span style="background-color:wheat;" class="badge rounded-pill   float-end" title ="Rcn Combined Stock">C</span>';
    }
    
}