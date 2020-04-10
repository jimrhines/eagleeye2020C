<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if(!class_exists('vx_netsuite_vars')){
    class vx_netsuite_vars{
    public static $pass='' ; 
      public static $email='' ; 
      public static $account='' ; 
      public static $app='' ;    
          public static $role='' ;   
      public static $host='https://webservices.netsuite.com';    
    }
}

if(!class_exists('vxcf_netsuite_api')){
    
class vxcf_netsuite_api extends vxcf_netsuite{
  
   public static $service;    
      public $url='' ; 
    public $info='' ; // info
    public $error= "";
    public $timeout= "30";

function __construct($info) {

$this->info=$info;
    require_once 'NSPHPClient.php';
    require_once 'NetSuiteService.php';
   
if(empty(self::$service)){
$info=isset($info['data']) ? $info['data'] : array();     
$this->set_service($info);
} 
  
}
public function get_token(){
$info=$this->info;
$info=isset($info['data']) ? $info['data'] : array();  

$params = new GetDataCenterUrlsRequest();
$params->account = vx_netsuite_vars::$account;

try{
$res = self::$service->getDataCenterUrls($params);
if(!empty($res->getDataCenterUrlsResult->dataCenterUrls->webservicesDomain)){
$res=$res->getDataCenterUrlsResult->dataCenterUrls;
$info['service_url']=$this->url=$res->webservicesDomain;   
$info['system_url']=$this->url=$res->systemDomain;   
$this->set_service($info);
}

   $users=$this->get_status_list();

    if(is_array($users) && count($users)>0){
    $info['valid_token']='true';    
}else{
      unset($info['valid_token']);
      if(is_string($users) && !empty($users)){
         $info['error'] =$users;
         $info['valid_token']=''; 
      }  
}
}catch(Exception $e){
   $info['error']=$e->getMessage();
   $info['valid_token']=''; 
}

    return $info;
}
public function set_service($info){
   
    if(isset($info['pass'])){ 
       vx_netsuite_vars::$pass= $info['pass'];
    }
    if(isset($info['email'])){ 
       vx_netsuite_vars::$email= $info['email'];
    }
        if(isset($info['account_id'])){ 
       vx_netsuite_vars::$account= $info['account_id'];
    }  
    if(isset($info['app_id'])){ 
       vx_netsuite_vars::$app= $info['app_id'];
    }  
    if(isset($info['role'])){ 
       vx_netsuite_vars::$role= $info['role'];
    }
    if(isset($info['url'])){ 
        $url=$info['url'];
         if(isset($info['system_url'])){ 
     $this->url=trailingslashit($info['system_url']);   
     }
        if(!empty($info['service_url'])){
         $url=$info['service_url'];   
        }
        $url=str_replace('system','webservices',$url);
       $this->service_url= trailingslashit( $url );
       
    vx_netsuite_vars::$host=$this->service_url;
        
    }
/* 
     ini_set('max_execution_time', 300); //300 seconds = 5 minutes
ini_set('max_execution_time', 0); // for infinite time of execution 
*/
  /*  $module_name='Customer';
$emailSearchField = new SearchStringField();
$emailSearchField->operator = "startsWith";
$emailSearchField->searchValue = 'bio';
$search_class=$module_name.'SearchBasic';
$search = new $search_class;
$search->email = $emailSearchField;

$request = new SearchRequest();
$request->searchRecord = $search;

$s_res = $this->post_crm('search',$request,20);
print_r($s_res);
die();*/
self::$service= new NetSuiteService(); 
    if(!empty($info['api']) && $info['api'] == 'token'){
$gen=new cfx_netsuite_token($info['key'],$info['secret'],$info['token'],$info['token_secret']);
    self::$service->setTokenGenerator($gen);
}
}
public function countries(){
    $json='{"AF":{"name":"Afghanistan","ns":"_afghanistan"},"AX":{"name":"Aland Islands","ns":"_alandIslands"},"AL":{"name":"Albania","ns":"_albania"},"DZ":{"name":"Algeria","ns":"_algeria"},"AS":{"name":"American Samoa","ns":"_americanSamoa"},"AD":{"name":"Andorra","ns":"_andorra"},"AO":{"name":"Angola","ns":"_angola"},"AI":{"name":"Anguilla","ns":"_anguilla"},"AQ":{"name":"Antarctica","ns":"_antarctica"},"AG":{"name":"Antigua and Barbuda","ns":"_antiguaAndBarbuda"},"AR":{"name":"Argentina","ns":"_argentina"},"AM":{"name":"Armenia","ns":"_armenia"},"AW":{"name":"Aruba","ns":"_aruba"},"AU":{"name":"Australia","ns":"_australia"},"AT":{"name":"Austria","ns":"_austria"},"AZ":{"name":"Azerbaijan","ns":"_azerbaijan"},"BS":{"name":"Bahamas","ns":"_bahamas"},"BH":{"name":"Bahrain","ns":"_bahrain"},"BD":{"name":"Bangladesh","ns":"_bangladesh"},"BB":{"name":"Barbados","ns":"_barbados"},"BY":{"name":"Belarus","ns":"_belarus"},"BE":{"name":"Belgium","ns":"_belgium"},"BZ":{"name":"Belize","ns":"_belize"},"BJ":{"name":"Benin","ns":"_benin"},"BM":{"name":"Bermuda","ns":"_bermuda"},"BT":{"name":"Bhutan","ns":"_bhutan"},"BO":{"name":"Bolivia","ns":"_bolivia"},"BA":{"name":"Bosnia and Herzegovina","ns":"_bosniaAndHerzegovina"},"BW":{"name":"Botswana","ns":"_botswana"},"BV":{"name":"Bouvet Island","ns":"_bouvetIsland"},"BR":{"name":"Brazil","ns":"_brazil"},"IO":{"name":"British Indian Ocean Territory","ns":"_britishIndianOceanTerritory"},"BN":{"name":"Brunei","ns":"_bruneiDarussalam"},"BG":{"name":"Bulgaria","ns":"_bulgaria"},"BF":{"name":"Burkina Faso","ns":"_burkinaFaso"},"BI":{"name":"Burundi","ns":"_burundi"},"KH":{"name":"Cambodia","ns":"_cambodia"},"CM":{"name":"Cameroon","ns":"_cameroon"},"CA":{"name":"Canada","ns":"_canada"},"CV":{"name":"Cape Verde","ns":"_capeVerde"},"KY":{"name":"Cayman Islands","ns":"_caymanIslands"},"CF":{"name":"Central African Republic","ns":"_centralAfricanRepublic"},"TD":{"name":"Chad","ns":"_chad"},"CL":{"name":"Chile","ns":"_chile"},"CN":{"name":"China","ns":"_china"},"CX":{"name":"Christmas Island","ns":"_christmasIsland"},"CO":{"name":"Colombia","ns":"_colombia"},"KM":{"name":"Comoros","ns":"_comoros"},"CK":{"name":"Cook Islands","ns":"_cookIslands"},"CR":{"name":"Costa Rica","ns":"_costaRica"},"HR":{"name":"Croatia","ns":"_croatiaHrvatska"},"CU":{"name":"Cuba","ns":"_cuba"},"CY":{"name":"Cyprus","ns":"_cyprus"},"CZ":{"name":"Czech Republic","ns":"_czechRepublic"},"DK":{"name":"Denmark","ns":"_denmark"},"DJ":{"name":"Djibouti","ns":"_djibouti"},"DM":{"name":"Dominica","ns":"_dominica"},"DO":{"name":"Dominican Republic","ns":"_dominicanRepublic"},"EC":{"name":"Ecuador","ns":"_ecuador"},"EG":{"name":"Egypt","ns":"_egypt"},"SV":{"name":"El Salvador","ns":"_elSalvador"},"GQ":{"name":"Equatorial Guinea","ns":"_equatorialGuinea"},"ER":{"name":"Eritrea","ns":"_eritrea"},"EE":{"name":"Estonia","ns":"_estonia"},"ET":{"name":"Ethiopia","ns":"_ethiopia"},"FK":{"name":"Falkland Islands","ns":"_falklandIslands"},"FO":{"name":"Faroe Islands","ns":"_faroeIslands"},"FJ":{"name":"Fiji","ns":"_fiji"},"FI":{"name":"Finland","ns":"_finland"},"FR":{"name":"France","ns":"_france"},"GF":{"name":"French Guiana","ns":"_frenchGuiana"},"PF":{"name":"French Polynesia","ns":"_frenchPolynesia"},"TF":{"name":"French Southern Territories","ns":"_frenchSouthernTerritories"},"GA":{"name":"Gabon","ns":"_gabon"},"GM":{"name":"Gambia","ns":"_gambia"},"GE":{"name":"Georgia","ns":"_georgia"},"DE":{"name":"Germany","ns":"_germany"},"GH":{"name":"Ghana","ns":"_ghana"},"GI":{"name":"Gibraltar","ns":"_gibraltar"},"GR":{"name":"Greece","ns":"_greece"},"GL":{"name":"Greenland","ns":"_greenland"},"GD":{"name":"Grenada","ns":"_grenada"},"GP":{"name":"Guadeloupe","ns":"_guadeloupe"},"GU":{"name":"Guam","ns":"_guam"},"GT":{"name":"Guatemala","ns":"_guatemala"},"GG":{"name":"Guernsey","ns":"_guernsey"},"GN":{"name":"Guinea","ns":"_guineaBissau"},"GY":{"name":"Guyana","ns":"_guyana"},"HT":{"name":"Haiti","ns":"_haiti"},"VA":{"name":"Vatican","ns":"_holySeeCityVaticanState"},"HN":{"name":"Honduras","ns":"_honduras"},"HK":{"name":"Hong Kong","ns":"_hongKong"},"HU":{"name":"Hungary","ns":"_hungary"},"IS":{"name":"Iceland","ns":"_iceland"},"IN":{"name":"India","ns":"_india"},"ID":{"name":"Indonesia","ns":"_indonesia"},"IR":{"name":"Iran","ns":"_iranIslamicRepublicOf"},"IQ":{"name":"Iraq","ns":"_iraq"},"IE":{"name":"Ireland","ns":"_ireland"},"IM":{"name":"Isle of Man","ns":"_isleOfMan"},"IL":{"name":"Israel","ns":"_israel"},"IT":{"name":"Italy","ns":"_italy"},"JM":{"name":"Jamaica","ns":"_jamaica"},"JP":{"name":"Japan","ns":"_japan"},"JE":{"name":"Jersey","ns":"_jersey"},"JO":{"name":"Jordan","ns":"_jordan"},"KZ":{"name":"Kazakhstan","ns":"_kazakhstan"},"KE":{"name":"Kenya","ns":"_kenya"},"KI":{"name":"Kiribati","ns":"_kiribati"},"KP":{"name":"North Korea","ns":"_koreaDemocraticPeoplesRepublic"},"KR":{"name":"South Korea","ns":"_koreaRepublicOf"},"KW":{"name":"Kuwait","ns":"_kuwait"},"KG":{"name":"Kyrgyzstan","ns":"_kyrgyzstan"},"LV":{"name":"Latvia","ns":"_latvia"},"LB":{"name":"Lebanon","ns":"_lebanon"},"LS":{"name":"Lesotho","ns":"_lesotho"},"LR":{"name":"Liberia","ns":"_liberia"},"LY":{"name":"Libya","ns":"_libya"},"LI":{"name":"Liechtenstein","ns":"_liechtenstein"},"LT":{"name":"Lithuania","ns":"_lithuania"},"LU":{"name":"Luxembourg","ns":"_luxembourg"},"MK":{"name":"Macedonia","ns":"_macedonia"},"MG":{"name":"Madagascar","ns":"_madagascar"},"MW":{"name":"Malawi","ns":"_malawi"},"MY":{"name":"Malaysia","ns":"_malaysia"},"MV":{"name":"Maldives","ns":"_maldives"},"ML":{"name":"Mali","ns":"_mali"},"MT":{"name":"Malta","ns":"_malta"},"MH":{"name":"Marshall Islands","ns":"_marshallIslands"},"MQ":{"name":"Martinique","ns":"_martinique"},"MR":{"name":"Mauritania","ns":"_mauritania"},"MU":{"name":"Mauritius","ns":"_mauritius"},"YT":{"name":"Mayotte","ns":"_mayotte"},"MX":{"name":"Mexico","ns":"_mexico"},"FM":{"name":"Micronesia","ns":"_micronesiaFederalStateOf"},"MD":{"name":"Moldova","ns":"_moldovaRepublicOf"},"MC":{"name":"Monaco","ns":"_monaco"},"MN":{"name":"Mongolia","ns":"_mongolia"},"ME":{"name":"Montenegro","ns":"_montenegro"},"MS":{"name":"Montserrat","ns":"_montserrat"},"MA":{"name":"Morocco","ns":"_morocco"},"MZ":{"name":"Mozambique","ns":"_mozambique"},"MM":{"name":"Myanmar","ns":"_myanmar"},"NA":{"name":"Namibia","ns":"_namibia"},"NR":{"name":"Nauru","ns":"_nauru"},"NP":{"name":"Nepal","ns":"_nepal"},"NL":{"name":"Netherlands","ns":"_netherlands"},"NC":{"name":"New Caledonia","ns":"_newCaledonia"},"NZ":{"name":"New Zealand","ns":"_newZealand"},"NI":{"name":"Nicaragua","ns":"_nicaragua"},"NE":{"name":"Niger","ns":"_niger"},"NG":{"name":"Nigeria","ns":"_nigeria"},"NU":{"name":"Niue","ns":"_niue"},"NF":{"name":"Norfolk Island","ns":"_norfolkIsland"},"MP":{"name":"Northern Mariana Islands","ns":"_northernMarianaIslands"},"NO":{"name":"Norway","ns":"_norway"},"OM":{"name":"Oman","ns":"_oman"},"PK":{"name":"Pakistan","ns":"_pakistan"},"PA":{"name":"Panama","ns":"_panama"},"PG":{"name":"Papua New Guinea","ns":"_papuaNewGuinea"},"PY":{"name":"Paraguay","ns":"_paraguay"},"PE":{"name":"Peru","ns":"_peru"},"PH":{"name":"Philippines","ns":"_philippines"},"PN":{"name":"Pitcairn","ns":"_pitcairnIsland"},"PL":{"name":"Poland","ns":"_poland"},"PT":{"name":"Portugal","ns":"_portugal"},"PR":{"name":"Puerto Rico","ns":"_puertoRico"},"QA":{"name":"Qatar","ns":"_qatar"},"RE":{"name":"Reunion","ns":"_reunionIsland"},"RO":{"name":"Romania","ns":"_romania"},"RU":{"name":"Russia","ns":"_russianFederation"},"RW":{"name":"Rwanda","ns":"_rwanda"},"SH":{"name":"Saint Helena","ns":"_saintHelena"},"KN":{"name":"Saint Kitts and Nevis","ns":"_saintKittsAndNevis"},"LC":{"name":"Saint Lucia","ns":"_saintLucia"},"SX":{"name":"Saint Martin (Dutch part)","ns":"_saintMartin"},"VC":{"name":"Saint Vincent and the Grenadines","ns":"_saintVincentAndTheGrenadines"},"WS":{"name":"Samoa","ns":"_samoa"},"SM":{"name":"San Marino","ns":"_sanMarino"},"SA":{"name":"Saudi Arabia","ns":"_saudiArabia"},"SN":{"name":"Senegal","ns":"_senegal"},"RS":{"name":"Serbia","ns":"_serbia"},"SC":{"name":"Seychelles","ns":"_seychelles"},"SL":{"name":"Sierra Leone","ns":"_sierraLeone"},"SG":{"name":"Singapore","ns":"_singapore"},"SK":{"name":"Slovakia","ns":"_slovakRepublic"},"SI":{"name":"Slovenia","ns":"_slovenia"},"SB":{"name":"Solomon Islands","ns":"_solomonIslands"},"SO":{"name":"Somalia","ns":"_somalia"},"ZA":{"name":"South Africa","ns":"_southAfrica"},"GS":{"name":"South Georgia\/Sandwich Islands","ns":"_southGeorgia"},"SS":{"name":"South Sudan","ns":"_southSudan"},"ES":{"name":"Spain","ns":"_spain"},"LK":{"name":"Sri Lanka","ns":"_sriLanka"},"PS":{"name":"Palestinian Territory","ns":"_stateOfPalestine"},"SD":{"name":"Sudan","ns":"_sudan"},"SR":{"name":"Suriname","ns":"_suriname"},"SJ":{"name":"Svalbard and Jan Mayen","ns":"_svalbardAndJanMayenIslands"},"SZ":{"name":"Swaziland","ns":"_swaziland"},"SE":{"name":"Sweden","ns":"_sweden"},"CH":{"name":"Switzerland","ns":"_switzerland"},"SY":{"name":"Syria","ns":"_syrianArabRepublic"},"TW":{"name":"Taiwan","ns":"_taiwan"},"TJ":{"name":"Tajikistan","ns":"_tajikistan"},"TZ":{"name":"Tanzania","ns":"_tanzania"},"TH":{"name":"Thailand","ns":"_thailand"},"TG":{"name":"Togo","ns":"_togo"},"TK":{"name":"Tokelau","ns":"_tokelau"},"TO":{"name":"Tonga","ns":"_tonga"},"TT":{"name":"Trinidad and Tobago","ns":"_trinidadAndTobago"},"TN":{"name":"Tunisia","ns":"_tunisia"},"TR":{"name":"Turkey","ns":"_turkey"},"TM":{"name":"Turkmenistan","ns":"_turkmenistan"},"TC":{"name":"Turks and Caicos Islands","ns":"_turksAndCaicosIslands"},"TV":{"name":"Tuvalu","ns":"_tuvalu"},"UG":{"name":"Uganda","ns":"_uganda"},"UA":{"name":"Ukraine","ns":"_ukraine"},"AE":{"name":"United Arab Emirates","ns":"_unitedArabEmirates"},"GB":{"name":"United Kingdom (UK)","ns":"_unitedKingdom"},"US":{"name":"United States","ns":"_unitedStates"},"VI":{"name":"United States (US) Virgin Islands","ns":"_virginIslandsUSA"},"UY":{"name":"Uruguay","ns":"_uruguay"},"UZ":{"name":"Uzbekistan","ns":"_uzbekistan"},"VU":{"name":"Vanuatu","ns":"_vanuatu"},"VE":{"name":"Venezuela","ns":"_venezuela"},"VN":{"name":"Vietnam","ns":"_vietnam"},"VG":{"name":"British Virgin Islands","ns":"_virginIslandsBritish"},"WF":{"name":"Wallis and Futuna","ns":"_wallisAndFutunaIslands"},"EH":{"name":"Western Sahara","ns":"_westernSahara"},"YE":{"name":"Yemen","ns":"_yemen"},"ZM":{"name":"Zambia","ns":"_zambia"},"ZW":{"name":"Zimbabwe","ns":"_zimbabwe"}}';
    return json_decode($json,true);
}

public function get_item_fields(){ 
   
      $json='["purchaseDescription","copyDescription","expenseAccount","dateConvertedToInv","originalItemType","originalItemSubtype","cogsAccount","intercoCogsAccount","salesDescription","fraudRisk","includeChildren","incomeAccount","intercoIncomeAccount","taxSchedule","dropshipExpenseAccount","deferRevRec","revenueRecognitionRule","revenueAllocationGroup","createRevenuePlansOn","isTaxable","matrixType","assetAccount","matchBillToReceipt","billQtyVarianceAcct","billPriceVarianceAcct","billExchRateVarianceAcct","gainLossAccount","shippingCost","shippingCostUnits","handlingCost","handlingCostUnits","weight","weightUnit","weightUnits","costingMethodDisplay","unitsType","stockUnit","purchaseUnit","saleUnit","issueProduct","billingSchedule","trackLandedCost","matrixItemNameTemplate","isDropShipItem","isSpecialOrderItem","stockDescription","deferredRevenueAccount","producer","manufacturer","revRecSchedule","mpn","multManufactureAddr","manufacturerAddr1","manufacturerCity","manufacturerState","manufacturerZip","countryOfManufacture","roundUpAsComponent","purchaseOrderQuantity","purchaseOrderAmount","purchaseOrderQuantityDiff","receiptQuantity","receiptAmount","receiptQuantityDiff","defaultItemShipMethod","itemCarrier","itemShipMethodList","manufacturerTaxId","scheduleBNumber","scheduleBQuantity","scheduleBCode","manufacturerTariff","preferenceCriterion","minimumQuantity","enforceMinQtyInternally","minimumQuantityUnits","softDescriptor","shipPackage","shipIndividually","costCategory","pricesIncludeTax","purchasePriceVarianceAcct","quantityPricingSchedule","reorderPointUnits","useMarginalRates","preferredStockLevelUnits","costEstimateType","costEstimate","transferPrice","overallQuantityPricingType","pricingGroup","vsoePrice","vsoeSopGroup","costEstimateUnits","vsoeDeferral","vsoePermitDiscount","vsoeDelivered","itemRevenueCategory","preferredLocation","reorderMultiple","cost","lastInvtCountDate","nextInvtCountDate","invtCountInterval","invtClassification","costUnits","totalValue","averageCost","useBins","quantityReorderUnits","leadTime","autoLeadTime","lastPurchasePrice","autoPreferredStockLevel","preferredStockLevelDays","safetyStockLevel","safetyStockLevelDays","backwardConsumptionDays","seasonalDemand","safetyStockLevelUnits","demandModifier","distributionNetwork","distributionCategory","autoReorderPoint","storeDisplayName","storeDisplayThumbnail","storeDisplayImage","storeDescription","storeDetailedDescription","storeItemTemplate","pageTitle","metaTagHtml","excludeFromSitemap","sitemapPriority","searchKeywords","isDonationItem","showDefaultDonationAmount","maxDonationAmount","dontShowPrice","noPriceMessage","outOfStockMessage","onSpecial","outOfStockBehavior","relatedItemsDescription","specialsDescription","featuredDescription","shoppingDotComCategory","shopzillaCategoryId","nexTagCategory","urlComponent","customForm","itemId","upcCode","displayName","vendorName","parent","isOnline","isHazmatItem","hazmatId","hazmatShippingName","hazmatHazardClass","hazmatPackingGroup","hazmatItemUnits","hazmatItemUnitsQty","isGcoCompliant","offerSupport","isInactive","availableToPartners","department","class","location","costingMethod","currency","preferredStockLevel","accountingBookDetailList","purchaseTaxCode","defaultReturnCost","supplyReplenishmentMethod","alternateDemandSourceItem","fixedLotSize","periodicLotSizeType","supplyType","demandTimeFence","supplyTimeFence","rescheduleInDays","rescheduleOutDays","periodicLotSizeDays","supplyLotSizingMethod","forwardConsumptionDays","demandSource","quantityBackOrdered","quantityCommitted","quantityAvailable","quantityOnHand","onHandValueMli","quantityOnOrder","rate","reorderPoint","quantityCommittedUnits","salesTaxCode","quantityAvailableUnits","quantityOnHandUnits","vendor","quantityOnOrderUnits","internalId","externalId"]'; 

 $fields=json_decode($json,1);

$emailSearchField = new SearchEnumMultiSelectField();
$emailSearchField->operator = "anyOf";
$emailSearchField->searchValue = array('_inventoryItem'); // ItemType class

$search = new ItemSearchBasic();
$search->type = $emailSearchField;

$request = new SearchRequest();
$request->searchRecord = $search;
$res = $this->post_crm('search',$request,10);

$fields_arr=array();
$last_fields=array();
$custom_fields=array();
if(!empty($res->searchResult->recordList->record[0])){
    foreach($res->searchResult->recordList->record[0] as $k=>$v){
if( in_array($k,$fields)){
$type='';
if(!empty($v)){
if(is_string($v)){
$type='string';    
}else if(is_object($v) && isset($v->name)){
 $type='lookup';   $v=$v->name;
}
}
if(!empty($type)){
$fields_arr[$k]=array('label'=>$k.' - '.substr($v,0,30),'type'=>$type);
}else{      
$last_fields[$k]=array('label'=>$k,'type'=>'String');      
}      
}
if($k == 'customFieldList' && !empty($v->customField) && is_array($v->customField)){
  foreach($v->customField as $kk){
  $custom_fields[$kk->internalId]=$kk->value;    
  }  
}   
    }   
}
// var_dump($custom_fields,$res->searchResult->recordList->record[0]); die();    
   $custom_type='itemCustomField';
  $res=$this->get_crm_fields($custom_type); 
if(!empty($res)){
    foreach($res as $k=>$v){
        if(isset($custom_fields[$v['id']])){
    $v['label']=$v['label'].' - '.substr($custom_fields[$v['id']],0,30);        
        }
$fields_arr[$v['name']]=$v;        
    }
}
return array_merge($fields_arr,$last_fields); 
}
public function get_crm_fields($object,$is_options=false){ 
/*
$record = new RecordRef();
$record->internalId='5';
$record->type='customList';
  
 $list_req=new GetListRequest();
 $list_req->baseRef[]=$record;
 $meta=$this->post_crm('getList',$list_req); 
 
  var_dump($meta); die();  

$deps=$this->get_locs();
var_dump($deps); die();
$searchField = new SearchMultiSelectField();
$searchField->operator = "anyOf";
$searchField->searchValue = array($record);
$search = new CustomListSearchBasic();
//$search->internalId = $searchField;

$request = new SearchRequest();
$request->searchRecord = $search;

$options_arr = $this->post_crm('search',$request,200);
var_dump($options_arr); die();
*/
       if($object == 'Task'){ 
  $json='["title","assigned","sendEmail","timedEvent","estimatedTime","estimatedTimeOverride","actualTime","timeRemaining","percentTimeComplete","percentComplete","parent","startDate","endDate","dueDate","completedDate","priority","status","message","accessLevel","reminderType","reminderMinutes","createdDate","lastModifiedDate","owner","contactList","timeItemList","externalId"]'; 
      }else if($object == 'PhoneCall'){
  $json='["title","message","phone","externalId"]';
      }else if($object == 'Opportunity'){
  $json='["job","title","tranId","oneTime","recurWeekly","recurMonthly","recurQuarterly","recurAnnually","source","salesRep","contribPct","partner","salesGroup","syncSalesTeams","leadSource","entityStatus","probability","tranDate","expectedCloseDate","daysOpen","forecastType","currencyName","exchangeRate","projectedTotal","rangeLow","rangeHigh","projAltSalesAmt","altSalesRangeLow","altSalesRangeHigh","weightedTotal","actionItem","winLossReason","memo","taxTotal","isBudgetApproved","tax2Total","salesReadiness","totalCostEstimate","buyingTimeFrame","estGrossProfit","buyingReason","estGrossProfitPercent","billingAddress","billAddressList","shippingAddress","shipIsResidential","shipAddressList","class","closeDate","createdDate","lastModifiedDate","department","location","subsidiary","status","vatRegNum","customForm","currency","estimatedBudget","entity","internalId","externalId"]';
      }else if($object == 'SupportCase'){
  $json='["title","email","phone","contact","outgoingMessage","incomingMessage","endDate","escalationMessage","insertSolution","emailForm","newSolutionFromMsg","searchSolution","internalOnly","customForm","subsidiary","profile","company","startDate","serialNumber","item","module","product","inboundEmail","issue","status","isInactive","priority","origin","category","assigned","helpDesk","externalId"]';
      }else if($object == 'SalesOrder'){ //discountItem , discountRate
      $json='["email","message","memo","otherRefNum","source","country","attention","addressee","addrPhone","addr1","addr2","addr3","city","state","zip","ship_country","ship_attention","ship_addressee","ship_addrPhone","ship_addr1","ship_addr2","ship_addr3","ship_city","ship_state","ship_zip","shippingCost","discountTotal","taxTotal","total","subTotal","class","department","externalId","discountRate","discountItem","subsidiary","currency"]';
      }else if($object == 'Invoice'){ 
      $json='["email","message","memo","otherRefNum","source","country","attention","addressee","addrPhone","addr1","addr2","addr3","city","state","zip","ship_country","ship_attention","ship_addressee","ship_addrPhone","ship_addr1","ship_addr2","ship_addr3","ship_city","ship_state","ship_zip","shippingCost","discountTotal","taxTotal","total","subTotal","class","department","opportunity","externalId","recurringBill","billingAccount","tranDate","dueDate","discountAmount","salesRep","subsidiary","currency"]';
      } else if($object == 'Customer'){
      $json='["altName","isPerson","phoneticName","salutation","firstName","middleName","lastName","companyName","entityStatus","parent","phone","fax","email","url","country","attention","addrPhone","addr1","addr2","addr3","city","state","zip","isInactive","category","title","printOnCheckAs","altPhone","homePhone","mobilePhone","altEmail","language","comments","numberFormat","negativeNumberFormat","dateCreated","image","emailPreference","subsidiary","customForm","representingSubsidiary","salesRep","territory","contribPct","partner","salesGroup","vatRegNumber","accountNumber","taxExempt","terms","creditLimit","creditHoldOverride","monthlyClosing","overrideCurrencyFormat","displaySymbol","symbolPlacement","balance","overdueBalance","daysOverdue","unbilledOrders","consolUnbilledOrders","consolOverdueBalance","consolDepositBalance","consolBalance","consolAging","consolAging1","consolAging2","consolAging3","consolAging4","consolDaysOverdue","priceLevel","currency","prefCCProcessor","depositBalance","shipComplete","taxable","taxItem","resaleNumber","aging","aging1","aging2","aging3","aging4","startDate","alcoholRecipientType","endDate","reminderDays","shippingItem","thirdPartyAcct","thirdPartyZipcode","thirdPartyCountry","giveAccess","estimatedBudget","accessRole","sendEmail","password","password2","requirePwdChange","campaignCategory","leadSource","receivablesAccount","drAccount","fxAccount","defaultOrderPriority","webLead","referrer","keywords","clickStream","lastPageVisited","visits","firstVisit","lastVisit","billPay","openingBalance","lastModifiedDate","openingBalanceDate","openingBalanceAccount","stage","emailTransactions","printTransactions","faxTransactions","syncPartnerTeams","isBudgetApproved","globalSubscriptionStatus","externalId","currency"]';
      }else if($object == 'Contact'){
      $json='["salutation","firstName","middleName","lastName","title","phone","fax","email","defaultAddress","isPrivate","subsidiary","phoneticName","altEmail","officePhone","homePhone","mobilePhone","supervisorPhone","supervisor","assistant","assistantPhone","comments","image","billPay","isInactive","externalId"]';
      }
      
      $fields=!empty($json) ? json_decode($json,true) : array();
$labels=array('addrPhone'=>'Address Phone','attention'=>'Address Attention','addressee'=>'Addressee','ship_addressee'=>'Shipping Addressee','addr1'=>'Address Line 1','addr2'=>'Address Line 2','addr3'=>'Address Line 3','city'=>'City','state'=>'State','zip'=>'Zip','country'=>'Country','addrText'=>'Complete Address','ship_addrPhone'=>'Shipping Phone','ship_attention'=>'Shipping Attention','ship_addr1'=>'Shipping Address Line 1','ship_addr2'=>'Shipping Address Line 2','ship_addr3'=>'Shipping Address Line 3','ship_addrText'=>'Shipping Complete Address','ship_city'=>'Shippig City','ship_state'=>'Shipping State','ship_zip'=>'Shipping Zip','ship_country'=>'Shipping Country');
    $arr=array(); 
    $lists_map=array('issue'=>'supportCaseIssue','origin'=>'supportCaseOrigin','category'=>'supportCaseType','priority'=>'supportCasePriority','status'=>'supportCaseStatus');

  foreach($fields as $k=>$v){
  $type='Text';  
  if( in_array($v,array('parent','department','class'))){
    $type='List';    
  }  
  if( in_array($v,array('isPerson'))){
    $type='bool';    
  } 
  $sel_options=$ops=array(); 
  if($object == 'SupportCase' && isset($lists_map[$v])){
      $type='List';  
    $ops=$this->get_all($lists_map[$v]); 
}
    if($v == 'subsidiary'){
       $ops=$this->get_subsidiaries();        
    }
     if($v == 'currency'){ $type='List';
       $ops=$this->get_all('currency');        
    }
     if( !empty($ops) && is_array($ops)){ 
    $type='List'; 
            foreach($ops as $key=>$val){
             $sel_options[]=array('value'=>$key,'name'=>$val);  
           }
    }
 $label=$v; if(isset($labels[$v])){ $label=$labels[$v]; $type='address'; }
   $arr[$v]=array('name'=>$v,'label'=>$label,'type'=>$type);
   if(!empty($sel_options)){
   $arr[$v]['options']=$sel_options;    
   $arr[$v]['eg']=join(",",array_map(function($v){return  $v['value'].'='.$v['name'];}, array_slice($sel_options,0,20)));   
        }
   if( ( in_array($object,array('Customer','SalesOrder') ) && in_array($v,array('email','lastName','firstName','phone')) ) || ($object == 'Task' && in_array($v,array('title'))) || ($object == 'PhoneCall' && in_array($v,array('title'))) || ($object == 'SupportCase' && in_array($object,array('category','issue')))){
   $arr[$v]['req']='true';    
   }  
  }
 // if($object == 'customer'){
 $custom_type='';
 if(in_array($object, array('Customer')) ){
  $custom_type='entityCustomField';    
  
 }else  if(in_array($object, array('SalesOrder','Invoice')) ){
  $custom_type='transactionBodyCustomField';    
 }else if(in_array($object, array('Task','PhoneCall','SupportCase')) ){
   $custom_type='crmCustomField';  
 }else if(!in_array($object, array('Contact'))){
     $custom_type=$object;
 }
//$custom_type='itemOptionCustomField';
//$custom_type='transactionColumnCustomField';
if(!empty($custom_type)){
$ct = new CustomizationType();  
$ct->getCustomizationType = $custom_type;

$c = new GetCustomizationIdRequest();
$c->customizationType = $ct;
$c->includeInactives = false;

$fields=$this->post_crm('getCustomizationId',$c);
//var_dump($fields); die();


 $fields_arr=array();
 if(!empty($fields->getCustomizationIdResult->customizationRefList->customizationRef)){
   $fields=$fields->getCustomizationIdResult->customizationRefList->customizationRef;

$list=new GetListRequest();
   
foreach($fields as $k=>$v){
 
       if(!empty($v->internalId)){
$ref= new RecordRef();
$ref->internalId=$v->internalId;
$ref->type= $custom_type; //entityCustomField
$list->baseRef[]=$ref;
       }
}

$options_list=$options_map=array();
      
$meta=$this->post_crm('getList',$list); 

if(!empty($meta->readResponseList->readResponse)){
    foreach($meta->readResponseList->readResponse as $k=>$v){
        if(!empty($v->record->internalId)){
        if(!empty($v->record->selectRecordType->internalId)){
            $options_map[$v->record->internalId]=$v->record->selectRecordType->internalId;
            $record = new RecordRef();
            $record->internalId = $v->record->selectRecordType->internalId;
            $options_list[]=$record;   
        }
        $field_type=''; 
        if($object == 'transactionColumnCustomField'){
        $field_type='line_field';   
        }else  if($object == 'itemOptionCustomField'){   
        $field_type='line_option';   
        } 
        
        $fields_arr[$v->record->internalId]=array('label'=>$v->record->label,'type'=>$v->record->fieldType,'name'=>$v->record->scriptId,'id'=>$v->record->internalId,'is_custom'=>'true','field_type'=>$field_type);
        }else if(!empty($v->status->statusDetail[0]->message)){
       $arr=$v->status->statusDetail[0]->message;     
        }       
    }  
  }

//options
if(!empty($options_list)){

$searchField = new SearchMultiSelectField();
$searchField->operator = "anyOf";
$searchField->searchValue = $options_list;

$search = new CustomListSearchBasic();
$search->internalId = $searchField;

$request = new SearchRequest();
$request->searchRecord = $search;

$options_arr = $this->post_crm('search',$request,200);
//var_dump($custom_type.'-----'.$object);
if( $custom_type == 'itemOptionCustomField'){
 //   var_dump($options_map,$options_arr,$meta);
}
  if(!empty($options_arr->searchResult->recordList->record)){
      foreach($options_arr->searchResult->recordList->record as $k=>$v){
      
        if( !empty($v->customValueList->customValue)){
            $sel_options=array();
            foreach($v->customValueList->customValue as $val){
                if($val->isInactive === false){
             $sel_options[]=array('value'=>$val->valueId,'name'=>$val->value); 
                } 
           }
        //
        if(!empty($sel_options)){
   foreach($options_map as $kk=>$vv){
        if($v->internalId == $vv){
        $fields_arr[$kk]['options']=$sel_options;    
        $fields_arr[$kk]['eg']=join(",",array_map(function($v){return  $v['value'].'='.$v['name'];}, $sel_options));   
        }  }
        } 
        }  
      }
  }
}

}else if(!empty($fields->error)){
  $arr=$fields->error;   
 }
if(!empty($arr['partner'])){
$search = new PartnerSearchBasic();
$request = new SearchRequest();
$request->searchRecord = $search;

$s_res = $this->post_crm('search',$request,200);
//die(json_encode($s_res));
if ($s_res->searchResult->status->isSuccess && is_array($s_res->searchResult->recordList->record) && count($s_res->searchResult->recordList->record)>0) {
    $field=array('label'=>'Partner','type'=>'_listRecord','name'=>'partner');
$ops=array();
foreach($s_res->searchResult->recordList->record as $v){
    $name=trim($v->firstName.' '.$v->lastName);
    if(!empty($v->companyName) ){ 
    if(!empty($name) ){ 
        $name.=' - '; 
    }  $name.=$v->companyName; }
    if(!empty($name)){
 $ops[]=array('value'=>$v->internalId,'name'=>$name);  
    } 
}
$field['options']=$ops;
if(!empty($ops)){
$field['eg']=join(",",array_map(function($v){return  $v['value'].'='.$v['name'];}, array_slice($ops,0,20)));
}
$fields_arr['partner']=$field;
}
}

 if(!empty($fields_arr) && is_array($fields_arr) && is_array($arr)){
    foreach($fields_arr as $k=>$v){
        $arr[$v['name']]=$v;
    }
}
//  var_dump($arr,$options_arr,$object,$options_map,$meta); die();
 
if(in_array($object, array('SalesOrder','Invoice')) && is_array($arr)  ){ //&& !empty($arr)
    $body_fields=$this->get_crm_fields('transactionColumnCustomField');
    if(is_array($body_fields)){ $arr=array_merge($arr,$body_fields); }
    $item_options=$this->get_crm_fields('itemOptionCustomField');
    if(is_array($item_options)){ $arr=array_merge($arr,$item_options); }
}
}
 /*$json='{"11":{"label":"Test Data","type":"_checkBox","name":"custentity1","id":"11","is_custom":"true"},"33":{"label":"LSA Link Name","type":"_freeFormText","name":"custentity_link_name_lsa","id":"33","is_custom":"true"},"34":{"label":"LSA Link","type":"_hyperlink","name":"custentity_link_lsa","id":"34","is_custom":"true"},"35":{"label":"Last Sales Activity","type":"_date","name":"custentity_date_lsa","id":"35","is_custom":"true"},"1401":{"label":"Product Name","type":"_freeFormText","name":"custentity_productname","id":"1401","is_custom":"true"},"1369":{"label":"Stage","type":"_freeFormText","name":"custentity_stage","id":"1369","is_custom":"true"},"1373":{"label":"Company Contact First Name","type":"_freeFormText","name":"custentity_companycontactfirstname","id":"1373","is_custom":"true"},"1374":{"label":"Company Contact Last Name","type":"_freeFormText","name":"custentity_companycontactlastname","id":"1374","is_custom":"true"},"1384":{"label":"Purchasing","type":"_listRecord","name":"custentity_purchasing","id":"1384","is_custom":"true","options":[{"value":1,"name":"Commercial"},{"value":2,"name":"Personal"}],"eg":"1=Commercial2=Personal"},"1385":{"label":"Golf car make","type":"_freeFormText","name":"custentity_golfcarmake","id":"1385","is_custom":"true"},"1386":{"label":"Golf Car Year","type":"_freeFormText","name":"custentity_golfcaryear","id":"1386","is_custom":"true"},"1387":{"label":"Golf Car Model","type":"_freeFormText","name":"custentity_golfcarmodel","id":"1387","is_custom":"true"},"1389":{"label":"Golf Car Type","type":"_freeFormText","name":"custentity_golfcartype","id":"1389","is_custom":"true"},"1388":{"label":"Parts \/ Service","type":"_listRecord","name":"custentity_parts_service","id":"1388","is_custom":"true","options":[{"value":1,"name":"Parts"},{"value":2,"name":"Service"}],"eg":"1=Parts2=Service"},"1390":{"label":"Type of Finance","type":"_listRecord","name":"custentity_typeoffinance","id":"1390","is_custom":"true","options":[{"value":1,"name":"cash"},{"value":2,"name":"finance"},{"value":3,"name":"lease"},{"value":4,"name":"loan"},{"value":5,"name":"price range 4k"},{"value":6,"name":"Unknown"}],"eg":"1=cash2=finance3=lease4=loan5=price range 4k6=Unknown"},"1463":{"label":"Legacy Entity ID","type":"_freeFormText","name":"custentity_legacyentityid","id":"1463","is_custom":"true"},"1394":{"label":"SEO Term","type":"_freeFormText","name":"custentity_seoterm","id":"1394","is_custom":"true"},"1395":{"label":"SEO Engine","type":"_freeFormText","name":"custentity_seoengine","id":"1395","is_custom":"true"},"1396":{"label":"Google GCLID","type":"_freeFormText","name":"custentity_googlegclid","id":"1396","is_custom":"true"},"1397":{"label":"UTM Content","type":"_freeFormText","name":"custentity_utmcontent","id":"1397","is_custom":"true"},"1398":{"label":"UTM Term","type":"_freeFormText","name":"custentity_utmterm","id":"1398","is_custom":"true"},"1402":{"label":"UTM Campaign","type":"_freeFormText","name":"custentity_utmcampaign","id":"1402","is_custom":"true"},"1403":{"label":"UTM Medium","type":"_freeFormText","name":"custentity_utmmedium","id":"1403","is_custom":"true"},"1399":{"label":"UTM Adgroup","type":"_listRecord","name":"custentity_utmadgroup","id":"1399","is_custom":"true","options":[{"value":1,"name":"branding"},{"value":2,"name":"buy-ezgo"},{"value":3,"name":"buy-ez-go"},{"value":4,"name":"e-z-go"},{"value":5,"name":"florida"},{"value":6,"name":"general"},{"value":7,"name":"NA"},{"value":8,"name":"Orlando"},{"value":9,"name":"sell"}],"eg":"1=branding2=buy-ezgo3=buy-ez-go4=e-z-go5=florida6=general7=NA8=Orlando9=sell"},"1410":{"label":"Email Opt Out","type":"_freeFormText","name":"custentityemail_opt_out","id":"1410","is_custom":"true"},"1037":{"label":"Customer Type","type":"_listRecord","name":"custentity_customertype","id":"1037","is_custom":"true","options":[{"value":1,"name":"COD"},{"value":4,"name":"Email Invoice PDF"},{"value":3,"name":"OPS OPS OPS"},{"value":2,"name":"Referral"},{"value":5,"name":"Retail"}],"eg":"1=COD4=Email Invoice PDF3=OPS OPS OPS2=Referral5=Retail"},"1367":{"label":"Overall Sales Duration","type":"_freeFormText","name":"custentity_overallsalesduration","id":"1367","is_custom":"true"},"1368":{"label":"Next Step","type":"_freeFormText","name":"custentity_nextstep","id":"1368","is_custom":"true"},"1370":{"label":"Lead Conversion Time","type":"_freeFormText","name":"custentity_leadconversiontime","id":"1370","is_custom":"true"},"1371":{"label":"Sales Cycle Duration","type":"_freeFormText","name":"custentity_salescycleduration","id":"1371","is_custom":"true"},"1375":{"label":"Lead Source","type":"_freeFormText","name":"custentity_leadsource","id":"1375","is_custom":"true"},"1376":{"label":"Lead Status","type":"_freeFormText","name":"custentity_leadstatus","id":"1376","is_custom":"true"},"1380":{"label":"Expected Revenue","type":"_freeFormText","name":"custentity_expectedrevenue","id":"1380","is_custom":"true"},"1400":{"label":"Probability","type":"_listRecord","name":"custentity_probability","id":"1400","is_custom":"true","options":[{"value":1,"name":"10"},{"value":2,"name":"20"},{"value":3,"name":"25"},{"value":4,"name":"50"},{"value":5,"name":"60"},{"value":6,"name":"75"},{"value":7,"name":"90"},{"value":8,"name":"100"},{"value":9,"name":"0"}],"eg":"1=102=203=254=505=606=757=908=1009=0"},"1651":{"label":"Custom List","type":"_listRecord","name":"custentity4","id":"1651","is_custom":"true","options":[{"value":1,"name":"first option"},{"value":2,"name":"second value"},{"value":3,"name":"third value"}],"eg":"1=first option2=second value3=third value"},"1650":{"label":"Test File","type":"_freeFormText","name":"custentity3","id":"1650","is_custom":"true"},"1136":{"label":"External ID","type":"_freeFormText","name":"custentity_externalid","id":"1136","is_custom":"true"},"1312":{"label":"Invoice Email Alternate Distribution List","type":"_freeFormText","name":"custentity_invoice_email_addresses","id":"1312","is_custom":"true"},"1366":{"label":"Product SKU","type":"_freeFormText","name":"custentity_product_sku","id":"1366","is_custom":"true"},"1377":{"label":"Created By","type":"_freeFormText","name":"custentity_legacycreatedby","id":"1377","is_custom":"true"},"1378":{"label":"Created By Id","type":"_freeFormText","name":"custentity_createdbyid","id":"1378","is_custom":"true"},"1379":{"label":"Created Time","type":"_freeFormText","name":"custentity_createdtime","id":"1379","is_custom":"true"},"1381":{"label":"Serial Number","type":"_freeFormText","name":"custentity_serialnumber","id":"1381","is_custom":"true"},"1382":{"label":"Amount","type":"_freeFormText","name":"custentity_amount","id":"1382","is_custom":"true"},"1383":{"label":"Closing Date","type":"_date","name":"custentity_closingdate","id":"1383","is_custom":"true"},"1393":{"label":"Condition","type":"_listRecord","name":"custentity_condition","id":"1393","is_custom":"true","options":[{"value":1,"name":"New"},{"value":2,"name":"Used"},{"value":3,"name":"Unknown"}],"eg":"1=New2=Used3=Unknown"},"1408":{"label":"Make","type":"_freeFormText","name":"custentity_make","id":"1408","is_custom":"true"},"1392":{"label":"Year of the Batteries","type":"_freeFormText","name":"custentity_yearofthebatteries","id":"1392","is_custom":"true"},"1411":{"label":"Type","type":"_freeFormText","name":"custentity_type","id":"1411","is_custom":"true"},"1409":{"label":"Legacy Account Name","type":"_freeFormText","name":"custentity_legacy_account_names","id":"1409","is_custom":"true"},"1391":{"label":"Legacy contact ID","type":"_freeFormText","name":"custentity_legacycontactid","id":"1391","is_custom":"true"},"1404":{"label":"Opportunity Owner Id","type":"_freeFormText","name":"custentity_opportunityownerid","id":"1404","is_custom":"true"},"1412":{"label":"Legacy Customer Type","type":"_freeFormText","name":"custentity_legacycustomertype","id":"1412","is_custom":"true"},"1453":{"label":"Display in Service Repair Request?","type":"_checkBox","name":"custentity_svcrepairdisplay","id":"1453","is_custom":"true"},"1458":{"label":"Tax Certificate Copy","type":"_image","name":"custentity2","id":"1458","is_custom":"true"},"1652":{"label":"Test Image","type":"_image","name":"custentity5","id":"1652","is_custom":"true"},"1653":{"label":"Test Link","type":"_hyperlink","name":"custentity6","id":"1653","is_custom":"true"}}';
 $fields_arr=json_decode($json,true);*/

return $arr;
}
public function get_all($type){ 
$all_rec=new GetSelectValueFieldDescription();
$all_rec->recordType=$type;

$all_req=new GetAllRequest();
$all_req->record=$all_rec;

$res=$this->post_crm('getAll',$all_req);

$field_info=__('Status List Not Found');
if(!empty($res->getAllResult->recordList->record)){
    $field_info=array();
    foreach($res->getAllResult->recordList->record as $v){
$field_info[$v->internalId]= $v->name;
    }
}else if(!empty($res->getAllResult->status->statusDetail[0]->message)){
 $field_info=$res->getAllResult->status->statusDetail[0]->message;   
}else if(isset($res->error)){
  $field_info=$res->error;  
}else{
    $field_info=json_encode($res);
}
return $field_info;
}

public function get_status_list(){ 



$all_rec=new GetSelectValueFieldDescription();
$all_rec->recordType='customerStatus';

$all_req=new GetAllRequest();
$all_req->record=$all_rec;

$res=$this->post_crm('getAll',$all_req);

$field_info=__('Status List Not Found');
if(!empty($res->getAllResult->recordList->record)){
    $field_info=array();
    foreach($res->getAllResult->recordList->record as $v){
$field_info[$v->internalId]= strtoupper(trim($v->stage,'_')).'-'.$v->name;
    }
}else if(!empty($res->getAllResult->status->statusDetail[0]->message)){
 $field_info=$res->getAllResult->status->statusDetail[0]->message;   
}else if(isset($res->error)){
  $field_info=$res->error;  
}else{
    $field_info=json_encode($res);
}
return $field_info;
}
public function get_deps(){ 

$search = new DepartmentSearchBasic();

$request = new SearchRequest();
$request->searchRecord = $search;

$folders=$this->post_crm('search',$request);

$arr=array();
if(!empty($folders->searchResult->recordList->record)){
    foreach($folders->searchResult->recordList->record as $k=>$v){
      $arr[$v->internalId]=$v->name;  
    }
}else if(isset($folders->searchResult->status->statusDetail[0]->message)){
  $arr=$folders->searchResult->status->statusDetail[0]->message;  
}else if(isset($folders->error)){
 $arr=$folders->error;   
}

  return $arr;
}
public function get_locs(){ 

$search = new LocationSearchBasic();

$request = new SearchRequest();
$request->searchRecord = $search;

$folders=$this->post_crm('search',$request);

$arr=array();
if(!empty($folders->searchResult->recordList->record)){
    foreach($folders->searchResult->recordList->record as $k=>$v){
      $arr[$v->internalId]=$v->name;  
    }
}else if(isset($folders->searchResult->status->statusDetail[0]->message)){
 $arr=$folders->searchResult->status->statusDetail[0]->message;   

}else if(isset($folders->error)){
 $arr=$folders->error;   
}

  return $arr;
}
public function get_classes(){ 

$search = new ClassificationSearchBasic();

$request = new SearchRequest();
$request->searchRecord = $search;

$folders=$this->post_crm('search',$request);

$arr=array();
if(!empty($folders->searchResult->recordList->record)){
    foreach($folders->searchResult->recordList->record as $k=>$v){
      $arr[$v->internalId]=$v->name;  
    }
}else if(isset($folders->searchResult->status->statusDetail[0]->message)){
  $arr=$folders->searchResult->status->statusDetail[0]->message;  
}else if(isset($folders->error)){
 $arr=$folders->error;   
}

  return $arr;
}
public function get_pay_methods(){ 

$search = new PaymentMethodSearchBasic();

$request = new SearchRequest();
$request->searchRecord = $search;

$folders=$this->post_crm('search',$request);
$arr=array();
if(!empty($folders->searchResult->recordList->record)){
    foreach($folders->searchResult->recordList->record as $k=>$v){
      $arr[$v->internalId]=$v->name;  
    }
}else if(isset($folders->searchResult->status->statusDetail[0]->message)){
  $arr=$folders->searchResult->status->statusDetail[0]->message;  
}else if(isset($folders->error)){
 $arr=$folders->error;   
}

  return $arr;
}
public function get_ship_methods(){ 
    
$obj = new GetSelectValueFieldDescription();
$obj->recordType = 'salesOrder';
$obj->field = 'shipmethod';
 
$request = new getSelectValueRequest();
$request->fieldDescription = $obj;
$request->pageIndex = 0;
 
$res = $this->post_crm('getSelectValue',$request);
$arr=array();
if(!empty($res->getSelectValueResult->baseRefList->baseRef)){
    foreach($res->getSelectValueResult->baseRefList->baseRef as $k=>$v){
      $arr[$v->internalId]=$v->name;  
    }
}else if(isset($res->getSelectValueResult->status->statusDetail[0]->message)){
 $arr=$res->getSelectValueResult->status->statusDetail[0]->message;   
}

  return $arr;
}
public function get_lead_source(){ 

$obj = new GetSelectValueFieldDescription();
$obj->recordType = 'salesOrder';
$obj->field = 'leadSource';
 
$request = new getSelectValueRequest();
$request->fieldDescription = $obj;
$request->pageIndex = 0;
 
$res =$this->post_crm('getSelectValue',$request); 

$arr=array();
if(!empty($res->getSelectValueResult->baseRefList->baseRef)){
    foreach($res->getSelectValueResult->baseRefList->baseRef as $k=>$v){
      $arr[$v->internalId]=$v->name;  
    }
}else if(isset($res->getSelectValueResult->status->statusDetail[0]->message)){
 $arr=$res->getSelectValueResult->status->statusDetail[0]->message;   
}

  return $arr;
}
public function get_folders(){ 

$r=new RecordRef();
$r->internalId="@NONE@";
$field = new SearchMultiSelectField();
$field->operator = 'anyOf';
$field->searchValue = $r;

$search = new FolderSearchBasic();
$search->parent = $field;

$request = new SearchRequest();
$request->searchRecord = $search;
$folders=$this->post_crm('search',$request,1000);

$arr=array();
if(!empty($folders->searchResult->recordList->record)){
    foreach($folders->searchResult->recordList->record as $k=>$v){
      $arr[$v->internalId]=$v->name;  
    }
}else if(isset($folders->error)){
 $arr=$folders->error;   
}
  return $arr;
}
public function get_note_types(){ 
    
$all_rec=new GetSelectValueFieldDescription();
$all_rec->recordType='noteType';

$all_req=new GetAllRequest();
$all_req->record=$all_rec;

$res=$this->post_crm('getAll',$all_req);

$field_info=__('Status List Not Found');
if(!empty($res->getAllResult->recordList->record)){
    $field_info=array();
    foreach($res->getAllResult->recordList->record as $v){
$field_info[$v->internalId]= $v->name;
    }
}
return $field_info;
}
public function price_levels(){ 
$all_rec=new GetSelectValueFieldDescription();
$all_rec->recordType='priceLevel';

$all_req=new GetAllRequest();
$all_req->record=$all_rec;

$res=$this->post_crm('getAll',$all_req);

$field_info=__('Status List Not Found');
if(!empty($res->getAllResult->recordList->record)){
    $field_info=array();
    foreach($res->getAllResult->recordList->record as $v){
$field_info[$v->internalId]= $v->name;
    }
}
return $field_info;
}

public function get_discount_items(){
$emailSearchField = new SearchEnumMultiSelectField();
$emailSearchField->operator = "anyOf";
$emailSearchField->searchValue = array('_discount'); // ItemType class

$search = new ItemSearchBasic();
$search->type = $emailSearchField;

$request = new SearchRequest();
$request->searchRecord = $search;
$res = $this->post_crm('search',$request,100);

$field_info=__('No Item Found');
if(!empty($res->searchResult->recordList->record)){
    $field_info=array();
    foreach($res->searchResult->recordList->record as $v){
$field_info[$v->internalId]= $v->itemId;      
    }
}
return $field_info;
} 
public function get_users(){ 

$search = new EmployeeSearchBasic();

$request = new SearchRequest();
$request->searchRecord = $search;

$res = $this->post_crm('search',$request,'');

$field_info=__('No User Found');
if(!empty($res->searchResult->recordList->record)){
    $field_info=array();
    foreach($res->searchResult->recordList->record as $v){
        if($v->isInactive == false  && !empty($v->email)){
$field_info[$v->internalId]= $v->firstName.' '.$v->lastName.'('.$v->email.')';
        }
    }
}
  return $field_info;
}
public function get_subsidiaries(){ 

$search = new SubsidiarySearchBasic();
$request = new SearchRequest();
$request->searchRecord = $search;

$res = $this->post_crm('search',$request,'');

$field_info=__('No User Found');
if(!empty($res->searchResult->recordList->record)){
    $field_info=array();
    foreach($res->searchResult->recordList->record as $v){
        if($v->isInactive == false  && !empty($v->name) ){
$field_info[$v->internalId]= $v->name;
        }
    }
}
  return $field_info;
}

public function push_object($module,$fields,$meta){ 
 /*        $request = new GetRequest();
      
    $request->baseRef = new RecordRef();
$request->baseRef->internalId = '645233';
$request->baseRef->type = 'customer';
$res=$this->post_crm('get',$request);

  //  $res=$this->get_entry('Customer','645233');
    var_dump($res); die();

    $emailSearchField = new SearchEnumMultiSelectField();
$emailSearchField->operator = "anyOf";
$emailSearchField->searchValue = array('_inventoryItem'); // ItemType class

$field=new SearchStringField();
$field->operator='startsWith';
$field->searchValue='screw- cap';
//$field->searchValue='--AA--';
$search = new ItemSearchBasic();
//$search->type = $emailSearchField;
//$search->displayName = $field;

$r=new RecordRef();
$r->internalId="7582";
//$r->internalId="77057";
$field = new SearchMultiSelectField();
$field->operator = 'anyOf';
$field->searchValue[] = $r;

$search->internalId = $field;
//$search->vendorName = $field;

$request = new SearchRequest();
$request->searchRecord = $search;
$res = $this->post_crm('search',$request);
$item=$res->searchResult->recordList->record[0];
$price=0;
if(!empty($item->pricingMatrix->pricing)){
  foreach($item->pricingMatrix->pricing as $v){
    if(!empty($v->priceList->price[0]->value) && $v->priceLevel->internalId == 1){
     $price=$v->priceList->price[0]->value;
     if(!empty($item->currency) && !empty($v->currency->name) && $item->currency == $v->currency->name ){
        $price=$v->priceList->price[0]->value;  
     }
    // break;   
    }  
  }  
}
//$res=$this->price_levels(); 
var_dump($price,$item,$res); die();*/

if( !empty($meta['forms_check']) && !empty($meta['form_url']) ){
    $url_arr=parse_url($meta['form_url']);
    
    $code=$status=0; $error='';
    if(!empty($url_arr['query'])){
    parse_str($url_arr['query'],$q_arr);
    if(!empty($q_arr['formid'])){
    $url=$url_arr['scheme'].'://'.$url_arr['host'].$url_arr['path'].'?compid='.$q_arr['compid'];    
$post=array('h'=>$q_arr['h'],'formid'=>$q_arr['formid'],'taxable'=>'T','subsidiary'=>'-1','type'=>'Customer','submitter'=>'Submit');
foreach($fields as $k=>$v){
$k=strtolower($k);
$val=$v['value'];
 if($k == 'country'){
     $countries=$this->countries();
      if(isset($countries[$val]['ns'])){
   //  $val=$countries[$val]['ns'];  
      }
  }

$post[$k]=$val;
}
$user_agent='';
if(isset($_SERVER['HTTP_USER_AGENT'])){
    $user_agent=$_SERVER['HTTP_USER_AGENT'];
}else if(isset($_SERVER['USER_AGENT'])){
    $user_agent=$_SERVER['USER_AGENT'];
}
$head=array('User-Agent: '.$user_agent);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if($code == 302){
$status='1';    
}
//var_dump($url,$post,$code,$status); die();
curl_close($ch);
}    
    }
return array("error"=>$error,"id"=>'','link'=>'',"action"=>'Added',"status"=>$status,"data"=>$fields,"response"=>array('code'=>$code),'object'=>'Customer');  
}
//check primary key
 $extra=array();
  $debug = isset($_REQUEST['vx_debug']) && current_user_can('manage_options');
  $event= isset($meta['event']) ? $meta['event'] : '';
  $id= isset($meta['crm_id']) ? $meta['crm_id'] : '';
  $crm_fields= isset($meta['fields']) ? $meta['fields'] : '';
  $req_id='';
  $module_name=ucfirst($module);

  if($debug){ ob_start();}
if(!empty($meta['primary_key']) && !empty($fields[$meta['primary_key']]['value'])){    
$field=$meta['primary_key'];
$search_val=$fields[$field]['value'];

$emailSearchField = new SearchStringField();
$emailSearchField->operator = "startsWith";
$emailSearchField->searchValue = $search_val;
$search_class=$module_name.'SearchBasic';
$search = new $search_class;
$search->{$field} = $emailSearchField;

$request = new SearchRequest();
$request->searchRecord = $search;

$s_res = $this->post_crm('search',$request,20);
//die(json_encode($s_res));
if ($s_res->searchResult->status->isSuccess && is_array($s_res->searchResult->recordList->record) && count($s_res->searchResult->recordList->record)>0) {
    $id=$s_res->searchResult->recordList->record[count($s_res->searchResult->recordList->record)-1]->internalId;
    $extra["response"]=$id;
}else{
 $extra["response"]=$s_res;   
}  
//var_dump($id,$s_res); die();
$extra["body"]=array($field=>$search_val);

  if($debug){
  ?>
  <h3>Search field</h3>
  <p><?php print_r($field) ?></p>
  <h3>Search term</h3>
  <p><?php print_r($search) ?></p>
    <h3>POST Body</h3>
  <p><?php print_r($body) ?></p>
  <h3>Search response</h3>
  <p><?php print_r($res) ?></p>  
  <?php
  } 

}


 $status=$action=""; $send_body=true; $arr=array();
 $entry_exists=false; 
 $link=""; $error=""; 


 $object=$module;  $send_req=true;
if($id == ""){
    //insert new object
$action="Added";  $status="1";
}else{
 $entry_exists=true;
   if(in_array($event,array('delete'))){
     $action="Deleted";  
  $status="5";  
  }else{
    //update object
 $action="Updated"; $status="2";
    if(!empty($meta['update'])){ $send_req=false; }
  }
}
if($send_req){
$custom=isset($meta['fields']) ? $meta['fields'] : array();
//var_dump($fields,$custom); die();
if(in_array($status,array('1','2'))){

    $customer = new $module_name;
  
   if($module_name == 'Customer'){ 
    $customer->isPerson=true;
    if(empty($field['firstName']) && empty($fields['lastName']) && !empty($fields['companyName'])){
     $customer->isPerson=false;   
    }
   }  
$images=array();
  $address=$line_fields=$line_options=array();
  $countries=$this->countries();

  foreach($fields as $k=>$v){
  
      $val=$v['value']; 
    //  if(strpos($v['label'],'Date') == strlen($v['label'])-4){ //date at end
   $field_type= !empty($custom[$k]['field_type']) ? $custom[$k]['field_type'] : '';
   if( in_array($field_type, array('line_field','line_option') ) ){
   if( $v['type'] =='' && strpos($v['field'],"__vx_pa-") ===0 ){ 
    $v['field']=substr($v['field'],8); $v['is_line_meta']='1';
} 
       $line_fields[$k]=$v; continue;
   }
   
 if(!empty($custom[$k]['is_custom'])){ 
        if($custom[$k]['type'] == '_image'){
   $images[$k]=$val;    
   }else{
  $field=$this->net_field($custom,$k,$val);
  $customer->customFieldList[]=$field;     
   }
 }
 else{
       if(strpos($v['label'],'Date') !== false ){ 
       //if date at the end
       $val=strtotime($val);   
       if(empty($val)){continue;}
      }else if(in_array($v['label'],array('sendEmail'))){
          $val=(bool)$val;
      }
      
  if($custom[$k]['type'] == 'address'){
  $i=0;
  if(strpos($k,'ship_') === 0){
     $i=1;  $k=substr($k,5);
  }
  if($k == 'country'){
      if(isset($countries[$val]['ns'])){
     $address[$i][$k]=$countries[$val]['ns'];  
      }
  }else{
        $address[$i][$k]=$val; 
  }
  
  }else if($custom[$k]['type'] == 'bool' ){
   $customer->{$k} = (bool)$val;    
  }
  else if($custom[$k]['type'] == 'List' || in_array($k,array('partner','subsidiary','customForm','leadSource','category')) || ($module == 'SupportCase' && in_array($k,array('company','origin','issue','category'))) ){
 $record= new RecordRef();
$record->internalId =$val;
$customer->{$k} = $record; 
  }else{   
      $customer->{$k}=$val;  

  }      
 }        
}
//var_dump($customer,$fields); die(); 
if(!empty($address)){

 if($module == 'Customer' && !empty($address[0])){
 $addres = new Address();
 foreach($address[0] as $k=>$v){
 $addres->{$k}=$v;      
 }
$address_book = new CustomerAddressBook();
//$address_book->defaultShipping = false;
//$address_book->defaultBilling = false;
//$address_book->isResidential = true;
$address_book->addressbookAddress = $addres;
$address_book_list = new CustomerAddressbookList();
$address_book_list->addressbook = $address_book;
$address_book_list->replaceAll = false;

$customer->addressbookList = $address_book_list;         
 }else{
     if(!empty($address[0])){
  $addres = new Address();
 foreach($address[0] as $k=>$v){
 $addres->{$k}=$v;      
 } 
$customer->billingAddress=$addres;     
}
     if(!empty($address[1])){
  $addres = new Address();
 foreach($address[1] as $k=>$v){
 $addres->{$k}=$v;      
 } 
$customer->shippingAddress=$addres;     
}
     
 }
}
$line_items=array();
if( in_array($module,array('SalesOrder','Invoice')) && method_exists(self::$_order,'get_items')){
$items=self::$_order->get_items(); 
     $products=array();  
    $list_class='SalesOrderItemList'; $item_class='SalesOrderItem';
    if($module == 'Invoice'){ $list_class='InvoiceItemList'; $item_class='InvoiceItem'; } 
    
     if(is_array($items) && count($items)>0 ){
          $list=new $list_class; 
      foreach($items as $item_id=>$item){
       $p_id= !empty($item['variation_id']) ? $item['variation_id'] : $item['product_id'];
        $line_desc=array();
        if(!isset($products[$p_id])){
        $product=wc_get_product($p_id);
        }else{
         $product=$products[$p_id];   
        }
     if($product){   
        $products[$p_id]=$product;
        $sku=$product->get_sku();
//search sku in netsuite
$emailSearchField = new SearchStringField();
$emailSearchField->operator = "is";
$emailSearchField->searchValue = $sku;

$search=new ItemSearchBasic();
$search->itemId=$emailSearchField;

$request = new SearchRequest();
$request->searchRecord = $search;
$res=$this->post_crm('search',$request); 

if ($res->searchResult->status->isSuccess && is_array($res->searchResult->recordList->record) && count($res->searchResult->recordList->record)>0) {
    $rec=$res->searchResult->recordList->record[count($res->searchResult->recordList->record)-1];
    $net_id=$rec->internalId;
    $extra["Line Item Found in Netsuite"]=$net_id;
   
      $record = new RecordRef();
$record->internalId = $net_id;
$item_n=new $item_class;
$item_n->item=$record;
 $qty=1;
    if(method_exists($item,'get_quantity')){
    $qty=$item->get_quantity();
    }else if(method_exists(self::$_order,'get_item_meta')){
    $qty=self::$_order->get_item_meta($item_id, '_qty', true);    
    }
$item_n->quantity=$qty;
    
    if(!empty($line_fields)){
        foreach($line_fields as $field_id=>$field){
 if(in_array($field['type'],array('','custom')) && !empty($field['field']) && !empty($field['is_line_meta'])){    //gen or custom field       
$val=wc_get_order_item_meta($item_id,$field['field'],true);
 }else{ //custom value
 $val=$field['value'];    
 }
$fields[$field_id]=$val;
$field=$this->net_field($custom,$field_id,$val);
$item_n->customFieldList[]=$field;  

        }
    }
     if(!empty($line_options)){
        foreach($line_options as $field_id=>$field){
if(in_array($field['type'],array('','custom')) && !empty($field['field']) && !empty($field['is_line_meta']) ){ 
$val=wc_get_order_item_meta($item_id,$field['field'],true);
}else{ //custom value
 $val=$field['value'];    
}
$fields[$field_id]=$val;
$field=$this->net_field($custom,$field_id,$val);
$item_n->options[]=$field;  
   
        }
    }   
  if(!empty($meta['price_level_check']) && !empty($meta['price_level'])){
 $fields['price_level']=array('label'=>'Price Level','value'=>$meta['price_level']); 
 $record= new RecordRef();
$record->internalId = $meta['price_level'];
$item_n->price = $record;
}

$list->item[]=$item_n;
}else{
    $extra["Line Item Not Found in Netsuite"]='SKU = '.$sku;
}
     }else{
    $extra["Line Item Not Found in Woo"]='SKU = '.$sku;     
     }
}
} }

if(!empty($list->item)){
$customer->itemList=$list;   
}

if(!empty($meta['customer_check']) && !empty($meta['object_customer'])){
     $customer_feed=$meta['object_customer']; 
   if( isset(self::$feeds_res[$customer_feed]) ){
   $customer_res=self::$feeds_res[$customer_feed];
  if(!empty($customer_res['id'])){
$fields['customer_id']=array('label'=>'Customer ID','value'=>$customer_res['id']); 
$record= new RecordRef();
$record->internalId = $customer_res['id'];
$customer->entity = $record;
  }   
   }  
}
/*
$c='3124';
$record= new RecordRef();
$record->internalId = $c;
$customer->entity = $record;

$c='19';
$record= new RecordRef();
$record->internalId = $c;
$customer->department = $record;

$c='33';
$record= new RecordRef();
$record->internalId = $c;
$customer->class = $record;
*/
if(!empty($meta['loc_check']) && !empty($meta['loc'])){
 $fields['loc']=array('label'=>'Location','value'=>$meta['loc']); 
 $record= new RecordRef();
$record->internalId = $meta['loc'];
$customer->location = $record;
}

if(!empty($meta['ship_method_check']) && !empty($meta['ship_method'])){

    if(empty($meta['ship_method']) && method_exists(self::$_order,'get_shipping_methods')){
   $methods=self::$_order->get_shipping_methods(); 
    $method=reset($methods); $method_id=$method->get_method_id();
        if(!empty($meta['ship_map'][$method_id])){
      $meta['ship_method']= $meta['ship_map'][$method_id]; 
    } }

 $fields['ship_method']=array('label'=>'Shipping Method','value'=>$meta['ship_method']); 
 $record= new RecordRef();
$record->internalId = $meta['ship_method'];
$customer->shipMethod = $record;
}

if(!empty($meta['pay_method_check']) && !empty($meta['pay_method'])){
 
 if(empty($meta['pay_method']) && method_exists(self::$_order,'get_payment_method')){
   $pay_method=self::$_order->get_payment_method();
   if(!empty($meta['pay_map'][$pay_method])){
       $meta['pay_method']=$meta['pay_map'][$pay_method];
   }   
 }
 $fields['pay_method']=array('label'=>'Payment Method','value'=>$meta['pay_method']); 
 $record= new RecordRef();
$record->internalId = $meta['pay_method'];
$customer->paymentMethod = $record;
}


if(!empty($meta['discount_item_check'])){
 
 if(empty($meta['discount_item']) && method_exists(self::$_order,'get_payment_method')){
   $pay_method=self::$_order->get_payment_method();
   if(!empty($meta['discount_item_map'][$pay_method])){
       $meta['discount_item']=$meta['discount_item_map'][$pay_method];
   }   
 } 
 $fields['discount_item']=array('label'=>'Discount Item','value'=>$meta['discount_item']); 
 //fees
 if(!empty($meta['discount_item_fee'][$pay_method])){
     $fee=self::$_order->get_meta($meta['discount_item_fee'][$pay_method],true);
  $fields['discount_item_fee']=array('label'=>'Discount Item Fee','value'=>$fee);
  $customer->discountRate = $fee;
 }
 
 $record= new RecordRef();
$record->internalId = $meta['discount_item'];
$customer->discountItem = $record;
}

if(!empty($meta['lead_source_check']) && !empty($meta['lead_source'])){
 $fields['lead_source']=array('label'=>'Lead Source','value'=>$meta['lead_source']); 
 $record= new RecordRef();
$record->internalId = $meta['lead_source'];
$customer->leadSource = $record;
}


if(!empty($meta['class_check']) && !empty($meta['class'])){
 $fields['class']=array('label'=>'Class','value'=>$meta['class']); 
 $record= new RecordRef();
$record->internalId = $meta['class'];
$customer->class = $record;
}
if(!empty($meta['order_status'])){
 $fields['order_status']=array('label'=>'Order Status','value'=>$meta['order_status']); 
$customer->orderStatus = $meta['order_status'];
}




if(!empty($meta['status_sel'])){
 $fields['status_sel']=array('label'=>'Status','value'=>$meta['status_sel']); 
 $customer->status=$meta['status_sel'];  
}
if(!empty($meta['priority_sel'])){
 $fields['priority_sel']=array('label'=>'Priority','value'=>$meta['priority_sel']); 
 $customer->priority=$meta['priority_sel'];    
}
if(!empty($meta['status_check']) && !empty($meta['status'])){
$fields['status_sel']=array('label'=>'Customer Stage ','value'=>$meta['status']); 
$statu= new RecordRef();
$statu->internalId = $meta['status'];
$customer->entityStatus = $statu;
}


if(!empty($meta['vx_company_id']) ){
$fields['company_rec']=array('label'=>'Company Rec','value'=>$meta['vx_company_id']['value']); 
$comp= new RecordRef();
$comp->internalId = $meta['vx_company_id']['value'];
$customer->company = $comp;
}
if(!empty($meta['vx_contact_id']) ){
$fields['contact_rec']=array('label'=>'Contact Rec','value'=>$meta['vx_contact_id']['value']); 
$comp= new RecordRef();
$comp->internalId = $meta['vx_contact_id']['value'];
$customer->contact = $comp;
}
if(!empty($meta['emp_check']) && !empty($meta['emp'])){
$fields['assigned_to']=array('label'=>'Assigned To','value'=>$meta['emp']); 
$emp= new RecordRef();
$emp->internalId = $meta['emp'];
if( in_array($module,array('Customer','SalesOrder') )){
$customer->salesRep=$emp;  
}else{
$customer->assigned=$emp; 
}

} 
}
if($module == 'Contact'){

}

//$images=array_slice($images,1);
if(!empty($images) && !empty($meta['folder'])){

   $list_req = new AddListRequest();
    foreach($images as $k=>$v){
    $file=new File();
$file_name=substr($v,strrpos($v,'/')+1);
    $file->name=!empty($file_name ) ? $file_name : uniqid() ;
$file->url=$v;
//$file->url='https://0.s3.envato.com/files/222832246/banner.png';
  $folder= new RecordRef();
$folder->internalId = $meta['folder'];
$file->folder= $folder;
$list_req->record[]=$file;
    }
$list_res = $this->post_crm('addList',$list_req);
//var_dump($list_res,$list_req); die('----------');
if(!empty($list_res->writeResponseList->writeResponse)){
    $list_res=$list_res->writeResponseList->writeResponse;
   $no=0;
     foreach($images as $k=>$v){
       if(!empty($list_res[$no]->baseRef->internalId)){
$option=new ListOrRecordRef();
$option->internalId=$list_res[$no]->baseRef->internalId;
$fieldb = new SelectCustomFieldRef();
$fieldb->value = $option;
$fieldb->scriptId = $k;
$customer->customFieldList[] =$fieldb;
$no++;
       }
     }

}
}
//var_dump($customer,$custom); die();
if($status == '2'){    
   $customer->internalId=$id;
    
 $request = new UpdateRequest();
$request->record = $customer;
$res=$this->post_crm('update',$request); 

}
else if($status == '1'){

 $request = new AddRequest();
$request->record = $customer;
$res = $this->post_crm('add',$request);
/*$request = new GetRequest();
$request->baseRef = new RecordRef();
$request->baseRef->internalId = 4719;
$request->baseRef->internalId = 1045190;
$request->baseRef->type = 'supportCase';
$request->baseRef->type = 'salesOrder';
$res=$this->post_crm('get',$request);*/
//if($module == 'SalesOrder'){
//var_dump($customer,$res);  die('---------');
//}
}
else if($status == '5'){
    
$request = new DeleteRequest();
$request->baseRef = new RecordRef();
$request->baseRef->internalId = $id;
$request->baseRef->type = "customer";
$res = $this->post_crm('delete',$request);   

}

//var_dump($res,$customer); die();
//die(json_encode($res));
//$arr=json_decode(json_encode($res),true);
$arr=(array)$res;
//var_dump($id,$res,$status); die();
if (isset($res->writeResponse->status->isSuccess) && $res->writeResponse->status->isSuccess === true ) {
     $id=$res->writeResponse->baseRef->internalId;
 if(!empty($this->url)){
     if($module == 'Task'){
 $link=$this->url.'app/crm/calendar/task.nl?id='.$id;
     }else if($module == 'SalesOrder'){
 $link=$this->url.'app/accounting/transactions/salesord.nl?id='.$id;
     }else if($module == 'SupportCase'){
 $link=$this->url.'app/crm/support/supportcase.nl?id='.$id;
     }else{
 $link=$this->url.'app/common/entity/custjob.nl?id='.$id;
     }
 }

} else {
    $id=$status='';
$error= 'UnKnow Error';
if(isset($res->writeResponse->status->statusDetail[0]->message)){
 $error=$res->writeResponse->status->statusDetail[0]->message;   
}else if(isset($res->error)){
 $error=$res->error;   
}
//var_dump($res,$error); die();
} }
//var_dump($status,$error); die();
  if($debug){
  ?>
  <h3>Account Information</h3>
  <p><?php //print_r($this->info) ?></p>
  <h3>Data Sent</h3>
  <p><?php print_r($post) ?></p>
  <h3>Fields</h3>
  <p><?php echo json_encode($fields) ?></p>
  <h3>Response</h3>
  <p><?php print_r($response) ?></p>
  <h3>Object</h3>
  <p><?php print_r($module."--------".$action) ?></p>
  <?php
  $contents=trim(ob_get_clean());
  if($contents!=""){
  update_option($this->id."_debug",$contents);   
  }
  }

       //add entry note
 if(!empty($meta['__vx_entry_note']) && !empty($id)){
 $disable_note=$this->post('disable_entry_note',$meta);
   if(!($entry_exists && !empty($disable_note))){
       $entry_note=$meta['__vx_entry_note'];

$note=new Note();

$note->title=$entry_note['title'];
$note->note=$entry_note['body'];
if(!empty($meta['note_dir'])){
$note->direction=$meta['note_dir'];
}
if(!empty($meta['note_type'])){
$con= new RecordRef();
$con->internalId = $meta['note_type'];
$note->noteType=$con;
}

$con= new RecordRef();
$con->internalId = $id;
if($module == 'Customer'){
$note->entity= $con;
}else if($module == 'SalesOrder'){
$note->transaction= $con;    
}else{
 $note->activity= $con;    
}

$request = new AddRequest();
$request->record = $note;
$note_res = $this->post_crm('add',$request);

  $extra['Note Title']=$entry_note['title'];
  $extra['Note Body']=$entry_note['body'];

   $extra['Note Id']=$note_res;   
  
   }  
 }
 
//var_dump($status); die();
return array("error"=>$error,"id"=>$id,"link"=>$link,"action"=>$action,"status"=>$status,"data"=>$fields,"response"=>$arr,"extra"=>$extra,'object'=>$module_name);
}
public function get_rec($id,$type='siteCategory'){
$request = new GetRequest();
$request->baseRef = new RecordRef();
$request->baseRef->internalId = $id;
$request->baseRef->type = $type;
$res=$this->post_crm('get',$request);   
$re=new stdClass();
if(!empty($res->readResponse->record)){
 $re=$res->readResponse->record;   
}
return $re;
}
public function net_field($custom,$k,$val){

$field=null;
   if($custom[$k]['type'] == '_listRecord'){
 
$option=new ListOrRecordRef();
$option->internalId=$val;

$field = new SelectCustomFieldRef();
$field->value = $option;
$field->scriptId = $k;
}
else if( $custom[$k]['type'] == '_multipleSelect'){
    
$field = new MultiSelectCustomFieldRef();
$sel_arr=explode(',',$val);
foreach($sel_arr as $v){
  $option=new ListOrRecordRef();
$option->internalId=trim($v);
$field->value[]=$option;    
}
$field->scriptId = $k;

}
elseif(in_array($custom[$k]['type'], array('_freeFormText', '_hyperlink') )){
  $field = new StringCustomFieldRef();
$field->value = $val;
$field->scriptId = $k;   
}
if($custom[$k]['type'] == '_date'){
    $val=strtotime($val);   
$field = new DateCustomFieldRef();
$field->value = $val;
$field->scriptId = $k;
   }
   return $field;
   
}

public function get_customer_invoice($id){
      $customerSearchBasic = new CustomerSearchBasic();
    $searchValue = new RecordRef();
    $searchValue->type = 'customer';
    $searchValue->internalId = $id;
    $searchMultiSelectField = new SearchMultiSelectField();
    cfx_netsuite_common::setFields($searchMultiSelectField, array('operator' => 'anyOf', 'searchValue' => $searchValue));
    $customerSearchBasic->internalId = $searchMultiSelectField;

    $transactionSearchBasic = new TransactionSearchBasic();
    $searchMultiSelectEnumField = new SearchEnumMultiSelectField();
    cfx_netsuite_common::setFields($searchMultiSelectEnumField, array('operator' => 'anyOf', 'searchValue' => "_invoice"));

    $transactionSearchBasic->type = $searchMultiSelectEnumField;

    $transactionSearch = new TransactionSearch();
    $transactionSearch->basic = $transactionSearchBasic;
    $transactionSearch->customerJoin = $customerSearchBasic;

    $request = new SearchRequest();
    $request->searchRecord = $transactionSearch;
$val=0;
    $s_res = $this->post_crm('search',$request,'');

  if ($s_res->searchResult->status->isSuccess && is_array($s_res->searchResult->recordList->record) && count($s_res->searchResult->recordList->record)>0) {
  $val=$s_res->searchResult->recordList->record[count($s_res->searchResult->recordList->record)-1]->estGrossProfit;
}
  return $val;
}
public function get_entry($module,$id){

      $request = new GetRequest();
      
    $request->baseRef = new RecordRef();
$request->baseRef->internalId = $id;
$request->baseRef->type = $module;
$res=$this->post_crm('get',$request);
$arr=array();
if(!empty($res->readResponse->record)){
  if(!empty($res->readResponse->record->CustomFieldList)){
      foreach($res->readResponse->record->CustomFieldList as $k=>$v){
          if(isset($v->scriptId)){
       $arr[$v->scriptId]=$v->value;       
          }
      }
  }
 foreach($res->readResponse->record as $k=>$v){
     if(is_object($v) && isset($v->internalId)){
     $arr[$k]=$v->internalId;    
     }else{
     $arr[$k]=$v;    
     }
 }   
}
//var_dump($res,$module,$id); die();

  return $arr;     
}
public function post_crm($method,$request,$records=20){

try{
    if($method=='search' && !empty($records) ){
self::$service->setSearchPreferences(false, $records); 
    }
$res = self::$service->{$method}($request);

}catch(Exception $e){
$res=new stdClass();
$res->error=$e->getMessage();
}

return $res;
}
   

}
}
?>