<?php

require_once("DataList.php");

$testArray = array(
  array('name'=>'Anna','age'=>22,'owns'=> array('type'=>'furniture','label'=>'chair')),
  array('name'=>'Bill','age'=>12,'owns'=> array('type'=>'fruit','label'=>'apple')),
  array('name'=>'Cedric','age'=>33,'owns'=> array('type'=>'furniture','label'=>'sofa'))
);

$test = new DataList($testArray);

$test->where('age','> 15');
$test->where('name','^[AaBbCc](.*)$');
$test->find();

if($test->length()){
  
  $people = $test->sort('name');
  
  foreach($people as $person){
    echo $person['name'].", ".$person['age']."\n";
  }
  
}

?>