<?php
/**
 * Lesson Assessment Mapping System
 * Maps lessons to assessment topics and levels
 * Provides functions to get appropriate assessments based on lesson information
 */

class LessonAssessmentMapper {
    
    // Mapping of lesson files to assessment topics
    private static $lessonToAssessmentMap = [
        // Python lessons
        'lessons/python_basic.php' => ['topic' => 'Python', 'level' => 'beginner'],
        'lessons/python_intermediate.php' => ['topic' => 'Python', 'level' => 'intermediate'],
        'lessons/python_advanced.php' => ['topic' => 'Python', 'level' => 'advanced'],
        
        // Java lessons
        'lessons/java_basic.php' => ['topic' => 'Java', 'level' => 'beginner'],
        'lessons/java_intermediate.php' => ['topic' => 'Java', 'level' => 'intermediate'],
        'lessons/java_advanced.php' => ['topic' => 'Java', 'level' => 'advanced'],
        
        // C lessons
        'lessons/c_basic.php' => ['topic' => 'C', 'level' => 'beginner'],
        'lessons/c_intermediate.php' => ['topic' => 'C', 'level' => 'intermediate'],
        'lessons/c_advanced.php' => ['topic' => 'C', 'level' => 'advanced'],
        
        // C++ lessons
        'lessons/c++_basic.php' => ['topic' => 'C++', 'level' => 'beginner'],
        'lessons/c++_intermediate.php' => ['topic' => 'C++', 'level' => 'intermediate'],
        'lessons/c++_advanced.php' => ['topic' => 'C++', 'level' => 'advanced'],
        
        // PHP lessons
        'lessons/php_basic.php' => ['topic' => 'PHP', 'level' => 'beginner'],
        'lessons/php_intermediate.php' => ['topic' => 'PHP', 'level' => 'intermediate'],
        'lessons/php_advanced.php' => ['topic' => 'PHP', 'level' => 'advanced'],
        
        // JavaScript lessons
        'lessons/javascript_basic.php' => ['topic' => 'Javascript', 'level' => 'beginner'],
        'lessons/javascript_intermediate.php' => ['topic' => 'Javascript', 'level' => 'intermediate'],
        'lessons/javascript_advanced.php' => ['topic' => 'Javascript', 'level' => 'advanced'],
        
        // CSS lessons
        'lessons/css_basic.php' => ['topic' => 'CSS', 'level' => 'beginner'],
        'lessons/css_intermediate.php' => ['topic' => 'CSS', 'level' => 'intermediate'],
        'lessons/css_advanced.php' => ['topic' => 'CSS', 'level' => 'advanced'],
        
        // HTML lessons
        'lessons/html_basic.php' => ['topic' => 'HTML', 'level' => 'beginner'],
        'lessons/html_intermediate.php' => ['topic' => 'HTML', 'level' => 'intermediate'],
        'lessons/html_advanced.php' => ['topic' => 'HTML', 'level' => 'advanced'],
        
        // Node.js lessons
        'lessons/nodejs_basic.php' => ['topic' => 'Node.js', 'level' => 'beginner'],
        'lessons/nodejs_intermediate.php' => ['topic' => 'Node.js', 'level' => 'intermediate'],
        'lessons/nodejs_advanced.php' => ['topic' => 'Node.js', 'level' => 'advanced'],
        
        // React lessons
        'lessons/react_basic.php' => ['topic' => 'React', 'level' => 'beginner'],
        'lessons/react_intermediate.php' => ['topic' => 'React', 'level' => 'intermediate'],
        'lessons/react_advanced.php' => ['topic' => 'React', 'level' => 'advanced'],
        
        // Laravel lessons
        'lessons/laravel_basic.php' => ['topic' => 'Laravel', 'level' => 'beginner'],
        'lessons/laravel_intermediate.php' => ['topic' => 'Laravel', 'level' => 'intermediate'],
        'lessons/laravel_advanced.php' => ['topic' => 'Laravel', 'level' => 'advanced'],
        
        // SQL lessons
        'lessons/sql_basic.php' => ['topic' => 'SQL', 'level' => 'beginner'],
        'lessons/sql_intermediate.php' => ['topic' => 'SQL', 'level' => 'intermediate'],
        'lessons/sql_advanced.php' => ['topic' => 'SQL', 'level' => 'advanced'],
        
        // NoSQL lessons
        'lessons/nosql_basic.php' => ['topic' => 'NoSQL', 'level' => 'beginner'],
        'lessons/nosql_intermediate.php' => ['topic' => 'NoSQL', 'level' => 'intermediate'],
        'lessons/nosql_advanced.php' => ['topic' => 'NoSQL', 'level' => 'advanced'],
        
        // MySQL lessons
        'lessons/mysql_basic.php' => ['topic' => 'MySQL', 'level' => 'beginner'],
        'lessons/mysql_intermediate.php' => ['topic' => 'MySQL', 'level' => 'intermediate'],
        'lessons/mysql_advanced.php' => ['topic' => 'MySQL', 'level' => 'advanced'],
        
        // PostgreSQL lessons
        'lessons/postgresql_basic.php' => ['topic' => 'PostgreSQL', 'level' => 'beginner'],
        'lessons/postgresql_intermediate.php' => ['topic' => 'PostgreSQL', 'level' => 'intermediate'],
        'lessons/postgresql_advanced.php' => ['topic' => 'PostgreSQL', 'level' => 'advanced'],
        
        // Oracle Database lessons
        'lessons/oracledatabase_basic.php' => ['topic' => 'Oracle Database', 'level' => 'beginner'],
        'lessons/oracledatabase_intermediate.php' => ['topic' => 'Oracle Database', 'level' => 'intermediate'],
        'lessons/oracledatabase_advanced.php' => ['topic' => 'Oracle Database', 'level' => 'advanced'],
        
        // MongoDB lessons
        'lessons/mongodb_basic.php' => ['topic' => 'MongoDB', 'level' => 'beginner'],
        'lessons/mongodb_intermediate.php' => ['topic' => 'MongoDB', 'level' => 'intermediate'],
        'lessons/mongodb_advanced.php' => ['topic' => 'MongoDB', 'level' => 'advanced'],
        
        // SQL Server lessons
        'lessons/sqlserver_basic.php' => ['topic' => 'SQL Server', 'level' => 'beginner'],
        'lessons/sqlserver_intermediate.php' => ['topic' => 'SQL Server', 'level' => 'intermediate'],
        'lessons/sqlserver_advanced.php' => ['topic' => 'SQL Server', 'level' => 'advanced'],
        
        // Cassandra lessons
        'lessons/cassandra_basic.php' => ['topic' => 'Cassandra', 'level' => 'beginner'],
        'lessons/cassandra_intermediate.php' => ['topic' => 'Cassandra', 'level' => 'intermediate'],
        'lessons/cassandra_advanced.php' => ['topic' => 'Cassandra', 'level' => 'advanced'],
        
        // Redis lessons
        'lessons/redis_basic.php' => ['topic' => 'Redis', 'level' => 'beginner'],
        'lessons/redis_intermediate.php' => ['topic' => 'Redis', 'level' => 'intermediate'],
        'lessons/redis_advanced.php' => ['topic' => 'Redis', 'level' => 'advanced'],
        
        // DynamoDB lessons
        'lessons/dynamodb_basic.php' => ['topic' => 'DynamoDB', 'level' => 'beginner'],
        'lessons/dynamodb_intermediate.php' => ['topic' => 'DynamoDB', 'level' => 'intermediate'],
        'lessons/dynamodb_advanced.php' => ['topic' => 'DynamoDB', 'level' => 'advanced'],
        
        // Relational vs NoSQL lessons
        'lessons/relational_vs_nosql_basic.php' => ['topic' => 'NoSQL', 'level' => 'beginner'],
    ];
    
