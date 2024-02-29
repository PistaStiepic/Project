<?php
include 'connect2.php';

// Function to display records using bubble sort
//paramenter: array to be sorted 
//parameters: order either ASC or DECS
function bubble_sort($array, $order) {
    $n = count($array);
    for($i = 0; $i < $n; $i++) {
        for($j = 0; $j < $n - $i - 1; $j++) {
            if($order == "ASC" ? $array[$j]["productDesc"] > $array[$j+1]["productDesc"] :
                $array[$j]["productDesc"] < $array[$j+1]["productDesc"]) {
                $temp = $array[$j];
                $array[$j] = $array[$j+1];
                $array[$j+1] = $temp;
            }
        }
    }
    return $array;
}

// Function to display records using insertion sort
//paramenter: array to be sorted 
//parameters: order either ASC or DECS
function insertion_sort($array, $order) {
    $n = count($array);
    for($i = 1; $i < $n; $i++) {
        $key = $array[$i];
        $j = $i - 1;
        if($order == "ASC") {
            while($j >= 0 && $array[$j]["productDesc"] > $key["productDesc"]) {
                $array[$j+1] = $array[$j];
                $j--;
            }
        } else {
            while($j >= 0 && $array[$j]["productDesc"] < $key["productDesc"]) {
                $array[$j+1] = $array[$j];
                $j--;
            }
        }
        $array[$j+1] = $key;
    }
    return $array;
}

// Function to find a record using linear search
//paramenter: array to be searched 
//parameters: value is search element
function linear_search($array, $value) {
    $n = count($array);
    for($i = 0; $i < $n; $i++) {
        if($array[$i]["productID"] == $value) {
            return $array[$i];
        }
    }
    return null;
}

// Function to find a record using binary search
//paramenter: array to be searched 
//parameters: value is search element
function binary_search($array, $value) {
    $n = count($array);
    $left = 0;
    $right = $n - 1;
    while($left <= $right) {
        $mid = floor(($left + $right) / 2);
        if($array[$mid]["productID"] == $value) {
            return $array[$mid];
        } elseif($array[$mid]["productID"] < $value) {
            $left = $mid + 1;
        } else {
            $right = $mid - 1;
        }
    }
    return null;
}

// Get records from the database
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

// Insert records in array
$goals = array();
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $goals[] = $row;
    }
}

// Check if user selected a sort order in form
$order = isset($_POST["order"]) ? $_POST["order"] : "";

// Check if user selected a search method in form
$search_method = isset($_POST["search_method"]) ? $_POST["search_method"] : "";

// Check if user selected a sort method in form
$sort_method = isset($_POST["sort_method"]) ? $_POST["sort_method"] : "";

// Check if user submitted a search value in form
$search_value = isset($_POST["search_value"]) ? $_POST["search_value"] : "";

// Sort the goals array based on user selected sort order either ASC or DESC
if($order == "ASC" || $order == "DESC") {;
    // use the selected sorting method defined above
    if($sort_method == "bubble") {
        $goals = bubble_sort($goals, $order);
        } elseif($sort_method == "insertion") {
            $goals = insertion_sort($goals, $order);
        }
}

// Find a record based on user selected search method and value
$found_goal = null;
if($search_method == "linear") {
    //call linear_search method and get search element 
$found_goal = linear_search($goals, $search_value);
} elseif($search_method == "binary") {
    //call linear_search method and get search element 
$found_goal = binary_search($goals, $search_value);
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <link rel="stylesheet" href="goals.css" />
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="http://cdn.rawgit.com/necolas/normalize.css/master/normalize.css">
    <link rel="stylesheet" href="http://cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
    <title>Task</title>
</head>
<body>
    <h1>Example Search/Sort</h1>
    <form method="post">
        <label for="order">Sort by:</label>
        <select name="order" id="order">
            <option value="">--Select order--</option>
            <option value="ASC">Ascending</option>
            <option value="DESC">Descending</option>
        </select>
        <br><br>
        <label for="sort_method">Sort method:</label>
        <select name="sort_method" id="sort_method">
            <option value="">--Select Sort method--</option>
            <option value="bubble">Bubble Sort</option>
            <option value="insertion">Inserion Sort</option>
        </select>
        <br><br>
        <label for="search_method">Search method:</label>
        <select name="search_method" id="search_method">
            <option value="">--Select method--</option>
            <option value="linear">Linear search</option>
            <option value="binary">Binary search</option>
        </select>
        <br><br>
        <label for="search_value">Search value:</label>
        <input type="text" name="search_value" id="search_value">
        <br><br>
        <input type="submit" value="Submit">
    </form>
    <br>
    <?php if(!empty($goals)): ?>
        <table left:80px>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Category</th>
                <th>Product Image</th>
                <th>Product Price</th>
            </tr>
            <?php foreach($goals as $goal): ?>
                <tr>
                    <td><?php echo $goal["productID"]; ?></td>
                    <td><?php echo $goal["productName"]; ?></td>
                    <td><?php echo $goal["productDesc"]; ?></td>
                   
                    <?php echo "<td>" . "<img src=".$goal["productImg"].' width=100px height="100px">' . "</td>"?>;
                    
                

<td><?php echo $goal["productPrice"]; ?></td>

                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <br>
    <?php if($found_goal !== null): ?>
        <!-- Return the goal text field of the recod find -->
        <p>Goal found: <?php echo $found_goal["productName"]; ?></p>
    <?php endif; ?>
</body>
</html>