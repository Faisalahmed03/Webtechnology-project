<?php
include_once '../includes/functions.php';
include_once '../includes/db.php';
session_start();
require_login();
require_role('admin'); 

$errors = [];
$question_text = '';
$question_type_id = '';
$difficulty_level_id = '';
$explanation_text = '';
$options = []; 
$tags = ''; 


$question_types = [];
$stmt_q_types = $conn->query("SELECT id, name FROM question_types ORDER BY name");
if ($stmt_q_types) {
    $question_types = $stmt_q_types->fetch_all(MYSQLI_ASSOC);
    $stmt_q_types->close();
}

$difficulty_levels = [];
$stmt_d_levels = $conn->query("SELECT id, name FROM difficulty_levels ORDER BY id");
if ($stmt_d_levels) {
    $difficulty_levels = $stmt_d_levels->fetch_all(MYSQLI_ASSOC);
    $stmt_d_levels->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_text = sanitize_input($_POST['question_text']);
    $question_type_id = sanitize_input($_POST['question_type_id']);
    $difficulty_level_id = sanitize_input($_POST['difficulty_level_id']);
    $explanation_text = sanitize_input($_POST['explanation_text']);
    $tags_input = sanitize_input($_POST['tags']);

    
    if (!is_required($question_text)) {
        $errors[] = "Question text is required.";
    }
    if (!is_required($question_type_id)) {
        $errors[] = "Question type is required.";
    }

    

    if (isset($_POST['options']) && is_array($_POST['options'])) {
        foreach ($_POST['options'] as $key => $option_data) {
            $text = sanitize_input($option_data['text']);
            $is_correct = isset($option_data['is_correct']) ? 1 : 0;
            if (is_required($text)) {
                $options[] = ['text' => $text, 'is_correct' => $is_correct];
            }
        }
    }

    if (empty($errors)) {
        $conn->begin_transaction();
        try {
            
            $stmt_question = $conn->prepare("INSERT INTO questions (question_text, question_type_id, difficulty_level_id, explanation_text, created_by_user_id) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt_question) {
                throw new Exception("Database error (prepare question): " . $conn->error);
            }
            $user_id = $_SESSION['user_id'];
            $difficulty_level_id = !empty($difficulty_level_id) ? $difficulty_level_id : NULL;
            $stmt_question->bind_param("siisi", $question_text, $question_type_id, $difficulty_level_id, $explanation_text, $user_id);
            if (!$stmt_question->execute()) {
                throw new Exception("Database error (execute question): " . $stmt_question->error);
            }
            $new_question_id = $stmt_question->insert_id;
            $stmt_question->close();

            
            $selected_question_type_name = '';
            foreach($question_types as $qt) {
                if ($qt['id'] == $question_type_id) {
                    $selected_question_type_name = $qt['name'];
                    break;
                }
            }

            if (strtolower($selected_question_type_name) === 'multiple choice' && !empty($options)) {
                $stmt_option = $conn->prepare("INSERT INTO question_options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
                if (!$stmt_option) {
                    throw new Exception("Database error (prepare option): " . $conn->error);
                }
                foreach ($options as $opt) {
                    $stmt_option->bind_param("isi", $new_question_id, $opt['text'], $opt['is_correct']);
                    if (!$stmt_option->execute()) {
                        throw new Exception("Database error (execute option): " . $stmt_option->error);
                    }
                }
                $stmt_option->close();
            }
            
            else if (strtolower($selected_question_type_name) === 'true/false') {
                $stmt_option = $conn->prepare("INSERT INTO question_options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
                if (!$stmt_option) throw new Exception("Database error (prepare T/F option): " . $conn->error);
                
                
                $true_text = 'True'; $is_true_correct = (isset($_POST['true_false_correct']) && $_POST['true_false_correct'] === 'true') ? 1 : 0;
                $stmt_option->bind_param("isi", $new_question_id, $true_text, $is_true_correct);
                if (!$stmt_option->execute()) throw new Exception("Database error (execute True option): " . $stmt_option->error);

                
                $false_text = 'False'; $is_false_correct = (isset($_POST['true_false_correct']) && $_POST['true_false_correct'] === 'false') ? 1 : 0;
                $stmt_option->bind_param("isi", $new_question_id, $false_text, $is_false_correct);
                if (!$stmt_option->execute()) throw new Exception("Database error (execute False option): " . $stmt_option->error);
                $stmt_option->close();
            }

            
            if (!empty($tags_input)) {
                $tag_names = array_map('trim', explode(',', $tags_input));
                $tag_ids_to_link = [];
                $stmt_find_tag = $conn->prepare("SELECT id FROM tags WHERE name = ?");
                $stmt_insert_tag = $conn->prepare("INSERT INTO tags (name, created_by_user_id) VALUES (?, ?)");
                $stmt_link_tag = $conn->prepare("INSERT INTO question_tags (question_id, tag_id) VALUES (?, ?)");

                if (!$stmt_find_tag || !$stmt_insert_tag || !$stmt_link_tag) {
                    throw new Exception("Database error (prepare tags): " . $conn->error);
                }

                foreach ($tag_names as $tag_name) {
                    if (empty($tag_name)) continue;
                    $stmt_find_tag->bind_param("s", $tag_name);
                    $stmt_find_tag->execute();
                    $result_tag = $stmt_find_tag->get_result();
                    if ($row_tag = $result_tag->fetch_assoc()) {
                        $tag_ids_to_link[] = $row_tag['id'];
                    } else {
                        $stmt_insert_tag->bind_param("si", $tag_name, $user_id);
                        if (!$stmt_insert_tag->execute()) {
                            throw new Exception("Database error (insert tag): " . $stmt_insert_tag->error);
                        }
                        $tag_ids_to_link[] = $stmt_insert_tag->insert_id;
                    }
                }
                $stmt_find_tag->close();
                $stmt_insert_tag->close();

                foreach ($tag_ids_to_link as $tag_id) {
                    $stmt_link_tag->bind_param("ii", $new_question_id, $tag_id);
                    if (!$stmt_link_tag->execute()) {
                        
                        if ($conn->errno !== 1062) { 
                           throw new Exception("Database error (link tag): " . $stmt_link_tag->error);
                        }
                    }
                }
                $stmt_link_tag->close();
            }

            $conn->commit();
            set_message("Question added successfully!", "success");
            header("Location: manage_questions.php");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = $e->getMessage();
        }
    }
}

include_once '../includes/header.php';
?>

<h2>Add New Question</h2>

<?php display_errors($errors); ?>
<?php display_message(); ?>

<form action="add_question.php" method="post" id="addQuestionForm">
    <div>
        <label for="question_text">Question Text: <span class="required">*</span></label>
        <textarea name="question_text" id="question_text" rows="4" required><?php echo htmlspecialchars($question_text); ?></textarea>
    </div>

    <div>
        <label for="question_type_id">Question Type: <span class="required">*</span></label>
        <select name="question_type_id" id="question_type_id" required>
            <option value="">-- Select Type --</option>
            <?php foreach ($question_types as $type): ?>
                <option value="<?php echo $type['id']; ?>" <?php echo ($question_type_id == $type['id']) ? 'selected' : ''; ?> data-type-name="<?php echo htmlspecialchars(strtolower($type['name'])); ?>">
                    <?php echo htmlspecialchars($type['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="options_container" style="display: none;"> 
        <h4>Options:</h4>
        <div id="mcq_options_list">
            
        <h4>Correct Answer:</h4>
        <label><input type="radio" name="true_false_correct" value="true"> True</label>
        <label><input type="radio" name="true_false_correct" value="false"> False</label>
    </div>

    <div>
        <label for="difficulty_level_id">Difficulty Level:</label>
        <select name="difficulty_level_id" id="difficulty_level_id">
            <option value="">-- Select Difficulty (Optional) --</option>
            <?php foreach ($difficulty_levels as $level): ?>
                <option value="<?php echo $level['id']; ?>" <?php echo ($difficulty_level_id == $level['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($level['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="explanation_text">Explanation (Optional):</label>
        <textarea name="explanation_text" id="explanation_text" rows="3"><?php echo htmlspecialchars($explanation_text); ?></textarea>
        <small>This will be shown to students after they attempt the question or quiz (if configured).</small>
    </div>

    <div>
        <label for="tags">Tags (comma-separated, e.g., algebra, easy, chapter1):</label>
        <input type="text" name="tags" id="tags" value="<?php echo htmlspecialchars($tags); ?>">
    </div>

    <div>
        <input type="submit" value="Add Question">
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionTypeSelect = document.getElementById('question_type_id');
    const mcqOptionsContainer = document.getElementById('options_container');
    const trueFalseContainer = document.getElementById('true_false_container');
    const addMcqOptionBtn = document.getElementById('add_mcq_option_btn');
    const mcqOptionsList = document.getElementById('mcq_options_list');
    let optionCounter = 1; 

    function toggleOptionInputs() {
        const selectedOption = questionTypeSelect.options[questionTypeSelect.selectedIndex];
        const typeName = selectedOption ? selectedOption.dataset.typeName : '';

        if (typeName === 'multiple choice') {
            mcqOptionsContainer.style.display = 'block';
            if(mcqOptionsList.children.length === 0) addMcqOption(); 
            trueFalseContainer.style.display = 'none';
        } else if (typeName === 'true/false') {
            mcqOptionsContainer.style.display = 'none';
            trueFalseContainer.style.display = 'block';
        } else {
            mcqOptionsContainer.style.display = 'none';
            trueFalseContainer.style.display = 'none';
        }
    }

    function addMcqOption() {
        const newItem = document.createElement('div');
        newItem.classList.add('mcq_option_item');
        newItem.style.marginBottom = '10px';
        newItem.innerHTML = `
            <input type="text" name="options[${optionCounter}][text]" placeholder="Option text">
            <label><input type="checkbox" name="options[${optionCounter}][is_correct]" value="1"> Correct</label>
            <button type="button" class="remove_option_btn">Remove</button>
        `;
        mcqOptionsList.appendChild(newItem);
        optionCounter++;
        
        newItem.querySelector('.remove_option_btn').addEventListener('click', function() {
            this.parentElement.remove();
        });
    }

    questionTypeSelect.addEventListener('change', toggleOptionInputs);
    addMcqOptionBtn.addEventListener('click', addMcqOption);

    
    toggleOptionInputs();

    
    mcqOptionsList.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove_option_btn')) {
            e.target.parentElement.remove();
        }
    });


    const initialTemplate = mcqOptionsList.querySelector('.mcq_option_item');
    if(initialTemplate && mcqOptionsContainer.style.display === 'none') {
        
    } else if (initialTemplate && mcqOptionsContainer.style.display === 'block' && mcqOptionsList.children.length > 1) {
        
        
    }

});
</script>

<p><a href="manage_questions.php">Back to Manage Questions</a></p>
<p><a href="index.php">Back to Admin Dashboard</a></p>

<?php
$conn->close();
include_once '../includes/footer.php';
?>
