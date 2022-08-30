<?php
session_start();
	//error_reporting(0);	
	if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
};
include 'dbs/dbLinks.php';
$curTime = date("Y:m:d:h:i:a");
$ref = $_SERVER['HTTP_REFERER'];
$httpAgent=$_SERVER['HTTP_USER_AGENT'];
$ip=$_SERVER['REMOTE_ADDR'];
$trackingPage = $_SERVER['SCRIPT_NAME'];


$docroot = "";
if ($_SERVER['HTTP_HOST'] == "localhost")
{ $docroot = $_SERVER['DOCUMENT_ROOT']."propCalcs";
} else { $docroot = $_SERVER['DOCUMENT_ROOT'];}
//include 'compon.php';
include 'mfuncs/mFuncs.php';

$navbarType = "navbar-light";
$navBrandClass = "prop";
//include 'dbs/dbRates.php';
//include $docroot."/models/agent.php";
//include $docroot."/models/lo.php";
//include $docroot.'/dbs/dbAgent.php';	
$page = 'home';



if(isset($_POST['sub-calc'])){
    $initSubmit = '';
    $btnTxt = "<b>New Results Below</b>";
} else {$initSubmit = 'd-none'; $btnTxt = "Calculate";}

function heRateAdj($score){
    if ($score >= 720) {
				$rateAdj = 0;
            } else if ($score >= 700) {
				$rateAdj = .125;
            } else if ($score >= 680) {
				$rateAdj = .25;
			} else if ($score >= 660) {
				$rateAdj =.375;
			} else if($score >= 640) {
				$rateAdj = .50;
            } else if($score >= 620) {
				$rateAdj = .75;
};
return $rateAdj;
}

function mortgageCalc($val, $curbal, $costs, $cash){
$maxCashMtg = $val * .80;
$maxRefiMtg = $val * .95;
$maxRenoMtg = $val * .97;
$maxRenoFHA = $val * 1.1;

if ($maxCashMtg < $curbal + $costs){
	if ($curbal + $costs < $maxRefiMtg){
		$calcMtg = $curbal + $costs;
		$maxAvailMtg = $calcMtg;
        $refiType = "rateTerm";
	} else {$calcMtg = $maxRefiMtg; 
            $maxAvailMtg = $calcMtg;
            $refiType = "rateTerm";}
} elseif ($curbal + $costs + $cash < $maxCashMtg) {
		$calcMtg = $curbal + $costs + $cash;
		$maxAvailMtg  = $maxCashMtg;
		$refiType = "cashOut";
} else {$calcMtg = $maxCashMtg; $maxAvailMtg = $maxCashMtg; $refiType = "cashOut";}

if ($curbal + $costs + $cash < $maxRenoMtg){
    $calcRenoMtg = $curbal + $costs + $cash;
} else {$calcRenoMtg = $maxRenoMtg;}

if ($curbal + $costs + $cash < $maxRenoFHA){
    $calcRenoFHA = $curbal + $costs + $cash;
} else {$calcRenoFHA = $maxRenoFHA;}

$res = array (
    "calcMtg"=>$calcMtg,
    "maxAvailMtg"=>$maxAvailMtg,
    "calcRenoMtg"=>$calcRenoMtg,
    "maxRenoAvail"=>$maxRenoMtg,
    "calcRenoFHA"=>$calcRenoFHA,
    "maxRenoFHA"=>$maxRenoFHA,
    "refiType"=>$refiType
);
return $res;
}//end function

function cashBackDiff($cb,$d){
    if($cb >= $d){
        $cbColor = "#464646";
        $cbText = "You Get";
        $cbExpl = "";
        $cbExplgrid = "";
        $cbSym = "";
    } elseif($cb >=0 ){
        $cbColor = "#464646";
        $cbText = "You Get";
        $cbExpl = "<tr><td class='text-right' style='color:red;' colspan='2'>*Conv Cashout: Not enough equity to get more cash*</td></tr>";
        $cbExplgrid = "*Conv Cashout: Not enough equity to get more cash*";
        $cbSym = "<span style='color:red;'>*</span>";
    }   else {
        $cbColor = "red";
        $cbText = "You Pay";
        $cbExpl = "<tr><td class='text-right' style='color:red;' colspan='2'>*Conv Cashout: Not enough equity to get cash*</td></tr>";
        $cbExplgrid = "*Conv Cashout: Not enough equity to get cash*";
        $cbSym = "<span style='color:red;'>*</span>";
    };
    $res = array(
        "cbColor"=>$cbColor,
        "cbText"=>$cbText,
        "cbExpl"=>$cbExpl,
        "cbExplgrid"=>$cbExplgrid,
        "cbSym"=>$cbSym
    );
    return $res;
}

