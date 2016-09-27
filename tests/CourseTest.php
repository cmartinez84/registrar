<?php
// ./vendor/bin/phpunit tests
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    // mysql.server start -uroot -proot.
    require_once "src/Student.php";
    require_once "src/Course.php";


    $server = 'mysql:host=localhost;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class CourseTest extends PHPUnit_Framework_TestCase
    {
        protected function teardown(){
            Student::deleteAll();
            Course::deleteAll();
        }

        function test_getId()
        {

            $name = "PHP";
            $course_number = "PHP 101";
            $test_course = new Course(null, $name, $course_number);
            $test_course->save();
            //Act
            $result = $test_course->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {

            $name = "PHP";
            $course_number = "PHP 101";
            $test_course = new Course(null, $name, $course_number);
            $test_course->save();

            $result = Course::getAll();

            $this->assertEquals($test_course, $result[0]);
        }

        function test_getAll()
        {
            $name = "PHP";
            $course_number = "PHP 101";
            $test_course = new Course(null, $name, $course_number);
            $test_course->save();

            $name2 = "Java";
            $course_number2 = "Java 101";
            $test_course2 = new Course(null, $name2, $course_number2);
            $test_course2->save();

            $result = Course::getAll();

            $this->assertEquals([$test_course, $test_course2], $result);
        }

        function test_findCourse()
        {
            $name = "PHP";
            $course_number = "PHP 101";
            $test_course = new Course(null, $name, $course_number);
            $test_course->save();

            $name2 = "Java";
            $course_number2 = "Java 101";
            $test_course2 = new Course(null, $name2, $course_number2);
            $test_course2->save();
            $search_id = $test_course->getId();

            $result = Course::find($search_id);

            $this->assertEquals($test_course, $result);
        }

        // function test_getClients()
        // {
        //     $test_stylist1 = new Course(333, "alexandra", "PHP 101", "children");
        //     $test_stylist1->save();
        //     $test_stylist2 = new Course(null, "bob", "11-22-3333", "burgers");
        //     $test_stylist2->save();
        //     $test_client = new Client(null, "bob", "1-11-2111", "2-22-2122", 333);
        //     $test_client->save();
        //
        //     $found_clients = Course::getClients(333);
        //     $result = $found_clients[0];
        //
        //     $this->assertEquals($test_client, $result);
        // }

        function test_delete()
        {
            $name = "PHP";
            $course_number = "PHP 101";
            $test_course = new Course(null, $name, $course_number);
            $test_course->save();

            $name2 = "Java";
            $course_number2 = "Java 101";
            $test_course2 = new Course(null, $name2, $course_number2);
            $test_course2->save();

            $test_course->delete();
            $result = Course::getAll();

            $this->assertEquals([$test_course2], $result);
        }

        function test_deleteAll()
        {
            $name = "PHP";
            $course_number = "PHP 101";
            $test_course = new Course(null, $name, $course_number);
            $test_course->save();

            $name2 = "Java";
            $course_number2 = "Java 101";
            $test_course2 = new Course(null, $name2, $course_number2);
            $test_course2->save();

            Course::deleteAll();
            $result = Course::getAll();

            $this->assertEquals([], $result);
        }

        function test_update()
        {
            $name = "PHP";
            $course_number = "PHP 101";
            $test_course = new Course(null, $name, $course_number);
            $test_course->save();

            $new_name = "HPH";

            $test_course->update($new_name, 'number');
            $found_course = Course::find($test_course->getId());
            $result = $found_course->getName();

            $this->assertEquals($new_name, $result);
        }
        function test_addStudent(){
            $new_student = new Student(null, "bob", "1-11-1999");
            $new_student->save();
            $new_course = new Course(null, "php", "PHP 101");
            $new_course->save();

            $new_course->addStudent($new_student->getId());

            $this->assertEquals([$new_student], $new_course->getStudents());


        }
    }
?>
