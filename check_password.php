<?php
$enteredPassword = 'staff123';
$hashedPasswordFromDB = '$2y$10$vF88RDN7SxAvayl4EU8q.O.B8gvnz0FlOVf0i/ICTMMWvlF8/FspG';


if (password_verify($enteredPassword, $hashedPasswordFromDB)) {
    echo "MATCHED!";
} else {
    echo "NOT MATCHED!";
}

?>
