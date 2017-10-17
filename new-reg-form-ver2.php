<?php
/*
  Plugin Name: Custom Registration Form version 2
  Plugin URI: https://www.facebook.com/bodunjo
  Description: Custom Registration Form version 2 for http://uberlin.com.ua/
  Version: 1.0
  Author: Stepanchuk Evgeniy
  Author URI: https://www.facebook.com/bodunjo
 */

add_action( 'wp_ajax_custom_registration_city_ajax', 'custom_registration_city_ajax' );
add_action( 'wp_ajax_nopriv_custom_registration_city_ajax', 'custom_registration_city_ajax' );
function custom_registration_city_ajax()
{
	$cities = abra_kadabra_db_city();
	$local  = local();
	$serv   = '<option value="">' . $local['city'] . '</option>';
	foreach ( $cities as $citye )
	{
		$serv .= '<option value="' . $citye['name'] . '">' . $citye['name'] . '</option>';
	}
	echo $serv;
	die();
}

function abra_kadabra_db_city()
{
	global $wpdb;

	return $wpdb->get_results( "SELECT name FROM city ORDER BY name", 'ARRAY_A' );
}


add_action( 'wp_ajax_custom_registration_car_brand_ajax', 'custom_registration_car_brand_ajax' );
add_action( 'wp_ajax_nopriv_custom_registration_car_brand_ajax', 'custom_registration_car_brand_ajax' );
function custom_registration_car_brand_ajax()
{
	$car_brand = abra_kadabra_db_car_brand();
	$local     = local();
	$serv      = '<option value="">' . $local['carMark'] . '</option>';
	foreach ( $car_brand as $brand )
	{
		$serv .= '<option value="' . $brand['brand'] . '">' . $brand['brand'] . '</option>';
	}
	echo $serv;
	die();
}

function abra_kadabra_db_car_brand()
{
	global $wpdb;

	return $wpdb->get_results( "SELECT DISTINCT car_brand AS brand FROM cars ORDER BY car_brand", 'ARRAY_A' );
}


add_action( 'wp_ajax_custom_registration_car_model_ajax', 'custom_registration_car_model_ajax' );
add_action( 'wp_ajax_nopriv_custom_registration_car_model_ajax', 'custom_registration_car_model_ajax' );
function custom_registration_car_model_ajax()
{
	$car_model = abra_kadabra_db_car_model( $_REQUEST['carMark'] );
	$local     = local();
	$serv      = '<option value="">' . $local['carModel'] . '</option>';
	foreach ( $car_model as $model )
	{
		$serv .= '<option value="' . $model['model'] . '">' . $model['model'] . '</option>';
	}
	echo $serv;
	die();
}

function abra_kadabra_db_car_model( $carBrand )
{
	global $wpdb;

	return $wpdb->get_results( "SELECT car_model AS model FROM cars WHERE car_brand='" . $carBrand . "'", 'ARRAY_A' );
}


add_action( 'wp_ajax_custom_registration_car_color_ajax', 'custom_registration_car_color_ajax' );
add_action( 'wp_ajax_nopriv_custom_registration_car_color_ajax', 'custom_registration_car_color_ajax' );
function custom_registration_car_color_ajax()
{
	$car_color = abra_kadabra_db_car_color();

	$local = local();
	$serv  = '<option value="">' . $local['carColor'] . '</option>';
	foreach ( $car_color as $color )
	{
		$serv .= '<option value="' . $color['car_color'] . '">' . $color['car_color'] . '</option>';
	}
	echo $serv;
	die();
}

function abra_kadabra_db_car_color()
{
	global $wpdb;

	return $wpdb->get_results( "SELECT DISTINCT car_color FROM color ORDER BY car_color", 'ARRAY_A' );
}


