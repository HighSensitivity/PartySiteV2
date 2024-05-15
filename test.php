<?php
ini_set("display_errors", "off");

// Event Directory
$eventsDir = 'events';
$eventsPath = __DIR__ . '/' . $eventsDir;

// Create the events directory if it doesn't exist
if (!file_exists($eventsPath)) {
    mkdir($eventsPath, 0755, true);
}

// Initialize events array
$events = [];

// Sanitize user inputs
$eventName = !empty($_POST['eventName']) ? htmlspecialchars($_POST['eventName'], ENT_QUOTES) : '';
$eventDate = !empty($_POST['eventDate']) ? htmlspecialchars($_POST['eventDate'], ENT_QUOTES) : '';
$eventTime = !empty($_POST['eventTime']) ? htmlspecialchars($_POST['eventTime'], ENT_QUOTES) : '';
$eventVenue = !empty($_POST['eventVenue']) ? htmlspecialchars($_POST['eventVenue'], ENT_QUOTES) : '';

// Basic input validation
if (!empty($eventName) && !empty($eventDate) && !empty($eventTime) && !empty($eventVenue)) {
    $eventData = [
        'Name' => $eventName,
        'Date' => $eventDate,
        'Time' => $eventTime,
        'Venue' => $eventVenue
    ];

    // Generate the file name with the specified format
    $latestEventFile = glob($eventsPath . '/event_*.json');
    $latestEventNumber = count($latestEventFile) + 1;
    $eventFileName = $eventsPath . '/event_' . $latestEventNumber . '.json';

    // Write the event data to a JSON file
    $jsonData = json_encode($eventData, JSON_PRETTY_PRINT);
    file_put_contents($eventFileName, $jsonData);
}

// Load existing events
$files = glob($eventsPath . '/event_*.json');
foreach ($files as $file) {
    $jsonData = file_get_contents($file);
    $events[] = json_decode($jsonData, true);
}

// Sort events by date and time
usort($events, function ($a, $b) {
    $dateA = strtotime($a['Date'] . ' ' . $a['Time']);
    $dateB = strtotime($b['Date'] . ' ' . $b['Time']);
    return $dateA - $dateB;
});

// Process form submission for editing event
if (isset($_POST['editEventName']) && isset($_POST['editEventDate']) && isset($_POST['editEventTime']) && isset($_POST['editEventVenue']) && isset($_POST['eventId'])) {
    $editEventName = htmlspecialchars($_POST['editEventName'], ENT_QUOTES);
    $editEventDate = htmlspecialchars($_POST['editEventDate'], ENT_QUOTES);
    $editEventTime = htmlspecialchars($_POST['editEventTime'], ENT_QUOTES);
    $editEventVenue = htmlspecialchars($_POST['editEventVenue'], ENT_QUOTES);
    $eventId = $_POST['eventId'];

    // Update the event data
    $eventFileName = $eventsPath . '/event_' . $eventId . '.json';
    if (file_exists($eventFileName)) {
        $eventData = json_decode(file_get_contents($eventFileName), true);

        // Update the event data
        $eventData['Name'] = $editEventName;
        $eventData['Date'] = $editEventDate;
        $eventData['Time'] = $editEventTime;
        $eventData['Venue'] = $editEventVenue;

        // Write the updated event data to the JSON file
        $jsonData = json_encode($eventData, JSON_PRETTY_PRINT);
        file_put_contents($eventFileName, $jsonData);
    }
}


