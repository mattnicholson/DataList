<?

/*

DataList
----------------------------------------------
Query and Sort multi-dimensional arrays in PHP

@author Matt Nicholson <matt@archivestudio.co.uk>
@website https://github.com/mattnicholson/DataList.git

*/

class DataList{
  
  public $list;
  public $sortable;
  public $sorted;
  public $subset;
  public $wheres;
  
  function __construct($list = array())
	{
		 $this->list = $list;
		 $this->sortable = $list;
		 $this->sorted = $list;
		 $this->subset = array();
		 $this->wheres = array();
	}
	
	function where($prop,$rule){
  	
  	$subset = (!sizeof($this->wheres)) ? $this->list : $this->subset;
  	$this->subset = array();
  	
  	foreach($subset as $item):
    	
    	if($this->match($item,$prop,$rule)) $this->subset[] = $item;
    	
  	endforeach;
  	
  	$this->wheres[] = array($prop,$rule);
  	
	}
	
	function match($item,$prop,$rule){
  	

  	$this->wheres[] = $rule;
  	$prop = $this->getRawValue($item,$prop);
  	$type = gettype($prop);
  	
  	switch($type):
  	  
  	  case 'string':
  	  
  	    $match = preg_match('/'.$rule.'/',$prop);
  	    
        break;
  	  case 'object':
  	    
  	    if(get_class($prop) == 'DateTime'):
  	      $value =  $prop->getTimestamp();
  	      

  	      $match = eval('return ($value '.$rule.');');
  	      
  	      
        else:
          
          $value =  0;
        endif;
        
        break;
  	   
  	   case 'integer' :
  	      
  	      $value =  $prop;
  	      $match = eval('return ($value '.$rule.');');
  	      
  	   break;	  
  	  default:
  	    $match = 0;
        break;
  	
  	endswitch;
  	
  	return $match;
  	
	}
	
	function find(){
  	
  	$this->sortable = (!sizeof($this->wheres)) ? $this->list : $this->subset;
  	$this->subset = array();
  	$this->wheres = array();
  	return $this->sortable;
  	
	}
	
	function all(){
  	
  	$this->sortable = $this->list;
  	$this->subset = array();
  	return $this->sortable;
  	
  	
	}
	
	function length(){
  	
  	return sizeof($this->sortable);
  	
	}
	
	function collate($prop=null){
  	
  	$collated = array();
  	
  	foreach($this->sorted as $item):
  	  $collated[] = $this->getRawValue($item,$prop);
  	endforeach;
  	
  	return $collated;
  	
	}
	
	function sort($props=null,$order='ASC'){
  	
  	$i = 0;
  	$arr = array();
  	
  	// Allow a string to be supplied
  	if(gettype($props) == 'string') $props = array($props);
  	if(!is_array($props)) $props = array();
  	
  	
  	foreach($this->sortable as $item):
    	
    	$k = $this->makeSortKey($item,$props);
    	
    	// Add a unique counter to the end
      $unique = $this->padValue($i);
      $k = $k.$unique;
    
    	$arr[$k] = $item;
    	
    	$i++;
    	
  	endforeach;
  	
  	switch($order):
  	  case 'DESC':
  	    krsort($arr);
  	  break;
  	  
  	 default:
  	    ksort($arr);
  	 break;
  	endswitch;
  	
  	$this->sorted = $arr;
  	
  	return $this->sorted;
  	
  	
	}
	
	function makeSortKey($item,$props){
  	
  	$i = 0;
  	
  	if(sizeof($props) == 1) :
  	  $k = $this->getSortablePropValue($item,$props[0]);
  	else:
  	  $k = "";
  	  
  	  foreach($props as $prop):
  	    
  	    $value = $this->getSortablePropValue($item,$prop);
  	    $k = $k.$value;
  	    
  	    $i++;
  	    
  	  endforeach;
  	  
  	  
  	  
  	endif;

    return $k;
  	
	}
	
	function getNestedProperty($item,$prop){
  	
  	$levels = explode('->',$prop);
  	
  	$i = $item;
  	
  	
  	
  	foreach($levels as $level):
  	  
  	  $i = $i->{$level};
  	  if(is_array($i)) $i = (object) $i;
  	endforeach;
  	
  	return $i;
  	
	}
	
	function getRawValue($item,$prop){
  	
  	if(is_array($item)) $item = (object) $item;
  	
  	if(strstr($prop,'->')):
      $prop = $this->getNestedProperty($item,$prop);
    else:
      $prop = $item->{$prop};
    endif;
    
    if(preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})$/',$prop)) $prop = new DateTime($prop);
  	
  	return $prop;
	}
	
	function getSortablePropValue($item,$prop){
  	
  	$prop = $this->getRawValue($item,$prop);
  	
  	$type = gettype($prop);
  	$dir = STR_PAD_LEFT;
  	$pad = 30;
  	switch($type):
  	  
  	  case 'string':
  	    $dir = STR_PAD_RIGHT;
  	    $pad = 100;
  	    $value = preg_replace('/[^a-z0-9]/','',strtolower($prop));
        break;
  	  case 'object':
  	    
  	    if(get_class($prop) == 'DateTime'):
  	      $value =  $prop->getTimestamp();
        else:
          $value =  0;
        endif;
        
        break;
  	  
  	  case 'integer':
  	    $value = $prop;
  	    break;
  	  
  	  default:
  	    $value = 0;
        break;
  	
  	endswitch;
  	
  	return $this->padValue($value,$pad,$dir);
  	
	}
	
	function getPropValue($item,$prop,$sortable){
	  
	  if(strstr($prop,'->')):
      $prop = $this->getNestedProperty($item,$prop);
    else:
      $prop = $item->{$prop};
    endif;
  	
  	$type = gettype($prop);
  	$dir = STR_PAD_LEFT;
  	switch($type):
  	  
  	  case 'string':
  	    $dir = STR_PAD_RIGHT;
  	    $value = preg_replace('/[^a-z0-9]/','',strtolower($prop));
        break;
  	  case 'object':
  	    
  	    if(get_class($prop) == 'DateTime'):
  	      $value =  $prop->getTimestamp();
        else:
          $value =  0;
        endif;
        
        break;
  	  
  	  case 'integer':
  	    $value = $prop;
  	    break;
  	  
  	  default:
  	    $value = 0;
        break;
  	
  	endswitch;
  	
  	return $this->padValue($value,100,$dir);
  	
  	
  	
	}
	
	function padValue($value,$pad=10,$dir = STR_PAD_LEFT){
  	
  	return str_pad($value, $pad, "0", $dir);
  	
	}
	
  
}
?>