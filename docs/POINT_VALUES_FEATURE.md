# Point Values Per Question Feature

## Overview
This feature allows instructors to assign different point values (weights) to individual questions, enabling more flexible grading schemes.

## Database Changes

### SQL Migration
Run the following SQL to add point_value columns:

```sql
-- Add to question_page table (MCQ questions)
ALTER TABLE question_page 
ADD COLUMN IF NOT EXISTS point_value INT DEFAULT 1 AFTER Answer;

-- Add to truefalse_question table
ALTER TABLE truefalse_question 
ADD COLUMN IF NOT EXISTS point_value INT DEFAULT 1 AFTER Answer;

-- Set default values for existing questions
UPDATE question_page SET point_value = 1 WHERE point_value IS NULL OR point_value = 0;
UPDATE truefalse_question SET point_value = 1 WHERE point_value IS NULL OR point_value = 0;
```

Or simply run: `mysql -u root -p oes < utils/add_point_values.sql`

## Features Implemented

### 1. Add Point Value When Creating Questions
- **File**: `Instructor/AddQuestion.php`
- **Field**: Point Value (1-100)
- **Default**: 1 point
- Instructors can specify how many points each question is worth

### 2. Edit Point Values
- **File**: `Instructor/EditQuestion.php`
- Instructors can modify point values for existing questions

### 3. Display Point Values
- **File**: `Instructor/ManageQuestions.php`
- Shows point value badge next to each question
- Displays total points for each exam

### 4. Weighted Scoring
- **File**: `Student/exam-interface.php` & `save-exam-result.php`
- Calculates scores based on point values
- Formula: `(Earned Points / Total Possible Points) × 100`

## Usage Examples

### Example 1: Standard Exam (All Equal Weight)
- 20 questions × 1 point each = 20 total points
- Student answers 15 correctly = 15/20 = 75%

### Example 2: Weighted Exam
- 10 easy questions × 1 point = 10 points
- 5 medium questions × 2 points = 10 points
- 3 hard questions × 5 points = 15 points
- **Total**: 35 points
- Student gets: 9 easy + 4 medium + 2 hard = 9 + 8 + 10 = 27/35 = 77.14%

### Example 3: Mixed Difficulty
- Question 1-5: 1 point each (basic concepts)
- Question 6-10: 2 points each (application)
- Question 11-15: 3 points each (analysis)
- Question 16-20: 5 points each (synthesis)
- **Total**: 5 + 10 + 15 + 25 = 55 points

## Instructor Workflow

1. **Create Question**
   - Go to "Add New Question"
   - Fill in question details
   - Set point value (1-100)
   - Save question

2. **View Questions**
   - Questions show point value badges
   - Total points displayed per exam
   - Filter by point value range

3. **Edit Point Values**
   - Click "Edit" on any question
   - Modify point value
   - Save changes

4. **Create Balanced Exams**
   - Mix different point values
   - Ensure total points align with grading scheme
   - Preview total points before publishing

## Student Experience

### During Exam
- Point values displayed on each question
- Progress bar shows points earned vs total
- Example: "Question 5 (3 points)"

### After Exam
- Results show: "You earned 45 out of 60 points (75%)"
- Breakdown by question with points
- Review shows which high-value questions were missed

## Grading Calculation

### Old Method (Equal Weight)
```
Score = (Correct Answers / Total Questions) × 100
Example: 15/20 = 75%
```

### New Method (Weighted)
```
Score = (Points Earned / Total Points) × 100
Example: 27/35 = 77.14%
```

## Best Practices

### 1. Point Value Guidelines
- **Easy questions**: 1-2 points
- **Medium questions**: 3-5 points
- **Hard questions**: 6-10 points
- **Complex problems**: 10+ points

### 2. Exam Design
- Balance difficulty with point distribution
- Higher points for questions requiring more time/skill
- Ensure total points are reasonable (50-100 typical)

### 3. Consistency
- Use similar point values across similar exams
- Document your point value scheme
- Inform students about point distribution

## API/Backend Changes

### InsertQuestionWithPoints.php
```php
// Handles point_value parameter
$pointValue = isset($_POST['point_value']) ? intval($_POST['point_value']) : 1;
```

### Calculate Exam Score
```php
// Get total possible points
$totalPoints = $con->query("SELECT SUM(point_value) as total FROM question_page WHERE exam_id = $examId")->fetch_assoc()['total'];

// Calculate earned points
$earnedPoints = 0;
foreach($answers as $answer) {
    if($answer['is_correct']) {
        $earnedPoints += $answer['point_value'];
    }
}

// Calculate percentage
$percentage = ($earnedPoints / $totalPoints) * 100;
```

## Migration for Existing Data

All existing questions will automatically get `point_value = 1`, maintaining backward compatibility. No changes to existing scores.

## Future Enhancements

1. **Partial Credit**: Award partial points for partially correct answers
2. **Negative Marking**: Deduct points for wrong answers
3. **Bonus Questions**: Extra credit questions
4. **Point Ranges**: Set min/max points per question type
5. **Auto-Balance**: Suggest point values based on difficulty
6. **Analytics**: Show which point values correlate with student performance

## Troubleshooting

### Issue: Point values not showing
**Solution**: Run the SQL migration to add the column

### Issue: Scores calculating incorrectly
**Solution**: Ensure all questions have point_value > 0

### Issue: Old exams showing wrong totals
**Solution**: Update old questions: `UPDATE question_page SET point_value = 1 WHERE point_value IS NULL`

## Files Modified

1. `Instructor/AddQuestion.php` - Added point value input
2. `Instructor/EditQuestion.php` - Added point value editing
3. `Instructor/ManageQuestions.php` - Display point values
4. `Instructor/InsertQuestionWithPoints.php` - Save with points
5. `Student/exam-interface.php` - Show points during exam
6. `Student/save-exam-result.php` - Calculate weighted scores
7. `utils/add_point_values.sql` - Database migration

## Testing Checklist

- [ ] Create question with custom point value
- [ ] Edit existing question's point value
- [ ] View questions showing point badges
- [ ] Take exam and see point values
- [ ] Verify score calculation is correct
- [ ] Check results page shows points earned
- [ ] Test with all point values = 1 (backward compatibility)
- [ ] Test with mixed point values
- [ ] Verify total points calculation
- [ ] Check exam committee can see point values

---

**Implementation Date**: January 30, 2026  
**Status**: ✅ Complete  
**Version**: 1.0
