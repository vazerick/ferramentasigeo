<?php
require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';

function websafe( $string ) {
//    dump(substr($string,1, 6));
//    $string = md5( substr($string,1, 6) );
//    dump($string);
    $string = substr($string,1, 6);
    $red = hexdec( substr( $string, 0, 2 ) );
    $green = hexdec( substr( $string, 2, 2 ) );
    $blue = hexdec( substr( $string, 4, 2 ) );

    $multiple = 51;

    $red = $red + ($multiple / 2);
    $red -= $red % $multiple;

    $green = $green + ($multiple / 2);
    $green -= $green % $multiple;

    $blue = $blue + ($multiple / 2);
    $blue -= $blue % $multiple;

    return sprintf( "#%02x%02x%02x", $red, $green, $blue );
}

for ($i = 1; $i <= 100; $i++) {
    $cor = cor_equipe($i);
//    $familia = $cor;
    $familia = websafe($cor);
//    $familia[2] = $familia[1];
//    $familia[4] = $familia[3];
//    $familia[6] = $familia[5];
    echo '<div class="row">';
    echo '<div class="col-7" style="color:#fff; background-color:' . $cor . '">';
    echo "Equipe ID " . $i . ". - " . $cor;
    echo '</div>';
    echo '<div class="col" style="color:#fff; background-color:' . $familia . '">';
    echo "Família: " . $familia;
    echo '</div>';
    echo '</div>';
}
