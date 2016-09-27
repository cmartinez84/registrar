<?php
// ./vendor/bin/phpunit tests
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Student.php";
    require_once "src/Course.php";
    require_once "src/Department.php";

    $server = 'mysql:host=localhost;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class DepartmentTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Student::deleteAll();
            Course::deleteAll();
            Department::deleteAll();
        }
        function test_getId()
        {
            $test_department = new Department(null, "misanthropology/intro to nihilism");
            $test_department->save();

            $result = $test_department->getId();

            $this->assertEquals(true, is_numeric($result));
        }
        function test_save(){
            $test_department = new Department(null, "misanthropology/intro to nihilism");
            $test_department->save();

            $result = Department::getAll();

            $this->assertEquals([$test_department], $result);
        }
        function test_getAll()
        {
            $test_department = new Department(null, "misanthropology/intro to nihilism");
            $test_department->save();
            $test_department2 = new Department(null, "political science");
            $test_department2->save();

            $result = Department::getAll();

            $this->assertEquals([$test_department, $test_department2], $result);
        }

        function test_deleteAll()
        {
            $test_department = new Department(null, "misanthropology/intro to nihilism");
            $test_department->save();
            $test_department2 = new Department(null, "political science");
            $test_department2->save();

            Department::deleteAll();

            $this->assertEquals([], Department::getAll());
        }
        function test_addCourse()
        {
            $test_department = new Department(null, "misanthropology");
            $test_department->save();
            $test_course = new Course(null,"intro to nihilism", "666");
            $test_course->save();

            $test_department->addCourse($test_course->getId());
            $result = $test_department->getCourses();


            $this->assertEquals([$test_course], $result);

        }
    }

?>
