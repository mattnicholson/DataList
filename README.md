# DataList
Basic querying &amp; sorting for PHP arrays.

```
require_once("DataList.php");

$testArray = array(
  array('name'=>'Anna','age'=>22,'owns'=> array('type'=>'furniture','label'=>'chair')),
  array('name'=>'Bill','age'=>12,'owns'=> array('type'=>'fruit','label'=>'apple')),
  array('name'=>'Cedric','age'=>33,'owns'=> array('type'=>'furniture','label'=>'sofa'))
);

$test = new DataList($testArray);
```

Basic Sort
-------------------

```
$people = $test->sort('name');
```

Reverse Sort
-------------------

```
$people = $test->sort('name','DESC');
```

Nested Sort
-------------------
Sorts data based on values in nested properties.

```
$people = $test->sort('owns->label');
```

Find
-------------------
Search the data, or filter by criteria.
If the property you're searching is a string, the search rule will be treated as a regular expression

```
$test->where('name','Anna');
$test->find();

$people = $test->sort('name');

```

Find alternative
-------------------
More obvious regular expression. Any name starting a or b:

```
$test->where('name','^[AaBb](.*)$');
$test->find();

$people = $test->sort('name');

```

Numeric find
-------------------

```
$test->where('age','> 20');
$test->find();

$people = $test->sort('age');

```


Find in nested properties
-------------------
This uses object notation to drill into the data. Works on arrays and objects.

```
$test->where('owns->type','furniture');
$test->find();

$people = $test->sort('name');

```



Multiple find criteria
-------------------
These are treated as 'and', not 'or', so where Rule #1 AND where rule #2

```
$test->where('age','> 15');
$test->where('name','^[AaBbCc](.*)$');
$test->find();

$people = $test->sort('name');

```

Count results
-------------------

```
$test->where('age','> 15');
$test->where('name','^[AaBbCc](.*)$');
$test->find();

echo $test->length();

```

Full example
-------------------
Shows querying the data and iterating over results

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




