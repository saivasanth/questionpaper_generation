<?php
include("dbconfig.php");
$dbPortal = dbconnect();

if(isset($_POST['getClassValue']) && $_POST['getClassValue'] == "1")
{
    $sql = "SELECT * FROM qpg_class_master WHERE id='". $_POST['idVal'] ."'";
    $class = GetRow($sql);
    echo json_encode($class);
}

if(isset($_POST['getChapterValue']) && $_POST['getChapterValue'] == "1")
{
    $sql = "SELECT ccm.id,ccm.name as chaptername,csm.id as subject_id,csm.type as subjectname
    FROM qpg_chapter_master ccm
    LEFT JOIN qpg_subject_master csm ON csm.id=ccm.subject_id
    WHERE ccm.id='". $_POST['idVal'] ."'";
    $class = GetRow($sql);
    echo json_encode($class);
}

if(isset($_POST['setClassValue']) && $_POST['setClassValue'] == "1")
{
    $sql = "UPDATE qpg_class_master
            SET class_name='". $_POST['className'] ."'
            WHERE id='". $_POST['classId'] ."'";
    $class = Execute($sql);
}

if(isset($_POST['setChapterValue']) && ($_POST['setChapterValue'] == "1" || $_POST['setChapterValue'] == "2"))
{
    if($_POST['setChapterValue'] == "1")
    {
        $sql = "INSERT INTO qpg_chapter_master(name,subject_id,class_id)
                VALUES('". $_POST['chapterName'] ."','". $_POST['subjectId'] ."','". $_POST['classId'] ."')";
    }
    else
    {
        $sql = "UPDATE qpg_chapter_master
                SET name='". $_POST['chapterName'] ."',subject_id='". $_POST['subjectId'] ."'
                WHERE id='". $_POST['chapterId'] ."'";
    }
    $class = Execute($sql);
}

if(isset($_POST['getQuestionValue']) && $_POST['getQuestionValue'] == "1")
{
    $sql = "SELECT *
    FROM qpg_question cq
    WHERE cq.id='". $_POST['idVal'] ."'";
    $question = GetRow($sql);
    echo json_encode($question);
}

if(isset($_POST['getAllChapters']) && $_POST['getAllChapters'] == "1")
{
    if(isset($_POST['categoryId']))
    {
        $sql = "SELECT mqc.id,mqc.name as chaptername, mcqtm.id as question_type_id, mcqtm.name as question_type
        FROM mdl_question_categories mqc, mdl_qpg_question_type_master mcqtm
        WHERE mqc.parent='". $_POST['categoryId'] ."' AND mcqtm.visible='1' ORDER BY mqc.id ASC, mcqtm.id ASC";
        $chapters = GetAll($sql);
    }
    elseif(isset($_POST['subjectId']))
    {
        $sql = "SELECT mqc.id,mqc.name as chaptername, mcqtm.id as question_type_id, mcqtm.name as question_type,
                        mqc2.name as categoryname
                FROM mdl_course mc
                LEFT JOIN mdl_context mcon ON mcon.instanceid=mc.id
                LEFT JOIN mdl_question_categories mqc2 ON mqc2.contextid=mcon.id AND mqc2.parent=0
                LEFT JOIN mdl_question_categories mqc ON mqc.parent=mqc2.id
                ,mdl_qpg_question_type_master mcqtm
                WHERE mc.id='". $_POST['subjectId'] ."' AND mqc.id > 0 AND mcqtm.visible='1' ORDER BY mqc.id ASC, mcqtm.id ASC";
        $chapters = GetAll($sql);
    }
    elseif(isset($_POST['classId']))
    {
        $sql = "SELECT mqc.id,mqc.name as chaptername, mcqtm.id as question_type_id, mcqtm.name as question_type,
                        mc.fullname as subjectname, mqc2.name as categoryname
                FROM mdl_course mc
                LEFT JOIN mdl_context mcon ON mcon.instanceid=mc.id
                LEFT JOIN mdl_question_categories mqc2 ON mqc2.contextid=mcon.id AND mqc2.parent=0
                LEFT JOIN mdl_question_categories mqc ON mqc.parent=mqc2.id
                ,mdl_qpg_question_type_master mcqtm
                WHERE mc.category='". $_POST['classId'] ."' AND mqc.id > 0 AND mcqtm.visible='1' ORDER BY mqc.id ASC, mcqtm.id ASC";
        $chapters = GetAll($sql);
    }
    echo json_encode($chapters);
}

if(isset($_POST['insertQuestion']) && $_POST['insertQuestion'] == "1")
{
    $sql = "INSERT INTO 
            qpg_question(question,question_html,question_type_id,difficulty_id,chapter_id) 
            VALUES('". $_POST['question'] ."','". $_POST['htmlVal'] ."','". $_POST['qpgQuestionType'] ."','". $_POST['qpgDifficulty'] ."','". $_POST['chapterId'] ."')";
    $insertedId = InsertExecute($sql);
    echo json_encode($insertedId);
}

