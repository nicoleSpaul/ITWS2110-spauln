<?php
session_start();

require_once 'dbConnect.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Variables for form status and messages
$message = '';
$projects = [];
$users = [];
$newlyAddedProjectId = null;

try {
    $userStmt = $dbconn->query("SELECT userId, CONCAT(firstName, ' ', lastName) AS fullName FROM users ORDER BY lastName");
    $users = $userStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $message = "Error retrieving user list: " . $e->getMessage();
}

if (isset($_POST['add_project'])) {
    // 2.1. Basic Data Retrieval
    $projectName = trim($_POST['projectName']);
    $projectDescription = trim($_POST['projectDescription']);
    $memberIds = $_POST['members'] ?? [];

    // 2.2. Validation: Check Name and Member Count 
    if (empty($projectName) || empty($projectDescription) || count($memberIds) < 3) {
        $message = "Error: Project name and description are required, and the project must have at least 3 members.";
    } else {
        try {
            //check for duplicate project name just in case
            $checkStmt = $dbconn->prepare("SELECT projectId FROM projects WHERE name = :name");
            $checkStmt->execute([':name' => $projectName]);
            
            if ($checkStmt->fetch()) {
                $message = "Error: A project named '{$projectName}' already exists.";
            } else {
                //make sure inserts dont end badly/midway w rollbacks
                $dbconn->beginTransaction();

                //insert project information
                $projStmt = $dbconn->prepare("
                    INSERT INTO projects (name, description) VALUES (:name, :description)
                ");
                $projStmt->execute([
                    ':name' => $projectName,
                    ':description' => $projectDescription
                ]);
                
                $newlyAddedProjectId = $dbconn->lastInsertId();

                //insert project members into projectMembrship table 
                $memberStmt = $dbconn->prepare("
                    INSERT INTO projectMembership (projectId, memberId) VALUES (:projectId, :memberId)
                ");
                
                foreach ($memberIds as $memberId) {
                    $memberStmt->execute([
                        ':projectId' => $newlyAddedProjectId,
                        ':memberId' => (int)$memberId 
                    ]);
                }

                //commit the transaction
                $dbconn->commit();
                $message = "Success: Project '{$projectName}' added with " . count($memberIds) . " members.";
            }
        } catch (Exception $e) {
            //Rollback if any insert failed to avoid bad inersts
            if ($dbconn->inTransaction()) {
                $dbconn->rollBack();
            }
            $message = "An unexpected error occurred during project creation: " . $e->getMessage();
        }
    }
}


try {
    //fetch all projects and their member counts
    $sql = "
        SELECT 
            p.projectId, 
            p.name, 
            p.description, 
            COUNT(pm.memberId) AS member_count
        FROM projects p
        LEFT JOIN projectMembership pm ON p.projectId = pm.projectId
        GROUP BY p.projectId, p.name, p.description
        ORDER BY p.projectId DESC
    ";
    $projListStmt = $dbconn->query($sql);
    $projects = $projListStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    //If the database is connected but this query fails
    $message .= " Error displaying projects: " . $e->getMessage();
}

?>




<!doctype html>
<html>
<head>
    <title>Project Management Dashboard</title>
    <style>
        .highlight {
            border: 2px solid #4CAF50; /* Green border for new project */
            background-color: #f0fff0; /* Light green background */
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Project Management Dashboard</h1>

    <?php if ($message): ?>
        <p style='color: red; border: 1px solid red; padding: 10px;'><?php echo $message; ?></p>
    <?php endif; ?>

    <hr>
    <h2>1. Add a New Project</h2>
    <form method="post" action="index.php">
        
        <label for="projectName">Project Name:</label>
        <input type="text" name="projectName" required><br><br>

        <label for="projectDescription">Description:</label><br>
        <textarea name="projectDescription" rows="4" cols="50" required></textarea><br><br>

        <label>Project Members (Select at least 3):</label><br>
        <select name="members[]" multiple size="10" required>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo htmlentities($user['userId']); ?>">
                    <?php echo htmlentities($user['fullName']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p style="font-size: small;">Hold CTRL or CMD to select multiple members.</p>
        <br>
        
        <input type="submit" name="add_project" value="Add Project">
    </form>
    <hr>

    <h2>2. View Existing Projects (<?php echo count($projects); ?> Total)</h2>
    
    <?php if (empty($projects)): ?>
        <p>No projects found in the database.</p>
    <?php else: ?>
        <?php foreach ($projects as $project): ?>
            <?php 
                //apply highlight class if this project was just added
                $class = ($project['projectId'] == $newlyAddedProjectId) ? 'highlight' : '';
            ?>
            <div class="<?php echo $class; ?>">
                <h3><?php echo htmlentities($project['name']); ?> 
                    <?php if ($class) echo " (NEWLY ADDED)"; ?>
                </h3>
                <p><strong>ID:</strong> <?php echo $project['projectId']; ?></p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlentities($project['description'])); ?></p>
                <p><strong>Members:</strong> <?php echo $project['member_count']; ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <hr>

    <form method="post" action="login.php">
        <input name="logout" type="submit" value="Logout" />
    </form>
</body>
</html>