    /**
     * Get assessment mapping for a lesson file
     * @param string $lessonFile The lesson file path
     * @return array|null Array with topic and level, or null if not found
     */
    public static function getAssessmentMapping($lessonFile) {
        return self::$lessonToAssessmentMap[$lessonFile] ?? null;
    }
    
    /**
     * Get assessment questions for a specific lesson
     * @param string $lessonFile The lesson file path
     * @return array|null Assessment questions for the lesson level, or null if not found
     */
    public static function getAssessmentForLesson($lessonFile) {
        $mapping = self::getAssessmentMapping($lessonFile);
        if (!$mapping) {
            return null;
        }
        
        // Load assessment bank
        $assessmentBank = self::loadAssessmentBank();
        if (!$assessmentBank || !isset($assessmentBank[$mapping['topic']])) {
            return null;
        }
        
        $topic = $assessmentBank[$mapping['topic']];
        $level = $mapping['level'];
        
        // Return assessment for the specific level
        return isset($topic[$level]) ? $topic[$level] : null;
    }
    
    /**
     * Get all assessment levels for a lesson topic
     * @param string $lessonFile The lesson file path
     * @return array|null All assessment levels for the topic, or null if not found
     */
    public static function getAllAssessmentsForLesson($lessonFile) {
        $mapping = self::getAssessmentMapping($lessonFile);
        if (!$mapping) {
            return null;
        }
        
        // Load assessment bank
        $assessmentBank = self::loadAssessmentBank();
        if (!$assessmentBank || !isset($assessmentBank[$mapping['topic']])) {
            return null;
        }
        
        return $assessmentBank[$mapping['topic']];
    }
    
    /**
     * Get lesson file from skill and level
     * @param string $skill The skill name
     * @param string $level The level (basic, intermediate, advanced)
     * @return string|null The lesson file path, or null if not found
     */
    public static function getLessonFile($skill, $level = 'basic') {
        $skill = strtolower($skill);
        $level = strtolower($level);
        
        // Handle special cases
        if ($skill === 'c++' || $skill === 'cpp') {
            $skill = 'c++';
        } elseif ($skill === 'oracle database' || $skill === 'oracle') {
            $skill = 'oracledatabase';
        } elseif ($skill === 'sql server' || $skill === 'mssql') {
            $skill = 'sqlserver';
        } elseif ($skill === 'node.js' || $skill === 'nodejs' || $skill === 'node') {
            $skill = 'nodejs';
        } elseif ($skill === 'javascript' || $skill === 'js') {
            $skill = 'javascript';
        }
        
        $lessonFile = "lessons/{$skill}_{$level}.php";
        
        // Check if the lesson file exists in our mapping
        return isset(self::$lessonToAssessmentMap[$lessonFile]) ? $lessonFile : null;
    }
    
