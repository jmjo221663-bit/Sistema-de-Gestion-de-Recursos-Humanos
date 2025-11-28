<?php
if (isset($_GET['pass'])) {
    echo password_hash($_GET['pass'], PASSWORD_BCRYPT);
} else {
    echo "Uso: ?pass=TuContraseña";
}
//$2y$10$IOAEYbln28LnErd4uAzjLe/NY45ETh9KSvvSrtOFgZiC7HAglEg4W