<?php
/** @var mysqli $connection */
require "connection.php";
require_once "functions.php";
session_start();

if(isset($_GET['pet_id']) && isset($_GET['requester_id']) && isset($_GET['adoption_id'])){
    $pet_id = $_GET['pet_id'];
    $requester_id = $_GET['requester_id'];
    $adoption_id = $_GET['adoption_id'];

    $pet_sql = "SELECT added_by FROM pets WHERE id='$pet_id'";
    $pet_result = mysqli_query($connection, $pet_sql);
    if (!$pet_result || mysqli_num_rows($pet_result) === 0) {
        die("Error in pet SQL query or no results: " . mysqli_error($connection));
    }
    $addedBy = mysqli_fetch_assoc($pet_result)['added_by'];

    $requester_sql = "SELECT email FROM users WHERE id='$requester_id'";
    $requester_result = mysqli_query($connection, $requester_sql);
    if (!$requester_result || mysqli_num_rows($requester_result) === 0) {
        die("Error in requester SQL query or no results: " . mysqli_error($connection));
    }
    $requesterEmail = mysqli_fetch_assoc($requester_result)['email'];

    if($addedBy == 1){
        $subject = 'Approval Email';
        $body = "<h2>Your request for adoption has been approved!</h2>
                         <p>You can come to take the pet within this week.</p>
                         <p>For further information or if you have any question don't hesitate to ask as replying this email.</p>
                         <p>Thank you for your adoption!</p>";

        if(!sendEmail($requesterEmail, $subject, $body)) {
            echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
            require 'links.php';
            echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
            require 'admin-navbar.php';
            echo "<div class='errors show'>
                      <p>Something went wrong sending email.</p>
                   </div>
                   </body>
                   </html>";
        } else {
            echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
            require 'links.php';
            echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
            require 'admin-navbar.php';
            echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
                      <p style='color: antiquewhite;'>Email sent successfully.</p>
                  </div>
                  </body>
                  </html>";
            $adoptionDate = date("Y-m-d H:i:s", time());
            $sql = "UPDATE adoptions SET adoption_date='$adoptionDate' WHERE id='$adoption_id'";
            if(!mysqli_query($connection, $sql)) {
                echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
                require 'links.php';
                echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
                require 'admin-navbar.php';
                echo "<div class='errors show'>
                          <p>Database error occurred!</p>
                      </div>
                      </body>
                      </html>";
            }
        }
    } else {
        $sql = "SELECT email, name, surname FROM users WHERE id='$addedBy'";
        if($result = mysqli_query($connection, $sql)){
            if(mysqli_num_rows($result) > 0) {
                $adder = mysqli_fetch_assoc($result);
                $subject = 'Approval Email';
                $body = "<h2>Your request for adoption has been approved!</h2>
                                 <p>The pet you are adopting is " . htmlspecialchars($adder['name']) . " " . htmlspecialchars($adder['surname']) . "'s.</p>
                                 <p>To take the pet home you should contact <a href='mailto:" . htmlspecialchars($adder['email']) . "'>" . htmlspecialchars($adder['email']) . ".</a></p>
                                 <p>Thank you for your adoption!</p>";

                if (!sendEmail($requesterEmail, $subject, $body)) {
                    echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
                    require 'links.php';
                    echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
                    require 'admin-navbar.php';
                    echo "<div class='errors show'>
                      <p>Something went wrong sending email.</p>
                   </div>
                   </body>
                   </html>";
                } else {
                    echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
                    require 'links.php';
                    echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
                    require 'admin-navbar.php';
                    echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
                      <p style='color: antiquewhite;'>Email sent successfully.</p>
                  </div>
                  </body>
                  </html>";
                    $adoptionDate = date("Y-m-d H:i:s", time());
                    $sql = "UPDATE adoptions SET adoption_date='$adoptionDate' WHERE id='$adoption_id'";
                    if(!mysqli_query($connection, $sql)) {
                        echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
                        require 'links.php';
                        echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
                        require 'admin-navbar.php';
                        echo "<div class='errors show'>
                          <p>Database error occurred!</p>
                      </div>
                      </body>
                      </html>";
                    }
                }
            }
        }
    }

} else if (isset($_GET['pet_id']) && isset($_GET['requester_id']) && isset($_GET['surrender_id']) && isset($_GET['approve'])) {
    $pet_id = $_GET['pet_id'];
    $requester_id = $_GET['requester_id'];
    $surrender_id = $_GET['surrender_id'];
    $approve = $_GET['approve'];

    $requester_sql = "SELECT email FROM users WHERE id='$requester_id'";
    $requester_result = mysqli_query($connection, $requester_sql);
    if (!$requester_result || mysqli_num_rows($requester_result) === 0) {
        die("Error in requester SQL query or no results: " . mysqli_error($connection));
    }
    $requesterEmail = mysqli_fetch_assoc($requester_result)['email'];

    $subject = $approve == 1 ? "Approval Email" : "Reject Email";
    $body = "<h2>Your request to surrend your pet has " . ($approve == 1 ? "" : "not") . " been approved!</h2>
             <p>For further information or if you have any question don't hesitate to ask as replying this email.</p>";

    if(!sendEmail($requesterEmail, $subject, $body)) {
        echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
        require 'links.php';
        echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
        require 'admin-navbar.php';
        echo "<div class='errors show'>
                      <p>Something went wrong sending email.</p>
                   </div>
                   </body>
                   </html>";
    } else {
        echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
        require 'links.php';
        echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
        require 'admin-navbar.php';
        echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
                      <p style='color: antiquewhite;'>Email sent successfully.</p>
                  </div>
                  </body>
                  </html>";

        if($approve == 1) {
            $sql = "UPDATE pets SET added_by=1 WHERE id='$pet_id'";
            if(!mysqli_query($connection, $sql)) {
                echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
                require 'links.php';
                echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
                require 'admin-navbar.php';
                echo "<div class='errors show'>
                          <p>Database error occurred!</p>
                      </div>
                      </body>
                      </html>";
            }
            $surrenderedAt = date("Y-m-d H:i:s", time());
            $sql = "UPDATE surrendered_pets SET surrendered_at='$surrenderedAt' WHERE id='$surrender_id'";
            if(!mysqli_query($connection, $sql)) {
                echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
                require 'links.php';
                echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
                require 'admin-navbar.php';
                echo "<div class='errors show'>
                          <p>Database error occurred!</p>
                      </div>
                      </body>
                      </html>";
            }
        }
    }
}