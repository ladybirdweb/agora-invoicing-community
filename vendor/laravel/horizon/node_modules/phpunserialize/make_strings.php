<?php
error_reporting(0);

function dump ($o) {
  $o = serialize($o);
  echo json_encode($o), "\n";
}

class Foo {
  public $bar = 1;
  protected $baz = 2;
  private $xyzzy = array(1,2,3,4,5,6,7,8,9);
  protected $self;
  public function __construct () {
    $this->self = $this;
  }
}
$f = new Foo();
dump($f);

class Bar {
}
$f = new Bar();
$f->self = $f;
dump($f);

class Child extends Foo {
    public $lorem = 42;
    protected $ipsum = 37;
    private $dolor = 13;
}
$f = new Child();
dump($f);

$f = new stdClass;
$f->obj1->obj2->obj3->arr = array();
$f->obj1->obj2->obj3->arr[] = 1;
$f->obj1->obj2->obj3->arr[] = 2;
$f->obj1->obj2->obj3->arr[] = 3;
$f->obj1->obj2->obj3->arr['ref1'] = $f->obj1->obj2;
$f->obj1->obj2->obj3->arr['ref2'] = &$f->obj1->obj2->obj3->arr;
dump($f);

$f = array(
    'int' => 42,
    'str' => "lorem",
    'nul' => null,
    'obj' => new stdClass(),
);
$f['obj']->lorem = 10;
$f['obj']->ipsum = new stdClass();
$f['obj']->ipsumLink = $f['obj']->ipsum;
$f['obj']->ipsumRef  = &$f['obj']->ipsum;

$f['intRef']  = &$f['int'];
$f['strRef']  = &$f['str'];
$f['nulRef']  = &$f['nul'];
$f['objLink'] = $f['obj'];
$f['objRef']  = &$f['obj'];
dump($f);

$f = new SplDoublyLinkedList();
dump($f);

$o = new StdClass();
$a = new StdClass();
$o->a = $a;
$f = new SplObjectStorage();
$f[$o] = 1;
$f[$a] = 2;
dump($f);

$f = 'blåbærsyltetøy';
dump($f);

$f = '$¢€𠜎';
dump($f);

$array = array(
  "0b5f7j" => "value1",
  "anotherKey" => "value2"
);
dump($array);
