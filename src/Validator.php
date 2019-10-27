<?php

namespace App;

class Validator implements ValidatorInterface
{
    public function validate(array $course)
    {
        // BEGIN (write your solution here)
        $errors = [];

        if ($course['paid'] == '') {
            $errors['paid'] = "Can't be blank";
        }

        if ($course['title'] == '') {
            $errors['title'] = "Can't be blank";
        }

        return $errors;
        // END
    }
}
