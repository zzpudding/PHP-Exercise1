<?php
/**
 * Created by PhpStorm.
 * User: zhangyujia
 * Date: 4617.11.17
 * Time: 13:31
 */
session_start();

$name = $visitorInfo = $browser = $os = $browserCounter = "";

//count the total visitors' number
if(isset($_SESSION['views']))
    $_SESSION['views']=$_SESSION['views']+1;
else
    $_SESSION['views']=0;

//get the visitor's general information and process it
$visitorInfo = $_SERVER['HTTP_USER_AGENT'];

//determine the browser type and count the number of same browser's user
if(strpos($visitorInfo,'Chrome')){
    $browser = "Chrome";
    if(!isset($_SESSION['Chrome']))
        $_SESSION['Chrome']= 0;
    else
        $_SESSION['Chrome'] ++;
    $browserCounter = $_SESSION['Chrome'];
}elseif (strpos($visitorInfo,'Firefox')){
    $browser = "Firefox";
    if(!isset($_SESSION['firefox']))
        $_SESSION['firefox']= 0;
    else
        $_SESSION['firefox'] ++;
    $browserCounter = $_SESSION['firefox'];
}elseif (strpos($visitorInfo,'Safari')){
    $browser = "Safari";
    if(!isset($_SESSION['safari']))
        $_SESSION['safari']= 0;
    else
        $_SESSION['safari'] ++;
    $browserCounter = $_SESSION['safari'];
}elseif (strpos($visitorInfo,'Mozilla')){
    $browser = "Mozilla";
    if(!isset($_SESSION['mozilla']))
        $_SESSION['mozilla']= 0;
    else
        $_SESSION['mozilla'] ++;
    $browserCounter = $_SESSION['mozilla'];
}

//determine the OS type
if(strpos($visitorInfo,"Mac")){
    $os= "Mac";
}elseif(stripos($sys, "Linux")){
    $os = "Linux";
}elseif(stripos($sys, "Android")){
    $os = "Android";
}elseif(stripos($sys, "NT")){
    $os = "Windows";
}

?>

<html>
<body>
<form action="visitor.php" method="post">
    What's your name: <input type="text" name="name">
    <input type="submit" name="submit"><br>
</form>
</body>
</html>

<?php
if (isset($_POST['submit'])&& !empty($_POST['name'])&& preg_match("/^[a-zA-Z ]*$/", $_POST["name"])){
    //count current number of views
    $viewsNum = count(file('visitorData.csv'));
    echo "Hello " . $_POST['name'] . ", you are visitor " . $viewsNum . "!" . "<br>"  ;
    echo $browserCounter . " visitors also use " . $browser . "." . "<br>" . "<br>";

    if(($handle = fopen("visitorData.csv", "r+")) !== FALSE) {
        fputcsv($handle, array('ID', 'Name', 'Client', 'OS'));

        //display csv file as HTML table
        echo "<html><body><table>\n\n";
        $file = fopen("visitorData.csv", "r");
        while (($line = fgetcsv($file)) !== false) {
            echo "<tr>";
            foreach ($line as $cell) {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
            echo "</tr>\n";
        }
        fclose($file);
        echo "\n</table></body></html>";

        //using loop to save visitor data
        while (($line = fgetcsv($handle)) !== FALSE) {
            foreach($line as $x => $x_value) {
            }
        }

        $newVisitor = array($viewsNum, $_POST['name'], $browser, $os);
        fputcsv($handle,$newVisitor);
    }
}else{
    echo '<span style="color:red;">Name cannot be empty and must only contain letters.' . "<br>" ;
    echo 'Please enter letters and click submit again.';
}
?>

