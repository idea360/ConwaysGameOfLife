<?php
	class Grid{
		public $length;
		public $grid = Array();
		public $width = 30;
		public $height = 12;
		
		function __construct($fileName){
			$this->createGrid($fileName);
		}
		
		function createGrid ($fileName){
			$handle = fopen($fileName, "r");
			if($handle){
				$lines = file($fileName);
				foreach ($lines as $line_num => $line){
					$this->grid[] = str_split($line);
				}
    			fclose($handle);
			}
			else{
				fclose($handle);
				return 1;
			}
			return 0;
		}
		
		function display(){
			echo "<table>";
			for($i = 0; $i < $this->height; $i++){
				echo "<tr>";
				for($j = 0; $j < $this->width; $j++){
					echo "<td>" . $this->grid[$i][$j] . "</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
		}
	}// End Class Grid
	
	class GOL extends Grid{
		public $level = 0;
		
		function __construct($fileName){
			parent::__construct($fileName);
			//display the orginal board!
			$this->display();
			$this->playGame();
		}
		
		private function playGame(){
			//go through the 2-d array
			//If the value is an 'X' then check it against the 4 rules!
			
			//Display the level we're on!
			echo "<h4> Level: " . $this->level . "<h4>";
			while ($this->level < 100){
				for($i = 0; $i < $this->height; $i++){
					for($j = 0; $j < $this->width; $j++){
						if($this->grid[$i][$j] == 'X' || $this->grid[$i][$j] == '.'){
							$this->rulesAlive($i, $j);
						}
					}
				}
				//Add 1 to level and sleep for 1 second before calling playGame again!
				$this->level++;
				$this->display();
				sleep(1);
				//DIE(); //Debug!
				$this->playGame();			
			}
		}
		private function rulesAlive($row, $column){
			/*
			 * 1. Any live cell with fewer than two live neighbours dies, as if caused by under-population.
			 * 2. Any live cell with two or three live neighbours lives on to the next generation.
			 * 3. Any live cell with more than three live neighbours dies, as if by overcrowding.
			 * 4. Any dead cell with exactly three live neighbours becomes a live cell, as if by reproduction.
			 */
			
			$upperRow = $row - 1;
			$lowerRow = $row + 1;
			$leftColumn = $column - 1;
			$rightColumn = $column + 1;
			$numAliveNeighbors = 0;
			
			//First let's count how many people are alive around us!
			//We need 8 if statements!
			//I don't like this many chained if statements, need to refactor this!
			
			//Start with the top rows! Left -> right
			if ($this->grid[$upperRow][$leftColumn] == 'X'){
				$numAliveNeighbors++;
			}
			if ($this->grid[$upperRow][$column] == 'X'){
				$numAliveNeighbors++;
			}
			if($this->grid[$upperRow][$rightColumn] == 'X'){
				$numAliveNeighbors++;
			}
			//Now let's check directly to left and the right!
			if ($this->grid[$row][$leftColumn] == 'X'){
				$numAliveNeighbors++;
			}
			if($this->grid[$row][$rightColumn] == 'X'){
				$numAliveNeighbors++;
			}
			//Now let's check along the bottom rows! Left -> right
			if ($this->grid[$lowerRow][$leftColumn] == 'X'){
				$numAliveNeighbors++;
			}
			if ($this->grid[$lowerRow][$column] == 'X'){
				$numAliveNeighbors++;
			}
			if ($this->grid[$lowerRow][$rightColumn] == 'X'){
				$numAliveNeighbors++;
			}
			
			//Any live cell with fewer than two live neighbours dies, as if caused by under-population.
			if($this->grid[$row][$column] == 'X' && $numAliveNeighbors < 2){
				$this->grid[$row][$column] = '.';
			}
			//Any live cell with two or three live neighbours lives on to the next generation.
			if($this->grid[$row][$column] == 'X' && ($numAliveNeighbors == 2 || $numAliveNeighbors == 3) ){
				
			}
			//Any live cell with more than three live neighbours dies, as if by overcrowding.
			if($this->grid[$row][$column] == 'X' && $numAliveNeighbors > 3){
				$this->grid[$row][$column] = '.';
			}
			//Any dead cell with exactly three live neighbours becomes a live cell, as if by reproduction.
			if ($this->grid[$row][$column] == '.' && $numAliveNeighbors == 3){
				$this->grid[$row][$column] = 'X';
			}
		}//End rulesAlive
	}//End GOL
?>

<!DOCTYPE html>
<html> 
<head>
	<meta charset='UTF-8'>
	<title>Conway's Game of Life</title>
	<style type="text/css">
		table {
    		color: white;
    		background-color: black;
    		margin-top: 10px;
     	}
     	h1 {
     		color: green;
     	}
     	p {
     		margin: 0;
     	}
  	</style>
</head>
<body>
<h1>Conway's Game of Life</h1>
<?php 
	$myGrid = new GOL('example02.txt');
	$myGrid->display();
?>
<h3>Interation <?= $myGrid->level ?></h3>
</body>
</html>