<?php
// connect to database
require_once "config.php";

// lets assume a user is logged in with id $user_id
$user_id = $_SESSION["id"];

// if user clicks like or dislike button
if (isset($_POST['action'])) {
    $post_id = $_POST['post_id'];
    $action = $_POST['action'];
    switch ($action) {
        case 'like':
            $sql = "INSERT INTO rating_info (user_id, post_id, rating_action) 
         	   VALUES ($user_id, $post_id, 'like') 
         	   ON DUPLICATE KEY UPDATE rating_action='like'";
            break;
        case 'dislike':
            $sql = "INSERT INTO rating_info (user_id, post_id, rating_action) 
               VALUES ($user_id, $post_id, 'dislike') 
         	   ON DUPLICATE KEY UPDATE rating_action='dislike'";
            break;
        case 'unlike':
            $sql = "DELETE FROM rating_info WHERE user_id=$user_id AND post_id=$post_id";
            break;
        case 'undislike':
            $sql = "DELETE FROM rating_info WHERE user_id=$user_id AND post_id=$post_id";
            break;
        default:
            break;
    }

    // execute query to effect changes in the database ...
    mysqli_query($conn, $sql);
    echo getRating($post_id);
    exit(0);
}

// Get total number of likes for a particular post
function getLikes($id)
{
    global $conn;
    $sql = "SELECT COUNT(*) FROM rating_info 
  		  WHERE post_id = $id AND rating_action='like'";
    $rs = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($rs);
    $sql2 = "UPDATE usulan SET jumlah_vote = '$result[0]' WHERE id = $id";
    mysqli_query($conn, $sql2);
    return $result[0];
}