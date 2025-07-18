# Lesson Assessment Connection System

## Overview

The Lesson Assessment Connection System integrates the existing lesson structure with the assessment bank to provide targeted, level-appropriate assessments based on the specific lesson being taught. This ensures that assessments are directly connected to the learning content and difficulty level.

## How It Works

### 1. Lesson Structure
- Lessons are organized by topic and level: `lessons/{topic}_{level}.php`
- Example: `lessons/python_basic.php`, `lessons/java_intermediate.php`
- Each lesson file corresponds to a specific skill and proficiency level

### 2. Assessment Bank Structure
- Assessments are organized by topic and level in `assessments_bank.json`
- Each topic has three levels: `beginner`, `intermediate`, `advanced`
- Each level contains: `multipleChoice`, `debugging`, and `coding` questions

### 3. Mapping System
The `lesson_assessment_mapping.php` file provides the connection between lessons and assessments:

```php
// Example mapping
'lessons/python_basic.php' => ['topic' => 'Python', 'level' => 'beginner']
'lessons/java_intermediate.php' => ['topic' => 'Java', 'level' => 'intermediate']
```

## Key Components

### 1. LessonAssessmentMapper Class

**Main Functions:**
- `getAssessmentMapping($lessonFile)` - Gets assessment mapping for a lesson
- `getAssessmentForLesson($lessonFile)` - Gets assessment questions for a specific lesson
- `getAllAssessmentsForLesson($lessonFile)` - Gets all assessment levels for a topic
- `getLessonFile($skill, $level)` - Gets lesson file from skill and level
- `getAvailableLevels($skill)` - Gets available levels for a skill

### 2. API Endpoints

The mapping system provides REST API endpoints:

- `GET lesson_assessment_mapping.php?action=get_assessment_for_lesson&lesson_file={file}`
- `GET lesson_assessment_mapping.php?action=get_all_assessments_for_lesson&lesson_file={file}`
- `GET lesson_assessment_mapping.php?action=get_lesson_file&skill={skill}&level={level}`
- `GET lesson_assessment_mapping.php?action=get_available_levels&skill={skill}`
- `GET lesson_assessment_mapping.php?action=get_assessment_info&lesson_file={file}`

### 3. Integration with Messaging System

The `takeAssessment()` function in `message.php` has been updated to:

1. **Get Lesson Information**: Retrieve the user's learning request/skill
2. **Map to Lesson File**: Convert skill to lesson file path
3. **Fetch Assessment**: Get assessment questions for that specific lesson
4. **Display with Context**: Show assessment with lesson information

## Usage Flow

### In the Messaging System

1. User clicks "Take Assessment" in a chat session
2. System retrieves the learning request/skill for the chat partner
3. Maps the skill to the appropriate lesson file (e.g., `lessons/python_basic.php`)
4. Fetches assessment questions for that lesson level
5. Displays assessment with lesson context (e.g., "Lesson Assessment: Python Basic")

### Example Flow

```
User wants to learn Python → 
Learning request: "python" → 
Lesson file: "lessons/python_basic.php" → 
Assessment: Python beginner level questions → 
Display: "Lesson Assessment: Python Basic"
```

## Benefits

### 1. **Targeted Assessments**
- Assessments match the exact lesson content and difficulty
- No more generic assessments - each is tailored to the learning session

### 2. **Level-Appropriate Questions**
- Beginner lessons get beginner questions
- Intermediate lessons get intermediate questions
- Advanced lessons get advanced questions

### 3. **Consistent Learning Path**
- Assessment difficulty progresses with lesson difficulty
- Students are tested on what they're actually learning

### 4. **Better User Experience**
- Clear connection between lessons and assessments
- Users understand what they're being tested on
- Assessment results are more meaningful

## Supported Topics

The system supports all major programming and database topics:

### Programming Languages
- Python (Basic, Intermediate, Advanced)
- Java (Basic, Intermediate, Advanced)
- C (Basic, Intermediate, Advanced)
- C++ (Basic, Intermediate, Advanced)
- PHP (Basic, Intermediate, Advanced)
- JavaScript (Basic, Intermediate, Advanced)

### Web Technologies
- CSS (Basic, Intermediate, Advanced)
- HTML (Basic, Intermediate, Advanced)
- React (Basic, Intermediate, Advanced)
- Laravel (Basic, Intermediate, Advanced)
- Node.js (Basic, Intermediate, Advanced)

### Databases
- SQL (Basic, Intermediate, Advanced)
- NoSQL (Basic, Intermediate, Advanced)
- MySQL (Basic, Intermediate, Advanced)
- PostgreSQL (Basic, Intermediate, Advanced)
- Oracle Database (Basic, Intermediate, Advanced)
- MongoDB (Basic, Intermediate, Advanced)
- SQL Server (Basic, Intermediate, Advanced)
- Cassandra (Basic, Intermediate, Advanced)
- Redis (Basic, Intermediate, Advanced)
- DynamoDB (Basic, Intermediate, Advanced)

## Testing

Use the test page `test_lesson_assessment.php` to verify the system:

1. Select a skill and level
2. Click "Test Lesson Mapping"
3. View the results showing:
   - Lesson file found
   - Assessment questions available
   - Sample questions
   - All assessment levels

## Fallback System

If the lesson-based assessment system fails, it falls back to the original assessment method:

1. Try lesson-based assessment first
2. If lesson file not found, try skill-based mapping
3. If skill mapping fails, try user skill fallback
4. If all fail, show error message

## Future Enhancements

### 1. **Dynamic Level Detection**
- Automatically detect user's current level based on session history
- Suggest appropriate lesson level for assessment

### 2. **Progress Tracking**
- Track assessment scores per lesson
- Show progress through lesson levels

### 3. **Adaptive Assessments**
- Adjust question difficulty based on previous performance
- Provide personalized assessment experiences

### 4. **Lesson-Specific Questions**
- Create questions that reference specific lesson content
- Include code examples from the actual lessons

## Technical Implementation

### File Structure
```
siaproject/
├── lesson_assessment_mapping.php    # Main mapping system
├── assessments_bank.json           # Assessment questions
├── lessons/                        # Lesson files
│   ├── python_basic.php
│   ├── python_intermediate.php
│   └── ...
├── message.php                     # Updated messaging system
└── test_lesson_assessment.php      # Test page
```

### Database Integration
The system integrates with existing database tables:
- `learning_goals` - User's learning requests
- `sessions` - Session information
- Assessment results are stored with lesson context

### Error Handling
- Graceful fallbacks if lesson files don't exist
- Clear error messages for missing assessments
- Logging for debugging mapping issues

## Conclusion

The Lesson Assessment Connection System provides a seamless integration between the learning content and assessment system. It ensures that users are tested on the exact material they're learning, at the appropriate difficulty level, creating a more effective and meaningful learning experience. 