add_action( 'wp_ajax_custom_registration_submit_ajax', 'custom_registration_submit_ajax' );
add_action( 'wp_ajax_nopriv_custom_registration_submit_ajax', 'custom_registration_submit_ajax' );
function custom_registration_submit_ajax()
{

	global $name, $lname, $fname, $email, $phonenumber, $city, $payType, $bankCard, $personalData, $carMark, $carModel, $carColor, $carYear, $carNumber, $fileFrontPage, $fileKategory, $fileTechMark, $fileTechNumber, $polis;

	$name               = $_REQUEST['name'];
	$lname              = $_REQUEST['lname'];
	$fname              = $_REQUEST['fname'];
	$email              = $_REQUEST['email'];
	$phonenumber        = $_REQUEST['phonenumber'];
	$city               = $_REQUEST['city'];
	$payType            = $_REQUEST['payType'];
	$bankCard           = $_REQUEST['bankCard'];
	$personalData       = $_REQUEST['personalData'];
	$carMark            = $_REQUEST['carMark'];
	$carModel           = $_REQUEST['carModel'];
	$carColor           = $_REQUEST['carColor'];
	$carYear            = $_REQUEST['carYear'];
	$carNumber          = $_REQUEST['carNumber'];
	$file['FrontPage']  = $_FILES['fileFrontPage'];
	$file['Kategory']   = $_FILES['fileKategory'];
	$file['TechMark']   = $_FILES['fileTechMark'];
	$file['TechNumber'] = $_FILES['fileTechNumber'];
	$file['polis']      = $_FILES['filePolis'];
	$step               = $_REQUEST['step'];

	if ( $step == '1' )
	{
		$reg_error_1 = registration_validationPart1( $name, $lname, $fname, $email, $phonenumber, $city );

		if ( count( $reg_error_1 ) == 0 )
		{

			$response = complete_registrationPart1(
				$name, $lname, $fname, $email, $phonenumber, $city
			);

			if($response['user']->resoult == 'ok'){
				$response = [ 'success' => '2' ];
            }else {
				$response = [ 'error' => array('user' =>$response['user']->description) ];
			}
		} else {
			$response = [ 'error' => $reg_error_1 ];
		}
	}
	if ( $step == '2' )
	{
		$reg_error_2 = registration_validationPart2($name, $lname, $fname, $email, $phonenumber, $city, $payType, $bankCard, $carMark, $carModel, $carColor, $carYear, $carNumber );

		if ( count( $reg_error_2 ) == 0 )
		{
			$response = complete_registrationPart2(
				$payType, $bankCard, $carMark, $carModel, $carColor, $carYear, $carNumber
			);

			if($response['user']->resoult == 'ok'){
				$response = [ 'success' => '3' ];
			} else {
				$response = [ 'error' => array('user' =>$response['user']->description) ];
            }
		} else {
			$response = [ 'error' => $reg_error_2 ];
		}
	}
	if ( $step == '3' )
	{
		$reg_error_3 = registration_validationPart3( $personalData, $file );

		if ( count( $reg_error_3 ) == 0 )
		{
			$response = complete_registrationPart3(	$email, $file);

			if ( $response['doc_1']->result == 'ok' )
			{
				if ( $response['doc_2']->result == 'ok' )
				{
					if ( $response['doc_3']->result == 'ok' )
					{
						if ( $response['doc_4']->result == 'ok' )
						{
							if ( $response['doc_5']->result == 'ok' )
							{
								$response = [ 'success' => 'Спасибо за регистрацию!' ];
							} else
							{
								$response = [ 'error' => array( 'doc_5' => $response['doc_5']->description ) ];
							}
						} else
						{
							$response = [ 'error' => array( 'doc_4' => $response['doc_4']->description ) ];
						}
					} else
					{
						$response = [ 'error' => array( 'doc_3' => $response['doc_3']->description ) ];
					}
				} else
				{
					$response = [ 'error' => array( 'doc_2' => $response['doc_2']->description ) ];
				}
			} else
			{
				$response = [ 'error' => array( 'doc_1' => $response['doc_1']->description ) ];
			}
		}else {
			$response = [ 'error' => $reg_error_3 ];
		}
	}


	echo json_encode( $response );
	die();
}

