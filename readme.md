### A collection that contains no duplicate elements. More formally, sets contain no pair of elements $e1 and $e2 such that $e1->equals($e2), and at most one null element. As implied by its name, this class models the mathematical set abstraction.

Simple use case:

```
require_once 'autoload.php';

class Season implements ComparableInterface 
{
    private $name;

    public function __construct($name) 
    {
        $this->name = $name;
    }

    public function compareTo(ComparableInterface $o) 
    {
        $seasons = ['summer', 'fall', 'winter', 'spring'];
        $pos     = array_search(strtolower($this->name), $seasons);
        $oPos    = array_search(strtolower($o->name), $seasons);

        if($pos < $oPos) return -1;
        else if($pos > $oPos) return 1;
        else return 0;
    }

    public function compareToIgnoreCase(ComparableInterface $o) 
    {
        return $this->compareTo($o);
    }

    public function __toString() 
    {
        return $this->name;
    }
}

$set = new BinarySearchSet();
$set->add(new Season('fall'));
$set->add(new Season('summer'));
$set->add(new Season('spring'));
$set->add(new Season('winter'));

echo $set.PHP_EOL;
```