// Process form submission for deleting event
if (isset($_POST['deleteEventId'])) {
    $deleteEventId = $_POST['deleteEventId'];

    // Find the corresponding event file and delete it
    $eventFileName = $eventsPath . '/event_' . $deleteEventId . '.json';
    if (file_exists($eventFileName)) {
        unlink($eventFileName);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">

    <!-- CSS 
    ========================= -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="css/plugins.css">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Animate On Scroll CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
	
	<!-- Main Style CSS -->
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,700,800" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
</head>

<body>
    <!-- Main Wrapper Start -->
    <div class="main-wrapper home-4">
        <!-- header-area start -->
        <div class="header-area">
            <!-- header-top start -->
            <div class="header-top text-center">
                <div class="col-12 header-top-three ">
                    <!-- logo start -->
                    <div class="logo ">
                        <a href="#"><img src="images/logo.png" alt=""></a>
                    </div>

                    <!-- logo end -->
                </div>
            </div>

            <!-- Header-top end -->
            <!-- Header-bottom start -->
            <div class="header-bottom-area header-sticky">
                <div class="container-fluid">
                    <div class="row">
                        <!-- main-menu-area start -->
                        <div class="main-menu-area">
                            <nav class="main-navigation">
                                <ul>
                                    <li><a class="nav-link" href="index.html"><i class="fa fa-home"></i> Home</a></li>
                                    <li><a class="nav-link" href="#"><i class="fa fa-info-circle"></i> About</a></li>
                                    <li><a class="nav-link" href="#"><i class="fa fa-book"></i> Events</a></li>
                                    <li><a class="nav-link" href="#"><i class="fa fa-address-card"></i> Contact </a></li>
										<ul class="navbar-nav ml-auto">
											<li class="nav-item">
												<span class="btn btn-primary rounded-pill">
										<i class="fas fa-user"></i> Admin, 👋 <span id="loggedInUserEmail"></span>
												</span>
												<span class="nav-item btn btn-primary rounded-pill" onclick="logout()">
										<i class="fas fa-sign-out-alt"></i> Logout </span>
											</li>
										</ul>
                                </ul>
                            </nav>
                        </div>
                        <!-- main-menu-area start -->
                    </div>
                    <div class="col">
                        <!-- mobile-menu start -->
                        <div class="mobile-menu d-block d-lg-none"></div>
                        <!-- mobile-menu end -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Header-bottom end -->
    </div>
    <!-- Header-area end -->

    <div class="container mt-5">
        <h1>Admin Panel</h1>
        <div class="row">
		    
            <div class="col-md-6">
                <h2>Create Event</h2>
                <form id="eventForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label for="eventName">Event Name</label>
                        <input type="text" class="form-control" id="eventName" name="eventName" required>
                    </div>
                    <div class="form-group">
                        <label for="eventDate">Event Date</label>
                        <input type="text" class="form-control datepicker" id="eventDate" name="eventDate" required>
                    </div>
                    <div class="form-group">
                        <label for="eventTime">Event Time</label>
                        <input type="text" class="form-control timepicker" id="eventTime" name="eventTime" required>
                    </div>
                    <div class="form-group">
                        <label for="eventVenue">Event Venue</label>
                        <input type="text" class="form-control" id="eventVenue" name="eventVenue" required>
                    </div>
					<div class="form-group">
  <label for="formFile" class="form-label">Upload Event Image</label>
  <input class="form-control" type="file" id="formFile">
</div>
<hr>
<div class="text-center">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Create Event</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-rotate-right"></i> Reload</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-repeat"></i> Revert</button>
					</div>
                </form>
            </div>
            <div class="col-md-6">
<!-- Upcoming Events -->
<h2 class="text-center">Upcoming Events</h2>
<hr>
<div class="mb-3">
    <input type="text" id="eventSearch" class="form-control" placeholder="Search...">
</div>
<div id="eventList">
    <?php if (empty($events)): ?>
        <div class="text-center">
            <h3 class="mt-5 font-weight-bold"><i class="fas fa-calendar-times"></i> There are no upcoming events at the moment.</h3>
        </div>
    <?php else: ?>
        <?php foreach ($events as $event): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <input type="checkbox" class="event-checkbox" value="<?php echo $event['ID']; ?>">
                    <h5 class="card-title"><?php echo htmlspecialchars($event['Name'] ?? ''); ?></h5>
                    <p class="card-text"><i class="fas fa-calendar-alt"></i> <?php echo date('F j, Y', strtotime($event['Date'] ?? '')); ?></p>
                    <p class="card-text"><i class="fas fa-clock"></i> <?php echo date('g:i A', strtotime($event['Time'] ?? '')); ?></p>
                    <p class="card-text"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['Venue'] ?? ''); ?></p>
                    <div class="text-center">
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-primary edit-btn" data-toggle="modal" data-target="#editModal_<?php echo $event['ID']; ?>"><i class="fas fa-pencil-alt"></i> Edit</button>
                        <!-- Delete Button -->
                        <button type="button" class="btn btn-danger delete-btn" data-toggle="modal" data-target="#deleteModal_<?php echo $event['ID']; ?>"><i class="fas fa-trash-alt"></i> Delete</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Search filter and multi-select functionality -->
<script>
    $(document).ready(function() {
        // Search filter
        $('#eventSearch').on('input', function() {
            var value = $(this).val().toLowerCase();
            $('#eventList .card').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Multi-select functionality
        $('#eventList').on('change', '.event-checkbox', function() {
            var selectedEvents = [];
            $('.event-checkbox:checked').each(function() {
                selectedEvents.push($(this).val());
            });
            console.log(selectedEvents); // Use this array for further processing
        });
    });
</script>


    <!-- Edit Modal -->
    <div class="modal fade" id="editModal_<?php echo $event['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel_<?php echo $event['ID']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel_<?php echo $event['ID']; ?>">Edit Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editEventForm_<?php echo $event['ID']; ?>" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class="form-group">
                            <label for="editEventName_<?php echo $event['ID']; ?>">Event Name</label>
                            <input type="text" class="form-control" id="editEventName_<?php echo $event['ID']; ?>" name="editEventName" value="<?php echo htmlspecialchars($event['Name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="editEventDate_<?php echo $event['ID']; ?>">Event Date</label>
                            <input type="text" class="form-control datepicker" id="editEventDate_<?php echo $event['ID']; ?>" name="editEventDate" value="<?php echo htmlspecialchars($event['Date'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="editEventTime_<?php echo $event['ID']; ?>">Event Time</label>
                            <input type="text" class="form-control timepicker" id="editEventTime_<?php echo $event['ID']; ?>" name="editEventTime" value="<?php echo htmlspecialchars($event['Time'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="editEventVenue_<?php echo $event['ID']; ?>">Event Venue</label>
                            <input type="text" class="form-control" id="editEventVenue_<?php echo $event['ID']; ?>" name="editEventVenue" value="<?php echo htmlspecialchars($event['Venue'] ?? ''); ?>" required>
                        </div>
                        <input type="hidden" name="eventId" value="<?php echo $event['ID']; ?>">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Modal -->

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal_<?php echo $event['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel_<?php echo $event['ID']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel_<?php echo $event['ID']; ?>">Delete Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this event?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <form id="deleteEventForm_<?php echo $event['ID']; ?>" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <input type="hidden" name="deleteEventId" value="<?php echo $event['ID']; ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    <script>
        $(document).ready(function() { 
            $('.datepicker').datepicker({ 
                format: 'yyyy-mm-dd', 
                autoclose: true 
            }); 
        
            $('.timepicker').timepicker({ 
                showMeridian: false 
            }); 
        });
    </script>

</body>

</html>