    /**
     * Get available levels for a skill
     * @param string $skill The skill name
     * @return array Array of available levels
     */
    public static function getAvailableLevels($skill) {
        $levels = [];
        $skill = strtolower($skill);
        
        // Handle special cases
        if ($skill === 'c++' || $skill === 'cpp') {
            $skill = 'c++';
        } elseif ($skill === 'oracle database' || $skill === 'oracle') {
            $skill = 'oracledatabase';
        } elseif ($skill === 'sql server' || $skill === 'mssql') {
            $skill = 'sqlserver';
        } elseif ($skill === 'node.js' || $skill === 'nodejs' || $skill === 'node') {
            $skill = 'nodejs';
        } elseif ($skill === 'javascript' || $skill === 'js') {
            $skill = 'javascript';
        }
        
        foreach (['basic', 'intermediate', 'advanced'] as $level) {
            $lessonFile = "lessons/{$skill}_{$level}.php";
            if (isset(self::$lessonToAssessmentMap[$lessonFile])) {
                $levels[] = $level;
            }
        }
        
        return $levels;
    }
    
    /**
     * Load assessment bank from JSON file
     * @return array|null Assessment bank data or null if failed
     */
    private static function loadAssessmentBank() {
        $assessmentFile = 'assessments_bank.json';
        if (!file_exists($assessmentFile)) {
            error_log("Assessment file not found: $assessmentFile");
            return null;
        }
        
        $content = file_get_contents($assessmentFile);
        if (!$content) {
            error_log("Could not read assessment file: $assessmentFile");
            return null;
        }
        
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error: " . json_last_error_msg());
            return null;
        }
        
        return $data ?: null;
    }
    
    /**
     * Get assessment info for display
     * @param string $lessonFile The lesson file path
     * @return array|null Assessment info with topic, level, and question counts
     */
    public static function getAssessmentInfo($lessonFile) {
        $mapping = self::getAssessmentMapping($lessonFile);
        if (!$mapping) {
            return null;
        }
        
        $assessment = self::getAssessmentForLesson($lessonFile);
        if (!$assessment) {
            return null;
        }
        
        $info = [
            'topic' => $mapping['topic'],
            'level' => $mapping['level'],
            'question_counts' => []
        ];
        
        if (isset($assessment['multipleChoice'])) {
            $info['question_counts']['multipleChoice'] = count($assessment['multipleChoice']);
        }
        if (isset($assessment['debugging'])) {
            $info['question_counts']['debugging'] = count($assessment['debugging']);
        }
        if (isset($assessment['coding'])) {
            $info['question_counts']['coding'] = count($assessment['coding']);
        }
        
        return $info;
    }
}

// API endpoint for getting assessment data
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    // Enable error logging for debugging
    error_log("API call: " . $_GET['action'] . " with params: " . json_encode($_GET));
    
    switch ($_GET['action']) {
        case 'get_assessment_for_lesson':
            if (isset($_GET['lesson_file'])) {
                error_log("Getting assessment for lesson: " . $_GET['lesson_file']);
                $assessment = LessonAssessmentMapper::getAssessmentForLesson($_GET['lesson_file']);
                error_log("Assessment result: " . ($assessment ? 'found' : 'not found'));
                echo json_encode(['status' => 'success', 'data' => $assessment]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lesson file parameter required']);
            }
            break;
            
        case 'get_all_assessments_for_lesson':
            if (isset($_GET['lesson_file'])) {
                $assessments = LessonAssessmentMapper::getAllAssessmentsForLesson($_GET['lesson_file']);
                echo json_encode(['status' => 'success', 'data' => $assessments]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lesson file parameter required']);
            }
            break;
            
        case 'get_lesson_file':
            if (isset($_GET['skill']) && isset($_GET['level'])) {
                $lessonFile = LessonAssessmentMapper::getLessonFile($_GET['skill'], $_GET['level']);
                echo json_encode(['status' => 'success', 'data' => $lessonFile]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Skill and level parameters required']);
            }
            break;
            
        case 'get_available_levels':
            if (isset($_GET['skill'])) {
                $levels = LessonAssessmentMapper::getAvailableLevels($_GET['skill']);
                echo json_encode(['status' => 'success', 'data' => $levels]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Skill parameter required']);
            }
            break;
            
        case 'get_assessment_info':
            if (isset($_GET['lesson_file'])) {
                $info = LessonAssessmentMapper::getAssessmentInfo($_GET['lesson_file']);
                echo json_encode(['status' => 'success', 'data' => $info]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lesson file parameter required']);
            }
            break;
            
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            break;
    }
    exit();
}
?> 