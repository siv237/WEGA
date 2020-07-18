<?php

// Наименование системы
$namesys="Балкон правый";

// Описание системы
$comment="Система условно-постоянной циркуляции - глубокая протока с выделенным баком и насыщением гофрошлангом";


////////////////Калибровка термистора 
////  трехточечная

$px1=672; 	$py1=10;
$px2=533.65; 	$py2=22;
$px3=400; 	$py3=33.3;


$pa = -(-$px1*$py3 + $px1*$py2 - $px3*$py2 + $py3*$px2 + $py1*$px3 - $py1*$px2) /  (-pow($px1,2)*$px3 + pow($px1,2)*$px2 - $px1*pow($px2,2) + $px1*pow($px3,2) - pow($px3,2)*$px2 + $px3*pow($px2,2) );
$pb = ( $py3*pow($px2,2) - pow($px2,2)*$py1 + pow($px3,2)*$py1 + $py2*pow($px1,2) - $py3*pow($px1,2) - $py2 * pow($px3,2) ) /  ( (-$px3+$px2) * ($px2*$px3 - $px2*$px1 + pow($px1,2) - $px3*$px1 ) );
$pc = ( $py3*pow($px1,2)*$px2 - $py2*pow($px1,2)*$px3 - pow($px2,2)*$px1*$py3 + pow($px3,2)*$px1*$py2 + pow($px2,2)*$py1*$px3 - pow($px3,2)*$py1*$px2 ) /  ( (-$px3+$px2) * ($px2*$px3 - $px2*$px1 + pow($px1,2) - $px3*$px1 ) );


$ta = $pa;
$tb = $pb;
$tc = $pc;


/////////////////Калибровка EC
// По двум точкам от нуля

$R1=510; // Резистор делителя R1
$Rx1=-10; // Внутреннее сопротивление
$Rx2=-8; // Внутреннее сопротивление
$Dr=1023; // Предел АЦП
$k=0.02; // Коэффициент термокомпенсации

// Первая точка
$ex1=610; // Omh2
$ec1=1.114; // ec1

/// Вторая точка
$ex2=159; // Omh1
$ec2=4.988; // ec2
// формула
$eb=(-log($ec1/$ec2))/(log($ex2/$ex1));
$ea=pow($ex1,(-$eb))*$ec1;

///////////// Калибровка уровня 
/// по 2м точкам от 0
$distz=40; // cm  Уровень 0
$distmax=3.5; // cm Уровень максимального налива
$LevelFull=32;  // Предел бака
$LevelAdd=12; // Запас раствора вне измерительного бака. 

//Замер 1 
$lev1=  17; // литров
$dst1= 18; // cm от 0
//Замер 2 
$lev2=  27; // литров
$dst2= 31.52; // cm от 0


$lb=(-log($lev1/$lev2))/(log($dst2/$dst1));
$la=pow($dst1,(-$lb))*$lev1;

/////// настройки помощника
$ECPlan=2.2; // План по ЕС
$La=4; // Защита от перелива в литрах;
$Slk=3.052/2.2; // отношение веса солей к ЕС
$konc=100; // Концентрация концентратов




//38000 lux 15:10 19.05.2020
//3800 lux  19:05 20.05.2020

//Калибровка Luxmetr

$x1=431;   // raw
$y1=10000; //Lux

$x2=642;   // raw
$y2=65000; // Lux


$xb=(-log($y1/$y2))/(log($x2/$x1));
$xa=pow($x1,(-$xb))*$y1;


//echo $xa;
//echo '<br>';
//echo $xb;
//echo '<br>';


$apht=$xa;
$bpht=$xb;

include "../../db.php";
$my_db="balkon_r";
$tb="sens";


$dAirTemp="AM2320_1_t";
$dAirHum="AM2320_1_h";
$RootTemp="18b20_1_t";
$EcTempRaw="thermistor_1_raw";
$LightRaw="1023-phtresist_1_raw";
$dist="us25_1_dst";
$A1="ec_1_an";
$A2="ec_1_ap";

$f_lev="levmin(".$la."*pow(@dist,".$lb."))";

$f_soil="(@lev+".$LevelAdd.")*@ECt*".$Slk;


//@SoilAll:=if(relay1=1,(@lev+".$LevelAdd.")*@ECt*".$Slk.",null)

$csv="s.csv";
$gnups="s.gnuplot";
$img="s.png";
$gimg=$img;

?>
