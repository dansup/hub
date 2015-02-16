<?php

header('Content-Type: application/json');

echo json_encode(array('resp'=>404, 'error'=>true, 'error_desc'=>'The API endpoint does not exist.'), JSON_PRETTY_PRINT);
