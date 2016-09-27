<?php
// ./vendor/bin/phpunit tests
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Student.php";

    $server = 'mysql:host=localhost;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class StudentTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown(){
            Student::deleteAll();
        }

        function test_getId()
        {
            $test_student = new Student(null, "bob", "1-11-1111");
            $test_student->save();

            $result = $test_student->getId();

            $this->assertEquals(true, is_numeric($result));

        }
        function test_save()
        {
            $test_student = new Student(null, "barbara", "1-11-1999");
            $test_student->save();

            $result = Student::getAll();

            $this->assertEquals([$test_student], $result);
        }

        function test_getAll()
        {
            $test_student = new Student (null, "bob", "1-11-1990");
            $test_student2 = new Student (null, "barbara", "1-1-1888");
            $test_student->save();
            $test_student2->save();

            $result = Student::getAll();

            $this->assertEquals([$test_student, $test_student2], $result);
        }

        function test_find()
        {
            $test_student = new Student (null, "bob", "1-11-1990");
            $test_student2 = new Student (null, "barbara", "1-1-1888");
            $test_student->save();
            $test_student2->save();
            $test_student2_id = $test_student2->getId();

            $result = Student::find($test_student2_id);

            $this->assertEquals($test_student2, $result);

        }

        function test_delete()
        {
            $test_student = new Student (null, "bob", "1-11-1990");
            $test_student2 = new Student (null, "barbara", "1-1-1888");
            $test_student->save();
            $test_student2->save();
            $test_student->delete();

            $result = Student::getAll();

            $this->assertEquals([$test_student2], $result);
        }

        function test_deleteAll()
        {
            $test_student = new Student (null, "bob", "1-11-1990");
            $test_student2 = new Student (null, "barbara", "1-1-1888");
            $test_student->save();
            $test_student2->save();

            Student::deleteAll();
            $result = Student::getAll();

            $this->assertEquals([], $result);
        }

        function test_update()
        {
            $test_student = new Student(null, "barbara", "1-11-1999");
            $test_student->save();

            $new_name = "bob";
            $new_enrollment = "2-22-2222";

            $test_student->update($new_name, $new_enrollment);
            $found_student = Student::find($test_student->getId());

            $result = [$found_student->getName(), $found_student->getEnrollment()];

            $this->assertEquals([$new_name, $new_enrollment], $result);
        }
    }
?>
