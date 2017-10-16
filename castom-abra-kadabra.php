<?php
/*
  Plugin Name: Custom Abra Kadabra
  Plugin URI: https://www.facebook.com/bodunjo
  Description: Custom Abra Kadabra for my team
  Version: 1.0
  Author: Stepanchuk Evgeniy
  Author URI: https://www.facebook.com/bodunjo
 */
require_once __DIR__ . '/../appointments/includes/class-app-service.php';

global $wpdb, $total_time, $total_price, $k, $all_data, $serviceID;
$total_price = 0;
$total_time  = 0;
$k           = 0;

$serviceID = array();
function serviceID_array( $id ) {
	global $serviceID;
	array_push( $serviceID, $id );

	return $serviceID;
}

//////////////////////////////////////////////////////////////
add_action( 'wp_ajax_castom_table_abra_kadabra_ajax', 'castom_table_abra_kadabra_ajax' );
function castom_table_abra_kadabra_ajax() {
	global $total_time, $total_price, $k;

	$services = array();

	$req = split( ',', $_REQUEST['service_id'] );

	for ( $i = 0; $i < count( $req ); $i ++ ) {
		array_push( $services, abra_kadabra_db_table( $req[ $i ] ) );
	}

	$data         = array();
	$data['data'] = array();

	foreach ( $services as $s ) {

		if ( Get_user_type() == 'careem' ) {
			$price = $s['careem'];
		}
		if ( Get_user_type() == 'gold' ) {
			$price = $s['gold'];
		}
		if ( Get_user_type() == 'silver' ) {
			$price = $s['silver'];
		}
		if ( Get_user_type() == 'bronze' ) {
			$price = $s['bronze'];
		}
		if ( get_locale() == 'ar' ) {
			$name = $s['name_ar'];
		} else {
			$name = $s['name_en'];
		}
		$total_time  += $s['time'];
		$total_price += $price;

		$time = $s['time'] . " دقيقة";
		if ( $s['time'] == "300" ) {
			$time = "5 ساعات";
		}

		if ( $s['category_en'] == "Engine Oils" ) {
			$price .= " للتر";
		}

		array_push( $data['data'], array( $name, $time, $price, $s['service_id'], ) );
	}


	$data['total_price'] = $total_price;
	$data['total_time']  = $total_time;
	echo json_encode( $data );
	wp_die();
}

//////////////////////////////////////////////////////////////
add_action( 'wp_ajax_castom_total_abra_kadabra_ajax', 'castom_total_abra_kadabra_ajax' );
function castom_total_abra_kadabra_ajax() {
	global $total_time, $total_price;

	$services = array();

	$req = $_REQUEST['service_id'];

	$total = str_replace( '\\"', ' ', $req );
	$total = str_replace( '[', ' ', $total );
	$total = str_replace( ']', ' ', $total );
	$total = split( ',', $total );


	for ( $i = 0; $i < count( $total ); $i ++ ) {
		array_push( $services, abra_kadabra_db_total( $total[ $i ] ) );
	}

	$data       = array();
	$total_name = "";
	foreach ( $services as $s ) {

		if ( Get_user_type() == 'careem' ) {
			$price = $s['careem'];
		}
		if ( Get_user_type() == 'gold' ) {
			$price = $s['gold'];
		}
		if ( Get_user_type() == 'silver' ) {
			$price = $s['silver'];
		}
		if ( Get_user_type() == 'bronze' ) {
			$price = $s['bronze'];
		}
		if ( get_locale() == 'ar' ) {
			$name     = $s['name_ar'];
			$category = $s['category_ar'];
		} else {
			$name     = $s['name_en'];
			$category = $s['category_en'];
		}
		$total_time  += $s['time'];
		$total_price += $price;
		$total_name  .= "/" . $s['name_ar'];
	}

	$data['total_name']  = $total_name;
	$data['total_price'] = $total_price;
	$data['total_time']  = $total_time;
	echo json_encode( $data );
	wp_die();
}

//////////////////////////////////////////////////////////////

add_action( 'wp_ajax_castom_abra_kadabra_ajax', 'castom_abra_kadabra_ajax' );
function castom_abra_kadabra_ajax() {
	$category = $_REQUEST['category'];
	$services = abra_kadabra_db_services( $category );

	$serv = '<option value="">احجز</option>';
	foreach ( $services as $service ) {
		$serv .= '<option value="' . $service['service_id'] . '">' . $service['name'] . '</option>';
	}
	echo $serv;
	wp_die();
}

