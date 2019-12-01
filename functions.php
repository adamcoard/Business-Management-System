<?php

if(isset($_REQUEST['func_type']) && !empty($_REQUEST['func_type']))
{
	switch($_REQUEST['func_type'])
	{
		case 'get_calender_full':
			get_calender_full($_REQUEST['year'],$_REQUEST['month']);
			break;
		case 'get_projects_information':
			get_projects_information($_REQUEST['date']);
			break;
		case 'add_project_information':
			add_project_information($_REQUEST['date'],$_REQUEST['title'],$_REQUEST['cost']);
			break;
		default:
			break;
	}
}

// Get calendar in html
function get_calender_full($year = '', $month = '')
{
	$date_Year = ($year != '')?$year:date("Y");
	$date_Month = ($month != '')?$month:date("m");
	$date = $date_Year.'-'.$date_Month.'-01';
	$current_Month_First_Day = date("N",strtotime($date));
	$total_Days_ofMonth = cal_days_in_month(CAL_GREGORIAN,$date_Month,$date_Year);
	$total_Days_ofMonthDisplay = ($current_Month_First_Day == 7)?($total_Days_ofMonth):($total_Days_ofMonth + $current_Month_First_Day);
	$boxDisplay = ($total_Days_ofMonthDisplay <= 35)?35:42;
	
?>

 	<div id = "calender_section">
		<h2>
        	<a href = "javascript:void(0);" onclick = "get_calendar_data('calendar_div','<?php echo date("Y",strtotime($date.' - 1 Month')); ?>','<?php echo date("m",strtotime($date.' - 1 Month')); ?>');">&lt;</a>
            <select name = "month_dropdown" class = "month_dropdown dropdown"><?php echo get_all_months__of_year($date_Month); ?></select>
			<select name = "year_dropdown" class = "year_dropdown dropdown"><?php echo get_year($date_Year); ?></select>
            <a href = "javascript:void(0);" onclick = "get_calendar_data('calendar_div','<?php echo date("Y",strtotime($date.' + 1 Month')); ?>','<?php echo date("m",strtotime($date.' + 1 Month')); ?>');">&gt;</a>
        </h2>
		
		<!-- List projects -->
		<div id="project_list" class="modal"></div>

        <!-- Add project -->
		<div id = "project_add" class = "modal">
		  	<div class = "modal-content">
		    	<span class = "close"><a href = "#" onclick = "close_popup('project_add')">×</a></span>
		    	<p>Add project due <span id = "projectDateView"></span></p>
		    	<br/>
		        <p><b>Project Title: </b><input type = "text" id = "projectTitle" value = ""/></p>
		        <br/>
		        <p><b>Project Cost: </b><input type = "number" id = "projectCost" value = ""/></p>
		        <input type = "hidden" id = "projectDate" value = ""/>
		        <br/>
		        <input type = "button" id = "add_project_informationBtn" value = "Add"/>
		  	</div>
		</div>

        <div id = "calender_section_top">
			<ul>
				<li>Sunday</li>
				<li>Monday</li>
				<li>Tuesday</li>
				<li>Wednesday</li>
				<li>Thursday</li>
				<li>Friday</li>
				<li>Saturday</li>
			</ul>
		</div>
		
		<div id = "calender_section_bot">
			<ul>
			<?php 
			
				// View add and number of projects
				$dayCount = 1;
				
				for($cb=1; $cb<=$boxDisplay; $cb++)
				{
					if(($cb >= $current_Month_First_Day+1 || $current_Month_First_Day == 7) && $cb <= ($total_Days_ofMonthDisplay))
					{
						// Get current date
						$currentDate = $date_Year.'-'.$date_Month.'-'.$dayCount;
						$projectNum = 0;
							
						// Include database connection file
						require_once('connection.php');
						
						// Get number of projects on current date
						$result = $db->query("SELECT title FROM projects WHERE date = '".$currentDate."'");
						$projectNum = $result->num_rows;
						
						// Define cell color
						if(strtotime($currentDate) == strtotime(date("Y-m-d")))
						{
							echo '<li date="'.$currentDate.'" class="grey date_cell">';
						}
						elseif($projectNum > 0)
						{
							echo '<li date="'.$currentDate.'" class="pinkish date_cell">';
						}
						else
						{
							echo '<li date="'.$currentDate.'" class="date_cell">';
						}
						
						// Date cell
						echo '<span>';
						echo $dayCount;
						echo '</span>';
						
						// Project popup
						echo '<div id="date_popup_'.$currentDate.'" class="date_popup_wrap none">';
						echo '<div class="date_window">';
						echo '<div class="popup_project">Projects ('.$projectNum.')</div>';
						echo ($projectNum > 0)?'<a href="javascript:;" onclick="get_projects_information(\''.$currentDate.'\');">View Projects</a><br/>':'';
						
						// Add project
						echo '<a href="javascript:;" onclick="add_project_information(\''.$currentDate.'\');">Add Project</a>';
						echo '</div></div>';
						echo '</li>';
						
						$dayCount++;
			?>
			<?php }else{ ?>
				<li><span>&nbsp;</span></li>
			<?php } } ?>
			</ul>
		</div>
	</div>

	<script type = "text/javascript">
	
	//	Ajax code to get project details from database
		function get_calendar_data(target_div, year, month)
		{
			$.ajax({
				type:'POST',
				url:'functions.php',
				data:'func_type=get_calender_full&year='+year+'&month='+month,
				success:function(html)
				{
					$('#'+target_div).html(html);
				}
			});
		}
		
		function get_projects_information(date)
		{
			$.ajax({
				type:'POST',
				url:'functions.php',
				data:'func_type=get_projects_information&date='+date,
				success:function(html)
				{
					$('#project_list').html(html);
					$('#project_add').slideUp('slow');
					$('#project_list').slideDown('slow');
				}
			});
		}
		
		// Add project information to database
		function add_project_information(date, cost)
		{
			$('#projectDate').val(date);
			$('#projectCost').val(cost);
			$('#projectDateView').html(date);
			$('#project_list').slideUp('slow');
			$('#project_add').slideDown('slow');
		}
		
		// Save project information into database
		$(document).ready(function()
		{
			$('#add_project_informationBtn').on('click',function()
			{
				var date = $('#projectDate').val();
				var title = $('#projectTitle').val();
				var cost = $('#projectCost').val();
				$.ajax({
					type:'POST',
					url:'functions.php',
					data:'func_type=add_project_information&date='+date+'&title='+title+'&cost='+cost,
					success:function(msg)
					{
						if(msg == 'ok')
						{
							var dateSplit = date.split("-");
							$('#projectTitle').val('');
							$('#projectCost').val('');
							alert('Project has been added');
							get_calendar_data('calendar_div', dateSplit[0], dateSplit[1]);
						}
						else
						{
							alert('ERROR: Invalid input.');
						}
					}
				});
			});
		});
		
		$(document).ready(function()
		{
			$('.date_cell').mouseenter(function()
			{
				date = $(this).attr('date');
				$(".date_popup_wrap").fadeOut();
				$("#date_popup_"+date).fadeIn();	
			});
			
			$('.date_cell').mouseleave(function()
			{
				$(".date_popup_wrap").fadeOut();		
			});
			
			$('.month_dropdown').on('change', function()
			{
				get_calendar_data('calendar_div',$('.year_dropdown').val(),$('.month_dropdown').val());
			});
			
			$('.year_dropdown').on('change', function()
			{
				get_calendar_data('calendar_div',$('.year_dropdown').val(),$('.month_dropdown').val());
			});
			
			$(document).click(function()
			{
				$('#project_list').slideUp('slow');
			});
		});
		
		// Close popup	
		function close_popup(project_id)
		{
			$('#'+project_id).css('display', 'none');
		}
	</script>
<?php
}

