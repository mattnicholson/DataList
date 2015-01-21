# DataList
Simple sort &amp; find PHP class for array &amp; multi-dimensional arrays 

```
require_once("DataList.php");
```

Usage:

```
$testArray = array(
  array('name'=>'Anna','age'=>22,'owns'=> array('type'=>'furniture','label'=>'chair')),
  array('name'=>'Bill','age'=>12,'owns'=> array('type'=>'fruit','label'=>'apple')),
  array('name'=>'Cedric','age'=>33,'owns'=> array('type'=>'furniture','label'=>'sofa'))
);

$test = new DataList($testArray);
```

Basic Sort:

```
$people = $test->sort('name');
```

Reverse:

```
$people = $test->sort('name','DESC');
```

Sort by nested property:

```
$people = $test->sort('owns->label');
```

Find (if string field it's a regular expression):

```
$test->where('name','Anna');
$test->find();

$people = $test->sort('name');

```

Find alternative (regular expression). Any name starting a or b:

```
$test->where('name','^[AaBb](.*)$');
$test->find();

$people = $test->sort('name');

```

Find in nested properties (uses object notation, even if it's an array):

```
$test->where('owns->type','furniture');
$test->find();

$people = $test->sort('name');

```

Multiple find criteria. These are treated as 'and', not 'or':

```
$test->where('age','> 15');
$test->where('name','^[AaBbCc](.*)$');
$test->find();

$people = $test->sort('name');

```

Find out how many returned:

```
$test->where('age','> 15');
$test->where('name','^[AaBbCc](.*)$');
$test->find();

echo $test->length();

```

Iterate over results:

```
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

```




