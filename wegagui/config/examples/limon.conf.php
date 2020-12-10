<?php
// Наименование системы
$namesys="Лимон большой";

// Описание системы
$comment="Подпор активный - с периодической проливкой";

// Объем бака в литрах
$LevelFull=12;

// Запас раствора вне бака в литрах в трубказ коробах в субстрате
$LevelAdd=5.5; 

// Аварийная защита от перелива в литрах (это сколько литров сольется назад в бак при внезапной остановке циркуляции)
$La=1;

// Плановое значение ЕС в мСм/см
$ECPlan=1.5;

// Соотношение веса солей к ЕС (вес солей в литре / ЕС)
$Slk=2.47/1.5;

// Концентрация концентратов 100:1, 200:1 и т.п.
$konc=100;

// Уведомления
$LevelAddEvent=4; // Сообщать при необходимости долить больше чем указанное число литров
$chat_id="-473002458";




// Калибровка термистора ЕС трехточечная

// Для калибровки необходимо расположить цифровой датчик температуры рядом с аналоговым и измерить три точки
// минимальную, среднюю, и максимально высокую для раствора
// px - показания АЦП, py - реальное значение температуры

// Точка 1 - минимальная температура
$px1=1710; 	$py1=19.8;

// Точка 2 - средняя (потимальная)
$px2=1942; 	$py2=25;

// Точка 3 - максимум
$px3=2072; 	$py3=28;


// Функция нелинейной экстраполяции значние по 3м точкам
$pa = -(-$px1*$py3 + $px1*$py2 - $px3*$py2 + $py3*$px2 + $py1*$px3 - $py1*$px2) /  (-pow($px1,2)*$px3 + pow($px1,2)*$px2 - $px1*pow($px2,2) + $px1*pow($px3,2) - pow($px3,2)*$px2 + $px3*pow($px2,2) );
$pb = ( $py3*pow($px2,2) - pow($px2,2)*$py1 + pow($px3,2)*$py1 + $py2*pow($px1,2) - $py3*pow($px1,2) - $py2 * pow($px3,2) ) /  ( (-$px3+$px2) * ($px2*$px3 - $px2*$px1 + pow($px1,2) - $px3*$px1 ) );
$pc = ( $py3*pow($px1,2)*$px2 - $py2*pow($px1,2)*$px3 - pow($px2,2)*$px1*$py3 + pow($px3,2)*$px1*$py2 + pow($px2,2)*$py1*$px3 - pow($px3,2)*$py1*$px2 ) /  ( (-$px3+$px2) * ($px2*$px3 - $px2*$px1 + pow($px1,2) - $px3*$px1 ) );
$ta = $pa;
$tb = $pb;
$tc = $pc;


// Калибровка сенсора EC

$R1=509.3; // Резистор делителя R1 в омах

$Rx1=-120; // Внутреннее сопротивление подбираются таким образом, что-бы во всех калибровочных растворах значения Rp и  Rn сошлись.
$Rx2=0; // Внутреннее сопротивление
$Dr=4095; // Предел АЦП

$k=0.02; // Коэффициент термокомпенсации, зависит от состава раствора и корректируется по графику так, чтобы раствор при разнызной температуре не менял свой ЕС


// Для калибровки наиболее удобно использовать аптечный раствор кальция хлорида шестиводного. Он жидкий в ампулах 100 г/л
// Ампулы бывают на 5 и 10 мл.
// Можно приготовить три калибровочных раствора 1, 2 и 5 ампул растворить в полулитре (если ампула 10мл то в литре) дистиллята с ЕС 0.01
// 
// Получим там где:
//
//    одна ампула ЕС = 1.114 мсм/см
//    две апулы ЕС = 2.132 мсм/см
//    три ампуы ЕС = 3.107 мсм/см
//    четыре ЕС = 4.057 мсм/см
//    пять ЕС = 4.988 мсм/см
//    шесть ЕС = 5.909 мсм/см


// Значения сопротивления и соотвествующее ему ЕС
// Первая точка
$ex1=538; // Omh1
$ec1=1.08; // ec1
// Вторая точка
$ex2=155; // Omh2
$ec2=4.89; // ec2

// Функция нелинейной апроксимации по трем точкам одна из которых нулевая
$eb=(-log($ec1/$ec2))/(log($ex2/$ex1));
$ea=pow($ex1,(-$eb))*$ec1;