function castom_reg_form_my_custom_js_footer()
{
	echo '<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';
	?>
    <script>
        jQuery(function () {
            jQuery("#tabs").tabs();
        });

        var new_reg_ajax_url = 'https://uberlin.com.ua/wp-admin/admin-ajax.php';

        jQuery(document).ready(function () {

            var city = document.getElementById('city');
            jQuery('.close').click(function () {
                jQuery('#myModal').css('display', 'none')
            });

            jQuery(document).click(function (e) {
                if (e.target == jQuery('#myModal')) {
                    jQuery('#myModal').css('display', 'none')
                }
            });
            var data = {
                'action': 'custom_registration_city_ajax'
            };

            jQuery.post(new_reg_ajax_url, data, function (response) {
                if (response != 0) {
                    jQuery("#city").html(response);
                }
            });

            data = {
                'action': 'custom_registration_car_brand_ajax'
            };
            jQuery.post(new_reg_ajax_url, data, function (response) {
                if (response != 0) {
                    jQuery("#carMark").html(response);
                }
            });

            data = {
                'action': 'custom_registration_car_color_ajax'
            };
            jQuery.post(new_reg_ajax_url, data, function (response) {
                if (response != 0) {
                    jQuery("#carColor").html(response);
                }
            });

            jQuery('#buttonSubmitPart1').click(function () {
                var form_data = new FormData();
                form_data.append("action", 'custom_registration_submit_ajax');
                form_data.append("name", jQuery('#name').val());
                form_data.append("lname", jQuery('#lname').val());
                form_data.append("fname", jQuery('#fname').val());
                form_data.append("email", jQuery('#email').val());
                form_data.append("phonenumber", jQuery('#phonenumber').val());
                form_data.append("city", jQuery('#city').val());
                form_data.append("step", 1);
                jQuery.ajax({
                    type: "post",
                    url: new_reg_ajax_url,
                    data: form_data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log('success');
                        if (response.error) {
                            console.log('2 error');
                            jQuery('#modal-text').html('');
                            jQuery('#myModal').css('display', 'block');
                            if (response.error.name) {
                                jQuery('#modal-text').append('<p>' + response.error.name + '<p>');
                            }
                            if (response.error.lname) {
                                jQuery('#modal-text').append('<p>' + response.error.lname + '<p>');
                            }
                            if (response.error.fname) {
                                jQuery('#modal-text').append('<p>' + response.error.fname + '<p>');
                            }
                            if (response.error.email) {
                                jQuery('#modal-text').append('<p>' + response.error.email + '<p>');
                            }
                            if (response.error.phonenumber) {
                                jQuery('#modal-text').append('<p>' + response.error.phonenumber + '<p>');
                            }
                            if (response.error.city) {
                                jQuery('#modal-text').append('<p>' + response.error.city + '<p>');
                            }
                            if (response.error.user) {
                                jQuery('#modal-text').append('<p>' + response.error.user + '<p>');
                            }
                        }
                        if (response.success) {
                            console.log('1 success');
                            jQuery('#tabs-1').css('display', 'none');
                            jQuery('#tabs-2').css('display', 'block');

                        }
                    }
                });
            });

            jQuery('#buttonSubmitPart2').click(function () {
                var form_data = new FormData();
                form_data.append("action", 'custom_registration_submit_ajax');
                form_data.append("name", jQuery('#name').val());
                form_data.append("lname", jQuery('#lname').val());
                form_data.append("fname", jQuery('#fname').val());
                form_data.append("email", jQuery('#email').val());
                form_data.append("phonenumber", jQuery('#phonenumber').val());
                form_data.append("city", jQuery('#city').val());
                form_data.append("payType", jQuery('#payType').val());
                form_data.append("bankCard", jQuery('#bankCard').val());
                form_data.append("carMark", jQuery('#carMark').val());
                form_data.append("carModel", jQuery('#carModel').val());
                form_data.append("carColor", jQuery('#carColor').val());
                form_data.append("carYear", jQuery('#carYear').val());
                form_data.append("carNumber", jQuery('#carNumber').val());
                form_data.append("step", 2);
                jQuery.ajax({
                    type: "post",
                    url: new_reg_ajax_url,
                    data: form_data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log('success');
                        if (response.error) {
                            console.log('2 error');
                            jQuery('#modal-text').html('');
                            jQuery('#myModal').css('display', 'block');
                            if (response.error.payType) {
                                jQuery('#modal-text').append('<p>' + response.error.payType + '<p>');
                            }
                            if (response.error.bankCard) {
                                jQuery('#modal-text').append('<p>' + response.error.bankCard + '<p>');
                            }
                            if (response.error.carMark) {
                                jQuery('#modal-text').append('<p>' + response.error.carMark + '<p>');
                            }
                            if (response.error.carModel) {
                                jQuery('#modal-text').append('<p>' + response.error.carModel + '<p>');
                            }
                            if (response.error.carColor) {
                                jQuery('#modal-text').append('<p>' + response.error.carColor + '<p>');
                            }
                            if (response.error.carYear) {
                                jQuery('#modal-text').append('<p>' + response.error.carYear + '<p>');
                            }
                            if (response.error.carNumber) {
                                jQuery('#modal-text').append('<p>' + response.error.carNumber + '<p>');
                            }
                            if (response.error.user) {
                                jQuery('#modal-text').append('<p>' + response.error.user + '<p>');
                            }
                        }
                        if (response.success) {
                            console.log('2 success');
                            jQuery('#tabs-2').css('display', 'none');
                            jQuery('#tabs-3').css('display', 'block');
                        }
                    }
                });
            });

            jQuery('#buttonSubmitPart3').click(function () {
                var fileFrontPage = jQuery('#fileFrontPage')[0].files[0];
                var fileKategory = jQuery('#fileKategory')[0].files[0];
                var fileTechMark = jQuery('#fileTechMark')[0].files[0];
                var fileTechNumber = jQuery('#fileTechNumber')[0].files[0];
                var filePolis = jQuery('#filePolis')[0].files[0];
                var form_data = new FormData();
                form_data.append("fileFrontPage", fileFrontPage);
                form_data.append("fileKategory", fileKategory);
                form_data.append("fileTechMark", fileTechMark);
                form_data.append("fileTechNumber", fileTechNumber);
                form_data.append("polis", filePolis);
                form_data.append("action", 'custom_registration_submit_ajax');
                form_data.append("personalData", jQuery('#personalData').is(':checked'));
                jQuery.ajax({
                    type: "post",
                    url: new_reg_ajax_url,
                    data: form_data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log('success');
                        if (response.error) {
                            console.log('2 error');
                            jQuery('#modal-text').html('');
                            jQuery('#myModal').css('display', 'block');
                            if (response.error.fileFrontPage) {
                                jQuery('#modal-text').append('<p>' + response.error.fileFrontPage + '<p>');
                            }
                            if (response.error.fileKategory) {
                                jQuery('#modal-text').append('<p>' + response.error.fileKategory + '<p>');
                            }
                            if (response.error.fileTechMark) {
                                jQuery('#modal-text').append('<p>' + response.error.fileTechMark + '<p>');
                            }
                            if (response.error.fileTechNumber) {
                                jQuery('#modal-text').append('<p>' + response.error.fileTechNumber + '<p>');
                            }
                            if (response.error.polis) {
                                jQuery('#modal-text').append('<p>' + response.error.polis + '<p>');
                            }
                            if (response.error.user) {
                                jQuery('#modal-text').append('<p>' + response.error.user + '<p>');
                            }
                            if (response.error.doc_1) {
                                jQuery('#modal-text').append('<p>' + response.error.doc_1 + '<p>');
                            }
                            if (response.error.doc_2) {
                                jQuery('#modal-text').append('<p>' + response.error.doc_2 + '<p>');
                            }
                            if (response.error.doc_3) {
                                jQuery('#modal-text').append('<p>' + response.error.doc_3 + '<p>');
                            }
                            if (response.error.doc_4) {
                                jQuery('#modal-text').append('<p>' + response.error.doc_4 + '<p>');
                            }
                            if (response.error.doc_5) {
                                jQuery('#modal-text').append('<p>' + response.error.doc_5 + '<p>');
                            }
                        }
                        if (response.success) {
                            console.log('3 success');
                            jQuery('#modal-text').text(response.success);
                            jQuery('#myModal').css('display', 'block');
                            window.location.href = "http://uberlin.com.ua/welcometouber";

                        }
                    }
                });
            });
        });

        jQuery(document).on("change", "#carMark", function () {
            var selectval = jQuery(this).val();
            var data = {
                'action': 'custom_registration_car_model_ajax',
                'carMark': selectval
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(new_reg_ajax_url, data, function (response) {
                if (response != 0) {
                    jQuery("#carModel").html(response);
                }
            });
        });
    </script>
    <style>
        /* The Modal (background) */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 200px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.04);
        }

        /* Modal Content */
        .modal-content {
            font-size: 17px;
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 36%;
        }

        /* The Close Button */
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
	<?php
}

