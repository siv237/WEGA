<?php
// Наименование системы
$namesys="Балкон левый";

// Описание системы
$comment="Система постоянной циркуляции - глубокая протока с выделенным баком и насыщением гофрошлангом";

// Объем бака в литрах
$LevelFull=30;

// Запас раствора вне бака в литрах в трубказ коробах в субстрате
$LevelAdd=16; 

// Аварийная защита от перелива в литрах (это сколько литров сольется назад в бак при внезапной остановке циркуляции)
$La=4;

// Плановое значение ЕС в мСм/см
$ECPlan=2.5;

// Соотношение веса солей к ЕС (вес солей в литре / ЕС)
$Slk=3.37/2.5;

// Концентрация концентратов 100:1, 200:1 и т.п.
$konc=100;

// Уведомления
$LevelAddEvent=9; // Сообщать при необходимости долить больше чем указанное число литров
$chat_id="-473002458"; // Адрес чата с ботом



// Калибровка термистора ЕС трехточечная

// Для калибровки необходимо расположить цифровой датчик температуры рядом с аналоговым и измерить три точки
// минимальную, среднюю, и максимально высокую для раствора
// px - показания АЦП, py - реальное значение температуры

// Точка 1 - минимальная температура
$py_corr=1;
$px1=845; 	$py1=-7.5;

// Точка 2 - средняя (потимальная)
$px2=561.1; 	$py2=19.374+$py_corr;

// Точка 3 - максимум
$px3=520.12; 	$py3=23+$py_corr;


// Функция нелинейной экстраполяции значние по 3м точкам
$pa = -(-$px1*$py3 + $px1*$py2 - $px3*$py2 + $py3*$px2 + $py1*$px3 - $py1*$px2) /  (-pow($px1,2)*$px3 + pow($px1,2)*$px2 - $px1*pow($px2,2) + $px1*pow($px3,2) - pow($px3,2)*$px2 + $px3*pow($px2,2) );
$pb = ( $py3*pow($px2,2) - pow($px2,2)*$py1 + pow($px3,2)*$py1 + $py2*pow($px1,2) - $py3*pow($px1,2) - $py2 * pow($px3,2) ) /  ( (-$px3+$px2) * ($px2*$px3 - $px2*$px1 + pow($px1,2) - $px3*$px1 ) );
$pc = ( $py3*pow($px1,2)*$px2 - $py2*pow($px1,2)*$px3 - pow($px2,2)*$px1*$py3 + pow($px3,2)*$px1*$py2 + pow($px2,2)*$py1*$px3 - pow($px3,2)*$py1*$px2 ) /  ( (-$px3+$px2) * ($px2*$px3 - $px2*$px1 + pow($px1,2) - $px3*$px1 ) );
$ta = $pa;
$tb = $pb;
$tc = $pc;


// Калибровка сенсора EC

$R1=512.4; // Резистор делителя R1 в омах

$Rx1=-10; // Внутреннее сопротивление подбираются таким образом, что-бы во всех калибровочных растворах значения Rp и  Rn сошлись.
$Rx2=-8; // Внутреннее сопротивление
$Dr=1023; // Предел АЦП

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
$ex1=703; // Omh1
$ec1=1.114; // ec1
// Вторая точка
$ex2=170; // Omh2
$ec2=4.988; // ec2

// Функция нелинейной апроксимации по трем точкам одна из которых нулевая
$eb=(-log($ec1/$ec2))/(log($ex2/$ex1));
$ea=pow($ex1,(-$eb))*$ec1;


// Калибровка бака

// Функция калибровки объема в литрах по показаниям дальнометра в сантиметрах
// Метод кривой по трем точкам, одна из которых является нулем.
// Так как дальномер отмеряет значения от датчика до поверхности воды, то первое что надо сделать
// это задать точку 0, где раствора в баке нет и сигнал отражается ото дна бака.

// Такая калибровка подходит только для емкостей с равномерной формой без сложой геометрии.

$distz=40; // cm  Уровень 0 - бак пустой
$distmax=3.5; // cm критическое расстояние до датчика (бак полный)
// Делаем два замера на 1/3 и на 2/3
//Замер 1 
$dst1= 18; // cm от дна
$lev1=  17; // литров
//Замер 2 
$dst2= 31.52; // cm от дна
$lev2=  27; // литров

