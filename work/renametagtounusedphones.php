<?php

require('../php/funciones.php');

$conn = ConectaSQL('ocsweb');


//select tag,HARDWARE_ID from accountinfo where tag like '%CEL%' and tag not like '%BAJA_CEL%';

usar quyery

//$fp = @fopen("idtels.txt", 'r'); 

// Add each line to an array
if ($fp) {
   $arr = explode("\n", fread($fp, filesize("idtels.txt")));
}

foreach ($arr as &$value) {
	$of='';
	$of=GetOfficeFromOCSHWID($value,$conn);
	if(preg_match("/BAJA_CEL_/",$of['OFI'],$matches)) {
		echo "<br>HWID = ".$value." -> ".$of['OFI']."<br>";
        echo $sqlc = "update accountinfo set TAG='BAJA_".$of['TAG']."' where HARDWARE_ID='".$value."'";
        $resultc = $conn->query($sqlc);

	}
    

}
//print_r($array);

/*

HWID = 23900 -> BAJA_CEL_SUR
update accountinfo set TAG='BAJA_CELCCN103' where HARDWARE_ID='23900'
HWID = 23132 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELCCN106' where HARDWARE_ID='23132'
HWID = 27312 -> BAJA_CEL_NOR
update accountinfo set TAG='BAJA_CELCHI100' where HARDWARE_ID='27312'
HWID = 24018 -> BAJA_CEL_NOR
update accountinfo set TAG='BAJA_CELCHI110' where HARDWARE_ID='24018'
HWID = 23878 -> BAJA_CEL_NOR
update accountinfo set TAG='BAJA_CELCHI111' where HARDWARE_ID='23878'
HWID = 8745 -> BAJA_CEL_NOR
update accountinfo set TAG='BAJA_CELCHI113' where HARDWARE_ID='8745'
HWID = 11512 -> BAJA_CEL_NOR
update accountinfo set TAG='BAJA_CELCHI113' where HARDWARE_ID='11512'
HWID = 24477 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELCUL104' where HARDWARE_ID='24477'
HWID = 16207 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELCUL106' where HARDWARE_ID='16207'
HWID = 24055 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELCUL108' where HARDWARE_ID='24055'
HWID = 21388 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELCUL113' where HARDWARE_ID='21388'
HWID = 17471 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELCUL118' where HARDWARE_ID='17471'
HWID = 18089 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELCUL118' where HARDWARE_ID='18089'
HWID = 22927 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELGDL141' where HARDWARE_ID='22927'
HWID = 23597 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELHLO111' where HARDWARE_ID='23597'
HWID = 23719 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELHLO117' where HARDWARE_ID='23719'
HWID = 7619 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELHLO120' where HARDWARE_ID='7619'
HWID = 8217 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELHLO122' where HARDWARE_ID='8217'
HWID = 19640 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELHLO122' where HARDWARE_ID='19640'
HWID = 25136 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELHLO123' where HARDWARE_ID='25136'
HWID = 26664 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELIZT100' where HARDWARE_ID='26664'
HWID = 22123 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELIZT108' where HARDWARE_ID='22123'
HWID = 22654 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELIZT109' where HARDWARE_ID='22654'
HWID = 24393 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELIZT115' where HARDWARE_ID='24393'
HWID = 26442 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELIZT116' where HARDWARE_ID='26442'
HWID = 21236 -> BAJA_CEL_NOR
update accountinfo set TAG='BAJA_CELJUA102' where HARDWARE_ID='21236'
HWID = 21702 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELMAZ100' where HARDWARE_ID='21702'
HWID = 25301 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELMAZ101' where HARDWARE_ID='25301'
HWID = 15917 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELMAZ102' where HARDWARE_ID='15917'
HWID = 15323 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELMAZ104' where HARDWARE_ID='15323'
HWID = 23164 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELMAZ106' where HARDWARE_ID='23164'
HWID = 27531 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELMAZ110' where HARDWARE_ID='27531'
HWID = 10036 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELMAZ115' where HARDWARE_ID='10036'
HWID = 22004 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELMCH104' where HARDWARE_ID='22004'
HWID = 16522 -> BAJA_CEL_SUR
update accountinfo set TAG='BAJA_CELMER111' where HARDWARE_ID='16522'
HWID = 25133 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMER112' where HARDWARE_ID='25133'
HWID = 25132 -> BAJA_CEL_SUR
update accountinfo set TAG='BAJA_CELMER113' where HARDWARE_ID='25132'
HWID = 22807 -> BAJA_CEL_SUR
update accountinfo set TAG='BAJA_CELMER120' where HARDWARE_ID='22807'
HWID = 26535 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX101' where HARDWARE_ID='26535'
HWID = 26886 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX105' where HARDWARE_ID='26886'
HWID = 29227 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX111' where HARDWARE_ID='29227'
HWID = 24857 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX118' where HARDWARE_ID='24857'
HWID = 22314 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX123' where HARDWARE_ID='22314'
HWID = 23456 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX124' where HARDWARE_ID='23456'
HWID = 24145 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX125' where HARDWARE_ID='24145'
HWID = 26947 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX127' where HARDWARE_ID='26947'
HWID = 24785 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX133' where HARDWARE_ID='24785'
HWID = 22303 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX148' where HARDWARE_ID='22303'
HWID = 26778 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX148' where HARDWARE_ID='26778'
HWID = 10965 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX152' where HARDWARE_ID='10965'
HWID = 23345 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX152' where HARDWARE_ID='23345'
HWID = 24818 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX152' where HARDWARE_ID='24818'
HWID = 30629 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX152' where HARDWARE_ID='30629'
HWID = 28988 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX153' where HARDWARE_ID='28988'
HWID = 25024 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX155' where HARDWARE_ID='25024'
HWID = 29620 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX155' where HARDWARE_ID='29620'
HWID = 22294 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX156' where HARDWARE_ID='22294'
HWID = 21894 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX157' where HARDWARE_ID='21894'
HWID = 26421 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX158' where HARDWARE_ID='26421'
HWID = 22026 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX160' where HARDWARE_ID='22026'
HWID = 10961 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX162' where HARDWARE_ID='10961'
HWID = 22000 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX165' where HARDWARE_ID='22000'
HWID = 22578 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX171' where HARDWARE_ID='22578'
HWID = 23128 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX171' where HARDWARE_ID='23128'
HWID = 30230 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX171' where HARDWARE_ID='30230'
HWID = 25387 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX172' where HARDWARE_ID='25387'
HWID = 30842 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX172' where HARDWARE_ID='30842'
HWID = 27163 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX176' where HARDWARE_ID='27163'
HWID = 11003 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX177' where HARDWARE_ID='11003'
HWID = 11002 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX178' where HARDWARE_ID='11002'
HWID = 22581 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX183' where HARDWARE_ID='22581'
HWID = 26335 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX183' where HARDWARE_ID='26335'
HWID = 22442 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX185' where HARDWARE_ID='22442'
HWID = 23346 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX185' where HARDWARE_ID='23346'
HWID = 25792 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX185' where HARDWARE_ID='25792'
HWID = 23652 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX187' where HARDWARE_ID='23652'
HWID = 23656 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX189' where HARDWARE_ID='23656'
HWID = 22606 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX190' where HARDWARE_ID='22606'
HWID = 27912 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELMEX192' where HARDWARE_ID='27912'
HWID = 25223 -> BAJA_CEL_NOR
update accountinfo set TAG='BAJA_CELMTY113' where HARDWARE_ID='25223'
HWID = 15509 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELMTY115' where HARDWARE_ID='15509'
HWID = 17299 -> BAJA_CEL_NOR
update accountinfo set TAG='BAJA_CELMTY115' where HARDWARE_ID='17299'
HWID = 20430 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELMXL109' where HARDWARE_ID='20430'
HWID = 13994 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELNOG102' where HARDWARE_ID='13994'
HWID = 23118 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELOBR105' where HARDWARE_ID='23118'
HWID = 7310 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELOBR108' where HARDWARE_ID='7310'
HWID = 14626 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELOBR108' where HARDWARE_ID='14626'
HWID = 14647 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELOBR108' where HARDWARE_ID='14647'
HWID = 23207 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE104' where HARDWARE_ID='23207'
HWID = 22812 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE110' where HARDWARE_ID='22812'
HWID = 11060 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE111' where HARDWARE_ID='11060'
HWID = 25018 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE111' where HARDWARE_ID='25018'
HWID = 23091 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE112' where HARDWARE_ID='23091'
HWID = 23113 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE113' where HARDWARE_ID='23113'
HWID = 9676 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE114' where HARDWARE_ID='9676'
HWID = 30907 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE114' where HARDWARE_ID='30907'
HWID = 31449 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE114' where HARDWARE_ID='31449'
HWID = 23238 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE115' where HARDWARE_ID='23238'
HWID = 24594 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE115' where HARDWARE_ID='24594'
HWID = 26710 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE117' where HARDWARE_ID='26710'
HWID = 22200 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE120' where HARDWARE_ID='22200'
HWID = 26366 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELPUE121' where HARDWARE_ID='26366'
HWID = 9952 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELQUE100' where HARDWARE_ID='9952'
HWID = 24474 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELQUE102' where HARDWARE_ID='24474'
HWID = 22365 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELQUE105' where HARDWARE_ID='22365'
HWID = 24576 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELQUE105' where HARDWARE_ID='24576'
HWID = 22371 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELQUE107' where HARDWARE_ID='22371'
HWID = 25690 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELSTA103' where HARDWARE_ID='25690'
HWID = 30858 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELSTA103' where HARDWARE_ID='30858'
HWID = 26558 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELTEP102' where HARDWARE_ID='26558'
HWID = 27521 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELTIJ103' where HARDWARE_ID='27521'
HWID = 27520 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELTIJ106' where HARDWARE_ID='27520'
HWID = 32900 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELTIJ107' where HARDWARE_ID='32900'
HWID = 26014 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELTIJ112' where HARDWARE_ID='26014'
HWID = 28375 -> BAJA_CEL_NST
update accountinfo set TAG='BAJA_CELTIJ122' where HARDWARE_ID='28375'
HWID = 28709 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTOL100' where HARDWARE_ID='28709'
HWID = 26847 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTOL102' where HARDWARE_ID='26847'
HWID = 26855 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTOL104' where HARDWARE_ID='26855'
HWID = 27369 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTOL105' where HARDWARE_ID='27369'
HWID = 23134 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTOL107' where HARDWARE_ID='23134'
HWID = 26850 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTOL109' where HARDWARE_ID='26850'
HWID = 27222 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTOL112' where HARDWARE_ID='27222'
HWID = 22488 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTPZ101' where HARDWARE_ID='22488'
HWID = 23022 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTPZ102' where HARDWARE_ID='23022'
HWID = 24122 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTPZ104' where HARDWARE_ID='24122'
HWID = 22489 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTPZ106' where HARDWARE_ID='22489'
HWID = 9692 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTPZ111' where HARDWARE_ID='9692'
HWID = 22492 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTPZ111' where HARDWARE_ID='22492'
HWID = 25150 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTPZ111' where HARDWARE_ID='25150'
HWID = 30458 -> BAJA_CEL_CNT
update accountinfo set TAG='BAJA_CELTPZ112' where HARDWARE_ID='30458'
HWID = 17772 -> BAJA_CEL_SUR
update accountinfo set TAG='BAJA_CELVIL107' where HARDWARE_ID='17772'
HWID = 10698 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELZAP105' where HARDWARE_ID='10698'
HWID = 24922 -> BAJA_CEL_OCT
update accountinfo set TAG='BAJA_CELZAP107' where HARDWARE_ID='24922'*/
?>
