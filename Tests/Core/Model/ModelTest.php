<?php
/**
 * Description of ModelTest
 *
 * @author asuslov
 */
class ModelTest extends PHPUnit_Framework_TestCase {

//    /**
//     * всякие извраты для testInitData
//     */
//    public function providerInitData() {
//
//    }
//    /**
//     * dataProvider providerInitData
//     */
//    public function testInitData($data) {
//    }


    public function testDelete() {
        
    }
    public function testReset() {
        
    }
    public function testGetArray() {
        
    }
    public function testGetValues() {

    }
    public function testGetFields() {
        
    }
    /**
     * @covers Model::getObject
     */
    public function testGetObject() {
        /*
         * проверка, что отработает правильно когда таблица пустая
         * +добавляем поле
         * проверка, что при не правильном значении вернется 0
         * проверка, что при правильно вернутся нужные данные
         * проверка, что не сломается от инъекций и прочего
         */
    }

    public function testGetObjects() {
        /*
         * проверка, что отработает правильно когда таблица пустая
         * +добавляем поле
         * проверка, что при не правильном значении вернется 0
         * проверка, что при правильно вернутся нужные данные
         * проверка, что не сломается от инъекций и прочего
         * что не сломается от левого класса
         * проверяем что сретурнил не пустой массив
         * и все эелементы которые являются экземплярами наследников от модели
         * и они не пустые
         */
    }
    /**
     * @depends testGetObject
     */
    public function test__Construct() {
        /*
         * проверяем праильно отработает без переданных занчений
         * и что с переданными совпадает с testGetObject
         */
    }

    public function test__Get() {

    }

    public function test__Set() {

    }
}
?>