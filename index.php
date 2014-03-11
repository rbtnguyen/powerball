<?php
include('connection.php');
session_start();
?>
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset="utf-8"> 
	<meta name="author" content="Binh Nguyen">
    <meta name="description" content="California SuperLotto Plus Lottery Simulator">
	<title>POWERBALL Simulator</title>
	<link rel="stylesheet" type="text/css" href="lotto.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script>

	</script>

</head>
<body>
	<div class='container'>
		<h1 class="tlt">POWERBALL Simulator</h1>
		<div>
			<table class='prize-structure styled'>
				<thead>
					<tr>
						<th>Prize Level</th>
						<th>Prize</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Match 0 plus Powerball</td>
						<td>$4</td>
					</tr>
					<tr>
						<td>Match 1 plus Powerball</td>
						<td>$4</td>
					</tr>
					<tr>
						<td>Match 2 plus Powerball</td>
						<td>$7</td>
					</tr>
					<tr>
						<td>Match 3</td>
						<td>$7</td>
					</tr>
					<tr>
						<td>Match 3 plus Powerball</td>
						<td>$100</td>
					</tr>
					<tr>
						<td>Match 4</td>
						<td>$100</td>
					</tr>
					<tr>
						<td>Match 4 plus Powerball</td>
						<td>$10,000</td>
					</tr>
					<tr>
						<td>Match 5</td>
						<td>$1,000,000</td>
					</tr>
					<tr>
						<td>Match 5 plus Powerball</td>
						<td>$122,340,000</td>
					</tr>
				</tbody>
			</table>
			<div class='overlay'>
				<div class='inner-overlay'>
					<p>Jackpot amount based on average powerball jackpot. <a target="blank" href="http://www.lottostrategies.com/script/jackpot_history/draw_date/101">Source</a></p>
				</div>
			</div>
		</div>
		<div class='div-form'>
		
			<p>Pick 5 lucky numbers or <button class='randomize'>Randomize</button></p>

			<form method='post' action='process.php'>

				<input type='hidden' name='action' value='register'>
				<table class='numbers-table'>
					<?php for($row=0; $row<= 59; $row+= 10) { ?>
						<tr>

							<?php for($cell=1; $cell<=10; $cell++){
								if(($cell+$row)<=59){
							?>
							<td>
							<input name='lucky_numbers[]' class='regular-checkbox' id='<?= $row+$cell ?>' type='checkbox' value='<?= $row+$cell ?> '></input>
						<div class='label'><label for='<?= $row+$cell ?>'><?= $row+$cell ?></label></div>
							</td>

							<?php	} } ?>
						
						</tr>
					<?php } ?>
				</table>
					<section  id='mega-select'>
						<p>Pick Powerball Number</p>
						<select name='mega_number'>
							<?php for($number=1; $number<= 35; $number++) { ?>
							<option value='<?= $number ?>'><?= $number ?></option>
							<?php } ?>
						</select>
					</section>
					<section id='times-select'>
						<p>Pick Number of Simulations</p>
						<select name='tickets'>
							<option value='1'>1</option>
							<option value='104'>104 (Twice a Week for 1 Year)</option>
							<option value='1040' selected>1,040 (Twice a Week for 10 yYears)</option>
							<option value='5200'>5,200 (Twice a Week for 50 Years)</option>
							<option value='10000'>10,000 (≈ 20 Tickets a Week for 10 Years)</option>
						</select>
					</section>
		
				<input type='submit' value='PLAY' class='submit'>
				<div class='clear'></div>
			</form>
		</div><!-- end of .div-form -->
	<div class='clear'></div>

	<div class="details">
		<?php 

			if(isset($_SESSION['errors']))
			{  
				echo "<p style='color:red;'>".$_SESSION['errors']."</p>";
			}
		 
		  if(isset($_SESSION['total_lotto_data'])){   

		  		if($_SESSION['dollars_spent'] != 2)
	  			{
	  				if(($_SESSION['max_prize']*$_SESSION['max_prize_freq']*2) < $_SESSION['dollars_spent'])
			  		{
		  				$only1 = 'only';

			  			switch($_SESSION['max_prize_freq'])
				  		{
				  			case 1: $times = 'once'; $only2 ='only'; break;
				  			case 2: $times = 'twice'; $only2 ='only'; break;
				  			default: $times = $_SESSION['max_prize_freq']." times"; $only2 =''; break;
				  		}	
			  		}
			  		else
			  		{
			  			$only1 = '';
			  			$only2 ='';
			  			$times = $_SESSION['max_prize_freq']." times";
			  		}

			  		echo "You just played ".($_SESSION['dollars_spent'] / 2). " times. The largest prize you ever won was ".$only1." $".$_SESSION['max_prize']." and you ".$only2." won this amount ".$times."!";
	  			}
	  			else
	  			{
	  				echo "Thanks for playing!";
	  			}
		  
		 ?>
		 <p>
		 	<?php 
		 		$query = fetch_all("SELECT SUM(prize_money), SUM(money_spent), SUM(max_prize) FROM results");
				$percentage = 100*$query[0]['SUM(prize_money)']/ $query[0]['SUM(money_spent)'];
				echo 'Out of the $'.$query[0]['SUM(money_spent)'].' spent on tickets, players have won back $'.$query[0]['SUM(prize_money)']." or ".round($percentage)."% of their money. <br>";

				if($query[0]['SUM(max_prize)'] == 0)
				{
					echo '<br />No one has won the jackpot yet!';
				}
				elseif ($query[0]['SUM(max_prize)'] == 1) {
					echo '<br />The jackpot has been won once!';
				}
				else{
					echo '<br />The jackpot has been won '.$query[0]['SUM(max_prize)'].' times!';
				}

				
		 	?>
		 </p>

			<table id='summary' class='styled'>
				<tr>
					<td>
						Your Numbers:
					</td>
					<td> 
						<?php 
							foreach($_SESSION['lucky_numbers'] as $one_lucky_number)
							{
								echo "<span class='white-ball'>".$one_lucky_number."</span> ";
							}
						?>
						<?php echo "<span class='red-ball'>".$_SESSION['mega_number']."</span>"; ?>
					</td>
				</tr>
				<tr>
					<td>
						Total Prize Money Won:
					</td>
					<td>
						<?php echo "$".$_SESSION['total_prize']; ?>
					</td>
				</tr>
				<tr>
					<td>
						Total Money Spent:
					</td>
					<td>
						<?php echo "$".$_SESSION['dollars_spent']; ?>
					</td>
				</tr>
				<tr>
					<td>
						# of Prizes Won:
					</td>
					<td>	
						<?php echo " ".$_SESSION['prizes_won']; ?>
					</td>
					
				</tr>
			</table>
			<table class='lotto-table styled'>
				<tr>
					<td>
						Lotto Numbers:
					</td>
					<td>
						Matching Numbers:
					</td>
					<td>
						# of Matches:
					</td>
					<td>
						Prize:
					</td>
					
				</tr>
			<?php 

			 foreach($_SESSION['total_lotto_data'] as $one_draw_data) {


			 	if($one_draw_data['prize'] != 0)
			 	{
			 		echo "<tr class='winning-row'>";
			 	}
			 	else
			 	{
			 		echo "<tr class='row'>";
			 	}
			  ?>
				
	
					<td>
					<?php foreach($one_draw_data['numbers_drawn'] as $one_number)
							{
								echo "<span class='white-ball'>".$one_number."</span> ";
							}
							echo "<span class='red-ball'>".$one_draw_data['mega_number']."</span>";
					?>
					</td>
					<td>
					<?php foreach($one_draw_data['matching_numbers'] as $one_number)
							{
								echo "<span class='white-ball'>".$one_number."</span> ";
							}
						  if($_SESSION['mega_number'] == $one_draw_data['mega_number'])
						  {
						  	echo "+ "."<span class='red-ball'>".$one_draw_data['mega_number']."</span>";
						  }
					?>
					</td>
					<td>
					<?php 
						echo $one_draw_data['matching_numbers_count'];

						  if($_SESSION['mega_number'] == $one_draw_data['mega_number'])
						  {
						  	echo "<b> + Powerball</b>";
						  }
					 ?>
					</td>
					<td>
					<?php 
						if($one_draw_data['prize'] == 0)
						{
							echo "$".$one_draw_data['prize'];
						}
						elseif($one_draw_data['prize'] == 122340000)
						{
							echo "<span class='grand-prize prize'>'$".$one_draw_data['prize']." HOLY $%#@ YOU WON!!!!</span>";
						}
						else
						{
							echo "<span class='small-prize prize'>$".$one_draw_data['prize']."</span>";
						}
					
					 ?>
					</td>
				</tr>
			<?php } ?>
			</table>
		<?php } 

		session_unset();

		?>
		</div>
	</div><!-- end of container -->

	
	<script type="text/javascript">

		$(document).ready(function(){


			$('.randomize').on('click', function(){

				for(var i = 1; i<=59; i++)
					{						
						$('#'+i).prop('checked', false);	
					}

				var arr = []
				while(arr.length < 5){
				  var randomnumber=Math.ceil(Math.random()*59)
				  var found=false;
				  for(var i=0;i<arr.length;i++){
				    if(arr[i]==randomnumber){found=true;break}
				  }
				  if(!found)arr[arr.length]=randomnumber;
				}
				for(var i = 0; i<5; i++)
				{
					$('#'+arr[i]).prop('checked', true);
				}		
			});

			$('.overlay').mouseenter(function(){
		    	$('.inner-overlay').fadeIn();
		        console.log('in');
		    }); 
		     
		    $('.inner-overlay').mouseleave(function(){
		        $(this).fadeOut();
		        console.log('out');
		    });

		 //    $(window).scroll(function () {
   //  			if ($('#summary').offset().top - $(window).scrollTop() <= 0) {
   //      			$('#summary').addClass("stuck");
   //      			$('.lotto-table').addClass("stay");

   //  				console.log("blah");
   //  			} else {
   //      			$('#summary').removeClass("stuck");
   //      			$('.lotto-table').removeClass("stay");

   //  			}
			// });

		   

		});

	</script>

	
</body>
</html>