function heCashBackDiff($heCB,$heD){

    if($heCB >= $heD){
        $hecbColor = "#464646";
        $hecbText = "You Get";
        $hecbExpl = "";
        $hecbExplgrid = "";
        $cbSym2 = "";
    } elseif($heCB >=0 ){
        $hecbColor = "#464646";
        $hecbText = "You Get";
        $hecbExpl = "<tr><td class='text-right' style='color:red;' colspan='2'>**Home Equity: Not enough equity to get more cash**</td></tr>";
        $hecbExplgrid = "**Home Equity: Not enough equity to get more cash**";
        $cbSym2 = "<span style='color:red;'>**</span>";
    }   else {
        $hecbColor = "red";
        $hecbText = "You Pay";
        $hecbExpl = "<tr><td class='text-right' style='color:red;' colspan='2'>**Home Equity: Not enough equity to get cash**</td></tr>";
        $hecbExplgrid = "**Home Equity: Not enough equity to get cash**";
        $cbSym2 = "<span style='color:red;'>**</span>";
    }
        $res = array(
        "hecbColor"=>$hecbColor,
        "hecbText"=>$hecbText,
        "hecbExpl"=>$hecbExpl,
        "hecbExplgrid"=>$hecbExplgrid,
        "cbSym2"=>$cbSym2
    );
    return $res;
}

if(isset($_POST['salesPrice'])) {
  $salesPrice = $_POST['salesPrice'];
} else { $salesPrice = "250000";
}

if(isset($_POST['reTax'])) {
  $reTax = $_POST['reTax'];
} else { $reTax = "0";
}

if(isset($_POST['creditScore'])) {
  $score = $_POST['creditScore'];
} else { $score = "740";
}

$curBal = $_POST['curBal'];
$cashRepairs = $_POST['cash'];
$cashRepairsAdj = $cashRepairs + 800;
$reMonthly = floatval($reTax)/12;
$hoPrem = $_POST['hoins'];
$hoins = $hoPrem/12;
$loc = $_POST["loc"];

switch ($loc){
    case ($loc == "Phila"): $ppHoMo = 0;
                            $esHoMo = 6;
                            $ppTaxMo = 0;
                            $esTaxMo = 6;
    break;
    case ($loc == "PA"):    $ppHoMo = 0;
                            $esHoMo = 6;
                            $ppTaxMo = 0;
                            $esTaxMo = 6;
    break;       
    case ($loc == "NJ"):    $ppHoMo = 0;
                            $esHoMo = 6;
                            $ppTaxMo = 0;
                            $esTaxMo = 4;
    break;     
}

if($hoPrem == "" || $hoPrem == 0){
    $esHoMo = 0;
}

//Conv cashout calcs ------------------------------------------------->
$preCashInput = (($curBal + $cashRepairsAdj) / $salesPrice > .77) ? $preCashInput = $salesPrice * .77 : $preCashInput = $curBal + $cashRepairsAdj;
$preCashLTV = ($preCashInput / $salesPrice) * 100;
$preCashPtsArr1 = convRateAdj($score,$preCashLTV);
$preCashPts1 = $preCashPtsArr1['convPointAdj'] + $preCashPtsArr1['cashPointAdj'];
$preCashInput = $preCashInput + (($preCashPts1 /100) * $preCashInput) + 800;
$preCash1 = prelimLoan($preCashInput,$loc,"Conv",0,$reTax,$hoPrem,$esHoMo,$esTaxMo);
$cashLoan = ($preCash1 <= ($salesPrice * .80)) ? $preCash1 : $salesPrice * .80;
$maxLoanAvail = $salesPrice * .80;
$cashDP = $salesPrice - $cashLoan;
$cashOutRateAdj = $preCashPtsArr1['convRateAdj'] + $preCashPtsArr1['cashRateAdj'];
$cashRate = $conv30rate +$cashOutRateAdj;
$cashArr = ConvLoan($salesPrice,$cashDP,$cashRate,30,1,$score);
$cashLoanAmt = $cashArr['totalLoan'];
$cashCosts = ccosts($salesPrice,$cashLoanAmt,$cashRate,$loc,'Conv-Refi',$reTax,$preCashPts1,$hoPrem,$ppHoMo,$esHoMo,$ppTaxMo,$esTaxMo);
$totCostsCash = $cashCosts['subTotPP'] + $cashCosts['subTotCosts'] + $curBal;
$cashBack = $cashLoanAmt - $totCostsCash;

