<?php
require_once "../FormValidate/Form.class.php";

use FormValidate\Form;


?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>PHP Formularvalidierung</title>

        <link rel="stylesheet" href="stylesheets/style.css">

    </head>
    <body>



        <div class="page-wrapper">

            <form action="index.php" method="post" class="form-1">

                <?php
                /* set rules for the form */
                $rules = array();
                $rules["name"] = array(
                    "required" => true,
                    "minlength" => 3,
                    "maxlength" => 100,
                    "alphabetical" => true,
                    "label" => "name",
                    );
                $rules["email"] = array(
                    "required" => true,
                    "email" => true,
                    "label" => "email",
                    );
                $rules["phone"] = array(
                    "required" => true,
                    "phone" => true,
                    "label" => "phone number",
                    );
                $rules["age"] = array(
                    "required" => true,
                    "number" => true,
                    "minlength" => 2,
                    "maxlength" => 3,
                    "label" => "age",
                    );
                $rules["country"] = array(
                    "required" => true,
                    "label" => "country",
                    );
                $rules["agb"] = array(
                    "required" => true,
                    "label" => "terms of use",
                    );

                if(isset($_POST["send"])) {
                    // form has been submitted

                    $Form = new Form($_POST, $rules);
                    if($Form->validate()) {
                        // form data is valid

                        // sanitize data
                        $email = $Form->clean($_POST["email"], "email");
                        $anrede = $Form->clean($_POST["anrede"]);
                        $name = $Form->clean($_POST["name"]);
                        $phone = $Form->clean($_POST["phone"]);
                        $age = $Form->clean($_POST["age"], "number");
                        $country = $Form->clean($_POST["country"]);

                        ?>
                        <p class="form-item is-success">
                            Thank you for your request.
                        </p>
                        <?php
                    } else {
                        // errors occured - fetch them now
                        $errors = $Form->getErrors();
                        if($errors) {
                            foreach($errors as $error) {
                                // run through errors and output
                                ?>
                                <p class="form-item is-error">
                                    <?php echo $error; ?>
                                </p>
                                <?php
                            }
                        }
                    }
                }

                ?>

                <p class="form-item">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name">
                </p>

                <p class="form-item">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email">
                </p>

                <p class="form-item">
                    <label for="phone">Phone number</label>
                    <input type="text" name="phone" id="phone">
                </p>

                <p class="form-item">
                    <label for="age">Age</label>
                    <input type="number" name="age" id="age">
                </p>
                
                <p class="form-item">
                    <label for="country">Country</label>
                    <select name="country" id="country">
                        <option>United Kingdom</option>
                        <option>Germany</option>
                        <option>Italy</option>
                        <option>Other</option>
                    </select>
                </p>

                <p class="form-item add-margin">
                    <input type="checkbox" value="ok" name="agb" id="agb"> <label for="agb" class="is-inline">I accept the <a href="#">terms of use</a>
                </p>

                <p class="form-item add-margin">
                    <input type="submit" value="Send request" name="send">
                </p>

            </form>

        </div>



    </body>
</html>