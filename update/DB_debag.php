
<?PHP
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class Car
{
    protected $_name = "BMW M3";
    protected $_vin = "11";
    protected $_color = "Black";
    protected $_engine = "3.5";

    public function __construct($_name, $_vin, $_color, $_engine)
    {
        $this->_name;
        $this->_vin;
        $this->_color;
        $this->_engine;
    }
    public function getInfo()
    {
        echo "{$this->name} имеет цвет {$this->color}, vin {$this->vin}, а так же {$this->engine} объем двигателя";
    }
}
$bmwCar = new Car;

$bmwCar->$_vin = "12";

var_dump($bmwCar);