add_action( 'wp_footer', 'castom_reg_form_my_custom_js_footer' );


function custom_registration_function()
{
	global $name, $lname, $fname, $email, $phonenumber, $city, $payType, $bankCard, $personalData, $carMark, $carModel, $carColor, $carYear, $carNumber, $file;

	registration_form( local() );
}

function local()
{
	$local = get_locale();
	switch ( $local )
	{
		case "ru":
			return [
				'name'           => 'Имя',
				'lname'          => 'Фвмилия',
				'fname'          => 'Отчетство',
				'email'          => 'Email',
				'phonenumber'    => 'Номер телефона',
				'city'           => 'Выберете Город',
				'payType'        => 'Периодичность выплат',
				'bankCardLable'  => 'Только ПриватБанк',
				'bankCard'       => 'Номер Банковской карты',
				'personalData'   => 'Соглашение на обработку персональных данных ',
				'carMark'        => 'Марка',
				'carModel'       => 'Модель',
				'carColor'       => 'Цвет',
				'carYear'        => 'Год Выпуска',
				'carNumber'      => 'Госномера Автомобиля',
				'fileFrontPage'  => 'Водительское удостоверение (сторона с фото)',
				'fileKategory'   => 'Водительское удостоверение (сторона с категориями)',
				'fileTechMark'   => 'Тех. паспорт ТС (сторона с маркой)',
				'fileTechNumber' => 'Тех. паспорт ТС (сторона з номером)',
				'filePolis'      => 'Действующий страховой полис',
			];
			break;
		default:
			return [
				'name'           => 'Имя',
				'lname'          => 'Фамилия',
				'fname'          => 'Отчетство',
				'email'          => 'Email',
				'phonenumber'    => 'Номер телефона',
				'city'           => 'Выберете Город',
				'payType'        => 'Периодичность выплат',
				'bankCardLabel'  => 'Только ПриватБанк',
				'bankCard'       => 'Номер Банковской карты',
				'personalData'   => 'Соглашение на обработку персональных данных ',
				'carMark'        => 'Марка',
				'carModel'       => 'Модель',
				'carColor'       => 'Цвет',
				'carYear'        => 'Год Выпуска',
				'carNumber'      => 'Госномера Автомобиля',
				'fileFrontPage'  => 'Водительское удостоверение (сторона с фото)',
				'fileKategory'   => 'Водительское удостоверение (сторона с категориями)',
				'fileTechMark'   => 'Тех. паспорт ТС (сторона с маркой)',
				'fileTechNumber' => 'Тех. паспорт ТС (сторона з номером)',
				'filePolis'      => 'Действующий страховой полис',
			];
			break;
	}

}

