<?php
require_once 'db.php';

$sql = "SELECT id, name, email, course
        FROM students
        ORDER BY id DESC";

$stmt = $conn->query($sql);

$students = $stmt->fetchAll();

$message = [
    'created' => 'Student added successfully!',
    'updated' => 'Student updated successfully!',
    'deleted' => 'Student deleted successfully!'
];

$msg = $_GET['msg'] ?? '';

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f1f5f9;
            color: #1e293b;
            padding: 40px 20px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        h2 {
            font-size: 28px;
            margin-bottom: 25px;
            color: #0f172a;
        }

        .message {
            background: #dcfce7;
            color: #166534;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .add-btn {
            display: inline-block;
            background: #16a34a;
            color: white;
            padding: 11px 18px;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 22px;
            font-weight: bold;
            transition: 0.3s;
        }

        .contact-btn{
            display: inline-block;
            background: #16a34a;
            color: white;
            padding: 11px 18px;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 22px;
            font-weight: bold;
            transition: 0.3s;
        }

        .add-btn:hover {
            background: #15803d;
        }

        .contact-btn:hover {
            background: #15803d;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        thead {
            background: #1e293b;
            color: white;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr:hover {
            background: #eff6ff;
        }

        .edit-btn,
        .delete-btn {
            display: inline-block;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 13px;
            cursor: pointer;
            font-weight: bold;
        }

        .edit-btn {
            background: #2563eb;
        }

        .edit-btn:hover {
            background: #1d4ed8;
        }

        .delete-btn {
            background: #dc2626;
        }

        .delete-btn:hover {
            background: #b91c1c;
        }

        .action-form {
            display: inline-block;
            margin-left: 5px;
        }

        .empty {
            text-align: center;
            color: #64748b;
            padding: 25px;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px 8px;
            }

            .container {
                padding: 18px 12px;
            }

            h2 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>
    
    <div class="container">

        <h2>Student Management</h2>

        <?php if (isset($message[$msg])): ?>
            <p class="message">
                <?= htmlspecialchars($message[$msg]) ?>
            </p>
        <?php endif; ?>

        <a href="create.php" class="add-btn">
             Add New Student
        </a>

        <a href="contact.php" class="contact-btn">
            Contact Us
        </a>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="5" class="empty">
                                No students found.
                            </td>
                        </tr>
                    <?php else: ?>

                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td>
                                    <?= (int) $student['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($student['name']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($student['email']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($student['course']) ?>
                                </td>

                                <td>
                                    <a
                                        href="edit.php?id=<?= (int) $student['id'] ?>"
                                        class="edit-btn">
                                        Edit
                                    </a>

                                    <form
                                        action="delete.php"
                                        method="POST"
                                        class="action-form"
                                        onsubmit="return confirm('Delete this student?')">
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $student['id'] ?>">

                                        <button
                                            type="submit"
                                            class="delete-btn">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</script>


</body>


</html>