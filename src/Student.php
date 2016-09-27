<?php
    Class Student
    {
      private $id;
      private $name;
      private $enrollment;

      function __construct($id=null, $name, $enrollment)
      {
        $this->id = $id;
        $this->name = $name;
        $this->enrollment = $enrollment;
      }
      function getId()
      {
        return $this->id;
      }
      function getName()
      {
        return $this->name;
      }
      function setName($new_name)
      {
        $this->name = $new_name;
      }
      function getEnrollment()
      {
        return $this->enrollment;
      }
      function setEnrollment($new_enrollment)
      {
        $this->enrollment = $new_enrollment;
      }
      function save(){
          $GLOBALS['DB']->exec("INSERT INTO students (name, enrollment) VALUES (
              '{$this->getName()}',
              '{$this->getEnrollment()}'
          );");
          $this->id = $GLOBALS['DB']->lastInsertId();
      }
      function delete(){
          $GLOBALS['DB']->exec("DELETE FROM students WHERE id={$this->getid()};");
      }

      // function getClients($search_id){
      //     $all_clients = Client::getAll();
      //     $matched_clients = array();
      //     foreach($all_clients as $client){
      //         if($client->getStylistId() == $search_id){
      //             array_push($matched_clients, $client);
      //         }
      //     }
      //     return $matched_clients;
      // }

      static function find($search_id){
          $found_student = null;
          $returned_students = Student::getAll();
          foreach($returned_students as $student){
              $student_id = $student->getId();
              if($student_id == $search_id){
                  $found_student = $student;
              }
          }
          return $found_student;
      }

      static function getAll(){
          $returned_students = $GLOBALS['DB']->query("SELECT * FROM students");
          $students= array();
          foreach ($returned_students as $student) {
              $id = $student['id'];
              $name = $student['name'];
              $enrollment = $student['enrollment'];
              $new_student = new Student($id, $name, $enrollment);
              array_push($students, $new_student);
          }
          return $students;
      }
      static function deleteAll(){
          $GLOBALS['DB']->exec("DELETE FROM students;");
      }

      function update($name, $enrollment){
          $GLOBALS['DB']->exec("UPDATE students SET
              name ='{$name}',
              enrollment = '{$enrollment}'
              WHERE id ='{$this->getId()}';");
          $this->setName($name);
          $this->setEnrollment($enrollment);
      }
    }

 ?>
