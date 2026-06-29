<?php

namespace App\Helper;

class Helper
{
    public static function companyEmail()
    {
        return 'companyemail@gmail.com';
    }
    public static function companyFullName()
    {
        return 'Bewakoof Hotel';
    }
    public static function companyFullAddress()
    {
        return 'GIRIDIH, JHARKHAND';
    }
    public static function companyHelpLine()
    {
        return '+91 8620006057';
    }
    public static function companyContact()
    {
        return '+91 7903736371';
    }
    public static function siteLogo()
    {
        return '';
    }
    public static function adminLogo()
    {
        return asset('assests/logo/logo.png');
    }
    public static function invoiceLogo()
    {
        return asset('assests/logo/inv-logo.png');
    }
    public static function invoicePDFLogo()
    {
        return public_path('assests/logo/inv-logo.png');
    }
    public static function faviconLogo()
    {
        return asset('assests/logo/favicon.jpg');
    }
    public static function preloader()
    {
        return asset('assests/logo/logo.png');
    }
    public static function adminProfile()
    {
        return asset('assests/admin/dist/img/images123.png');
    }
    public static function defaultImage()
    {
        return asset('assets/default/default_image.png');
    }
    public static function defaultMetaKeywords()
    {
        return '';
    }
    public static function numberTowords($number)
    {

        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '',
            '1' => 'One',
            '2' => 'Two',
            '3' => 'Three',
            '4' => 'Four',
            '5' => 'Five',
            '6' => 'Six',
            '7' => 'Seven',
            '8' => 'Eight',
            '9' => 'Nine',
            '10' => 'Ten',
            '11' => 'Eleven',
            '12' => 'Twelve',
            '13' => 'Thirteen',
            '14' => 'Fourteen',
            '15' => 'Fifteen',
            '16' => 'Sixteen',
            '17' => 'Seventeen',
            '18' => 'Eighteen',
            '19' => 'Nineteen',
            '20' => 'Twenty',
            '30' => 'Thirty',
            '40' => 'Forty',
            '50' => 'Fifty',
            '60' => 'Sixty',
            '70' => 'Seventy',
            '80' => 'Eighty',
            '90' => 'Ninety'
        );
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' ' : null;
                $str[] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] : '';

        $myfinalAmt = $result  . " Only ";
        return $myfinalAmt;
    }
    public static function customFileDelete($filename)
    {
        if (!empty($filename)) {
            if (is_array($filename) == TRUE) {
                $filenames = array_filter($filename);
                if (!empty($filenames)) {
                    foreach ($filenames as $key => $file) {
                        if (\Illuminate\Support\Facades\Storage::exists($file)) {
                            \Illuminate\Support\Facades\Storage::delete($file);
                        }
                    }
                }
            } else {
                if (\Illuminate\Support\Facades\Storage::exists($filename)) {
                    \Illuminate\Support\Facades\Storage::delete($filename);
                }
            }
        }
    }
    public static function defaultMetaAuthor()
    {
        return 'Spruko Technologies Private Limited';
    }
    public static function defaultMetaDescription()
    {
        return 'Bootstrap Responsive Admin Web Dashboard HTML5 Template';
    }

    public static function adminCardClass()
    {
        return 'card card-outline';
    }
    public static function adminPrimaryButtonClass($default = 'btn-primary')
    {
        return 'btn ' . $default . ' btn-sm btn-flat';
    }
    public static function getUserType($var)
    {
        if ($var == '1') {
            return 'Admin';
        } elseif ($var == '2') {
            # code...
        } elseif ($var == '3') {
            return 'Franchisee';
        } elseif ($var == '4') {
            return 'Sub Franchisee';
        }
    }

    /**
     * adminDataTableLink
     * 
     * Arrtibute: url,title,label
     *
     * @param  mixed $arr
     * @return void
     */
    public static function adminDataTableLink($arr)
    {
        $url = (isset($arr['url'])) ? $arr['url'] : '#';
        $title = (isset($arr['title'])) ? $arr['title'] : '';
        $label = (isset($arr['label'])) ? $arr['label'] : '';
        $target = (isset($arr['target'])) ? 'target="' . $arr['target'] . '"' : '';
        $class = (isset($arr['class'])) ? \App\Helper\Helper::adminPrimaryButtonClass($arr['class']) : \App\Helper\Helper::adminPrimaryButtonClass();

        return "<a href='{$url}' class='{$class}'  title='{$title}'  {$target}>{$label}</a>";
    }
    public static function adminDataTableButton($arr)
    {
        $title = (isset($arr['title'])) ? $arr['title'] : '';
        $value = (isset($arr['value'])) ? $arr['value'] : '';
        $label = (isset($arr['label'])) ? $arr['label'] : '';
        $class = (isset($arr['class'])) ? \App\Helper\Helper::adminPrimaryButtonClass($arr['class']) : \App\Helper\Helper::adminPrimaryButtonClass();
        $onclick = (isset($arr['onclick'])) ? "onclick=" . $arr['onclick'] : '';


        return '<button value=' . $value . ' class="' . $class . '" type="button" title="' . $title . '" ' . $onclick . '>' . $label . '</button>';
    }
    public static function commonEditButton($url, $button_color = 'btn-primary')
    {
        $btnCalss = \App\Helper\Helper::adminPrimaryButtonClass($button_color);
        return '<a href="' . $url . '" class="' . $btnCalss . '" title="Edit"><i class="fas fa-edit"></i></a>';
    }
    public static function commonDisableEditButton($button_color = 'btn-primary')
    {
        $btnCalss = \App\Helper\Helper::adminPrimaryButtonClass($button_color);
        return '<button class="' . $btnCalss . '" type="button" disabled title="Edit"><i class="fas fa-edit"></i></button>';
    }
    public static function commonDeleteRestoreButton($rowid, $status, $buttontype)
    {
        // $buttontype => 1=Delete , 2= Restore
        $changeStatusArr = json_encode([
            'id'    => $rowid,
            'status' => $status,
        ]);
        $btn = '';
        if ($buttontype == '1') {
            $btn = "<button class='btn btn-sm btn-danger btn-flat' value='{$changeStatusArr}' type='button' title='Delete' onclick='commonDeleteRestore(this)'><i class='fas fa-trash-alt'></i></button>";
        } elseif ($buttontype == '2') {
            $btn = "<button class='btn btn-sm btn-success btn-flat' value='{$changeStatusArr}' type='button' title='Restore' onclick='commonDeleteRestore(this)'><i class='fas fa-trash-restore'></i></button>";
        }
        return $btn;
    }
    public static function getPaymentOption()
    {
        return [
            'cash'      => 'Cash',
            'online'      => 'Online',
        ];
    }
    public static function paymentOption($var)
    {
        $arr = \App\Helper\Helper::getPaymentOption();
        return (array_key_exists($var, $arr)) ? $arr[$var] : '';
    }
    public static function getDeliveryMode()
    {
        return [
            'parcel' => 'Parcel',
            'home_delivery' => 'Home Delivery',
            'dine_in' => 'Dine-in',
            'swiggy' => 'Swiggy',
            'zomato' => 'Zomato',
            'staff' => 'Staff',
        ];
    }
    public static function getPlateType($var)
    {
        if ($var == '1') {
            return 'Full';
        } elseif ($var == '2') {
            return 'Half';
        }
    }
    public static function getRestaurantStatus()
    {
        $settings = \Illuminate\Support\Facades\DB::table('panelsettings')->first();
        if (!$settings) {
            return 'Closed';
        }

        if ($settings->is_restaurant_open == 0) {
            return 'Closed';
        }

        $now = date('H:i:s');
        $openTime = $settings->restaurant_open_time;
        $closeTime = $settings->restaurant_close_time;

        if ($now >= $openTime && $now <= $closeTime) {
            return 'Open';
        }

        return 'Closed';
    }

    public static function customerOrderStatus($var)
    {
        $statusMap = [
            'pending' => [
                'label' => 'Pending',
                'bg' => 'rgba(234, 179, 8, 0.2)', // yellow
                'color' => '#ca8a04',
            ],
            'accepted' => [
                'label' => 'Accepted',
                'bg' => 'rgba(59, 130, 246, 0.2)', // blue
                'color' => '#2563eb',
            ],
            'preparing' => [
                'label' => 'Preparing',
                'bg' => 'rgba(249, 115, 22, 0.2)', // orange
                'color' => '#ea580c',
            ],
            'out_for_delivery' => [
                'label' => 'Out for Delivery',
                'bg' => 'rgba(99, 102, 241, 0.2)', // indigo
                'color' => '#4f46e5',
            ],
            'delivered' => [
                'label' => 'Delivered',
                'bg' => 'rgba(34, 197, 94, 0.2)', // green
                'color' => '#16a34a',
            ],
            'cancelled' => [
                'label' => 'Cancelled',
                'bg' => 'rgba(239, 68, 68, 0.2)', // red
                'color' => '#dc2626',
            ],
        ];

        $status = $statusMap[$var] ?? null;
        return $status;
    }
    public static function getCustomerOrderMenus()
    {
        $isActive = false;
        if (request()->routeIs('admin.customerorder.index')) {
            $isActive = true;
        }
        $status = request()->status ?? "pending";
        return [
            [
                'label' => 'Pending Orders',
                'url' => route('admin.customerorder.index', ['status' => 'pending']),
                'active' => $isActive && $status == 'pending',
            ],
            [
                'label' => 'Accepted Orders',
                'url' => route('admin.customerorder.index', ['status' => 'accepted']),
                'active' => $isActive && $status == 'accepted',
            ],
            [
                'label' => 'Preparing Orders',
                'url' => route('admin.customerorder.index', ['status' => 'preparing']),
                'active' => $isActive && $status == 'preparing',
            ],
            [
                'label' => 'Out for Delivery Orders',
                'url' => route('admin.customerorder.index', ['status' => 'out_for_delivery']),
                'active' => $isActive && $status == 'out_for_delivery',
            ],
            [
                'label' => 'Delivered Orders',
                'url' => route('admin.customerorder.index', ['status' => 'delivered']),
                'active' => $isActive && $status == 'delivered',
            ],
            [
                'label' => 'Cancelled Orders',
                'url' => route('admin.customerorder.index', ['status' => 'cancelled']),
                'active' => $isActive && $status == 'cancelled',
            ],
        ];
    }
}