if ($cashBack < 5000){
$preRTguess = prelimLoan($curBal,$loc,"Conv",1,$reTax,$hoPrem,$esHoMo,$esTaxMo);
$preRTltv = ($preRTguess / $salesPrice) * 100;
$preRTptsArr1 = convRateAdj($score,$preRTltv);
$preRTpts1 = $preRTptsArr1['convPointAdj'];
$preRTguess2 = prelimLoan($curBal,$loc,"Conv",$preRTpts1,$reTax,$hoPrem,$esHoMo,$esTaxMo);
$rtLoan = ($preRTguess2 <= ($salesPrice * .95)) ? $preRTguess2 + 1200 : $salesPrice * .95;
$maxRTavail = $rtLoan;
$maxLoanAvail = $maxRTavail;
$rtDP = $salesPrice - $rtLoan;
$rtRateAdj = $preRTptsArr1['convRateAdj'];
$rtRate = $conv30rate +$rtRateAdj;
$rtArr = ConvLoan($salesPrice,$rtDP,$rtRate,30,1,$score);
$rtLoanAmt = $rtArr['totalLoan'];
$rtCosts = ccosts($salesPrice,$rtLoanAmt,$rtRate,$loc,'Conv-Refi',$reTax,$preRTpts1,$hoPrem,$ppHoMo,$esHoMo,$ppTaxMo,$esTaxMo);
$totCostsRT = $rtCosts['subTotPP'] + $rtCosts['subTotCosts'] + $curBal;
$rtBack = $rtLoanAmt - $totCostsRT;
if ($rtBack > (.01 * $rtLoanAmt) || $rtBack > 2000){
    if( .01 * $rtBack > 2000){
        $rtBack = 2000;
    } else {$rtBack = .01 * $rtLoanAmt;}
} else {$rtBack = $rtBack;};
$zeroRate = $rtRate;
$zeroArr = $rtArr;
$zeroLoan = $rtArr['totalLoan'];
$zeroPNI = $rtArr['pi'];
$zeroMI = $rtArr['mi'];
$zeroTotPmt = $zeroPNI + $reMonthly + $zeroMI + $hoins;
$ccosts = $rtCosts;
$zeroTotCosts = $totCostsRT;
$zeroCash = $rtBack;
$zeroCBArr = cashBackDiff($rtBack,2000);
} else {
$zeroRate = $cashRate;
$zeroArr = $cashArr;
$zeroLoan = $zeroArr['totalLoan'];
$zeroPNI = $zeroArr['pi'];
$zeroMI = $zeroArr['mi'];
$zeroTotPmt = $zeroPNI + $reMonthly + $zeroMI + $hoins;
$ccosts = $cashCosts;
$zeroTotCosts = $totCosts97;
$zeroCash = $cashBack;
$zeroCBArr = cashBackDiff($cashBack,2000);
}

$convAPR = calcAPR($salesPrice, $zeroLoan, 360, $zeroRate, $ccosts['aprFees'], $zeroMI);
$dcharges = $ccosts['acharges'] + $ccosts['bcharges'] + $ccosts['ccharges'];
$icharges = $ccosts['echarges'] + $ccosts['fcharges'] + $ccosts['gcharges'] + $ccosts['hcharges'];
$jcharges = $dcharges + $icharges;

//Home Equity Calcs ------------------------------------------------------>
$heCosts = array(
"origFeeDoll"=>0,"apprFee"=>200, "credit"=>0,"floodChk"=>0,"discFee"=>0,"mersFee"=>0,"points"=>0,"notary"=>25,"eDocs"=>0,"wireFee"=>0,"settlement"=>0,"cpl"=>0, "expressMail"=>0, "endorsements"=>0,"taxCert"=>0,"recDeed"=>0,"recMtg"=>250,"termite"=>0,"homeInsp"=>0,"agentConvey"=>0,"recFees"=>250,"titleSvcs"=>0,"aprFees"=>0,"titlePrem"=>0,"ttax"=>0,"dailyIntDays"=>0,"dailyIntAmt"=>0,"ppInt"=>0,"ppHoMos"=>0,"escHoMos"=>0,"hoins"=>0,"ppHoIns"=>0,"escHoIns"=>0,"ppFloodMos"=>0,"ppPropTaxMos"=>0,"escPropTaxMos"=>0,"ppPropTax"=>0,"escPropTax"=>0,"propTax"=>0,"propTaxMonthly"=>0,"subTotCosts"=>475,"subTotPP"=>0,"acharges"=>0,"bcharges"=>200,"ccharges"=>25,"echarges"=>250,"fcharges"=>0,"gcharges"=>0,"hcharges"=>0
);

function maxHeLoan($val,$curbal,$cashWanted){
    $maxPreLoan = ($val * .90) - $curbal;
    if($maxPreLoan < $cashWanted){
        $calcLoan = $maxPreLoan;
        $maxAvailable = $calcLoan;
    } else {
        $calcLoan = $cashWanted;
        $maxAvailable = $maxPreLoan;
    };
    $res = array(
        "calcLoan"=>$calcLoan,
        "maxLoanAvail"=>$maxAvailable
    );
    return $res;
}
$heRateAdj = heRateAdj($score);
$heRate = $heRate + $heRateAdj;
$heArr = maxHeLoan($salesPrice,$curBal,$cashRepairs);
$term = $heArr['calcLoan'] <=5000 ? $term = 5 : $term =15;
$hePNI = pni($heArr['calcLoan'],$heRate,$term);
$hePNI = $hePNI <0 ? $hePNI = 0 : $hePNI = $hePNI;
$heLoan = $heArr['calcLoan'];
$heAPRloan = $heLoan;
$maxHeAvail = $heArr['maxLoanAvail'];
$FHAcosts = $heCosts;
$fhaUFMIP = 0;
$totCostsFHA = $FHAcosts['subTotPP'] + $FHAcosts['subTotCosts'];
$heCash = $heLoan - $totCostsFHA;
$heCashArr = heCashBackDiff($heCash,0);

if ($heLoan < 1000){
    $heLoan = 0;
    $maxHeAvail = 0;
    $totCostsFHA = 0;
    $heCash = 0;
    $fha_dcharges = 0;
    $fha_icharges = 0;
    $heAPRloan = 1000;
}
$heAPR = calcAPR($salesPrice, $heAPRloan, 180, $heRate, $totCostsFHA, 0);
$fha_dcharges = $FHAcosts['acharges'] + $FHAcosts['bcharges'] + $FHAcosts['ccharges']+ $fhaUFMIP;
$fha_icharges = $FHAcosts['echarges'] + $FHAcosts['fcharges'] + $FHAcosts['gcharges'] + $FHAcosts['hcharges'];
$fha_jcharges = $fha_dcharges + $fha_icharges;

