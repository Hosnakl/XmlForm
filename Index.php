<?php
error_reporting(E_ALL ^ E_NOTICE);
include("./common/header.php");
include("./common/footer.php");

$XMLFile = new SimpleXMLElement("Data/restaurant_reviews.xml", NULL, TRUE);
$childNodes = $XMLFile->children();

$showTable = false;
$restaurantSelected = null;
$showSavedMessage = false;

if (isset($_POST['dropdownList']) || isset($_POST['submitButton'])) {
    if ($_POST['dropdownList'] != "-1") {
        // if user changes data
        if (isset($_POST['submitButton'])) {
            // get the data
            $streetAddress = trim($_POST['streetAddress']);
            $city = trim($_POST['city']);
            $province = trim($_POST['province']);
            $postalCode = trim($_POST['postalCode']);
            $summary = trim($_POST['summary']);
            $rating = trim($_POST['rating']);

            $restaurant = $XMLFile->Resturant[intval($_POST['dropdownList'])];
            $restaurant->Address->StreetAddress = $streetAddress;
            $restaurant->Address->City = $city;
            $restaurant->Address->Province = $province;
            $restaurant->Address->PostalCode = $postalCode;
            $restaurant->Summary = $summary;
            $restaurant->Rating = $rating;
            //save the data
            $XMLFile->saveXML();
            $XMLFile->saveXML("Data/restaurant_reviews.xml");

            $showSavedMessage = true;
        }

        //show the remaining table
        $restaurantSelected = $_POST['dropdownList'];
        $showTable = true;

        // get data from XML 
        $restaurant = $XMLFile->Resturant[intval($restaurantSelected)];
        $streetAddress = trim($restaurant->Address->StreetAddress);
        $city = trim($restaurant->Address->City);
        $province = trim($restaurant->Address->Province);
        $postalCode = trim($restaurant->Address->PostalCode);
        $summary = trim($restaurant->Summary);
        $rating = trim($restaurant->Rating);

        $ratingRow = "<tr> <td> <label>Rating:</label> </td><td> <select name='rating'>";
        foreach (range(1, 5) as $i) {
            if ($rating == $i) {
                $ratingRow = $ratingRow . "<option value='$i' selected>$i</option>";
            } else {
                $ratingRow = $ratingRow . "<option value='$i'>$i</option>";
            }
        }

        $ratingRow = $ratingRow . "</select></td>";

        // create the remaining table 
        $table = <<<EOD
            <tr>
                <td>
                    <label>Street Address:</label>
                </td>
                <td>
                    <input name='streetAddress' type="text" value="$streetAddress" />
                </td>
                <td>
                    <span></span>
                </td>
            </tr>
            <tr>
                <td>
                    <label>City:</label>
                </td>
                <td>
                    <input name='city' type="text" value="$city" />
                </td>
                <td>
                    <span></span>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Province/State:</label>
                </td>
                <td>
                    <input name='province' type="text" value="$province" />
                </td>
                
            </tr>
            <tr>
                <td>
                    <label>Postal/Zip Code:</label>
                </td>
                <td>
                    <input name='postalCode' type="text" value="$postalCode" />
                </td>
                
            </tr>    
            <tr>
                <td>
                    <label>Summary:</label>
                </td>
                <td>
                    <textarea name='summary' type="text">$summary</textarea>
                </td>
                <td>
                    <span></span>
                </td>
            </tr>
            EOD;
        $table = $table . $ratingRow;
        $table = $table . <<<EOD
            <tr> 
                <td></td>
                <td> 
                    <button name='submitButton' onclick="myFunction()" type='submit'>Save Changes</button>
                </td> 
            </tr>
            EOD;
    }
}
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Online Restaurant Review</title>
        <style>
<?php include("./styles/styles.css"); ?>
        </style>
    </head>
    <body>
        <h2 class="center">Online Restaurant Review</h2>
        <div class="form-container">
            <p>Select a restaurant from the dropdown list to view/edit its review</p>
            <form action="Index.php" method="post">
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <label>Restaurants:</label>
                            </td>
                            <td>
                                <select name="dropdownList" onchange="this.form.submit()">
                                    <?php
                                    // populatng dropdown list options
                                    // if user has NOT selected a resturant yet
                                    if (!$showTable) {
                                        print "<option value='-1'>Select...</option>";
                                    }
                                    // create dropdown of restuarants
                                    $count = 0;
                                    foreach ($childNodes as $item) {
                                        $name = $item->Name;
                                        if ($count == $restaurantSelected && $restaurantSelected != null) {
                                            print "<option value='$count' selected>$name</option>";
                                        } else {
                                            print "<option value='$count'>$name</option>";
                                        }
                                        $count++;
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <?php
                        if ($showTable) {
                            print $table;
                        }
                        ?>
                    </tbody>
                </table>
            </form>
            
        </div>
        <script>
            function myFunction() {
                alert("your change has been saved!");
            }
        </script>
    </body>


</html>