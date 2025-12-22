
<?php



// Example 1
$text = "onlyalphanumericcharacters012345.";



if (ereg('[^A-Za-z0-9]', $text)) {

  echo "This contains characters other than letters and numbers";

}

else {

  echo "This contains only letters and numbers";    

}



// Example 2



$text = "mixedcharacters012345&../@";



if (ereg('[^A-Za-z0-9]', $text)) {

  echo "This contains characters other than letters and numbers";

}

else {

  echo "This contains only letters and numbers";    

}



?>