// Калибровка бака

// Функция калибровки объема в литрах по показаниям дальнометра в сантиметрах
// Метод кривой по трем точкам, одна из которых является нулем.
// Так как дальномер отмеряет значения от датчика до поверхности воды, то первое что надо сделать
// это задать точку 0, где раствора в баке нет и сигнал отражается ото дна бака.

// Такая калибровка подходит только для емкостей с равномерной формой без сложой геометрии.

$distz=17.05; // cm  Уровень 0 - бак пустой
$distmax=3.5; // cm критическое расстояние до датчика (бак полный)
// Делаем два замера на 1/3 и на 2/3
//Замер 1 
$dst1= $distz-11.5; // cm от дна
$lev1=  4.5; // литров
//Замер 2 
$dst2= 10.5; // cm от дна
$lev2=  12.35; // литров

$lb=(-log($lev1/$lev2))/(log($dst2/$dst1));
$la=pow($dst1,(-$lb))*$lev1;

//Калибровка Люксметра

$x1=431;   // raw АЦП
$y1=10000; //Lux

$x2=642;   // raw АЦП
$y2=65000; // Lux

$bpht=(-log($y1/$y2))/(log($x2/$x1));
$apht=pow($x1,(-$bpht))*$y1;

// Калибровка pH метра
$phr1=14576;
$ph1=4.01;

$phr2=12200;
$ph2=6.86;

$aph=(-$phr2*$ph1+$ph2*$phr1)/(-$phr2+$phr1);
$bph=(-$ph2+$ph1)/(-$phr2+$phr1);



// Параметры базы

include "../../db.php";
$my_db="esp32wega"; // Имя базы
$tb="sens"; // Имя таблицы с данными

// Соответсвие полей базы
$dAirTemp="am2320temp-8"; // температура воздуха в градусах
$dAirHum="am2320hum";  // влажность воздуха в процентах
$RootTemp="dtem1";  // температура корней в градусах
$EcTempRaw="tempRAW"; // температура в датчике ЕС в RAW АЦП
$LightRaw="0"; // датчик освещенности в RAW
$dist="Dst"; // дистанция до поверхности раствора в см
$A1="Ap"; // значение EC в RAW при отрицательной фазе цикла
$A2="An"; // значение EC в RAW при положительной фазе цикла
$phraw="pHraw"; // значение pH в RAW



$DistC0=$dist;
$DistC1="undist(".$dist.",0.1,0.4,5,19)";
$DistC2="if(".$distz."-".$DistC1.">0,".$distz."-".$DistC1.",null)";
$DistC3=$la."*pow(".$DistC2.",".$lb.")";
$DistC4="kalman(".$DistC3.",0.1,0,0.4,2)";
$DistC5=$DistC4;

//$DistC1="undist(".$dist.",0.07,0.5,5,19)";
//$DistC2="intpl(".$DistC1.")";
//$DistC3=$DistC2;
//$DistC3="if (".$DistC0." < 5, ".$DistC2." ,levmin(".$DistC2."))";
//$DistC3="if (".$DistC0." > 5, kalman(".$DistC2.",0.2,-0.1,2,11), kalman(".$DistC2.",0.0001,0,0,0))";
//$DistC4="kalman(".$DistC2.",0.005,0,0.3,0.3)";




// Функция апроксимации объема раствора
//$f_lev="levmin(".$la."*pow(@dist,".$lb."))";
$f_lev=$DistC5;

//$f_lev="levmin(intpl(".$dist."-3.0))";

// Формула расчета остатка солей
$f_soil="(@lev+".$LevelAdd.")*@ECt*".$Slk;


// Формула расчета pH
//$f_ph=".$aph."+".$bph."*".$phraw.";
$f_ph="(".$aph."+".$bph."*".$phraw.")/(1-0.0025*(@aTemp2-25))";
//$f_ph=$aph."+".$bph."*".$phraw;

// Функция калибровки ЕС
$f_ec=$ea."*pow(@R2,".$eb.")";

// Функция калибровки аналогово сенсора для компенсации ЕС
$f_atemp=$pa."*pow(@EcTempRaw,2) + ".$pb."*@EcTempRaw + ".$pc;

$csv="s.csv";
$gnups="s.gnuplot";
$img="s.png";
$gimg=$img;

?>