// Renovation calcs ------------------------------ >>>>>>>>>>>>>>>
$repairReturn = $cashRepairsAdj * .70;
$adjVal = $salesPrice + $repairReturn;

//Conv reno calcs
$contingency = .10 * $cashRepairs;
$permits = 500;
$repairConsultant = 850;
$reinspections = 500;
$titleUpdates = 450;
$otherRepairCosts = $contingency + $permits + $repairConsultant + $reinspections + $titleUpdates;
$maxConvRenoLoanAvail = $adjVal * .97;
$preRenoInput = (($curBal + $cashRepairsAdj + $otherRepairCosts) / $adjVal > .94) ? $preRenoInput = $adjVal * .96 : $preRenoInput = $curBal + $cashRepairsAdj + $otherRepairCosts;
$preRenoLTV = ($preRenoInput / $adjVal) * 100;
$preRenoRateArr = convRateAdj($score,$preRenoLTV);
$preRenoPts1 = $preRenoRateArr['convPointAdj'] + $HomeStylePts + .875;
$preRenoInput = $preRenoInput + (($preRenoPts1/100)* $preRenoInput);

$preConvReno = prelimLoan($preRenoInput,$loc,"Conv",0,$reTax,$hoPrem,$esHoMo,$esTaxMo);
if ($preConvReno < $maxConvRenoLoanAvail){
$renoLoan = $preConvReno;
} else {$renoLoan = $maxConvRenoLoanAvail;};
$ConvRenoDP = $adjVal - $renoLoan;
$convRenoRateAdj = $preRenoRateArr['convRateAdj'];
if($score > 719){
    $convRenoRateAdj = 0;
    $preRenoPts1 = $preRenoPts1 - .875;
} else {
    $convRenoRateAdj = $convRenoRateAdj;
};
$convRenoRate = $HomeStyleRate + $convRenoRateAdj;
$ConvRenoArr = ConvLoan($adjVal,$ConvRenoDP,$convRenoRate,30,1,$score);

$ConvRenoLoan = $ConvRenoArr['totalLoan'];
$ConvRenoPNI = $ConvRenoArr['pi'];
$ConvRenoMI = $ConvRenoArr['mi'];
$ConvRenoTotPmt = $ConvRenoPNI + $reMonthly + $ConvRenoMI + $hoins;
$ConvRenoCosts = ccosts($adjVal,$ConvRenoLoan,$ConvRenoRate,$loc,'Conv-Refi',$reTax,$preRenoPts1,$hoPrem,$ppHoMo,$esHoMo,$ppTaxMo,$esTaxMo);
$totConvRenoCosts = $ConvRenoCosts['subTotPP'] + $ConvRenoCosts['subTotCosts'] + $curBal + $otherRepairCosts;
$convRenoCash = $ConvRenoLoan - $totConvRenoCosts;
$ConvRenoCBArr = cashBackDiff($convRenoCash,2000);
$convRenoAPR = calcAPR($adjVal, $ConvRenoLoan, 360, $convRenoRate, $ConvRenoCosts['aprFees'], $ConvRenoMI);

//203k reno calcs
$fhaRateArr = fhaRateAdj($score);
$fhaRateAdj = $fhaRateArr['fhaRateAdj'];
$FHARenoRate = $FHArate + $fhaRateAdj;
$fha203kPtsAdj = .875;
$fhaPointAdj =  $fhaRateArr['fhaPointAdj'];
$FHApts = $fhaPointAdj + $fha203kPtsAdj;
$FHAcontingency = .10 * $cashRepairs;
$FHApermits = 500;
$FHArepairConsultant = 850;
$FHAreinspections = 500;
$FHAtitleUpdates = 450;
$FHAotherRepairCosts = $FHAcontingency + $FHApermits + $FHArepairConsultant + $FHAreinspections + $FHAtitleUpdates;
$preFHAReno = prelimLoan($curBal + $cashRepairs + $FHAotherRepairCosts,$loc,"FHA",$FHApts,$reTax,$hoPrem,$esHoMo,$esTaxMo);
$preFHARenoCosts = $preFHAReno - ($curBal + $cashRepairs + $FHAotherRepairCosts);
$FHARenoArr = mortgageCalc($adjVal, $curBal, $preFHARenoCosts, $cashRepairs+$FHAotherRepairCosts);
$maxFHARenoLoanAvail = $FHARenoArr['maxRenoFHA'];
$FHARenoDP = ($adjVal) - $FHARenoArr['calcRenoFHA'];
$FHARenoArr = FHALoan($adjVal,$FHARenoDP,$FHARenoRate,30,1,$score);
$FHARenoLoan = $FHARenoArr['totalLoan'];
$FHARenoPNI = $FHARenoArr['pi'];
$FHARenoMI = $FHARenoArr['mi'];
$FHARenoUFMIP = $FHARenoArr['UFMIP'];
$FHARenoTotPmt = $FHARenoPNI + $reMonthly + $FHARenoMI + $hoins;
$FHARenoCosts = ccosts($adjVal,$FHARenoLoan,$FHARenoRate,$loc,'FHA-Refi',$reTax,$FHApts,$hoPrem,$ppHoMo,$esHoMo,$ppTaxMo,$esTaxMo);
$totFHARenoCosts = $FHARenoCosts['subTotPP'] + $FHARenoCosts['subTotCosts'] + $curBal + $FHAotherRepairCosts;
$FHARenoCash = $FHARenoLoan - ($totFHARenoCosts + $FHARenoUFMIP);
$FHARenoCBArr = cashBackDiff($FHARenoCash,2000);
$FHARenoAPR = calcAPR($adjVal, $FHARenoLoan, 360, $FHARenoRate, $FHARenoCosts['aprFees'], $FHARenoMI);