// Get all months
function get_all_months__of_year($selected = '')
{
	$options = '';
	
	for($i=1; $i<=12; $i++)
	{
		$value = ($i < 01)?'0'.$i:$i;
		$selectedOpt = ($value == $selected)?'selected':'';
		$options .= '<option value="'.$value.'" '.$selectedOpt.' >'.date("F", mktime(0, 0, 0, $i+1, 0, 0)).'</option>';
	}
	
	return $options;
}

// Get all years
function get_year($selected = '')
{
	$options = '';
	
	for($i=2015; $i<=2025; $i++)
	{
		$selectedOpt = ($i == $selected)?'selected':'';
		$options .= '<option value="'.$i.'" '.$selectedOpt.' >'.$i.'</option>';
	}
	
	return $options;
}

// Display project information
function get_projects_information($date = '')
{

	include 'connection.php';
	
	$projectListHTML = '';
	$date = $date?$date:date("Y-m-d");
	
	//Get projects based on the current date
	$result = $db->query("SELECT title FROM projects WHERE date = '".$date."'");
	
	if($result->num_rows > 0)
	{
		$projectListHTML .= '<div class="modal-content">';
		$projectListHTML .= '<span class="close"><a href="#" onclick="close_popup("project_list")">×</a></span>';
		$projectListHTML .= '<h2>Projects due '.date("l, d M Y",strtotime($date)).'</h2>';
		$projectListHTML .= '<ul>';
		
		while($row = $result->fetch_assoc())
		{ 
            $projectListHTML .= '<li>'.$row['title'].'</li>';
        }
        
		$projectListHTML .= '</ul>';
		$projectListHTML .= '</div>';
	}
	
	echo $projectListHTML;
}

// Add project information
function add_project_information($date, $title, $cost)
{
	
	include 'connection.php';
	
	$currentDate = date("Y-m-d H:i:s");
	
	// Insert the project data into database
	$insert = $db->query("INSERT INTO projects (title,date,cost) VALUES ('".$title."','".$date."','".$cost."')");
	
	if($insert)
	{
		echo 'ok';
	}
	else
	{
		echo 'err';
	}
}
?>