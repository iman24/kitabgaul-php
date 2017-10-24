<?php

// KITABGAUL API - PHP CLI only
// Coded by Iman Nurzaman
// October 2017

function getpage(array $input, $pageNum, $perPage) {
// source stackoverflow.com
$start = ($pageNum-1) * $perPage;
$end = $start + $perPage;
$count = count($input);
// Conditionally return results
if ($start < 0 || $count <= $start) {
// Page is out of range
return array(); 
} else if ($count <= $end) {
// Partially-filled page
return array_slice($input, $start);
} else {
// Full page 
return array_slice($input, $start, $end - $start);
    }
}
echo shell_exec('tput setaf 3')."Kitabgaul API v2.0 by Imanz Nurza".shell_exec('tput sgr0')."\n";
echo "Visit".shell_exec('tput setaf 2')." http://taplax.wordpress.com ".shell_exec('tput sgr0')."for update\n";
if($argv[1] == "-det" && $argv[2] != ""){
goto haha;
}elseif(($argv[1] == "--help" || $argv[1] == "-h") && (!isset($argv[2]))){
echo "Help Me..!\n";
echo "Example: php ".$argv[0]." [option] [string]\nOption: -h | --help : show this help\n        -det [string] : show details of string\n";
exit;
}elseif($argv[1] == "-det" && !(isset($argv[2]))){
goto hahax;
}
if($argc == 1){
fwrite(STDOUT, "Enter word to search: ");
$argv[1]=trim(fgets(STDIN));
}
$json = file_get_contents('https://kitabgaul.com/api/entries;search?keyword='.$argv[1].'&includeContent=true&maxCount=10') or die('Problem connecting to server') ; 
//$json=file_get_contents('./search.json');
$json=json_decode($json); 

if($json->terms == null){
echo "Kata yang dicari tidak ditemukan silahkan periksa kata atau coba kata yang lainnya.\n";
exit;}
$a=1;
$my=array();
foreach ($json->terms as $array) {

echo "".$a.") $array->word\n";
$my[]=$array->word;
$a++;
}

hahax:
fwrite(STDOUT, "Enter your number [1-".count($my)."] : ");
$kata=trim(fgets(STDIN));
if(!is_numeric($kata) || ($kata > count($my)) || $kata == 0 || $kata < 0){
echo("Input hanya nomer aja atau angka terlalu besar. silahkan masukkan nomer option yang tersedia di atas\n");
goto hahax;
}
$kata=$kata-1;
$kata=$my[$kata];
haha:
if(isset($argv[2]))
$kata=$argv[2];
$detail=file_get_contents('https://kitabgaul.com/api/entries/'.$kata.'') or die('Error get data from server');
// for debug only
//$detail=file_get_contents('./gaje.json');
$det=json_decode($detail, true);
//$det=array('entries' => array('word' => 'iman', 'definition' => 'iman paling ganteng', 'example' => 'hoho'), array('word' => 'azi' , 'definition' => 'kaka', 'example' => 'hehe'));
$entries=$det['entries'];
if($entries == null){
echo "Kata yang dicari tidak ditemukan silahkan periksa kata atau coba kata yang lainnya.\n";
exit;}

// Config
$pagenum=1; // default page
$perpage=4; // data per page



// color config
$col=shell_exec('tput setaf 50');
$reset=shell_exec('tput sgr0');
//
// true apabila halaman berisi angka di validasi dengan fungsi is_numeric
while(is_numeric($pagenum)){

$countp=count($entries); // total data

// ngacapruk

$koplak=(($pagenum*$perpage)-($perpage-1));
$totalhal=ceil($countp/$perpage); // total halaman
$pag=getpage($entries,$pagenum,$perpage);

if($pagenum > $totalhal || $pagenum < 1)
exit("Out of page..Exited!!\n");
// extract data
foreach($pag as $h){
echo "---------------------------------\n".$koplak.") ".$col."Kata: ".$reset.shell_exec('tput setaf 3').$h['word'].$reset;
echo "\n".$col."Definisi: ".$reset.$h['definition'];
echo "\n".$col."Contoh: ".$reset."\n".$h['example'];
echo "\n";
// increment order number
$koplak++;

}
echo "Total ada ".($countp)." data dari ".$totalhal." Halaman \n";
echo "[Page ".$pagenum." of ".$totalhal." page(s)]\n";
if($countp > $perpage && $totalhal !== $pagenum){
fwrite(STDOUT, "Masukkan nomer halaman: ");
$pagenum=trim(fgets(STDIN));

}
else
exit; // exit if 

}

?>