/*If(!isset($_GET['addProp'])){
logVisit($curTime, $ref, $httpAgent, $ip, $trackingPage, $salesPrice, $cashRepairs, $curBal, $score, $loc, $reTax, $hoPrem, $cashBack, $heCash, $convRenoCash, $FHARenoCash);
}; */

?>

<?php include('common/head.php'); ?>

<script>

$(document).ready(function(){
  $("form").change(function(){
    $("#sub-calc").addClass("btn-subCalc");
    $("#sub-calc").html("Calculate");
  });
});

function getFocus(){
var myDiv = document.getElementById("resFocus");
    myDiv.scrollTop = myDiv.scrollHeight;
$("#mydiv").scrollTop($("#mydiv")[0].scrollHeight)
};

</script>

<body id="bodyInd" onLoad="getFocus()">
	
<!-- Header with Background Image -->
<header class="business-header2">

<!-- Navigation -->
<?php include('common/nav2.php'); ?>
<!-- End Navigation -->
  
     
<div class="container">
  <div class="row">         
      <div class="col-lg-12">
        <h2 class="text-center text-white mt-1 mb-0"><span class="tagLine">Homeowner Options</span></h2>
        <h5 class="text-center text-light"><span class="headerLine2">Number Crunching Simplified</span></h5>
        <p class="text-center">
        <br>
        </p>
          </div>
        </div>
       </div>   

   </header>
 <div class="clearfix"></div>   
      <!-- Generated by https://smooth.ie/blogs/news/svg-wavey-transitions-between-sections -->
</div>

<!-- Page Content -->
<div class="container"> <!-- begin  container 1-->

<div class="row text-center">
</div>

<div class="row mt-3"><!--- begin master row container 1 ------------>

<div class="col-md-8"><!------- begin left div ---------->

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
<div class="row">
    <div class="col">
        <h5><span class="dprimary"><strong>Home Equity Cost Estimates </strong></span></h5>
        <p style="line-height:1.05em;" class="condensed">Complete the fields below to see some estimated options for getting cash out of your home's current value. Property Tax & Ho Insurance are optional fields if you want to include them in your total monthly payment.  </p>
</div> 
    </div>

    
    <div class="form-row">
      <div class="col">  
    <label for="salesPrice">Estimated Value</label>
    <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text px-1">$</span>
            </div>
      <input type="text" class="form-control px-1" id="salesPrice" name="salesPrice" value="<?php echo $salesPrice ?>">
    </div>
     </div>

     <div class="col">
    <label for="cash">Cash Wanted</label>
    <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text px-1">$</span>
            </div>
      <input type="text" class="form-control px-1" id="cash" name="cash" value="<?php echo $cashRepairs ?>" placeholder="Cash/Repairs">
    </div>
  </div>
 
    
    </div>
    
    <div class="form-row mt-3">

    <div class="col">
    <label for="curBal">Current Balance</label>
    <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text px-1">$</span>
            </div>
      <input type="text" class="form-control px-1" id="curBal" name="curBal" value="<?php echo $curBal ?>" placeholder="Current Loan Balance">
    </div>
  </div>
        
    <div class="col">
    <label for="creditScore">Credit Score</label>
    <div class="input-group">
   <select name="creditScore" class="form-control px-1" id="creditScore">
  <option value="760" <?php if (isset($score) && $score=="760") echo "selected";?>>Perfect (760 & Above)</option>
  <option value="740" <?php if (isset($score) && $score=="740") echo "selected";?>>Exceptional (740 to 759)</option>
  <option value="720" <?php if (isset($score) && $score=="720") echo "selected";?>>Excellent (720 to 739)</option>
  <option value="700" <?php if (isset($score) && $score=="700") echo "selected";?>>Great (700 to 719)</option>
  <option value="680" <?php if (isset($score) && $score=="680") echo "selected";?>>Very Good (680 to 699)</option>
  <option value="660" <?php if (isset($score) && $score=="660") echo "selected";?>>Good (660 to 679)</option>
  <option value="640" <?php if (isset($score) && $score=="640") echo "selected";?>>Okay (640 to 659)</option>
  <option value="620" <?php if (isset($score) && $score=="620") echo "selected";?>>Fair (620 to 639)</option>
  </select>
    </div>
  </div>
    </div>
    <div class="form-row mt-3">

    <div class="col-3">
    <label for="loc">Location:</label>
    <div class="input-group">
   <select name="loc" class="form-control px-0" id="loc">
  <option value="PA" <?php if (isset($loc) && $loc=="PA") echo "selected";?>> PA</option>
  <option value="NJ" <?php if (isset($loc) && $loc=="NJ") echo "selected";?>> New Jersey</option> 
  </select>
    </div>
  </div>  

    <div class="col-5">
    <label for="reTax">Property Tax</label>
    <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text px-1">$</span>
            </div>
      <input type="text" class="form-control px-1" id="reTax" name="reTax" value="<?php echo $reTax ?>" placeholder="Property Tax">
    </div>
  </div>

  <div class="col-4">
    <label for="loc">Ho Insurance:</label>
    <div class="input-group">
        <div class="input-group-prepend">
              <span class="input-group-text px-1">$</span>
            </div>
      <input type="text" class="form-control px-1" id="hoins" name="hoins" value="<?php echo $hoPrem ?>" placeholder="Ho Insurance">
    </div>
   
    </div>
  </div>  
    

    
