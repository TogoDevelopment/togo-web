<?php

require_once 'database.php';

echo "qqq";

// Add Region
$query = "insert into Region (VAT,Type_Currency,Exchange_Rate,TimeZone) Values ('5','4','5','3')";

//$result=$database->query($query);

if ($result == true)
    echo "true";
else
    echo "false";


// Region Land

$langId = '732A82B9-A792-44DC-878B-9B27AD7DF738';
$RegId = 'D820CB0B-14A3-4EA3-BB68-4FBD69285914';
$RegNAme = 'Palistine';

$query_RegLang = "Insert into RegName (RegId,LangId,RegName) Values ('$RegId','$langId',N'$RegNAme')";
//$result_Reg_Lang=$database->query($query_RegLang);

if ($result_Reg_Lang == true)
    echo "Added";
else
    echo "NotAdd";


$RegId = 'D820CB0B-14A3-4EA3-BB68-4FBD69285914';
$RegNAme = '+972';
$query_AddPost = "insert into PostRegion(RegionId,PostValue) Values ('$RegId',N'$RegNAme')";
//$result_Reg_Lang_Post=$database->query($query_AddPost);

if ($result_Reg_Lang_Post == true)
    echo "Added";
else
    echo "NotAdd";


$query = "Update BusinessType set IdLanguage='732A82B9-A792-44DC-878B-9B27AD7DF738'";
//$result=$database->query($query);
if ($result == true)
    echo "trueeeadd";
else
    echo "NotIUpdated";


$query = "Insert into BusinessName (BusinessName,LanguageId,BusinessId) Values (N'Test4','732A82B9-A792-44DC-878B-9B27AD7DF738','C4B47726-F0EC-491C-811F-9910DA25F033')";
//$result=$database->query($query);

if ($result == true)
    echo "Inseeerteeeedbbbbeee";
else
    echo "NOtInserrrt";

$query = "Insert into SynonymsLanguage (LanguageId,Synonym,TagSynonym) Values (N'732A82B9-A792-44DC-878B-9B27AD7DF738','PhoneNumber','MobilePhone')";


//$result=$database->query($query);

if ($result == true)
    echo "Inseeerteeeedbbbbeee123456";
else
    echo "NOtInserrrt";


$query = "Insert into IDPlace (Name) Values ('test')";
//$result=$database->query($query);

if ($result == true)
    echo "Inseeerteeeedbbbbeee123456";
else
    echo "NOtInserrrt";


$NamePlace = 'فلسطين';

$query = "Insert into IDPlaceLanguage (IdLanguage,NamePlace,IdPlace) Values ('732A82B9-A792-44DC-878B-9B27AD7DF738',N'$NamePlace','76D19D6D-4C79-4681-8ED2-77B1C1100D3A')";
//$result=$database->query($query);

if ($result == true)
    echo "Inseeerteeeedbbbbeee123456a";
else
    echo "NOtInserrrt";


$query = "Insert into TypeLicence (Type) Values ('Test')";
//$result=$database->query($query);

if ($result == true)
    echo "Inseeerteeeedbbbbeee123456a";
else
    echo "NOtInserrrt";


$Type = 'تجاري';

$query = "Insert into TypeLicenceLanguage (TypeName,IdLanguage,IdTypeLicence) Values (N'$Type','732A82B9-A792-44DC-878B-9B27AD7DF738','B0E34207-19D4-46A0-AD5E-19F40E78C5BC')";
//$result=$database->query($query);

if ($result == true)
    echo "Inseeerteeeedbbbbeee123456a";
else
    echo "NOtInserrrt";

$query = "Insert into ColorTable (Color) Values ('test2')";
//$result=$database->query($query);

if ($result == true)
    echo "Inseeerteeeedbbbbeee123456as";
else
    echo "NOtInserrrt";


$query = "Insert into ColorNameLang (IdLanguage,IdColor,ColorName) Values ('732A82B9-A792-44DC-878B-9B27AD7DF738','DAFC1E80-6EFC-4531-9568-68FB5BBD324B
',N'اخضر')";
//$result=$database->query($query);

if ($result == true)
    echo "Inseeerteeeedbbbbeee123456as";
else
    echo "NOtInserrrt";

$namevichel = 'شحن';

$query = "Insert into VehicleNameLang (Name,IdLanguage,IdVehicle) Values (N'$namevichel','732A82B9-A792-44DC-878B-9B27AD7DF738','292F2998-9C39-48A0-85FE-02D71F404C71')";
//$result=$database->query($query);

if ($result == true)
    echo "Inseeerteeeedbbbbeee123456s";
else
    echo "NOtInserrrt";


$query = "Insert into CityRegion (RegionId,languageId,Name) Values ('D820CB0B-14A3-4EA3-BB68-4FBD69285914','732A82B9-A792-44DC-878B-9B27AD7DF738',N'جنين')";
//$result=$database->query($query);

if ($result == true)
    echo "Inseeerteeeedbbbbeee123456s";
else
    echo "NOtInserrrt";


$query = "Update CityRegion set LatRegion='32.462068',LongRegion='35.302535' Where id='C9FCAD39-79D9-40B1-90E1-BDD9A4CCDC66'";
//$result=$database->query($query);

if ($result == true)
    echo "Inseeerteeeedbbbbeee123456swww";
else
    echo "NOtInserrrt";


$query = "Insert into CityRegionLang  (languageId,CityId,CityName) Values ('732A82B9-A792-44DC-878B-9B27AD7DF738','C0210DA2-7B92-42DE-A3BD-91298B08F65F',N'نابلس')";

//$result=$database->query($query);

if ($result == true)
    echo "Inseeerteeeedbbbbeee123456rrr";
else
    echo "NOtInserrrt";


$query = "Select * from Customer Where TokenDevice='FD29DC68 B36432B9 16F0FC78 435532B0 5D20BBF8' AND id='DE7F8E84-D94F-42D8-B783-E0F99B37BA21'
";

$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);


$result = sqlsrv_query($database->getConnect(), $query, $params, $options);
$row_count = sqlsrv_num_rows($result);

if ($result == true)
    echo "yyyyyy" . $row_count . "ttttt";
else
    echo "NOtInserrrt";


?>