/////////////////////////////////////////////////////////////////
function castom_abra_my_custom_js() {
	echo '
	<link rel="stylesheet"  href="//cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css" type="text/css" media="all" />
	<link rel="stylesheet"  href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css" type="text/css" media="all" />
	';
}

add_action( 'wp_head', 'castom_abra_my_custom_js' );

function castom_abra_my_custom_js_footer() {
	echo '<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>';
	?>
    <script>

        var kadabra_ajax_url = '/salon/wp-admin/admin-ajax.php';

        jQuery(document).on("change", "#optionCategory", function () {
            jQuery("#kadabra_results").show();
            jQuery("#kadabra_results").html("Loading..");
            var selectval = jQuery(this).val();
            var data = {
                'action': 'castom_abra_kadabra_ajax',
                'category': selectval
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(kadabra_ajax_url, function (response) {
                if (response != 0) {
                    jQuery("#optionService").html(response);
                    jQuery("#optionService").show();
                    jQuery("#add_row").show();
                    jQuery("#hyiny_po_arabski").show();
                    jQuery("#kadabra_results").hide();
                } else {
                    jQuery("#error").html("الرجاء الدخول");
                    jQuery("#kadabra_results").hide();
                }

            });
        });

        var GlobalTable;
        var ArrayServiceID = [];
        var rowID = 0;

        jQuery("#add_row").on("click", function () {

            jQuery("#error").hide();
            var optionVal = jQuery("#optionService").val();

            if (optionVal == "") {
                jQuery("#error").html("لا يمكنك إضافة هذه الخدمة");
                jQuery("#error").show();
            }
            else if (jQuery.inArray(optionVal, ArrayServiceID) != -1) {
                jQuery("#error").html("لديك دينا بالفعل هذه الخدمة");
                jQuery("#error").show();
            } else {


                var array = ArrayServiceID.push(optionVal);

                var kadabra_ajax_url_service = kadabra_ajax_url + "?action=castom_table_abra_kadabra_ajax&service_id=" + ArrayServiceID;


                var table = jQuery("#abra_kadabra_table").DataTable({
                    "bDestroy": true,
                    "bSort": true,
                    "bFilter": true,
                    "paging": false,
                    "searching": false,
                    "info": false,
                    "ajax": kadabra_ajax_url_service,
                    "columnDefs": [
                        {
                            "targets": [3],
                            "visible": false,
                            "searchable": false
                        }
                    ]
                });

                GlobalTable = table;

                jQuery("#del_row").show();
                jQuery("#abra_kadabra_table").show();
                jQuery('#submit').show();
                jQuery.ajax({
                    type: "post",
                    url: kadabra_ajax_url,
                    data: {action: "castom_total_abra_kadabra_ajax", service_id: JSON.stringify(ArrayServiceID)},
                    dataType: "json",
                    success: function (answer) {
                        jQuery("#kadabra_total_time").val(answer.total_time);
                        jQuery("#kadabra_total_price").val(answer.total_price);
                        jQuery("#kadabra_total_name").val(answer.total_name);
                    }
                });
            }
        });


        jQuery("#abra_kadabra_table tbody").on("click", "tr", function () {
            if (jQuery(this).hasClass('selected')) {
                jQuery(this).removeClass('selected');
            }
            else {
                jQuery('tr.selected').removeClass('selected');
                jQuery(this).addClass('selected');
            }
            roww = GlobalTable.row(this).data();
            rowID = roww[3];
        });

        jQuery("#del_row").on("click", function () {

            ArrayServiceID.remove(rowID);

            if (ArrayServiceID.length == 0) {
                jQuery("#abra_kadabra_table").hide();
                jQuery("#submit").hide();
            } else {

                var kadabra_ajax_url_service = kadabra_ajax_url + "?action=castom_table_abra_kadabra_ajax&service_id=" + ArrayServiceID;

                var table = jQuery("#abra_kadabra_table").DataTable({
                    "bDestroy": true,
                    "bSort": true,
                    "bFilter": true,
                    "paging": false,
                    "searching": false,
                    "info": false,
                    "ajax": kadabra_ajax_url_service,
                    "columnDefs": [
                        {
                            "targets": [3],
                            "visible": false,
                            "searchable": false
                        }
                    ]
                });

                GlobalTable = table;

                jQuery("#del_row").show();
                jQuery("#abra_kadabra_table").show();
                jQuery('#submit').show();
                jQuery.ajax({
                    type: "post",
                    url: kadabra_ajax_url,
                    data: {action: "castom_total_abra_kadabra_ajax", service_id: JSON.stringify(ArrayServiceID)},
                    dataType: "json",
                    success: function (answer) {
                        jQuery("#kadabra_total_time").val(answer.total_time);
                        jQuery("#kadabra_total_price").val(answer.total_price);
                        jQuery("#kadabra_total_name").val(answer.total_name);
                    }
                });
            }
        });


        Array.prototype.remove = function () {
            var what, a = arguments, L = a.length, ax;
            while (L && this.length) {
                what = a[--L];
                while ((ax = this.indexOf(what)) !== -1) {
                    this.splice(ax, 1);
                }
            }
            return this;
        };


    </script>
	<?php
}

add_action( 'wp_footer', 'castom_abra_my_custom_js_footer' );


function castom_abra_kadabra_function() {

	if ( isset( $_REQUEST['submit'] ) ) {
		global $name, $duration, $price;
		$name     = $_REQUEST['kadabra_total_name'];
		$duration = $_REQUEST['kadabra_total_time'];
		$price    = $_REQUEST['kadabra_total_price'];


		complete_abra_kadabra( $name, $duration, $price );

	}

	$local    = local();
	$some     = abra_kadabra_db();
	$category = abra_kadabra_db_category();
	abra_kadabra_form( $some, $category, $local );

}

function abra_kadabra_db_category() {
	global $wpdb;
	$local = get_locale();
	switch ( $local ) {
		case "ar":
			$category = 'category_ar';
			break;
		default:
			$category = 'category_en';
			break;
	}

	return $wpdb->get_results( "SELECT " . $category . " AS category FROM wp_our_service GROUP BY " . $category . "", 'ARRAY_A' );
}

function Get_user_type() {
	$user = get_currentuserinfo();
	$desc = get_user_meta( $user->ID, 'description', true );

	return $desc;
}

function abra_kadabra_db_services( $categorys ) {
	global $wpdb;
	$local = get_locale();


	switch ( $local ) {

		case "ar":
			$name     = 'name_ar';
			$category = 'category_ar';
			break;
		default:
			$name     = 'name_en';
			$category = 'category_en';
			break;
	}
	$type = Get_user_type();

	if ( $type && $name && $category ) {
		return $wpdb->get_results( "SELECT service_id, " . $name . " AS name, time, " . $category . " AS category, " . $type . " AS price FROM wp_our_service WHERE " . $category . "='" . $categorys . "'", 'ARRAY_A' );
	}
}

function abra_kadabra_db_table( $service_id ) {
	global $wpdb;
	$db = $wpdb->get_row( "SELECT * FROM wp_our_service WHERE service_id=" . $service_id, 'ARRAY_A' );

	return $db;
}

function abra_kadabra_db_total( $service_id ) {
	global $wpdb;
	$db = $wpdb->get_row( "SELECT * FROM wp_our_service WHERE service_id=" . $service_id, 'ARRAY_A' );

	return $db;
}

function abra_kadabra_db() {
	global $wpdb;
	$local = get_locale();
	global $_POST;
	switch ( $local ) {

		case "ar":
			$name     = 'name_ar';
			$category = 'category_ar';
			break;
		default:
			$name     = 'name_en';
			$category = 'category_en';
			break;
	}
	$type = Get_user_type();

	if ( $name && $category && $type ) {
		return $wpdb->get_results( "SELECT service_id, " . $name . " AS name, time, " . $category . " AS category, " . $type . " AS price FROM wp_our_service WHERE service_id='" . $_POST['service_id'] . "'",
			'ARRAY_A' );
	}
}

function local() {
	$local = get_locale();
	switch ( $local ) {
		case "ar":
			return array(
				'icon'      => 'حذف',
				'number'    => 'رقم',
				'name'      => 'اسم',
				'category'  => 'الفئة',
				'time'      => 'زمن',
				'price'     => 'السعر',
				'total'     => 'مجموع',
				'option'    => 'احجز',
				'button'    => 'احجز الآن',
				'buttonAdd' => 'إضافة خدمة جديدة'
			);
			break;
		default:
			return array(
				'icon'      => 'Delete',
				'number'    => 'Number',
				'name'      => 'Name',
				'category'  => 'Category',
				'time'      => 'Time',
				'price'     => 'Price',
				'total'     => 'total',
				'option'    => 'Select category',
				'button'    => 'Book now',
				'buttonAdd' => 'Add New Service'
			);
			break;
	}

}


function abra_kadabra_form( $services, $category, $local ) {

	$selects = '<div style="width:100%;text-align:center;">
<div>حجز الخدمات:</div><select class="optionCategory" id="optionCategory">
<option value="0">' . $local['option'] . '</option>';

	for ( $i = 0; $i < count( $category ); $i ++ ) {
		$selects .= '<option value="' . $category[ $i ]['category'] . '">' . $category[ $i ]['category'] . '</option>';
	}
	$selects .= '</select></br>';

	$selects .= '<div id="hyiny_po_arabski">نوع الخدمة:</div><select class="options" id="optionService"></select>';
	$selects .= '<input type="button" id="del_row" value="-"/>
<input type="button" id="add_row" value="+"/></div>';

	$form = '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post" style="background: rgba(255, 255, 255, 0.79);color:black;">
<div style="text-align:center;color:white;display:none" id="kadabra_results"></div>
<div style="text-align: center; color: black; font-size: 20pt">حجز الخدمات</div>
' . $selects . '
<div id="error" style="text-align: center; font-size: 20px; color: black;"></div>

<table id="abra_kadabra_table" style="width:100% !important;">
<thead>
<tr>
<th style="width: 50%">' . $local['name'] . '</th>
<th style="width: 50%">' . $local['time'] . '</th>
<th style="width: 50%">' . $local['price'] . '</th>
<th style="width: 0%">ID</th>
</tr>
</thead>
<tbody></tbody>
</table>
<input type="hidden" name="kadabra_total_time" id="kadabra_total_time" value=""/>
<input type="hidden" name="kadabra_total_price" id="kadabra_total_price" value=""/>
<input type="hidden" name="kadabra_total_name" id="kadabra_total_name" value=""/>
<div class="btn_submit"><input type="submit" id="submit" name="submit" value="' . $local['button'] . '" /></div>
</form>
<script>
jQuery("#optionService").hide();
jQuery("#abra_kadabra_table").hide();
jQuery("#submit").hide();
jQuery("#add_row").hide();
jQuery("#del_row").hide();
jQuery("#hyiny_po_arabski").hide();
</script>';


	echo $form;


}

function complete_abra_kadabra( $name, $duration, $price ) {

	global $wpdb, $name, $duration, $price, $services_id;

	$service = array(
		'name'     => $name,
		'capacity' => 0,
		'duration' => $duration,
		'price'    => $price,
		'page'     => 0
	);
	$services_id = 0;

	$services = $wpdb->get_row( "SELECT * FROM wp_app_services WHERE name=" . name . " AND price=" . $price, 'ARRAY_A' );
	if ( json_encode($services['ID']) == "null" ) {
		$services_id = appointments_insert_service( $service );
		echo "<script>console.log(112)</script>";
	} else {
		$services_id = $services['ID'];
	}

	echo "<script>console.log(".$services_id.")</script>";

	echo '<script>document.cookie = "abra_kadabra_cooka=' . $services_id . '; path=/; expires=Thu, 18 Dec 2018 12:00:00 UTC;"</script>';
	$current_url = 'http' . ( empty( $_SERVER['HTTPS'] ) ? '' : 's' ) . '://' . $_SERVER['SERVER_NAME'] . '/salon/calendar/';
	echo '<script>window.location = "' . $current_url . '";</script>';
	die;
}

// Register a new shortcode: [castom_abra_kadabra]
add_shortcode( 'castom_abra_kadabra', 'castom_abra_kadabra_shortcode' );

// The callback function that will replace [book]
function castom_abra_kadabra_shortcode() {
	ob_start();
	castom_abra_kadabra_function();

	return ob_get_clean();
}