if(isset($_POST['updateQuestion']) && $_POST['updateQuestion'] == "1")
{
    $sql = "UPDATE qpg_question SET question = '". mysql_escape_string($_POST['question']) ."',question_html = '". mysql_escape_string($_POST['htmlVal']) ."',question_type_id = '". mysql_escape_string($_POST['qpgQuestionType']) ."',difficulty_id = '". mysql_escape_string($_POST['qpgDifficulty']) ."',chapter_id = '". mysql_escape_string($_POST['chapterId']) ."' WHERE id = '". mysql_escape_string($_POST['questionId']) ."'";
    $update = Execute($sql);
    echo json_encode($update);
}

if(isset($_POST['insertQuestionRules']) && $_POST['insertQuestionRules'] == "1")
{
    $sql = "INSERT INTO 
            mdl_qpg_question_paper(category_id,term,year,total_time,course_id,class_id, complexity_easy, complexity_medium, complexity_hard) 
            VALUES('". $_POST['categoryId'] ."','". $_POST['examTerm'] ."','". $_POST['examYear'] ."','". $_POST['examTime'] ."','". $_POST['courseId'] ."','". $_POST['classId'] ."','". $_POST['complexity_easy'] ."','". $_POST['complexity_medium'] ."','". $_POST['complexity_hard'] ."')";

    $insertedId = InsertExecute($sql);

    $chapterDets = explode("$", $_POST['fullQuesDets']);

    foreach($chapterDets as $chapterDet)
    {
        $temp = explode("*", $chapterDet);
        $sql = "INSERT INTO 
                mdl_qpg_question_paper_rules(question_paper_id,question_type_id,category_id,mark_per_question,no_of_question,total_marks) 
                VALUES('$insertedId','$temp[1]','" . $temp[0] . "','". $temp[2] ."','$temp[3]','". $temp[3]*$temp[2] ."')";
        Execute($sql);
    }
    echo json_encode($insertedId);
}

if(isset($_POST['getQuestionRuleValue']) && $_POST['getQuestionRuleValue'] == "1")
{
    $sql = "SELECT cqpr.*,ccm.name as chaptername,GROUP_CONCAT(CONCAT(cqpr.question_type_id,'-',cqpr.no_of_question)) as chapterValues
    FROM mdl_qpg_question_paper_rules cqpr
    LEFT JOIN mdl_question_categories ccm ON ccm.id=cqpr.category_id
    WHERE cqpr.question_paper_id='". $_POST['idVal'] ."'
    GROUP BY cqpr.category_id ORDER BY cqpr.category_id ASC";
    $questionRules = GetAll($sql);
    echo json_encode($questionRules);
}

if(isset($_POST['getQuestion']) && $_POST['getQuestion'] == "1")
{
    $sql = "SELECT mq.questiontext as question_html,mq.id
    FROM mdl_question mq
    LEFT JOIN mdl_qpg_question_type_master mqtm ON mqtm.default_mark = mq.defaultmark
    WHERE mqtm.id='". $_POST['questionType'] ."' AND category='". $_POST['chapterId'] ."'
    ORDER BY RAND()";
    $class = GetRow($sql);
    echo json_encode($class);
}

if(isset($_POST['getQuestions']) && $_POST['getQuestions'] == "1")
{
    $sql = "SELECT mq.questiontext as question_html,mq.id,mq.complexity_id
    FROM mdl_question mq
    LEFT JOIN mdl_qpg_question_type_master mqtm ON mqtm.default_mark = mq.defaultmark
    WHERE mqtm.id='". $_POST['questionType'] ."' AND category='". $_POST['chapterId'] ."'
    ORDER BY mq.id asc";
    $class = GetAll($sql);
    echo json_encode($class);
}

if(isset($_POST['saveQuestionPaper']) && $_POST['saveQuestionPaper'] == "1")
{
    $questionRulesId = implode(",", $_POST['questions_ruleid']);
    $sql = "DELETE FROM mdl_qpg_question_paper_questions WHERE question_paper_rules_id IN (". $questionRulesId .")";
    Execute($sql);
    
    $questionRuleIds = $_POST['questions_ruleid'];
    foreach($_POST['questions'] as $key => $questionId)
    {
        $sql = "INSERT INTO mdl_qpg_question_paper_questions(question_id,question_paper_rules_id) VALUES('$questionId','". $questionRuleIds[$key] ."')";
        Execute($sql);
    }
}

if(isset($_POST['deleteQuestion']) && $_POST['deleteQuestion'] == "1")
{
    $sql = "DELETE FROM qpg_question WHERE id = '". $_POST['questionId'] ."'";
    Execute($sql);
    
    $sql = "DELETE FROM cbsx_question_paper_questions WHERE question_id = '". $_POST['questionId'] ."'";
    Execute($sql);
}

if(isset($_POST['deleteChapter']) && $_POST['deleteChapter'] == "1")
{
    $sql = "DELETE FROM qpg_chapter_master WHERE id = '". $_POST['chapterId'] ."'";
    Execute($sql);
    
    $sql = "DELETE FROM qpg_question WHERE chapter_id = '". $_POST['chapterId'] ."'";
    Execute($sql);
    
    $sql = "DELETE FROM cbsx_question_paper_questions WHERE question_id NOT IN (SELECT id from qpg_question)";
    Execute($sql);
}