function registration_form( $local )
{
	$respons = '
<div class="acf-form">
    <div class="acf-fields acf-form-fields -top">
        <div id="tabs" class="acf-tab-wrap -top">

            <div id="tabs-1" style="display: block">
                <div class="acf-field acf-field-text">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="lname" type="text" name="lname"
                                   placeholder="' . $local['lname'] . '"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="acf-field acf-field-text">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="name" type="text" name="name"
                                   placeholder="' . $local['name'] . '"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="acf-field acf-field-text">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="fname" type="text" name="fname"
                                   placeholder="' . $local['fname'] . '"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="acf-field acf-field-text">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="email" type="text" name="email"
                                   placeholder="' . $local['email'] . '"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="acf-field acf-field-text number-phone">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="phonenumber" type="text" name="email"
                                   placeholder="' . $local['phonenumber'] . '"
                                   value="" maxlength="9"/>
                        </div>
                    </div>
                </div>
                <div class="acf-field acf-field-select">
                    <div class="acf-input-wrap">
                        <select class="" name="city" id="city">
                            <option value="">' . $local['city'] . '</option>
                        </select>
                    </div>
                </div>
                <div class="acf-form-submit">
                    <input class="acf-button button button-primary button-large" type="button" name="buttonSubmit"
                           id="buttonSubmitPart1"
                           value="Далее"/>
                </div>
            </div>

            <div id="tabs-2" style="display: none">
                <div class="acf-field acf-field-select">
                    <div class="acf-input">
                        <select class="" name="payType" id="payType">
                            <option value="">Периодичность выплат</option>
                            <option value="Ежедневно">Ежедневно</option>
                            <option value="Еженедельно">Еженедельно</option>
                        </select>
                    </div>
                </div>
                <div class="acf-field acf-field-text">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <label for="bankCard">' . $local['bankCardLabel'] . '<strong
                                        style="color: red">*</strong></label>
                            <input id="bankCard" type="text" name="bankCard" maxlength="16"
                                   placeholder="' . $local['bankCard'] . '"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="acf-field acf-field-select">
                    <div class="acf-input">
                        <select id="carMark" class="acf-input" name="carMark">
                            <option value="">' . $local['carMark'] . '</option>
                        </select>
                    </div>
                </div>
                <div class="acf-field acf-field-select">
                    <div class="acf-input">
                        <select id="carModel" class="acf-input" name="carModel">
                            <option value="">Сначала выберете марку</option>
                        </select>
                    </div>
                </div>
                <div class="acf-field acf-field-select">
                    <div class="acf-input">
                        <select class="acf-input" name="carColor" id="carColor">
                            <option value="">Цвет</option>
                        </select>
                    </div>
                </div>
                <div class="acf-field acf-field-select">
                    <div class="acf-input">
                        <select class="acf-input" name="carYear" id="carYear">
                            <option value="">Год выпуска</option>
                            <option value="1990">1990</option>
                            <option value="1991">1991</option>
                            <option value="1992">1992</option>
                            <option value="1993">1993</option>
                            <option value="1994">1994</option>
                            <option value="1995">1995</option>
                            <option value="1996">1996</option>
                            <option value="1997">1997</option>
                            <option value="1998">1998</option>
                            <option value="1999">1999</option>
                            <option value="2000">2000</option>
                            <option value="2001">2001</option>
                            <option value="2002">2002</option>
                            <option value="2003">2003</option>
                            <option value="2004">2004</option>
                            <option value="2005">2005</option>
                            <option value="2006">2006</option>
                            <option value="2007">2007</option>
                            <option value="2008">2008</option>
                            <option value="2009">2009</option>
                            <option value="2010">2010</option>
                            <option value="2011">2011</option>
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                        </select>
                    </div>
                </div>
                <div class="acf-field acf-field-text">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="carNumber" type="text" name="carNumber"
                                   placeholder="' . $local['carNumber'] . '"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="acf-form-submit">
                    <input class="acf-button button button-primary button-large" type="button" name="buttonSubmit"
                           id="buttonSubmitPart2"
                           value="Далее"/>
                </div>
            </div>

            <div id="tabs-3" style="display: none">
                <div>
                    <p>Загрузите фото (чёткое, необрезанное и незасвеченное, не более 5 МБ)</p>
                </div>
                <div>
                    <label class="acf-input-prepend" for="fileFrontPage">' . $local['fileFrontPage'] . '</label>
                    <input type="file" id="fileFrontPage" name="fileFrontPage" accept="image/*">
                </div>
                <div>
                    <label class="acf-input-prepend" for="fileKategory">' . $local['fileKategory'] . '</label>
                    <input type="file" id="fileKategory" name="fileKategory" accept="image/*">
                </div>
                <div>
                    <label class="acf-input-prepend" for="fileTechMark">' . $local['fileTechMark'] . '</label>
                    <input type="file" id="fileTechMark" name="fileTechMark" accept="image/*">
                </div>
                <div>
                    <label class="acf-input-prepend" for="fileTechNumber">' . $local['fileTechNumber'] . '</label>
                    <input type="file" id="fileTechNumber" name="fileTechNumber" accept="image/*">
                </div>
                <div>
                    <label class="acf-input-prepend" for="filePolis">' . $local['filePolis'] . '</label>
                    <input type="file" id="filePolis" name="filePolis" accept="image/*">
                </div>
                <div>
                    <input style="width: 10% !important;" type="checkbox" id="personalData" name="personalData"
                           value=""/>
                    <label for="personalData" style="font-size: 13px;">' . $local['personalData'] . '</label>
                </div>
                <div class="acf-form-submit">
                    <input class="acf-button button button-primary button-large" type="button" name="buttonSubmit"
                           id="buttonSubmitPart3"
                           value="Регистрация"/>
                </div>
            </div>

        </div>
        <div>
            <p>Остались вопросы?<br> Подбронее по ссылке <br><a href="http://uberlin.com.ua/welcometouber/">uberlin.com.ua/welcometouber</a>
                <br>или по телефону 7373</p>
        </div>

        <!-- The Modal -->
        <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="modal-text">
                </div>
            </div>

        </div>
    </div>
</div>
	';
	echo $respons;


}

