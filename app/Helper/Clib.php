<?php
namespace App\Helper;

class Clib
{
    
    public static function generateCode($model, $purpose, $status = '1')
    {
        $purpose = strtoupper( trim($purpose) );
        if ($status == '1') {
            $count = $model::withTrashed()->count();
        }else{
            $count = $model::count();
        }
        
        if (empty($count > 0)) {
            return  $purpose.'000001';
        } else {

            $id = intval($count) + 1;
            if (strlen($id) === 1 ) {
                return  $purpose.'00000'.($id);
            }elseif (strlen($id) === 2 ) {
                return  $purpose.'0000'.($id);
            }elseif (strlen($id) === 3 ) {
                return  $purpose.'000'.($id);
            }elseif (strlen($id) === 4 ) {
                return  $purpose.'00'.($id);
            }elseif (strlen($id) === 5 ) {
                return  $purpose.'0'.($id);
            }elseif(strlen($id) >= 6 ){
                return  $purpose.''.($id);
            }
        }
    }
    public static function getOrderNo()
    {
        $purpose = env('APP_SHORT_NAME')."ORDER";
        $purpose = strtoupper( trim($purpose) );
        $count = \App\Models\ProductOrder::count();
        if (empty($count)) {
            return  $purpose.'000001';
        } else {

            $id = intval($count) + 1;
            if (strlen($id) === 1 ) {
                return  $purpose.'00000'.($id);
            }elseif (strlen($id) === 2 ) {
                return  $purpose.'0000'.($id);
            }elseif (strlen($id) === 3 ) {
                return  $purpose.'000'.($id);
            }elseif (strlen($id) === 4 ) {
                return  $purpose.'00'.($id);
            }elseif (strlen($id) === 5 ) {
                return  $purpose.'0'.($id);
            }elseif(strlen($id) >= 6 ){
                return  $purpose.''.($id);
            }
        }
    }
}