$lb=(-log($lev1/$lev2))/(log($dst2/$dst1));
$la=pow($dst1,(-$lb))*$lev1;

//Калибровка Люксметра

$x1=431;   // raw АЦП
$y1=10000; //Lux

$x2=800;   // raw АЦП
$y2=65000; // Lux

$bpht=(-log($y1/$y2))/(log($x2/$x1));
$apht=pow($x1,(-$bpht))*$y1;


// Параметры базы

include "../../db.php";
$my_db="balkon_l"; // Имя базы
$tb="sens"; // Имя таблицы с данными

// Соответсвие полей базы
$dAirTemp="AM2302_1_t"; // температура воздуха в градусах
$dAirHum="AM2302_1_h";  // влажность воздуха в процентах
$RootTemp="18b20_1_t";  // температура корней в градусах
$EcTempRaw="thermistor_1_raw"; // температура в датчике ЕС в RAW АЦП
$LightRaw="1023-phtresist_1_raw"; // датчик освещенности в RAW
$dist="us25_1_dst"; // дистанция до поверхности раствора в см
$A1="ec_1_an"; // значение EC в RAW при отрицательной фазе цикла
$A2="ec_1_ap"; // значение EC в RAW при положительной фазе цикла
$phraw=""; // значение pH в RAW

//
//@EC:=if(@R2>0,  ".$ea."*pow(@R2,".$eb.") , 0),

// Функция калибровки ЕС
$f_ec=$ea."*pow(@R2,".$eb.")";


//@aTemp2:=".$pa."*pow(@EcTempRaw,2) + ".$pb."*@EcTempRaw + ".$pc.",
// Функция калибровки аналогово сенсора для компенсации ЕС
$f_atemp=$pa."*pow(@EcTempRaw,2) + ".$pb."*@EcTempRaw + ".$pc;



// Блок дорасчета уровня по ультрозвуковому датчику
// Первичные данне
$DistC0=$dist;
// Расчет расстояния от датчика до поверхности раствора в см
//$DistC1=$DistC0;
//$DistC2=$DistC1;
//$DistC3="kalman(".$DistC2.",1,0.2,3,1)";
//$DistC4="intpl(".$DistC3."-3)";



$DistC0=$dist;
$DistC1="undist(".$dist.",0.07,0.5,5,190)";
$DistC2="intpl(".$DistC1.")";
$DistC3=$DistC2;
//$DistC3="if (".$DistC0." < 5, ".$DistC2." ,levmin(".$DistC2."))";
//$DistC3="if (".$DistC0." > 5, kalman(".$DistC2.",0.2,-0.1,2,11), kalman(".$DistC2.",0.0001,0,0,0))";
$DistC4="kalman(".$DistC2.",0.005,0,0.3,0.3)";




//$DistC2="levmin(".$DistC1.")";
//$DistC3="kalman(".$DistC2.",0.2,-0.1,1,10)";
//$DistC4=$DistC3;

//$DistC1="intpl(".$dist.")";
// Расчет высоты водного столба от дна бака до поверхности раствора в см
//$DistC2="if (".$DistC1."<".$distz.",".$distz."-".$DistC1.",null)";
// Филтрация выбросов (сглаживание)
//$DistC3="kalman(".$DistC2.",6,-0.8,1,3)";
// Расчет уровня по раствора в литрах в зависимости от высоты водного столба и формы бака в л.
//$DistC4=$la."*pow(".$DistC3.",".$lb.")";
//$DistC4=$DistC3;


// Функция апроксимации объема раствора
//$f_lev=$la."*pow(@dist,".$lb.")";

//$f_lev="levmin(intpl(".$dist."-3.0))";
$f_lev=$DistC4;
//$f_lev=$DistC4;



// Формула расчета остатка солей
$f_soil="(@lev+".$LevelAdd.")*@ECt*".$Slk;

// Формула расчета pH
//$f_ph=".$aph."+".$bph."*".$phraw.";
$f_ph="null";


$csv="s.csv";
$gnups="s.gnuplot";
$img="s.png";
$gimg=$img;

?>