function check_card_number( $str )
{
	$str = strrev( preg_replace( '/[^0-9]/', '', $str ) );
	$chk = 0;
	for ( $i = 0; $i < strlen( $str ); $i ++ )
	{
		$tmp = intval( $str[ $i ] ) * ( 1 + $i % 2 );
		$chk += $tmp - ( $tmp > 9 ? 9 : 0 );
	}

	return ! ( $chk % 10 );
}

function server_post_request( $command, $body )
{
	$url      = 'http://1cconnect.uberlin.com.ua:4080/crm_test/hs/uberlin/' . $command;
	$response = wp_remote_post( $url, [
		'headers' => [
			'Authorization' => 'Basic ' . base64_encode( 'SiteUberlin' . ':' . 'GU1me9qi' ),
		],
		'body'    => $body
	] );

	return json_decode( $response['body'] );

}

function registration_validationPart1(
	$name, $lname, $fname, $email, $phonenumber, $city
) {
	$reg_errors = [];
	if ( strlen( $name ) < 2 || strlen( $name ) > 100 )
	{
		$reg_errors['name'] = 'Ошибка ввода имени';
	}
	if ( strlen( $lname ) < 2 || strlen( $lname ) > 100 )
	{
		$reg_errors['lname'] = 'Ошибка ввода фамилии';
	}
	if ( strlen( $fname ) < 2 || strlen( $fname ) > 100 )
	{
		$reg_errors['fname'] = 'Ошибка ввода отчества';
	}
	if ( strlen( $city ) < 2 || strlen( $city ) > 100 )
	{
		$reg_errors['city'] = 'Ошибка ввода города';
	}
	if ( is_email( $email ) )
	{
		$arg = server_post_request( 'check-email', json_encode( [ 'email' => $email ] ) );
		if ( $arg->result == 'busy' )
		{
			$reg_errors['email'] = 'Выбранный email уже существует';
		}
	} else
	{
		$reg_errors['email'] = 'Ошибка ввода Email';
	}
	if ( ! preg_match( "/[0-9]$/i", $phonenumber ) || strlen( $phonenumber ) != 9 )
	{
		$reg_errors['phonenumber'] = 'Ошибка ввода номера';
	}

	return $reg_errors;

}

