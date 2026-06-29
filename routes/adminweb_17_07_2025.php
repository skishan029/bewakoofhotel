<?php
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::match(['get', 'post'], '/', [\App\Http\Controllers\Admin\AuthController::class, 'index'])->name('index')->middleware('admin.guest');

    Route::get('logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    Route::match(['get', 'post'], 'verify/{otp_key}',  [\App\Http\Controllers\Admin\AuthController::class, 'verifyLoginOtp'])->name('verify')->whereAlphaNumeric('otp_key');

    
    Route::middleware(['admin.auth'])->group(function () {
        Route::match(['get', 'post'],'dashboard', [\App\Http\Controllers\Admin\MainController::class, 'dashboard'])->name('dashboard');

        Route::prefix('frontend')->name('frontend.')->middleware('admin.isaccessmenu:5')->group(function () {
            
            Route::match(['get', 'post'], 'setting', [\App\Http\Controllers\Admin\MainController::class, 'panelSetting'])->name('setting')->middleware('admin.isaccessmenu:11');

            Route::prefix('slider')->name('slider.')->middleware('admin.isaccessmenu:6')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\SliderController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'upload', [\App\Http\Controllers\Admin\Frontend\SliderController::class, 'upload'])->name('upload');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\SliderController::class, 'edit'])->name('edit')->whereNumber('id');
                Route::post('delete', [\App\Http\Controllers\Admin\Frontend\SliderController::class, 'delete'])->name('delete');
            });

            Route::prefix('gallery')->name('gallery.')->middleware('admin.isaccessmenu:8')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\GalleryController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'upload', [\App\Http\Controllers\Admin\Frontend\GalleryController::class, 'upload'])->name('upload');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\GalleryController::class, 'edit'])->name('edit')->whereNumber('id');
                Route::post('delete', [\App\Http\Controllers\Admin\Frontend\GalleryController::class, 'delete'])->name('delete');
            });
            
            Route::prefix('frequentlyaskedquestion')->name('frequentlyaskedquestion.')->middleware('admin.isaccessmenu:9')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\FrequentlyAskedQuestionController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\Frontend\FrequentlyAskedQuestionController::class, 'create'])->name('create');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\FrequentlyAskedQuestionController::class, 'edit'])->name('edit')->whereNumber('id');
            });
            

            Route::prefix('fooditem')->name('fooditem.')->middleware('admin.isaccessmenu:7')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\FoodItemController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'upload', [\App\Http\Controllers\Admin\Frontend\FoodItemController::class, 'upload'])->name('upload');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\FoodItemController::class, 'edit'])->name('edit')->whereNumber('id');
                Route::post('delete', [\App\Http\Controllers\Admin\Frontend\FoodItemController::class, 'delete'])->name('delete');
            });

            Route::prefix('testimonial')->middleware('admin.isaccessmenu:10')->name('testimonial.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\TestimonialController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\Frontend\TestimonialController::class, 'create'])->name('create');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\TestimonialController::class, 'edit'])->name('edit')->whereNumber('id');
                Route::post('delete', [\App\Http\Controllers\Admin\Frontend\TestimonialController::class, 'delete'])->name('delete');
            });

            Route::prefix('video')->name('video.')->middleware('admin.isaccessmenu:5')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\VideoController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'upload', [\App\Http\Controllers\Admin\Frontend\VideoController::class, 'video'])->name('upload');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\VideoController::class, 'edit'])->name('edit')->whereNumber('id');
                Route::post('delete', [\App\Http\Controllers\Admin\Frontend\VideoController::class, 'delete'])->name('delete');
            });
            Route::prefix('offer')->name('offer.')->middleware('admin.isaccessmenu:5')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'offerSet'])->name('create');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'edit'])->name('edit')->whereNumber('id');
                Route::post('delete', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'delete'])->name('delete');
            });
            Route::prefix('contactus')->name('contactus.')->middleware('admin.isaccessmenu:5')->group(function () {
                // Route::get('/', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\Frontend\ContactusController::class, 'imageAdd'])->name('create');
                // Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'edit'])->name('edit')->whereNumber('id');
                // Route::post('delete', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'delete'])->name('delete');
            });

        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::match(['get', 'post'], 'change-password', [\App\Http\Controllers\Admin\MainController::class, 'changePassword'])->name('changepassword');

            Route::match(['get', 'post'], 'employee-change-password', [\App\Http\Controllers\Admin\MainController::class, 'empChangePassword'])->name('empchangepassword');
            Route::post('emp-password', [\App\Http\Controllers\Admin\MainController::class, 'empPassword'])->name('emppassword');

        });

        Route::prefix('productmaster')->name('productmaster.')->group(function () {

            Route::prefix('category')->name('category.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\ProductMaster\CategoryController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\ProductMaster\CategoryController::class, 'create'])->name('create');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\ProductMaster\CategoryController::class, 'edit'])->name('edit')->whereNumber('id');
            });

            Route::prefix('product')->name('product.')->middleware('admin.isaccessmenu:1')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\ProductMaster\ProductController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\ProductMaster\ProductController::class, 'create'])->name('create');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\ProductMaster\ProductController::class, 'edit'])->name('edit');
            });
        });

        Route::prefix('order')->name('order.')->middleware('admin.isaccessmenu:2')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('index');
            Route::get('pending-index', [\App\Http\Controllers\Admin\OrderController::class, 'pendingIndex'])->name('pendingindex');
            Route::post('place-order-form', [\App\Http\Controllers\Admin\OrderController::class, 'placeOrderForm'])->name('placeorderform');
            Route::post('place-order-submit', [\App\Http\Controllers\Admin\OrderController::class, 'placeOrderSubmit'])->name('placeordersubmit');
            Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\OrderController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'edit'])->name('edit');
            
            Route::get('summary/{order_key}', [\App\Http\Controllers\Admin\OrderController::class, 'orderSummary'])->name('summary')->whereAlphaNumeric('order_key');
            

            Route::get('details/{order_key}', [\App\Http\Controllers\Admin\OrderController::class, 'details'])->name('details')->whereAlphaNumeric('order_key');

            Route::get('print/{order_key}', [\App\Http\Controllers\Admin\OrderController::class, 'print'])->name('print')->whereAlphaNumeric('order_key');
            
        });

        Route::prefix('report')->name('report.')->middleware('admin.isaccessmenu:3')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\OrderController::class, 'report'])->name('index');
            Route::get('product', [\App\Http\Controllers\Admin\OrderController::class, 'productReport'])->name('product');
        });

        Route::prefix('expence')->name('expence.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ExpenceController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'expence-Add', [\App\Http\Controllers\Admin\ExpenceController::class, 'expenceAdd'])->name('expenceadd');
            Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\ExpenceController::class, 'edit'])->name('edit')->whereNumber('id');
            Route::post('delete', [\App\Http\Controllers\Admin\ExpenceController::class, 'delete'])->name('delete');
        });

        Route::prefix('employee')->name('employee.')->group(function () {
             Route::get('/', [\App\Http\Controllers\Admin\EmployeeController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\EmployeeController::class, 'create'])->name('create');
             Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\EmployeeController::class, 'edit'])->name('edit')->whereNumber('id');
             Route::post('delete', [\App\Http\Controllers\Admin\EmployeeController::class, 'delete'])->name('delete');
        });

        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('index');
           Route::match(['get', 'post'], 'attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'attendance'])->name('attendance');
            // Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\EmployeeController::class, 'edit'])->name('edit')->whereNumber('id');
            Route::post('checkattendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'checkAttendance'])->name('checkattendance');
       });
        

        Route::prefix('ajax')->name('ajax.')->group(function () {
            Route::prefix('changestatus')->name('changestatus.')->group(function () {
                Route::post('user', [\App\Http\Controllers\Admin\AjaxDeleteController::class, 'userStatus'])->name('user');
                Route::post('product', [\App\Http\Controllers\Admin\AjaxDeleteController::class, 'productStatus'])->name('product');
                Route::post('frequentlyaskedquestion', [\App\Http\Controllers\Admin\AjaxDeleteController::class, 'frequentlyAskedQuestionStatus'])->name('frequentlyaskedquestion');
                Route::post('testimonial', [\App\Http\Controllers\Admin\AjaxDeleteController::class, 'testimonialStatus'])->name('testimonial');
            });
        });

        Route::get('backup', function () {
            $mysqlHostName      = env('DB_HOST');
            $mysqlUserName      = env('DB_USERNAME');
            $mysqlPassword      = env('DB_PASSWORD');
            $DbName             = env('DB_DATABASE');
            $tables             = array();
            $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword", array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $get_all_table_query = "SHOW TABLES";
            $statement = $connect->prepare($get_all_table_query);
            $statement->execute();
            $result = $statement->fetchAll();
        
            $prep = "Tables_in_$DbName";
            foreach ($result as $res) {
                $tables[] =  $res[$prep];
            }
        
            $output = '';
            $alterStatements = [];
            foreach ($tables as $table) {
                $show_table_query = "SHOW CREATE TABLE " . $table . "";
                $statement = $connect->prepare($show_table_query);
                $statement->execute();
        
                $show_table_result = $statement->fetchAll();
                //detached CONSTRAINT
                foreach ($show_table_result as $show_table_row) {
                    $preg = 'CONSTRAINT `(.*?)` FOREIGN KEY \(`(.*?)`\) REFERENCES `(.*?)` \(`(.*?)`\)';
                    preg_match_all('/' . $preg . '/', $show_table_row["Create Table"], $matches, PREG_SET_ORDER);
                    $createTableWithoutConstraints = preg_replace('/,?\s*' . $preg . ',?/', '', $show_table_row["Create Table"]);
                    if ($matches) {
                        $alterTableQuery = "ALTER TABLE `$table` ";
                        foreach ($matches as $match) {
                            $constraintName = $match[1];
                            $columnName = $match[2];
                            $referencedTable = $match[3];
                            $referencedColumn = $match[4];
        
                            $alterTableQuery .= "ADD CONSTRAINT `$constraintName` FOREIGN KEY (`$columnName`) REFERENCES `$referencedTable` (`$referencedColumn`), ";
                        }
                        $alterStatements[] = trim($alterTableQuery, ', ') . ';COMMIT;';
                    }
        
                    $output .= "\n\n" . $createTableWithoutConstraints . ";\n\n";
                }
                
        
                $select_query = "SELECT * FROM " . $table . "";
                $statement = $connect->prepare($select_query);
                $statement->execute();
                $total_row = $statement->rowCount();
                if(!$total_row) {
                    continue;
                }
                $columns = [];
                
                for ($count = 0; $count < $statement->columnCount(); $count++) {
                    $column = $statement->getColumnMeta($count);
                    $columns[] = "`".$column['name'] ."`";
                }
                $values = [];
                $output .= "\nINSERT INTO $table (";
                $output .= "" . implode(", ", $columns) . ") VALUES \n";
                for ($count = 0; $count < $total_row; $count++) {
                    $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
                    $table_value_array = array_values($single_result);
                    $rowValues = [];
        
                    foreach ($table_value_array as $value) {
                        if ($value === null) {
                            $rowValues[] = "NULL";
                        } elseif (is_numeric($value)) {
                            $rowValues[] = $value;
                        } elseif (is_array($value) || is_object($value)) {
                            $jsonValue = json_encode($value);
                            $rowValues[] = "'" . addslashes($jsonValue) . "'";
                        } else {
                            $rowValues[] = "'" . addslashes($value) . "'";
                        }
                    }
        
                    $values[] = "(" . implode(", ", $rowValues) . ")";
                    
                }
                $output .= implode(",\n ", $values) . ";\n";
            }
            
        
            $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';
        
            $file_handle = fopen($file_name, 'w+');
            fwrite($file_handle, $output);
            //add CONSTRAINT foreign key 
        
            foreach ($alterStatements as $alterStatement) {
                fwrite($file_handle, $alterStatement . "\n");
            }
            
            fclose($file_handle);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file_name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_name));
        
            //ob_clean();
            flush();
            readfile($file_name);
            unlink($file_name);
        
        })->name('databasebackup');



    });
});