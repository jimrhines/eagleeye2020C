<?php
/*
Template Name: Request Quote NetSuite Processing Page
*/

//Need to update form data submitted to align to new RFQ form
//http://jafty.com/blog/tag/get-contact-form-7-data/

//https://stackoverflow.com/questions/30533216/contact-form-7-use-hook-created-using-wpcf7-before-send-mail-for-only-one-conta
add_action( 'wpcf7_before_send_mail', 'wpcf7_post_form_values' );
	function wpcf7_post_form_values($contact_form){

		$submission = WPCF7_Submission::get_instance();
		$posted_data = $submission->get_posted_data();
		if( !empty($posted_data["first-name"])){  //use a field unique to your form
	       	$first_name = trim($posted_data["first-name"]);
	       	$last_name = trim($posted_data["last-name"]);
			$email = trim($posted_data["email-address"]);
			$phone = trim($posted_data["phone"]);
			$company = trim($posted_data["company"]);
			$URL = trim($posted_data["company-url"]);
			$city = trim($posted_data["city"]);
			$state = trim($posted_data["state"]);
			$province = trim($posted_data["province"]);
			$country = trim($posted_data["country"]);
			switch ($country) {
				case 'United States':
					$NScountry = 'US';
					break;
				case 'Canada':
					$NScountry = 'CA';
					break;
				case 'Afghanistan':
					$NScountry = 'AF';
					break;
				case 'Aland Islands':
					$NScountry = 'AX';
					break;
				case 'Albania':
					$NScountry = 'AL';
					break;
				case 'Algeria':
					$NScountry = 'DZ';
					break;
				case 'American Samoa':
					$NScountry = 'AS';
					break;
				case 'Andorra':
					$NScountry = 'AD';
					break;
				case 'Angola':
					$NScountry = 'AO';
					break;
				case 'Anguilla':
					$NScountry = 'AI';
					break;
				case 'Antartica':
					$NScountry = 'AQ';
					break;
				case 'Antigua and Barbuda':
					$NScountry = 'AG';
					break;
				case 'Argentina':
					$NScountry = 'AR';
					break;
				case 'Armenia':
					$NScountry = 'AM';
					break;
				case 'Aruba':
					$NScountry = 'AW';
					break;
				case 'Australia':
					$NScountry = 'AU';
					break;
				case 'Austria':
					$NScountry = 'AT';
					break;
				case 'Azerbaijan':
					$NScountry = 'AZ';
					break;
				case 'Bahamas':
					$NScountry = 'BS';
					break;
				case 'Bahrain':
					$NScountry = 'BH';
					break;
				case 'Bangladesh':
					$NScountry = 'BD';
					break;
				case 'Barbados':
					$NScountry = 'BB';
					break;
				case 'Belarus':
					$NScountry = 'BY';
					break;
				case 'Belgium':
					$NScountry = 'BE';
					break;
				case 'Belize':
					$NScountry = 'BZ';
					break;
				case 'Benin':
					$NScountry = 'BJ';
					break;
				case 'Bermuda':
					$NScountry = 'BM';
					break;
				case 'Bhutan':
					$NScountry = 'BT';
					break;
				case 'Bolivia':
					$NScountry = 'BO';
					break;
				case 'Bonaire, Saint Eustatius and Saba':
					$NScountry = 'BQ';
					break;
				case 'Bosnia and Herzegovina':
					$NScountry = 'BA';
					break;
				case 'Botswana':
					$NScountry = 'BW';
					break;
				case 'Bouvet Island':
					$NScountry = 'BV';
					break;
				case 'Brazil':
					$NScountry = 'BR';
					break;
				case 'British Indian Ocean Territory':
					$NScountry = 'IO';
					break;
				case 'Brunei Darussalam':
					$NScountry = 'BN';
					break;
				case 'Bulgaria':
					$NScountry = 'BG';
					break;
				case 'Burkina Faso':
					$NScountry = 'BF';
					break;
				case 'Burundi':
					$NScountry = 'BI';
					break;
				case 'Cambodia':
					$NScountry = 'KH';
					break;
				case 'Canary Islands':
					$NScountry = 'IC';
					break;
				case 'Cape Verde':
					$NScountry = 'CV';
					break;
				case 'Cayman Islands':
					$NScountry = 'KY';
					break;
				case 'Central African Republic':
					$NScountry = 'CF';
					break;
				case 'Ceuta and Melilla':
					$NScountry = 'EA';
					break;
				case 'Chad':
					$NScountry = 'TD';
					break;
				case 'Chile':
					$NScountry = 'CL';
					break;
				case 'China':
					$NScountry = 'CN';
					break;
				case 'Christmas Island':
					$NScountry = 'CX';
					break;
				case 'Cocos (Keeling) Island':
					$NScountry = 'CC';
					break;
				case 'Colombia':
					$NScountry = 'CO';
					break;
				case 'Comoros':
					$NScountry = 'KM';
					break;
				case 'Congo, Democratic Republic of':
					$NScountry = 'CD';
					break;
				case 'Congo, Republic of':
					$NScountry = 'CG';
					break;
				case 'Cook Islands':
					$NScountry = 'CK';
					break;
				case 'Costa Rica':
					$NScountry = 'CR';
					break;
				case "Cote d'Ivoire":
					$NScountry = 'CI';
					break;
				case 'Croatia/Hrvatska':
					$NScountry = 'HR';
					break;
				case 'Cuba':
					$NScountry = 'CU';
					break;
				case 'Curacao':
					$NScountry = 'CW';
					break;
				case 'Cyprus':
					$NScountry = 'CY';
					break;
				case 'Czech Republic':
					$NScountry = 'CZ';
					break;
				case 'Denmark':
					$NScountry = 'DK';
					break;
				case 'Djibouti':
					$NScountry = 'DJ';
					break;
				case 'Dominica':
					$NScountry = 'DM';
					break;
				case 'Dominican Republic':
					$NScountry = 'DO';
					break;
				case 'East Timor':
					$NScountry = 'TL';
					break;
				case 'Ecuador':
					$NScountry = 'EC';
					break;
				case 'Egypt':
					$NScountry = 'EG';
					break;
				case 'El Salvador':
					$NScountry = 'SV';
					break;
				case 'Equatorial Guinea':
					$NScountry = 'GQ';
					break;
				case 'Eritrea':
					$NScountry = 'ER';
					break;
				case 'Estonia':
					$NScountry = 'EE';
					break;
				case 'Ethiopia':
					$NScountry = 'ET';
					break;
				case 'Falkland Islands':
					$NScountry = 'FK';
					break;
				case 'Faroe Islands':
					$NScountry = 'FO';
					break;
				case 'Fiji':
					$NScountry = 'FJ';
					break;
				case 'Finland':
					$NScountry = 'FI';
					break;
				case 'France':
					$NScountry = 'FR';
					break;
				case 'French Guiana':
					$NScountry = 'GF';
					break;
				case 'French Polynesia':
					$NScountry = 'PF';
					break;
				case 'French Southern Territories':
					$NScountry = 'TF';
					break;
				case 'Gabon':
					$NScountry = 'GA';
					break;
				case 'Georgia':
					$NScountry = 'GE';
					break;
				case 'Germany':
					$NScountry = 'DE';
					break;
				case 'Ghana':
					$NScountry = 'GH';
					break;
				case 'Gibraltar':
					$NScountry = 'GI';
					break;
				case 'Greece':
					$NScountry = 'GR';
					break;
				case 'Greenland':
					$NScountry = 'GL';
					break;
				case 'Grenada':
					$NScountry = 'GD';
					break;
				case 'Guadeloupe':
					$NScountry = 'GP';
					break;
				case 'Guam':
					$NScountry = 'GU';
					break;
				case 'Guatemala':
					$NScountry = 'GT';
					break;
				case 'Guernsey':
					$NScountry = 'GG';
					break;
				case 'Guinea-Bissau':
					$NScountry = 'GW';
					break;
				case 'Guinea':
					$NScountry = 'GN';
					break;
				case 'Guyana':
					$NScountry = 'GY';
					break;
				case 'Haiti':
					$NScountry = 'HT';
					break;
				case 'Heard and McDonald Islands':
					$NScountry = 'HM';
					break;
				case 'Holy See (City Vatican State)':
					$NScountry = 'VA';
					break;
				case 'Honduras':
					$NScountry = 'HN';
					break;
				case 'Hong Kong':
					$NScountry = 'HK';
					break;
				case 'Hungary':
					$NScountry = 'HU';
					break;
				case 'Iceland':
					$NScountry = 'IS';
					break;
				case 'India':
					$NScountry = 'IN';
					break;
				case 'Indonesia':
					$NScountry = 'ID';
					break;
				case 'Iran (Islamic Republic of)':
					$NScountry = 'IR';
					break;
				case 'Iraq':
					$NScountry = 'IQ';
					break;
				case 'Ireland':
					$NScountry = 'IE';
					break;
				case 'Isle of Man':
					$NScountry = 'IM';
					break;
				case 'Israel':
					$NScountry = 'IL';
					break;
				case 'Italy':
					$NScountry = 'IT';
					break;
				case 'Jamaica':
					$NScountry = 'JM';
					break;
				case 'Japan':
					$NScountry = 'JP';
					break;
				case 'Jersey':
					$NScountry = 'JE';
					break;
				case 'Jordan':
					$NScountry = 'JO';
					break;
				case 'Kazakhstan':
					$NScountry = 'KZ';
					break;
				case 'Kenya':
					$NScountry = 'KE';
					break;
				case 'Kiribati':
					$NScountry = 'KI';
					break;
				case "Korea (Democratic People's Republic)":
					$NScountry = 'KP';
					break;
				case 'Korea (Republic of)':
					$NScountry = 'KR';
					break;
				case 'Kosovo':
					$NScountry = 'XK';
					break;
				case 'Kuwait':
					$NScountry = 'KW';
					break;
				case 'Kyrgyzstan':
					$NScountry = 'KG';
					break;
				case 'Laos':
					$NScountry = 'LA';
					break;
				case 'Latvia':
					$NScountry = 'LV';
					break;
				case 'Lebanon':
					$NScountry = 'LB';
					break;
				case 'Lesotho':
					$NScountry = 'LS';
					break;
				case 'Liberia':
					$NScountry = 'LR';
					break;
				case 'Libya':
					$NScountry = 'LY';
					break;
				case 'Liechtenstein':
					$NScountry = 'LI';
					break;
				case 'Lithuania':
					$NScountry = 'LT';
					break;
				case 'Luxembourg':
					$NScountry = 'LU';
					break;
				case 'Macau':
					$NScountry = 'MO';
					break;
				case 'Macedonia':
					$NScountry = 'MK';
					break;
				case 'Madagascar':
					$NScountry = 'MG';
					break;
				case 'Malawi':
					$NScountry = 'MW';
					break;
				case 'Malaysia':
					$NScountry = 'MY';
					break;
				case 'Maldives':
					$NScountry = 'MV';
					break;
				case 'Mali':
					$NScountry = 'ML';
					break;
				case 'Malta':
					$NScountry = 'MT';
					break;
				case 'Marshall Islands':
					$NScountry = 'MH';
					break;
				case 'Martinique':
					$NScountry = 'MQ';
					break;
				case 'Mauritania':
					$NScountry = 'MR';
					break;
				case 'Mauritius':
					$NScountry = 'MU';
					break;
				case 'Mayotte':
					$NScountry = 'YT';
					break;
				case 'Mexico':
					$NScountry = 'MX';
					break;
				case 'Micronesia, Federal State of':
					$NScountry = 'FM';
					break;
				case 'Moldova, Republic of':
					$NScountry = 'MD';
					break;
				case 'Monaco':
					$NScountry = 'MC';
					break;
				case 'Mongolia':
					$NScountry = 'MN';
					break;
				case 'Montenegro':
					$NScountry = 'ME';
					break;
				case 'Montserrat':
					$NScountry = 'MS';
					break;
				case 'Morocco':
					$NScountry = 'MA';
					break;
				case 'Mozambique':
					$NScountry = 'MZ';
					break;
				case 'Myanmar (Burma)':
					$NScountry = 'MM';
					break;
				case 'Namibia':
					$NScountry = 'NA';
					break;
				case 'Nauru':
					$NScountry = 'NR';
					break;
				case 'Nepal':
					$NScountry = 'NP';
					break;
				case 'Netherlands':
					$NScountry = 'NL';
					break;
				case 'New Caledonia':
					$NScountry = 'NC';
					break;
				case 'New Zealand':
					$NScountry = 'NZ';
					break;
				case 'Nicaragua':
					$NScountry = 'NI';
					break;
				case 'Niger':
					$NScountry = 'NE';
					break;
				case 'Nigeria':
					$NScountry = 'NG';
					break;
				case 'Niue':
					$NScountry = 'NU';
					break;
				case 'Norfolk Island':
					$NScountry = 'NF';
					break;
				case 'Northern Mariana Islands':
					$NScountry = 'MP';
					break;
				case 'Norway':
					$NScountry = 'NO';
					break;
				case 'Oman':
					$NScountry = 'OM';
					break;
				case 'Pakistan':
					$NScountry = 'PK';
					break;
				case 'Palau':
					$NScountry = 'PW';
					break;
				case 'Panama':
					$NScountry = 'PA';
					break;
				case 'Papua New Guinea':
					$NScountry = 'PG';
					break;
				case 'Paraguay':
					$NScountry = 'PY';
					break;
				case 'Peru':
					$NScountry = 'PE';
					break;
				case 'Philippines':
					$NScountry = 'PH';
					break;
				case 'Pitcairn Island':
					$NScountry = 'PN';
					break;
				case 'Poland':
					$NScountry = 'PL';
					break;
				case 'Portugal':
					$NScountry = 'PT';
					break;
				case 'Puerto Rico':
					$NScountry = 'PR';
					break;
				case 'Qatar':
					$NScountry = 'QA';
					break;
				case 'Reunion Island':
					$NScountry = 'RE';
					break;
				case 'Romania':
					$NScountry = 'RO';
					break;
				case 'Russian Federation':
					$NScountry = 'RU';
					break;
				case 'Rwanda':
					$NScountry = 'RW';
					break;
				case 'Saint Barth&#233;lemy':
					$NScountry = 'BL';
					break;
				case 'Saint Helena':
					$NScountry = 'SH';
					break;
				case 'Saint Kitts and Nevis':
					$NScountry = 'KN';
					break;
				case 'Saint Lucia':
					$NScountry = 'LC';
					break;
				case 'Saint Martin':
					$NScountry = 'MF';
					break;
				case 'Saint Vincent and the Grenadines':
					$NScountry = 'VC';
					break;
				case 'Samoa':
					$NScountry = 'WS';
					break;
				case 'San Marino':
					$NScountry = 'SM';
					break;
				case 'Sao Tome and Principe':
					$NScountry = 'ST';
					break;
				case 'Saudi Arabia':
					$NScountry = 'SA';
					break;
				case 'Senegal':
					$NScountry = 'SN';
					break;
				case 'Serbia':
					$NScountry = 'RS';
					break;
				case 'Seychelles':
					$NScountry = 'SC';
					break;
				case 'Sierra Leone':
					$NScountry = 'SL';
					break;
				case 'Singapore':
					$NScountry = 'SG';
					break;
				case 'Sint Maarten':
					$NScountry = 'SX';
					break;
				case 'Slovak Republic':
					$NScountry = 'SK';
					break;
				case 'Slovenia':
					$NScountry = 'SI';
					break;
				case 'Solomon Islands':
					$NScountry = 'SB';
					break;
				case 'Somalia':
					$NScountry = 'SO';
					break;
				case 'South Africa':
					$NScountry = 'ZA';
					break;
				case 'South Georgia':
					$NScountry = 'GS';
					break;
				case 'South Sudan':
					$NScountry = 'SS';
					break;
				case 'Spain':
					$NScountry = 'ES';
					break;
				case 'Sri Lanka':
					$NScountry = 'LK';
					break;
				case 'St. Pierre and Miquelon':
					$NScountry = 'PM';
					break;
				case 'State of Palestine':
					$NScountry = 'PS';
					break;
				case 'Sudan':
					$NScountry = 'SD';
					break;
				case 'Suriname':
					$NScountry = 'SR';
					break;
				case 'Svalbard and Jan Mayen Islands':
					$NScountry = 'SJ';
					break;
				case 'Swaziland':
					$NScountry = 'SZ';
					break;
				case 'Sweden':
					$NScountry = 'SE';
					break;
				case 'Switzerland':
					$NScountry = 'CH';
					break;
				case 'Syrian Arab Republic':
					$NScountry = 'SY';
					break;
				case 'Taiwan':
					$NScountry = 'TW';
					break;
				case 'Tajikistan':
					$NScountry = 'TJ';
					break;
				case 'Tanzania':
					$NScountry = 'TZ';
					break;
				case 'Thailand':
					$NScountry = 'TH';
					break;
				case 'The Gambia':
					$NScountry = 'GM';
					break;
				case 'Togo':
					$NScountry = 'TG';
					break;
				case 'Tokelau':
					$NScountry = 'TK';
					break;
				case 'Tonga':
					$NScountry = 'TO';
					break;
				case 'Trinidad and Tobago':
					$NScountry = 'TT';
					break;
				case 'Tunisia':
					$NScountry = 'TN';
					break;
				case 'Turkey':
					$NScountry = 'TR';
					break;
				case 'Turkmenistan':
					$NScountry = 'TM';
					break;
				case 'Turks and Caicos Islands':
					$NScountry = 'TC';
					break;
				case 'Tuvalu':
					$NScountry = 'TV';
					break;
				case 'Uganda':
					$NScountry = 'UG';
					break;
				case 'Ukraine':
					$NScountry = 'UA';
					break;
				case 'United Arab Emirates':
					$NScountry = 'AE';
					break;
				case 'United Kingdom':
					$NScountry = 'GB';
					break;
				case 'Uruguay':
					$NScountry = 'UY';
					break;
				case 'US Minor Outlying Islands':
					$NScountry = 'UM';
					break;
				case 'Uzbekistan':
					$NScountry = 'UZ';
					break;
				case 'Vanuatu':
					$NScountry = 'VU';
					break;
				case 'Venezuela':
					$NScountry = 'VE';
					break;
				case 'Vietnam':
					$NScountry = 'VN';
					break;
				case 'Virgin Islands (British)':
					$NScountry = 'VG';
					break;
				case 'Virgin Islands (USA)':
					$NScountry = 'VI';
					break;
				case 'Wallis and Futuna':
					$NScountry = 'WF';
					break;
				case 'Western Sahara':
					$NScountry = 'EH';
					break;
				case 'Yemen':
					$NScountry = 'YE';
					break;
				case 'Zambia':
					$NScountry = 'ZM';
					break;
				case 'Zimbabwe':
					$NScountry = 'ZW';
					break;
				default:
					$NScountry = '';
					break;
			}
			$zip = trim($posted_data["zip"]);
			$product_name = trim($posted_data["product-name"]);//this one is new and will require NetSuite updates and additional lines in the transfer to Netsuite below
			$system_needs = trim($posted_data["product-needs"]);
			$lead_source = trim($posted_data["lead-source"]);
			switch ($lead_source) {
				case 'Google':
					$NSlead_source = '10';
					break;
				case 'Yahoo/Bing':
					$NSlead_source = '28';
					break;
				case 'Global Spec':
					$NSlead_source = '9';
					break;
				case 'Direct Industry':
					$NSlead_source = '4';
					break;
				case 'Thomas Net':
					$NSlead_source = '20';
					break;
				case 'Email Campaign':
					$NSlead_source = '7';
					break;
				case 'Distributor/Reseller':
					$NSlead_source = '5';
					break;
				case 'Colleague':
					$NSlead_source = '3';
					break;
				case 'Trade Show':
					$NSlead_source = '-5';
					break;
				case 'Trade Magazine':
					$NSlead_source = '21';
					break;
				case 'Other':
					$NSlead_source = '-3';
					break;
				default:
					$NSlead_source = '';
					break;
			}
			$subscription_consent = $_REQUEST['consent'];
		}
	}
}	

		define("NETSUITE_URL", "https://1324912.restlets.api.netsuite.com/app/site/hosting/restlet.nl");
		define("NETSUITE_URL_2", "https://1324912.restlets.api.netsuite.com/app/site/hosting/restlet.nl?script=6&deploy=1");
		define("NETSUITE_ACCOUNT", "1324912");
		define("NETSUITE_CONSUMER_KEY", "b1e7087db11cf015e642601bea25aa95a891c207bc51f9988e175016ad793b97");
		define("NETSUITE_CONSUMER_SECRET", "5228144e39db6aa5bca30d28bb7c6c35a2ccc207bbf6a4feafb61e123120fd6f");
		define("NETSUITE_TOKEN_ID", "44ee385bfaa987c6bb9d971a46e50d9c7ffc55e76dcc0445433ed72a20756ba2");
		define("NETSUITE_TOKEN_SECRET", "0853ec57c60a9b508e895788dd5a670992036500ecdf56eb37844d242da19f30");

		function rand_string($length) {
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$size = strlen( $chars );
			$str = "";
			for( $i = 0; $i < $length; $i++ ) {
				$str .= $chars[ rand( 0, $size - 1 ) ];
			}

			return $str;
		}

		function sendOrderToNS() {
			global $first_name, $last_name, $email, $phone, $company, $URL, $city, $state, $NScountry, $zip, $product_name, $system_needs, $model_number, $NSlead_source;
			$details = array(array("recordtype" => "lead",
			   	        "action" => "create",
			   	        "first_name" => $first_name,
			   	        "last_name" => $last_name,
			   	        "email" => $email,
			   	        "phone" => $phone,
			   	        "company" => $company,
			   	        "URL" => $URL,
			   	        "city" => $city,
			   	        "state" => $state,
			   	        "country" => $NScountry,
			   	        "zip" => $zip,
			   	        "product_name" => $product_name,
			   	        "system_needs" => $system_needs,
			   	        "lead_source" => $NSlead_source));

			$data_string = json_encode($details);

			$oauth_nonce = rand_string(20);
			$oauth_timestamp = time();
			$oauth_signature_method = "HMAC-SHA1";
			$oauth_version = "1.0";
			$base_string =
			    "POST&" . rawurlencode(NETSUITE_URL) . "&" .
				rawurlencode(
					"deploy=" . "1"
			        	. "&oauth_consumer_key=" . NETSUITE_CONSUMER_KEY
			        	. "&oauth_nonce=" . $oauth_nonce
			        	. "&oauth_signature_method=" . $oauth_signature_method
			        	. "&oauth_timestamp=" . $oauth_timestamp
			        	. "&oauth_token=" . NETSUITE_TOKEN_ID
			        	. "&oauth_version=" . $oauth_version
			        	. "&script=" . "6"
		    		);
			$sig_string = rawurlencode(NETSUITE_CONSUMER_SECRET) . '&' . rawurlencode(NETSUITE_TOKEN_SECRET);
			$signature = base64_encode(hash_hmac("sha1", $base_string, $sig_string, true));

			$auth_header = 'OAuth '
			    . 'oauth_signature="' . rawurlencode($signature) . '",'
			    . 'oauth_version="' . rawurlencode($oauth_version) . '",'
			    . 'oauth_nonce="' . rawurlencode($oauth_nonce) . '",'
			    . 'oauth_signature_method="' . rawurlencode($oauth_signature_method) . '",'
			    . 'oauth_consumer_key="' . rawurlencode(NETSUITE_CONSUMER_KEY) . '",'
			    . 'oauth_token="' . rawurlencode(NETSUITE_TOKEN_ID) . '",'
			    . 'oauth_timestamp="' . $oauth_timestamp . '",'
			    . 'realm="' . rawurlencode(NETSUITE_ACCOUNT) .'"';

			//echo "<br><br>Signature:<br><br>";
			//echo $signature."<br>";
			//echo rawurlencode($signature);
			
			$auth_header_net = 'OAuth oauth_signature="rr%2FMGnVdECllQ9VjFr7uhXrvoV4%3D",oauth_version="1.0",oauth_nonce="qUcnY4KQk5s7mP6BTnjx",oauth_signature_method="HMAC-SHA1",oauth_consumer_key="934688c5814379c8312c2a9bf86dd358849e6918c81aa98e1f75345e11731bc4",oauth_token="a6fb1b38575c62ccef5eb818120f1cb67809191e7881eae141ea903351508aea",oauth_timestamp="1524518367",realm="1324912_SB1"';

			//echo "<br><br>Auth Header:<br>";
			//echo $auth_header;
			//echo "<br><br>.Net Auth Header:<br>";
			//echo $auth_header_net."<br><br>";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, NETSUITE_URL_2);
			curl_setopt($ch, CURLOPT_POST, true); //changed "POST" to true
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'User-Agent-x:SuiteScript-Call',
				'Authorization:' . $auth_header,
				'Content-Type:application/json',
				'Accept:application/json',
				'Content-Length:' . strlen($data_string)
			]);

			$result = curl_exec($ch);

			if ($result === false) //We are not hitting this since we are getting a server response.
			{
				print_r('Curl error: ' . curl_error($ch));
			}

			//print($result); // Authentication error.
			header("Location: ../request-quote-confirmation");
			curl_close($ch);
		}

		sendOrderToNS(); //Call the function to test.
   
?>