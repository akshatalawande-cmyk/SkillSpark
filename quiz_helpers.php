<?php

function quiz_create_tables(mysqli $conn): void
{
    mysqli_query(
        $conn,
        "CREATE TABLE IF NOT EXISTS course_quizzes (
            quiz_id INT AUTO_INCREMENT PRIMARY KEY,
            course_id INT NOT NULL,
            question_text TEXT NOT NULL,
            option_a VARCHAR(255) NOT NULL,
            option_b VARCHAR(255) NOT NULL,
            option_c VARCHAR(255) NOT NULL,
            option_d VARCHAR(255) NOT NULL,
            correct_option CHAR(1) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY (course_id)
            REFERENCES courses(course_id)
            ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    mysqli_query(
        $conn,
        "CREATE TABLE IF NOT EXISTS quiz_attempts (
            attempt_id INT AUTO_INCREMENT PRIMARY KEY,

            user_id INT NOT NULL,

            course_id INT NOT NULL,

            total_questions INT NOT NULL DEFAULT 5,

            correct_answers INT NOT NULL DEFAULT 0,

            points INT NOT NULL DEFAULT 0,

            completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY (user_id)
            REFERENCES users(id)
            ON DELETE CASCADE,

            FOREIGN KEY (course_id)
            REFERENCES courses(course_id)
            ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}

function quiz_user_has_custom_plan(mysqli $conn, int $userId): bool
{
    $planStmt = mysqli_prepare(
        $conn,
        "SELECT plan, status, end_date
         FROM subscriptions
         WHERE user_id = ?
         ORDER BY subscription_id DESC
         LIMIT 1"
    );

    if (!$planStmt) {
        return false;
    }

    mysqli_stmt_bind_param($planStmt, "i", $userId);
    mysqli_stmt_execute($planStmt);
    $planResult = mysqli_stmt_get_result($planStmt);
    $planRow = $planResult ? mysqli_fetch_assoc($planResult) : null;
    mysqli_stmt_close($planStmt);

    if (!$planRow) {
        return false;
    }

    $planName = trim((string) ($planRow['plan'] ?? ''));
    $status = trim((string) ($planRow['status'] ?? ''));
    $endDate = trim((string) ($planRow['end_date'] ?? ''));

    if ($planName !== 'Custom Yearly' || strcasecmp($status, 'Active') !== 0) {
        return false;
    }

    if ($endDate !== '' && strtotime($endDate) < strtotime(date('Y-m-d'))) {
        return false;
    }

    return true;
}

function quiz_seed_map(): array
{
    return [
        'Python Programming' => [
            ['question' => 'Which keyword is used to define a function in Python?', 'options' => ['A' => 'function', 'B' => 'def', 'C Programming' => 'func', 'D' => 'define'], 'answer' => 'B'],
            ['question' => 'Which data type stores True or False values?', 'options' => ['A' => 'string', 'B' => 'integer', 'C Programming' => 'boolean', 'D' => 'list'], 'answer' => 'C Programming'],
            ['question' => 'Which symbol starts a comment in Python?', 'options' => ['A' => '//', 'B' => '#', 'C Programming' => '--', 'D' => '/*'], 'answer' => 'B'],
            ['question' => 'Which built-in function shows output on the screen?', 'options' => ['A' => 'echo()', 'B' => 'print()', 'C Programming' => 'write()', 'D' => 'show()'], 'answer' => 'B'],
            ['question' => 'Which collection type uses square brackets?', 'options' => ['A' => 'tuple', 'B' => 'set', 'C Programming' => 'dictionary', 'D' => 'list'], 'answer' => 'D'],
        ],
        'Java Programming' => [
            ['question' => 'Which keyword declares a block-scoped variable?', 'options' => ['A' => 'var', 'B' => 'let', 'C Programming' => 'dim', 'D' => 'define'], 'answer' => 'B'],
            ['question' => 'Which method converts JSON text into a JavaScript object?', 'options' => ['A' => 'JSON.parse()', 'B' => 'JSON.stringify()', 'C Programming' => 'JSON.object()', 'D' => 'JSON.convert()'], 'answer' => 'A'],
            ['question' => 'Which symbol is used for strict equality?', 'options' => ['A' => '==', 'B' => '!=', 'C Programming' => '===', 'D' => '='], 'answer' => 'C Programming'],
            ['question' => 'Which browser object represents the current webpage?', 'options' => ['A' => 'window', 'B' => 'document', 'C Programming' => 'screen', 'D' => 'history'], 'answer' => 'B'],
            ['question' => 'Which function runs code after a delay?', 'options' => ['A' => 'setTimeout()', 'B' => 'setInterval()', 'C Programming' => 'delay()', 'D' => 'wait()'], 'answer' => 'A'],
        ],
        'Web Technology' => [
            ['question' => 'Which language is primarily used to structure web pages?', 'options' => ['A' => 'CSS', 'B' => 'HTML', 'C Programming' => 'SQL', 'D' => 'Python Programming'], 'answer' => 'B'],
            ['question' => 'Which language is mainly used to style web pages?', 'options' => ['A' => 'Java', 'B' => 'PHP', 'C Programming' => 'CSS', 'D' => 'C++ Programming'], 'answer' => 'C Programming'],
            ['question' => 'Which protocol is commonly used to transfer web pages?', 'options' => ['A' => 'HTTP', 'B' => 'FTP', 'C Programming' => 'SMTP', 'D' => 'SNMP'], 'answer' => 'A'],
            ['question' => 'Which tag creates a hyperlink in HTML?', 'options' => ['A' => '<link>', 'B' => '<href>', 'C Programming' => '<a>', 'D' => '<url>'], 'answer' => 'C Programming'],
            ['question' => 'Which CSS property changes text color?', 'options' => ['A' => 'font-color', 'B' => 'text-style', 'C Programming' => 'foreground', 'D' => 'color'], 'answer' => 'D'],
        ],
        'C Programming' => [
            ['question' => 'Which header file is commonly used for printf()?', 'options' => ['A' => 'stdlib.h', 'B' => 'string.h', 'C Programming' => 'stdio.h', 'D' => 'math.h'], 'answer' => 'C Programming'],
            ['question' => 'Which symbol ends a statement in C?', 'options' => ['A' => ':', 'B' => ';', 'C Programming' => '.', 'D' => ','], 'answer' => 'B'],
            ['question' => 'Which loop is best when the number of iterations is known?', 'options' => ['A' => 'for', 'B' => 'switch', 'C Programming' => 'if', 'D' => 'goto'], 'answer' => 'A'],
            ['question' => 'Which operator stores a value in a variable?', 'options' => ['A' => '==', 'B' => ':=', 'C Programming' => '=', 'D' => '=>'], 'answer' => 'C Programming'],
            ['question' => 'Which data type stores whole numbers?', 'options' => ['A' => 'float', 'B' => 'int', 'C Programming' => 'char', 'D' => 'double'], 'answer' => 'B'],
        ],
        'C++ Programming' => [
            ['question' => 'Which feature allows multiple functions with the same name but different parameters?', 'options' => ['A' => 'Encapsulation', 'B' => 'Inheritance', 'C Programming' => 'Polymorphism', 'D' => 'Compilation'], 'answer' => 'C Programming'],
            ['question' => 'Which stream is used to print output in C++?', 'options' => ['A' => 'cin', 'B' => 'cout', 'C Programming' => 'print', 'D' => 'echo'], 'answer' => 'B'],
            ['question' => 'Which operator is used to access a class member through an object?', 'options' => ['A' => '->', 'B' => '::', 'C Programming' => '.', 'D' => '#'], 'answer' => 'C Programming'],
            ['question' => 'Which keyword creates a class in C++?', 'options' => ['A' => 'class', 'B' => 'struct', 'C Programming' => 'object', 'D' => 'define'], 'answer' => 'A'],
            ['question' => 'Which concept allows one class to use properties of another class?', 'options' => ['A' => 'Looping', 'B' => 'Inheritance', 'C Programming' => 'Casting', 'D' => 'Overloading'], 'answer' => 'B'],
        ],
        'JavaScript Advanced' => [
            ['question' => 'Which concept lets a function remember variables from its outer scope?', 'options' => ['A' => 'Promise', 'B' => 'Closure', 'C Programming' => 'Callback', 'D' => 'Hoisting'], 'answer' => 'B'],
            ['question' => 'Which keyword is used with asynchronous functions?', 'options' => ['A' => 'await', 'B' => 'yield', 'C Programming' => 'pause', 'D' => 'defer'], 'answer' => 'A'],
            ['question' => 'Which method creates a new array with transformed items?', 'options' => ['A' => 'filter()', 'B' => 'map()', 'C Programming' => 'reduce()', 'D' => 'find()'], 'answer' => 'B'],
            ['question' => 'What does the spread operator look like?', 'options' => ['A' => '***', 'B' => '=>', 'C Programming' => '...', 'D' => '??'], 'answer' => 'C Programming'],
            ['question' => 'Which object is used to work with asynchronous results?', 'options' => ['A' => 'Promise', 'B' => 'Array', 'C Programming' => 'Date', 'D' => 'Math'], 'answer' => 'A'],
        ],
        'Data Structures' => [
            ['question' => 'Which data structure follows First In First Out order?', 'options' => ['A' => 'Stack', 'B' => 'Queue', 'C Programming' => 'Tree', 'D' => 'Graph'], 'answer' => 'B'],
            ['question' => 'Which data structure follows Last In First Out order?', 'options' => ['A' => 'Queue', 'B' => 'Array', 'C Programming' => 'Stack', 'D' => 'Linked list'], 'answer' => 'C Programming'],
            ['question' => 'Which data structure is best for hierarchical data?', 'options' => ['A' => 'Tree', 'B' => 'Queue', 'C Programming' => 'Stack', 'D' => 'Matrix'], 'answer' => 'A'],
            ['question' => 'Which search is commonly used on a sorted array?', 'options' => ['A' => 'Linear search', 'B' => 'Binary search', 'C Programming' => 'Depth-first search', 'D' => 'Breadth-first search'], 'answer' => 'B'],
            ['question' => 'Which linked list node usually contains data and a pointer?', 'options' => ['A' => 'Only data', 'B' => 'Only address', 'C Programming' => 'Data and link', 'D' => 'Index and size'], 'answer' => 'C Programming'],
        ],
    ];
}

function quiz_generic_seed(string $courseName): array
{
    return [
        ['question' => 'What is the main focus of the ' . $courseName . ' course?', 'options' => ['A' => 'Learning core concepts', 'B' => 'Cooking lessons', 'C Programming' => 'Travel planning', 'D' => 'Music theory'], 'answer' => 'A'],
        ['question' => 'Why are quizzes useful in ' . $courseName . '?', 'options' => ['A' => 'They reduce learning', 'B' => 'They help check understanding', 'C Programming' => 'They delete progress', 'D' => 'They replace lessons'], 'answer' => 'B'],
        ['question' => 'How many questions are included in each course quiz on this site?', 'options' => ['A' => '3', 'B' => '4', 'C Programming' => '5', 'D' => '10'], 'answer' => 'C Programming'],
        ['question' => 'What do points represent in the ' . $courseName . ' quiz?', 'options' => ['A' => 'Correct answers earned', 'B' => 'Video length', 'C Programming' => 'Subscription price', 'D' => 'Course duration'], 'answer' => 'A'],
        ['question' => 'What is generated after finishing the ' . $courseName . ' quiz?', 'options' => ['A' => 'Invoice', 'B' => 'Certificate', 'C Programming' => 'Password reset', 'D' => 'Database backup'], 'answer' => 'B'],
    ];
}

function quiz_initialize(mysqli $conn): void
{
    quiz_create_tables($conn);

    $coursesResult = mysqli_query($conn, "SELECT course_id, course_name FROM courses ORDER BY course_id ASC");
    if (!$coursesResult) {
        return;
    }

    $seedMap = quiz_seed_map();
    $countStmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM course_quizzes WHERE course_id = ?");
    $insertStmt = mysqli_prepare(
        $conn,
        "INSERT INTO course_quizzes (course_id, question_text, option_a, option_b, option_c, option_d, correct_option)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    if (!$countStmt || !$insertStmt) {
        if ($countStmt) {
            mysqli_stmt_close($countStmt);
        }
        if ($insertStmt) {
            mysqli_stmt_close($insertStmt);
        }
        return;
    }

    while ($course = mysqli_fetch_assoc($coursesResult)) {
        $courseId = (int) $course['course_id'];
        $courseName = trim((string) $course['course_name']);

        mysqli_stmt_bind_param($countStmt, "i", $courseId);
        mysqli_stmt_execute($countStmt);
        $countResult = mysqli_stmt_get_result($countStmt);
        $existingCount = $countResult ? (int) (mysqli_fetch_assoc($countResult)['total'] ?? 0) : 0;

        if ($existingCount >= 5) {
            continue;
        }

        $questions = $seedMap[$courseName] ?? quiz_generic_seed($courseName !== '' ? $courseName : 'Course');
        foreach ($questions as $question) {
            $questionText = $question['question'];
            $optionA = $question['options']['A'];
            $optionB = $question['options']['B'];
            $optionC = $question['options']['C Programming'];
            $optionD = $question['options']['D'];
            $correctOption = $question['answer'];

            mysqli_stmt_bind_param(
                $insertStmt,
                "issssss",
                $courseId,
                $questionText,
                $optionA,
                $optionB,
                $optionC,
                $optionD,
                $correctOption
            );
            mysqli_stmt_execute($insertStmt);
        }
    }

    mysqli_stmt_close($countStmt);
    mysqli_stmt_close($insertStmt);
}