function registration_validationPart2(
	$name, $lname, $fname, $email, $phonenumber, $city, $payType, $bankCard, $carMark, $carModel, $carColor, $carYear, $carNumber
) {
	$reg_errors = [];

	if ( strlen( $payType ) < 2 || strlen( $payType ) > 100 )
	{
		$reg_errors['payType'] = 'Ошибка ввода вариантов оплаты';
	}
	if ( isset( $bankCard ) )
	{
		if ( ! check_card_number( $bankCard ) )
		{
			$reg_errors['bankCard'] = 'Ошибка ввода банковской карты';
		}
	}
	if ( strlen( $carMark ) < 2 || strlen( $carMark ) > 100 )
	{
		$reg_errors['carMark'] = 'Ошибка ввода марки автомобиля';
	}
	if ( strlen( $carModel ) < 2 || strlen( $carModel ) > 100 )
	{
		$reg_errors['carModel'] = 'Ошибка ввода модели автомобиля';
	}
	if ( strlen( $carColor ) < 2 || strlen( $carColor ) > 100 )
	{
		$reg_errors['carColor'] = 'Ошибка ввода цвета автомобиля';
	}
	if ( strlen( $carYear ) < 2 || strlen( $carYear ) > 100 )
	{
		$reg_errors['carYear'] = 'Ошибка ввода года выпуска автомобиля';
	}
	if ( ! preg_match( "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8}$/", $carNumber ) || strlen( $carNumber ) != 8 )
	{
		$reg_errors['carNumber'] = 'Ошибка ввода Госномера автомобиля(номер автомобиля может содержать только латинские буквы и цифры)';
	}
	if ( strlen( $name ) < 2 || strlen( $name ) > 100 )
	{
		$reg_errors['name'] = 'Ошибка ввода имени';
	}
	if ( strlen( $lname ) < 2 || strlen( $lname ) > 100 )
	{
		$reg_errors['lname'] = 'Ошибка ввода фамилии';
	}
	if ( strlen( $fname ) < 2 || strlen( $fname ) > 100 )
	{
		$reg_errors['fname'] = 'Ошибка ввода отчества';
	}
	if ( strlen( $city ) < 2 || strlen( $city ) > 100 )
	{
		$reg_errors['city'] = 'Ошибка ввода города';
	}
	if ( is_email( $email ) )
	{
		$arg = server_post_request( 'check-email', json_encode( [ 'email' => $email ] ) );
		if ( $arg->result == 'busy' )
		{
			$reg_errors['email'] = 'Выбранный email уже существует';
		}
	} else
	{
		$reg_errors['email'] = 'Ошибка ввода Email';
	}
	if ( ! preg_match( "/[0-9]$/i", $phonenumber ) || strlen( $phonenumber ) != 9 )
	{
		$reg_errors['phonenumber'] = 'Ошибка ввода номера';
	}

	return $reg_errors;

}

function registration_validationPart3(
	$personalData, $file
) {
	$reg_errors = [];

	if ( $personalData == 'false' )
	{
		$reg_errors['personalData'] = 'Ошибка подтверждения персональных данных';
	}
	if ( empty( $file['FrontPage'] ) )
	{
		$reg_errors['fileFrontPage'] = 'Ошибка файла с фото';
	}
	if ( empty( $file['Kategory'] ) )
	{
		$reg_errors['fileKategory'] = 'Ошибка файла категории';
	}
	if ( empty( $file['TechMark'] ) )
	{
		$reg_errors['fileTechMark'] = 'Ошибка файла с тех. паспортом';
	}
	if ( empty( $file['TechNumber'] ) )
	{
		$reg_errors['fileTechNumber'] = 'Ошибка файла с тех. паспортом';
	}
	if ( empty( $file['polis'] ) )
	{
		$reg_errors['filePolis'] = 'Ошибка файла с полисом';
	}


	return $reg_errors;

}

