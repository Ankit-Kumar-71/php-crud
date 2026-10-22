<?php
require_once 'db.php';

$name = '';
$email = '';
$course = '';
$error = '';

// write code to fetch Student from id
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM students WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        $name = $student['name'];
        $email = $student['email'];
        $course = $student['course'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $course = trim($_POST['course'] ?? '');

    if ($name === '' || $email === '' || $course === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email.';
    } else {
        // Update the student record in the database
        $sql = "UPDATE students SET name = :name, email = :email, course = :course WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'course' => $course,
            'id' => $id
        ]);

        header('Location: index.php?msg=updated');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Student</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #1e293b;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
            background: #ffffff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            font-size: 28px;
            color: #0f172a;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
            color: #334155;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 15px;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .save-btn {
            width: 100%;
            padding: 13px;
            background: #16a34a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .save-btn:hover {
            background: #15803d;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 22px;
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 25px 20px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="form-container">

        <h2>Edit Student</h2>
        <p class="subtitle">Update student details below</p>

        <?php if ($error): ?>
            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label for="name">Student Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter student name"
                    value="<?= htmlspecialchars($name) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter email address"
                    value="<?= htmlspecialchars($email) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="course">Course</label>
                <input
                    type="text"
                    id="course"
                    name="course"
                    placeholder="Enter course name"
                    value="<?= htmlspecialchars($course) ?>"
                    required
                >
            </div>

            <button type="submit" class="save-btn">
                Save Student
            </button>

        </form>

        <a href="index.php" class="back-link">
            &larr; Back to Student List
        </a>

    </div>

</body>
</html>