if(isset($_POST['getMarks']) && $_POST['getMarks'] == "1")
{
    if(isset($_POST['type']) && $_POST['type'] == '1')
    {
        $sql = "SELECT DISTINCT ROUND(mq.defaultmark,0) as marks 
                FROM mdl_question mq 
                LEFT JOIN mdl_question_categories mqc ON mqc.id=mq.category 
                WHERE mqc.id='". $_POST['categoryId'] ."' OR mqc.parent='". $_POST['categoryId'] ."' 
                ORDER BY marks ASC";
        $marks = GetAll($sql);
    }
    elseif(isset($_POST['type']) && $_POST['type'] == '2')
    {
        $sql = "SELECT DISTINCT ROUND(mq.defaultmark,0) as marks 
                FROM mdl_course mc
                LEFT JOIN mdl_context mcon ON mcon.instanceid=mc.id 
                LEFT JOIN mdl_question_categories mqc ON mqc.contextid=mcon.id
                LEFT JOIN mdl_question mq ON mq.category = mqc.id
                WHERE mc.id='". $_POST['categoryId'] ."' 
                ORDER BY marks ASC";
        $marks = GetAll($sql);
    }
    elseif(isset($_POST['type']) && $_POST['type'] == '3')
    {
        $sql = "SELECT DISTINCT ROUND(mq.defaultmark,0) as marks 
                FROM mdl_course mc
                LEFT JOIN mdl_context mcon ON mcon.instanceid=mc.id 
                LEFT JOIN mdl_question_categories mqc ON mqc.contextid=mcon.id
                LEFT JOIN mdl_question mq ON mq.category = mqc.id
                WHERE mc.category='". $_POST['categoryId'] ."' 
                ORDER BY marks ASC";
        $marks = GetAll($sql);
    }
    
    echo json_encode($marks);
}

if(isset($_POST['getAllCourses']) && $_POST['getAllCourses']=='1')
{
    $sql = "SELECT id,fullname as name FROM mdl_course where category='". $_POST['classId'] ."' ORDER BY id ASC";
    $marks = GetAll($sql);
//    echo $sql;
    echo json_encode($marks);
}

if(isset($_POST['getAllCategories']) && $_POST['getAllCategories']=='1')
{
    $sql = "SELECT GROUP_CONCAT(id) FROM mdl_context where instanceid='". $_POST['courseId'] ."' GROUP BY instanceid";
    $contextIds = GetOne($sql);
//    echo $contextIds;
    $sql = "SELECT id,name FROM mdl_question_categories where contextid IN (". $contextIds .") AND parent='0' ORDER BY id ASC";
    $marks = GetAll($sql);
//    echo $sql;
    echo json_encode($marks);
}
if(isset($_POST['deleteQuestionPaper']) && $_POST['deleteQuestionPaper'] == "1")
{
    $sql = "SELECT GROUP_CONCAT(id) FROM mdl_qpg_question_paper_rules where question_paper_id='". $_POST['questionPaperId'] ."' GROUP BY question_paper_id";
    $ruleIds = GetOne($sql);
    
    if(strlen($ruleIds) > 0)
    {
        $sql = "DELETE FROM mdl_qpg_question_paper_questions WHERE question_paper_rules_id IN ($ruleIds)";
        Execute($sql);
    }
    $sql = "DELETE FROM mdl_qpg_question_paper_rules WHERE question_paper_id='". $_POST['questionPaperId'] ."'";
    Execute($sql);
    
    $sql = "DELETE FROM mdl_qpg_question_paper WHERE id='". $_POST['questionPaperId'] ."'";
    Execute($sql);
}

if(isset($_POST['getQuestionTypeValue']) && $_POST['getQuestionTypeValue'] == "1")
{
    $sql = "SELECT * FROM mdl_qpg_question_type_master where id='". $_POST['idVal'] ."'";
    $questionTypes = GetRow($sql);
    echo json_encode($questionTypes);
}



if(isset($_POST['setQuestionTypeValue']) && ($_POST['setQuestionTypeValue'] == "1" || $_POST['setQuestionTypeValue'] == "2"))
{
    if($_POST['setQuestionTypeValue'] == "1")
    {
        $sql = "INSERT INTO mdl_qpg_question_type_master(id,name,description,default_mark,visible)
                VALUES('". $_POST['id'] ."','". $_POST['name'] ."','". $_POST['description'] ."','". $_POST['mark'] ."','". $_POST['visib'] ."')";
    }
    else
    {
        $sql = "UPDATE mdl_qpg_question_type_master
                SET name='". $_POST['name'] ."',description='". $_POST['description'] ."',default_mark='". $_POST['mark'] ."',visible='". $_POST['visib'] ."'
                WHERE id='". $_POST['id'] ."'";
    }
    $class = Execute($sql);
}
?>
