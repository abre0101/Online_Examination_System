-- Add point_value column to question_page table
ALTER TABLE question_page 
ADD COLUMN IF NOT EXISTS point_value INT DEFAULT 1 AFTER Answer;

-- Add point_value column to truefalse_question table
ALTER TABLE truefalse_question 
ADD COLUMN IF NOT EXISTS point_value INT DEFAULT 1 AFTER Answer;

-- Update existing questions to have default point value of 1
UPDATE question_page SET point_value = 1 WHERE point_value IS NULL OR point_value = 0;
UPDATE truefalse_question SET point_value = 1 WHERE point_value IS NULL OR point_value = 0;