function complete_registrationPart1(
	$name, $lname, $fname, $email, $phonenumber, $city
) {

	$response = server_post_request( 'add-driver', json_encode( [
		'client' => [
			"last_name"       => $lname,
			"first_name"      => $name,
			"patronymic_name" => $fname,
			"email"           => $email,
			"telefon_number"  => "+380" . $phonenumber,
			"city"            => $city,
		]
	] ) );

	return [
		'user' => $response
	];
}

function complete_registrationPart2(
	$name, $lname, $fname, $email, $phonenumber, $city,$payType, $bankCard, $carMark, $carModel, $carColor, $carYear, $carNumber
) {

	$response = server_post_request( 'add-driver', json_encode( [
		'client' => [
			"last_name"       => $lname,
			"first_name"      => $name,
			"patronymic_name" => $fname,
			"email"           => $email,
			"telefon_number"  => "+380" . $phonenumber,
			"city"            => $city,
			"periodicity"     => $payType,
			"bankcard_number" => $bankCard
		],
		'car'    => [
			"license_plate" => $carNumber,
			"make"          => $carMark,
			"type"          => $carModel,
			"year"          => $carYear,
			"color"         => $carColor
		]
	] ) );

	return [
		'user' => $response,
	];
}

function complete_registrationPart3(
	$email, $file
) {

	$url = 'http://1cconnect.uberlin.com.ua:4080/crm_test/hs/uberlin/add-document';

	$response1 = wp_remote_post( $url, [
		'headers' => [
			'Authorization' => 'Basic ' . base64_encode( 'SiteUberlin' . ':' . 'GU1me9qi' ),
			'X-Parameters'  => $email . ' doc_1 ' . str_replace( "image/", "", $file['FrontPage']['type'] )
		],
		'body'    => file_get_contents( $file['FrontPage']['tmp_name'] )
	] );

	if ( json_decode( $response1['body'] )->result == 'ok' )
	{
		$response2 = wp_remote_post( $url, [
			'headers' => [
				'Authorization' => 'Basic ' . base64_encode( 'SiteUberlin' . ':' . 'GU1me9qi' ),
				'X-Parameters'  => $email . ' doc_2 ' . str_replace( "image/", "", $file['FrontPage']['type'] )
			],
			'body'    => file_get_contents( $file['Kategory']['tmp_name'] )
		] );

		if ( json_decode( $response2['body'] )->result == 'ok' )
		{
			$response3 = wp_remote_post( $url, [
				'headers' => [
					'Authorization' => 'Basic ' . base64_encode( 'SiteUberlin' . ':' . 'GU1me9qi' ),
					'X-Parameters'  => $email . ' doc_3 ' . str_replace( "image/", "", $file['TechMark']['type'] )
				],
				'body'    => file_get_contents( $file['TechMark']['tmp_name'] )
			] );

			if ( json_decode( $response3['body'] )->result == 'ok' )
			{
				$response4 = wp_remote_post( $url, [
					'headers' => [
						'Authorization' => 'Basic ' . base64_encode( 'SiteUberlin' . ':' . 'GU1me9qi' ),
						'X-Parameters'  => $email . ' doc_4 ' . str_replace( "image/", "", $file['TechNumber']['type'] )
					],
					'body'    => file_get_contents( $file['TechNumber']['tmp_name'] )
				] );

				if ( json_decode( $response3['body'] )->result == 'ok' )
				{
					$response5 = wp_remote_post( $url, [
						'headers' => [
							'Authorization' => 'Basic ' . base64_encode( 'SiteUberlin' . ':' . 'GU1me9qi' ),
							'X-Parameters'  => $email . ' doc_5 ' . str_replace( "image/", "", $file['polis']['type'] )
						],
						'body'    => file_get_contents( $file['polis']['tmp_name'] )
					] );
				}
			}
		}
	}


	return [
		'doc_1' => json_decode( $response1['body'] ),
		'doc_2' => json_decode( $response2['body'] ),
		'doc_3' => json_decode( $response3['body'] ),
		'doc_4' => json_decode( $response4['body'] ),
		'doc_5' => json_decode( $response5['body'] )
	];
}

// Register a new shortcode: [cr_custom_registration]
add_shortcode( 'cr_custom_registration', 'custom_registration_shortcode' );

// The callback function that will replace [book]
function custom_registration_shortcode()
{
	ob_start();
	custom_registration_function();

	return ob_get_clean();
}
