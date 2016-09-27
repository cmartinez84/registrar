<?php
    class Department
    {
        private $id;
        private $name;

        function __construct($id=null, $name)
        {
            $this->id = $id;
            $this->name = $name;
        }
        function getName()
        {
            return $this->name;
        }
        function setName($new_name)
        {
            $this->name = $new_name;
        }
        function getId()
        {
            return $this->id;
        }
        function save(){
            $GLOBALS['DB']->exec("INSERT INTO departments (name) VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }
        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM departments WHERE id={$this->getId()};");
        }
        function find($search_id){
            $found_department = null;
            $all_departments = Department::getAll();
            foreach ($all_departments as $department) {
                if($department->getId() == $search_id){
                    $found_department = $department;
                }
            }
            return $found_department;
        }

        static function deleteAll(){
            $GLOBALS['DB']->exec("DELETE FROM departments;");
        }
        static function getAll()
        {
            $returned_departments = $GLOBALS['DB']->query("SELECT * FROM departments");
            $departments = array();

            foreach($returned_departments as $department){
                $id = $department['id'];
                $name = $department['name'];
                $new_department = new Department($id, $name);
                array_push($departments, $new_department);
            }
            return $departments;
        }
        function addCourse($course_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO courses_departments (course_id, department_id) VALUES ({$course_id}, {$this->getId()});");
        }
        function addStudent($student_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO students_departments (student_id, department_id) VALUES ({$student_id}, {$this->getId()});");
        }
        function getCourses()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT courses.* FROM departments
                JOIN courses_departments ON (courses_departments.department_id = departments.id)
                JOIN courses ON(courses.id = courses_departments.course_id)
                WHERE departments.id={$this->getId()};"
            );
            $courses = array();
            foreach($returned_courses as $course){
                $id = $course['id'];
                $name = $course['name'];
                $course_number = $course['course_number'];
                $found_course = new Course($id, $name, $course_number);
                array_push($courses, $found_course);
            }
            return $courses;
        }
        function getStudents()
        {
            $returned_students = $GLOBALS['DB']->query("SELECT students.* FROM departments
                JOIN students_departments ON (students_departments.department_id = departments.id)
                JOIN students ON(students.id = students_departments.students_id)
                WHERE departments.id={$this->getId()};"
            );
            $students = array();
            foreach($returned_students as $student){
                $id = $student['id'];
                $name = $student['name'];
                $enrollment = $student['enrollment'];
                $found_student = new Student($id, $name, $enrollment);
                array_push($students, $found_student);
            }
            return $students;
        }


    }
 ?>
