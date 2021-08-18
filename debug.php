<?php 
require __DIR__ . "/src/bmkg.php";

$cuaca = new BMKG;
echo json_encode($cuaca->GetInfoCuaca(07, "DKI Jakarta"));