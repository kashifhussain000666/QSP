<?php session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('dblink-propDB.php');
$origin = "index.php";

function logVisit($date, $ref=NULL, $httpAgent=NULL, $ip, $trackingPage,  $salesPrice=NULL, $cashRepairs=NULL, $curBal=NULL, $score=NULL, $propLocation=NULL,  $propTax=NULL, $hoIns=NULL, $convCashBack=NULL, $heCashBack=NULL, $convRenoCash=NULL, $fha203kCash=NULL){
    global $link_propDB;
    global $origin;
    $qry = "INSERT INTO propLog (
        date_time,
        referrer,
        httpAgent,
        ip,
        trackingPage,
        salesPrice,
        cashRepairs,
        curBal,
        score,
        propLocation,
        propTax,
        hoIns,
        convCashBack,
        heCashBack,
        convRenoCash,
        203kCash
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = mysqli_stmt_init($link_propDB);
        if (!mysqli_stmt_prepare($stmt, $qry)) {
            $error = mysqli_error($link_propDB);
            header("Location: ../".$origin."?error=sqlerror-addFailed".$error);
            exit();
        }
        else {    
            mysqli_stmt_bind_param($stmt,"ssssssssisssssss", $date, $ref, $httpAgent, $ip, $trackingPage, $salesPrice, $cashRepairs, $curBal,   $score, $propLocation,$propTax, $hoIns, $convCashBack, $heCashBack, $convRenoCash, $fha203kCash );
             mysqli_stmt_execute($stmt);
            // header("Location: ../".$origin."?addProp=success");
            echo("<script>window.open('../".$origin."?addProp=success','_self')</script>");
            exit(); 
            }
        }