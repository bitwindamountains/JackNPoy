<?php
session_start();

if(!isset($_SESSION['playerScore'])){
    $_SESSION['playerScore']=0;
    $_SESSION['computerScore']=0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table{
           border-collapse: collapse;
        }
        th,td{
            border: 2px solid black;
            padding: 10px;
            text-align: center;
            font-size: 30px;
        }
        button{
            font-size: 30px;
        }
        input[type="radio"]{
            width: 25px;
            height: 25px;
        }
        .WL{
            color: white;
        }
        img{
            width:  300px;
            height: 500px;
        }
    </style>
</head>
<body>
    <?php
    $human=0;
    $computer=0;
if(isset($_POST['choices'])){
    if(isset($_POST['play'])){
        $human=$_POST['choices'];
        $computer=rand(1,3);

        if($human == 1 && $computer == 3||
           $human == 2 && $computer == 1||
           $human == 3 && $computer == 2
        ){
            $_SESSION['playerScore']++;
        }
        else if($human == $computer){
            
        }
        else 
           $_SESSION['computerScore']++;
    }
}
if(isset($_POST['quit'])){
    session_unset();
    session_destroy();
    header("Location: jackNpoy.php");
    exit();
}
    ?>
<form action="" method="post">
    <table width="100%">
        <tr>    
            <th colspan="2"><h1>JACK N' POY</h1></th>    
        </tr>
        <tr>
            <th>Player</th>
            <th>Computer</th>
        </tr>
        <tr>
            <td>
                <?php
                if($human == 1){
                    echo "<img src='RPS\Rock.jpg'>";
                }
                else if($human == 2){
                    echo "<img src='RPS\paper.jpg'>";
                }
                else if($human == 3){
                    echo "<img src='RPS\scissor.jpg'>";
                }
                else
                echo "<img src='RPS\Filler.png'>";
                ?>
            </td>
            <td>
            <?php
                if($computer== 1){
                    echo "<img src='RPS\Rock.jpg'>";
                }
                else if($computer == 2){
                    echo "<img src='RPS\paper.jpg'>";
                }
                else if($computer == 3){
                    echo "<img src='RPS\scissor.jpg'>";
                }
                else
                echo "<img src='RPS\Filler.png'>";
                ?>
            </td>
        </tr>
        <tr>
            <td class="WL"
                <?php
            if(isset($_POST['choices'])){
            if(isset($_POST['play'])){
                 if($human == 1 && $computer == 3||
                 $human == 2 && $computer == 1||
                 $human == 3 && $computer == 2
              ){
                 echo "style='background-color: red;'";
              
              }
              else if($human == $computer){
                echo "style='background-color: green;'";
             
              }
              else
              echo "style='background-color: blue;'";
            }}
                ?>
            >
          
            <?php  
            if(isset($_POST['choices'])){
            if(isset($_POST['play'])){
                 if($human == 1 && $computer == 3||
                 $human == 2 && $computer == 1||
                 $human == 3 && $computer == 2
              ){
                 echo "Player WINS!!!";
              }
              else if($human == $computer){
                echo "ITS A TIE!!!";
              }
              else
              echo "Player LOST!!!";
            }}
                ?>

            </td>
            <td class="WL"
    <?php
    if(isset($_POST['choices'])){
    if(isset($_POST['play'])){
        if ($computer == 1 && $human == 3 || 
            $computer == 2 && $human == 1 || 
            $computer == 3 && $human == 2) {
            echo 'style="background-color: red;"';
          
        } elseif ($human == $computer) {
            echo 'style="background-color: green;"';
        
        } else {
            echo 'style="background-color: blue;"';
         
        }
    }}
    ?>
    
>
        <?php
        if(isset($_POST['choices'])){
        if(isset($_POST['play'])){
         if ($computer == 1 && $human == 3 || 
         $computer == 2 && $human == 1 || 
         $computer == 3 && $human == 2) {
            echo "Computer WINS!!!";    
        } elseif ($human == $computer) {
            echo "ITS A TIE!!!";
        } else {
            echo "Computer LOST!!!";
        }
        }}
 
      ?>
</td>
        </tr>
        <tr>
            <td colspan="2">
            <input type="radio" name="choices" value="1">ROCK
            <input type="radio" name="choices" value="2">PAPER
            <input type="radio" name="choices" value="3">SCISSORS    
            </td>
        </tr>
        <tr>
            <td>
                <?php
                echo $_SESSION['playerScore'];
                ?>
            </td>
            <td>
               <?php
                echo $_SESSION['computerScore'];
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" name="play">Play</button>
                <button type="submit" name="quit">Quit</button>
            </td>
        </tr>
    
    </table>
</form>
</body>
</html>