<div class="row mt-3">
<div class="col">
<button type="submit" class="btn btn-block btn-secondary" name="sub-calc" id="sub-calc" ><?= $btnTxt ?></button>
</div>
</div>
<div id="resFocus"></div>
</form>
<?php

?>
</div> <!---  end left side div ------->

<!-- begin right side div --->
<div class="col-4 d-none d-md-block" id="agent1" style="vertical-align:middle;">
<p></p>
           
<p><a href="#" data-toggle="modal" data-target="#allModal"><img src="ims/puzzle-pieces2.png" class="img-fluid" style="max-height:300px;"></a></p>
            
</div> 
<!-- end right side div ------>
</div> <!-- end master row mt-3 --------->
</div>  <!-- end container 1 -->

<!-- begin large estimates container ----------------------->
<?php include 'cont-grid.php'; ?>
<!-- end large estimates container ------------->

<!-- Begin Smaller screens container ------------------------------------------------------------------------------------>            
<!-- Begin Smaller screens container ------------------------------------------------------------------------------------>
<div class="container d-block d-md-none mt-4">

      <!-- smaller screens Conv K-FIT ------------------------------------>
      <div class="row <?= $initSubmit?>">
       <div class="container">
      <div class="col-12">

    <!--- begin button summary Con97 ---->
      <div class="row" style="margin-bottom:5px;" >    
     <a data-toggle="collapse" href="#zero97" role="button" aria-expanded="false" aria-controls="zero97" class="btn btn-block text-left" id="btn-dan-blue">
       <p><span class="btnPmttext mb-1"><?php echo asDollars($zeroTotPmt) ?>&nbsp;/mos</span><span class="btnCashAmt mb-1">
        <span style="color:<?= $zeroCBArr['cbColor']; ?>; font-weight:bold;"><?= $zeroCBArr['cbText']; ?> :</span>
       <span style="color:<?= $zeroCBArr['cbColor']; ?>; font-weight:bold;"><?= asDollars($zeroCash) ?></span></span></p>
       <br>
    <p>
     <span class="btnProgtext mt-2">Conv Cashout Refi</span>
    <span class="btnDetails mt-0 px-0"><i class="far fa-caret-square-down fa-2x" style="float:right; margin: right 0;"></i></span>
    </p></a>
    </div>
     <!--- end button summary Con97 ---->
    
   <div class="row">
    <div class="container">
		<div class="collapse" id="zero97">   

    <div class="row px-2 mb-0" style="line-height: .85em;font-size: .95em;">               

                        <table class="table table-sm table-striped table-borderless snap_sumTable">
                          <thead style="font-size: 1.1em;">
                                <td class="snap_sumHead2"><span class="dan-medDarkGrey">Cost Summary</span></td>
                                <td class="text-right"><a href="#convDetails" data-target="#convDetails" data-toggle="modal" ><i class="fa-solid fa-arrow-up-right-from-square"></i> Details</a></td>
                          
                          </thead>
                            <tbody>
                            <tr class="snap_sumHeadTR">
                                <td class="fw-400">Current Balance :</td>
                                <td class="text-right"><?= asDollars($curBal) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-400">Closing Costs :</td>
                                <td class="text-right"><?= asDollars($ccosts['subTotCosts'] + $ccosts['subTotPP']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-400 text-right"><b>SubTotal :</b></td>
                                <td class="text-right "><b><?= asDollars($totCostsCash) ?></b></td>
                            </tr>

                            <tr>
                               <td class="fw-400 text-right"><span style="color:<?= $zeroCBArr['cbColor']; ?>; font-weight:bold;"><?= $zeroCBArr['cbText']; ?> :</span></td>
                                <td class="text-right"><span style="color:<?= $zeroCBArr['cbColor']; ?>; font-weight:bold;">
                                <?= $zeroCBArr['cbSym']; ?>
                                <?= asDollars($zeroCash) ?></span></td>
                
                                <?= $zeroCBArr['cbExpl']; ?>
                            </tr>
                        </tbody></table>
</div><!--- end snapshot cost summary row ------------------------------------->


<div class="row px-2 mb-0 mt-0" style="line-height: .85em;font-size: .95em;">
<p class="snap_sumHead2"> <span style="float:left;" class="dan-medDarkGrey">Loan Summary</span>
</p>                
                        <table class="table table-sm table-striped table-borderless snap_sumTable">
                            <tbody>
                            <tr class="snap_sumHeadTR">
                                <td class="fw-400">Loan Amount :</td>
                                <td class="text-right"><?= asDollars($zeroLoan) ?></td>
                            </tr>
                            <tr >
                                <td class="fw-400">Max Loan Available:</td>
                                <td class="text-right"><?= asDollars($maxLoanAvail) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-400">Rate (APR) :</td>
                                <td class="text-right"> <?= asPercent($zeroRate) ?>(<?= asPerc2($convAPR) ?>)</td>
                            </tr>
                            <tr>
                                <td class="fw-400">Loan Type / Term :</td>
                                <td class="text-right">Conv / 30yr Fixed</td>
                            </tr>
                        </tbody></table>
</div><!--- end snapshot loan summary row ------------------------------------->

<div class="row px-2 mb-0 mt-0" style="line-height: .85em;font-size: .95em;">
<p class="snap_sumHead2"> <span style="float:left;" class="dan-medDarkGrey">Payment Info</span>
</p>                
                        <table class="table table-sm table-striped table-borderless snap_sumTable">
                            <tbody>
                            <tr class="snap_sumHeadTR">
                                <td class="fw-400">Principal & Int : &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;   </td>
                                <td class="text-right"><?= asDollars($zeroPNI) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-400">Property Tax :</td>
                                <td class="text-right"> <?= asDollars($reMonthly)?></td>
                            </tr>
                            <tr>
                                <td class="fw-400">MI :</td>
                                <td class="text-right"><?= asDollars($zeroMI)?></td>
                            </tr>
                            <tr>
                                <td class="fw-400">Ho Insurance :</td>
                                <td class="text-right"><?= asDollars($hoins)?></td>
                            </tr>
                            <tr>
                                <td class="fw-400 text-right"><span style="color:#007bff; font-weight:bold;">Total Pmt :</span></td>
                                <td class="text-right"><span style="color:#007bff; font-weight:bold;"><?= asDollars($zeroTotPmt)?></span></td>
                            </tr>
                        </tbody></table>
</div><!--- end snapshot payment info  row ------------------------------------->  

<div class="row">
          <div class="col"></div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
          <p class="text-center">
           <button class="btn btn-block" data-toggle="collapse" href="#zero97" type="button" id="btn-dan-blue">Close</button></p>
           <p><hr></p>
          </div>
          <div class="col"></div>
        </div>
        
        </div> <!-- end collapse zero97 -------->
    </div> <!--- end conv kfit container ------->
        </div> <!---- end kfit small row -------->
 <!-- END  smaller screens Conv95 ------------------------------------>


<!-- Begin  smaller screens FHA ------------------------------------>         
  
 <div class="row mt-2" style="margin-bottom:10px;">    
    <a data-toggle="collapse" href="#detailsFHA" role="button" aria-expanded="false" aria-controls="detailsFHA" class="btn btn-outline-secondary btn-block text-left" id="btn-dan-darkBlue">
     <p>
      <span class="btnPmttext mb-1"><?php echo asDollars($hePNI) ?>&nbsp;/mos</span><span class="btnCashAmt mb-1">
        <span style="color:<?= $heCashArr['hecbColor']; ?>; font-weight:bold;"><?= $heCashArr['hecbText']; ?> :</span>
       <span style="color:<?= $heCashArr['hecbColor']; ?>; font-weight:bold;"><?= asDollars($heCash) ?></span></span>
      </p>
      <br>
    
     <p> <span class="btnProgtext mt-2">Home Equity (2nd Mtg)</span>
      <span class="btnDetails mt-0 px-0"><i class="far fa-caret-square-down fa-2x" style="float:right; margin: right 0;"></i></span>
      </p>
  </a>
    </div>
  
  <div class="row">
      <div class="container">
		<div class="collapse" id="detailsFHA">   
    
 <div class="row px-2 mb-0" style="line-height: .85em;font-size: .95em;">               

                        <table class="table table-sm table-striped table-borderless snap_sumTable">
                          <thead style="font-size: 1.1em;">
                                <td class="snap_sumHead2"><span class="dan-medDarkGrey">Cost Summary</span></td>
                                <td class="text-right"><a href="#fhaDetails" data-target="#fhaDetails" data-toggle="modal" ><i class="fa-solid fa-arrow-up-right-from-square"></i> Details</a></td>
                          
                          </thead>
                            <tbody>
                            <tr class="snap_sumHeadTR">
                                <td class="fw-400">1st Mortgage - <?= asDollars($curBal) ?> :</td>
                                <td class="text-right">keeping open</td>
                            </tr>
                            <tr>
                                <td class="fw-400">Closing Costs :</td>
                                <td class="text-right"><?= asDollars($FHAcosts['subTotCosts']+ $FHAcosts['subTotPP'] + $fhaUFMIP) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-400 text-right"><b>SubTotal :</b></td>
                                <td class="text-right "><b><?= asDollars($totCostsFHA) ?></b></td>
                            </tr>
                            <tr>
                                <td class="fw-400 text-right"><span style="color:<?= $heCashArr['hecbColor']; ?>; font-weight:bold;"><?= $heCashArr['hecbText']; ?> :</span></td>
                                <td class="text-right"><span style="color:<?= $heCashArr['hecbColor']; ?>; font-weight:bold;">
                                <?= $heCashArr['cbSym2']; ?>
                                <?= asDollars($heCash) ?></span></td>
                                <?= $heCashArr['hecbExpl']; ?>
                            </tr>
                        </tbody></table>
</div><!--- end snapshot cost summary row ------------------------------------->


<div class="row px-2 mb-0 mt-0" style="line-height: .85em;font-size: .95em;">
<p class="snap_sumHead2"> <span style="float:left;" class="dan-medDarkGrey">Loan Summary</span>
</p>                
                        <table class="table table-sm table-striped table-borderless snap_sumTable">
                            <tbody>
                            <tr class="snap_sumHeadTR">
                                <td class="fw-400">Loan Amount :</td>
                                <td class="text-right"><?= asDollars($heLoan) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-400">Max Loan Available:</td>
                                <td class="text-right"><?= asDollars($maxHeAvail) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-400">Rate (APR) :</td>
                                <td class="text-right"> <?= asPercent($heRate) ?>(<?= asPerc2($heAPR) ?>)</td>
                            </tr>
                            <tr>
                                <td class="fw-400">Loan Type / Term :</td>
                                <td class="text-right">Home Equity / 15yr Fixed</td>
                            </tr>
                        </tbody></table>
</div><!--- end snapshot loan summary row ------------------------------------->

<div class="row px-2 mb-0 mt-0" style="line-height: .85em;font-size: .95em;">
<p class="snap_sumHead2"> <span style="float:left;" class="dan-medDarkGrey">Payment Info</span>
</p>                
                        <table class="table table-sm table-striped table-borderless snap_sumTable">
                            <tbody>
                            <tr class="snap_sumHeadTR">
                                <td class="fw-400">Home Equity P&I : &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;   </td>
                                <td class="text-right"><?= asDollars($hePNI) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-400">Property Tax :</td>
                                <td class="text-right">not included</td>
                            </tr>
                            <tr>
                                <td class="fw-400">MI :</td>
                                <td class="text-right">not included</td>
                            </tr>
                            <tr>
                                <td class="fw-400">Ho Insurance :</td>
                                <td class="text-right">not included</td>
                            </tr>
                            <tr>
                                <td class="fw-400 text-right"><span style="color:#007bff; font-weight:bold;">Total Equity Pmt :</span></td>
                                <td class="text-right"><span style="color:#007bff; font-weight:bold;"><?= asDollars($hePNI)?></span></td>
                            </tr>
                        </tbody></table>
</div><!--- end snapshot payment info  row ------------------------------------->  

<div class="row">
          <div class="col"></div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
          <p class="text-center">
           <button class="btn btn-block" data-toggle="collapse" href="#detailsFHA" type="button" id="btn-dan-darkBlue">Close</button></p>
           <p><hr></p>
          </div>
          <div class="col"></div>
        </div>

        </div> <!--- end details collapse row ------------------------------------->   
        </div>
        </div>
  
  </div>
       </div>
</div> <!-- end fha small row -->
</div> <!-- end Container 2 -->

<!-- begin Container 4 -->
    <?php include 'cont-reno-sm.php'; ?>
<!-- end Container 4 -->

<div class="container-fluid mt-4" style="margin-bottom: -5px;">
<div class="row px-4" style="background: linear-gradient(45deg, rgb(58, 135, 226) 50%, rgb(111, 196, 244) 100%); ">
<div class="col-12 text-center">
    <p style="line-height:1.25em" class="text-light mt-3 mb-3">The Home Equity option does not pay off your current loan so the payment shown is in addition to your current mortgage payment. All the other options include paying off your current mortgage and reflects your total monthly cost. </p>
    <p> Renovation loans use the increased value of the home as if the repairs are already complete. This usually results in a higher available loan amount. The cashout can only be used for repairs. The Conv Cashout and Home Equity loans only use the current value of the home, the value of any repairs is not considered but the cashout can be used for any purpose.</p>
</div>
</div>
</div>

<!-- begin Container 4 -->
    <?php include 'cont-detail-conv-reno.php'; ?>
<!-- end Container 4 -->

<!-- begin Container 4 -->
    <?php include 'cont-detail-203k-reno.php'; ?>
<!-- end Container 4 -->

<!-- begin Container 4 -->
    <?php include 'cont-getAns.php'; ?>
<!-- end Container 4 -->

<!-- Begin Container Conv details modal -->
<?php include 'cont-detail-conv-cashout.php'; ?>
<!-- end Container conv details modal -->

<!-- Begin Container fha details modal -->
<?php include 'cont-detail-he.php'; ?>
<!-- end Container fha details modal -->

<!-- Footer -->
<?php include 'common/footer.php'; ?>

<!-- begin Container 4 -->
   
<!-- end Container 4 -->

</body>
</html>