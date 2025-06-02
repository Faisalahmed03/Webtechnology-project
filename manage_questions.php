<?php
include_once '../includes/functions.php';
include_once '../includes/db.php';
session_start();
require_login();
require_role('admin'); 

$questions = [];
$search_term = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$filter_type = isset($_GET['type']) ? sanitize_input($_GET['type']) : '';
$filter_difficulty = isset($_GET['difficulty']) ? sanitize_input($_GET['difficulty']) : '';


$question_types = [];
$stmt_q_types = $conn->query("SELECT id, name FROM question_types ORDER BY name");
if ($stmt_q_types) $question_types = $stmt_q_types->fetch_all(MYSQLI_ASSOC);

$difficulty_levels = [];
$stmt_d_levels = $conn->query("SELECT id, name FROM difficulty_levels ORDER BY id");
if ($stmt_d_levels) $difficulty_levels = $stmt_d_levels->fetch_all(MYSQLI_ASSOC);


$sql = "SELECT q.id, q.question_text, qt.name AS question_type, dl.name AS difficulty_level, u.username AS created_by, q.created_at 
        FROM questions q 
        JOIN question_types qt ON q.question_type_id = qt.id 
        LEFT JOIN difficulty_levels dl ON q.difficulty_level_id = dl.id 
        LEFT JOIN users u ON q.created_by_user_id = u.id";

$conditions = [];
$params = [];
$types = '';

if (!empty($search_term)) {
    $conditions[] = "q.question_text LIKE ?";
    $params[] = "%{$search_term}%";
    $types .= 's';
}
if (!empty($filter_type)) {
    $conditions[] = "q.question_type_id = ?";
    $params[] = $filter_type;
    $types .= 'i';
}
if (!empty($filter_difficulty)) {
    $conditions[] = "q.difficulty_level_id = ?";
    $params[] = $filter_difficulty;
    $types .= 'i';
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY q.created_at DESC";

$stmt = $conn->prepare($sql);

if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
    $stmt->close();
} else {
    
    $_SESSION['message'] = "Error fetching questions: " . $conn->error;
    $_SESSION['msg_type'] = "error";
}

include_once '../includes/header.php';
?>

<h2>Manage Question Bank</h2>

<?php display_message(); ?>

<p><a href="add_question.php" class="button">Add New Question</a></p>


<form action="manage_questions.php" method="get" class="filter-form">
    <input type="text" name="search" placeholder="Search questions..." value="<?php echo htmlspecialchars($search_term); ?>">
    
    <select name="type">
        <option value="">All Types</option>
        <?php foreach ($question_types as $type): ?>
            <option value="<?php echo $type['id']; ?>" <?php echo ($filter_type == $type['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($type['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="difficulty">
        <option value="">All Difficulties</option>
        <?php foreach ($difficulty_levels as $level): ?>
            <option value="<?php echo $level['id']; ?>" <?php echo ($filter_difficulty == $level['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($level['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filter</button>
    <a href="manage_questions.php">Clear Filters</a>
</form>


<?php if (!empty($questions)): ?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Question Text</th>
            <th>Type</th>
            <th>Difficulty</th>
            <th>Created By</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($questions as $question): ?>
        <tr>
            <td><?php echo $question['id']; ?></td>
            <td><?php echo nl2br(htmlspecialchars(substr($question['question_text'], 0, 100))) . (strlen($question['question_text']) > 100 ? '...' : ''); ?></td>
            <td><?php echo htmlspecialchars($question['question_type']); ?></td>
            <td><?php echo htmlspecialchars($question['difficulty_level'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($question['created_by'] ?? 'N/A'); ?></td>
            <td><?php echo date("Y-m-d H:i", strtotime($question['created_at'])); ?></td>
            <td>
                <a href="edit_question.php?id=<?php echo $question['id']; ?>">Edit</a> |
                <a href="view_question.php?id=<?php echo $question['id']; ?>">View</a> |
                <a href="delete_question.php?id=<?php echo $question['id']; ?>" onclick="return confirm('Are you sure you want to delete this question?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>No questions found. <a href="add_question.php">Add the first question!</a></p>
<?php endif; ?>

<p><a href="index.php">Back to Admin Dashboard</a></p>

<?php
$conn->close();
include_once '../includes/footer.php';
?>
