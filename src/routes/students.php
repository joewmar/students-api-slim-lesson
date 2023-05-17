<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

//Get Students Data
$app->get('/api/students', function (Request $request, Response $response) {

    $db = new DB();
    $sql = "SELECT * FROM tblstudents";
    //Connect Database
    $connect = $db->connect();
    //Execute Query
    $result = mysqli_query($connect, $sql);

    //Check number of row
    if (mysqli_num_rows($result) > 0) {
        // Fetch each data (mysqli_fetch_all get assosiative and value array)
        while($row = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
            $response->getBody()->write(json_encode($row));
        }
    } 
    else {
        $response->getBody()->write("0 results");
    }
    mysqli_free_result($result);
    $db->closeConnection($connect);
    return $response->withHeader('Content-Type', 'application/json');
});

// Get Single Student
$app->get('/api/students/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');   

    $db = new DB();
    $sql = "SELECT * FROM tblstudents WHERE id = $id";
    //Connect Database
    $connect = $db->connect();
    //Execute Query
    $result = mysqli_query($connect, $sql);

    //Check number of row
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $response->getBody()->write(json_encode($row));
    } 
    else {
        $response->getBody()->write("0 results");
    }
    mysqli_free_result($result);
    $db->closeConnection($connect);
    return $response->withHeader('Content-Type', 'application/json');
});

// Add Student
$app->post('/api/students/add', function (Request $request, Response $response) {
    //Get All Data of JSON from request users
    $data = $request->getBody();
    $value = json_decode($data, TRUE); // Convert to JSON

    $db = new DB();
    $sql = "INSERT INTO tblstudents (firstname, lastname, birthday, address, course, year, email, phoneno) VALUES (?,?,?,?,?,?,?,?)";

    //Connect Database
    $connect = $db->connect();
    
    //Execute Query
    if($stmt = mysqli_prepare($connect, $sql)){
        mysqli_stmt_bind_param($stmt, "sssssiss", $first_name, $last_name, $birthday, $address, $course, $year, $email, $phone_no);
        
        $first_name = $value['firstname'];  
        $last_name = $value['lastname']; 
        $birthday =  $value['birthday'];    
        $address =  $value['address'];    
        $course =  $value['course'];    
        $year = $value['year']; 
        $email = $value['email'];   
        $phone_no =  $value['phoneno'];  
        mysqli_stmt_execute($stmt);
        $response->getBody()->write("Record Added Successfully");
    }
    else{
        $response->getBody()->write("Error: Could not prepare query");
    }
    
    mysqli_stmt_close($stmt);
    $db->closeConnection($connect);
    return $response->withHeader('Content-Type', 'application/json');
});

// Update Student
$app->put('/api/students/update/{id}', function (Request $request, Response $response) {
    $sql = null;

    $id = $request->getAttribute('id');   
    $data = $request->getBody();
    $value = json_decode($data, TRUE);

    //Execute Query
    if(!(empty($id))){
        $first_name = $value['firstname'];  
        $last_name = $value['lastname']; 
        $birthday =  $value['birthday'];    
        $address =  $value['address'];    
        $course =  $value['course'];    
        $year = $value['year']; 
        $email = $value['email'];   
        $phone_no =  $value['phoneno'];  
        $sql = "UPDATE tblstudents SET firstname = '$first_name', lastname = '$last_name', birthday = '$birthday', address = '$address', course = '$course', year = '$year', email = '$email', phoneno = '$phone_no' WHERE id = " . $id;
    }
    else{
        die("Error: ID not Define");
    }

    $db = new DB();

    //Connect Database
    $connect = $db->connect();

    if (mysqli_query($connect, $sql)) {
        $response->getBody()->write("Record Update Successfully");
    } 
    else {
        $response->getBody()->write("Error: Update Record");
    }

    $db->closeConnection($connect);
    return $response->withHeader('Content-Type', 'application/json');
});

// Delete Student
$app->delete('/api/students/delete/{id}', function (Request $request, Response $response) {
    $sql = null;

    $id = $request->getAttribute('id');   
    $data = $request->getBody();
    $value = json_decode($data, TRUE);

    //Execute Query
    if(!(empty($id))){
        $first_name = $value['firstname'];  
        $last_name = $value['lastname']; 
        $birthday =  $value['birthday'];    
        $address =  $value['address'];    
        $course =  $value['course'];    
        $year = $value['year']; 
        $email = $value['email'];   
        $phone_no =  $value['phoneno'];  
        $sql = "DELETE FROM tblstudents WHERE id = " . $id;
    }
    else{
        die("Error: ID not Define");
    }

    $db = new DB();

    //Connect Database
    $connect = $db->connect();

    if (mysqli_query($connect, $sql)) {
        $response->getBody()->write("Record Delete Successfully");
    } 
    else {
        $response->getBody()->write("Error: Delete Record");
    }

    $db->closeConnection($connect);
    return $response->withHeader('Content-Type', 'application/json');
});
