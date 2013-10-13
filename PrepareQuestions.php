<?php
include_once 'Header.php';

$questionPaperId = $_REQUEST['questionPaperId'];
$sql = "SELECT cqp. * , ccm.name AS category_name, cq.id AS question_id, cq.questiontext, cqtm.id AS question_type_id, cq.category, cqtm.name AS question_type, cqpr.id AS rule_id, mc.fullname as course_name, cq.complexity_id
        FROM mdl_qpg_question_paper cqp
        LEFT JOIN mdl_qpg_question_paper_rules cqpr ON cqpr.question_paper_id = cqp.id
        LEFT JOIN mdl_qpg_question_paper_questions cqpq ON cqpq.question_paper_rules_id = cqpr.id
        LEFT JOIN mdl_question cq ON cq.id = cqpq.question_id
        LEFT JOIN mdl_qpg_question_type_master cqtm ON cqtm.id = cqpr.question_type_id
        LEFT JOIN mdl_question_categories ccm ON ccm.id = cqp.category_id
        LEFT JOIN mdl_course mc ON mc.id = cqp.course_id
        WHERE cqp.id =  '$questionPaperId'
        ORDER BY cqpr.question_type_id ASC";
$questionRules = GetAll($sql);

$sql = "SELECT * FROM mdl_qpg_question_paper_rules WHERE question_paper_id='$questionPaperId'
        ORDER BY question_type_id asc";
$chapterDets = GetAll($sql);

$sql = "SELECT id,name FROM mdl_qpg_question_type_master";
$questionTypeNames = GetAssoc($sql);

if(count($chapterDets) <= 0)
{
    echo "Something Seems Corrupted Please <a href='AddQuestionPaperRule.php'>Click Here</a>";
    die;
}
$complexity_easy = $questionRules[0]['complexity_easy'];
$complexity_medium = $questionRules[0]['complexity_medium'];
$complexity_hard = $questionRules[0]['complexity_hard'];
$complex_logic = complexity_logic($complexity_easy, $complexity_medium , $complexity_hard);
$questions = array();

if($questionRules[0]['question_id'] != "")
{
    $new = "0";
    foreach($questionRules as $questionItem)
    {
        if($questionItem['question_type'] != "")
        {
            $questions[] = array('question' => $questionItem['questiontext'],
                                'type' => $questionItem['question_type'],
                                'question_id' => $questionItem['question_id'],
                                'question_paper_rules_id' => $questionItem['rule_id'],
                                'chapter_id' => $questionItem['category'],
                                'question_type_id' => $questionItem['question_type_id']);
        }
    }
}
else
{
    $new = "1";
    $totTemp = 100;
    $prevQuesComplex = "";
    $tempComplex = $complex_logic;
    $tempQuestTypeId = "";
    
    foreach($chapterDets as $chapterDet)
    {
        if($tempQuestTypeId == "" || $tempQuestTypeId != $chapterDet['question_type_id'])
        {
            $tempQuestTypeId = $chapterDet['question_type_id'];
            $prevQuesComplex = "";
            $tempComplex = $complex_logic;
        }
        
        for($i=1; $i <= $chapterDet['no_of_question']; $i++)
        {
            if(!max($tempComplex))
                $tempComplex = $complex_logic;

            $temp = findMaxComplex($tempComplex);
            $complexy_type = $temp[1];
            if($prevQuesComplex == "" || $prevQuesComplex != $complexy_type)
            {
                $tempComplex = $temp[0];
                $prevQuesComplex = $complexy_type;
            }
            else
            {
                if($complexy_type == "1")
                {
                    if($tempComplex[0] <= ($tempComplex[1] + $tempComplex[2]))
                    {
                        if($tempComplex[1] >= $tempComplex[2])
                        {
                            $complexy_type = "2";
                            $tempComplex[1] = $tempComplex[1] - 1;
                        }
                        else
                        {
                            $complexy_type = "3";
                            $tempComplex[2] = $tempComplex[2] - 1;
                        }
                    }
                }
                elseif($complexy_type == "2")
                {
                    if($tempComplex[1] <= ($tempComplex[0] + $tempComplex[2]))
                    {
                        if($tempComplex[0] >= $tempComplex[2])
                        {
                            $complexy_type = "1";
                            $tempComplex[0] = $tempComplex[0] - 1;
                        }
                        else
                        {
                            $complexy_type = "3";
                            $tempComplex[2] = $tempComplex[2] - 1;
                        }
                    }
                }
                elseif($complexy_type == "3")
                {
                    if($tempComplex[2] <= ($tempComplex[0] + $tempComplex[1]))
                    {
                        if($tempComplex[0] >= $tempComplex[1])
                        {
                            $complexy_type = "1";
                            $tempComplex[0] = $tempComplex[0] - 1;
                        }
                        else
                        {
                            $complexy_type = "2";
                            $tempComplex[1] = $tempComplex[1] - 1;
                        }
                    }
                }
                $prevQuesComplex = $complexy_type;
            }
            $sql = "SELECT cq . * , cqtm.name AS question_type
                    FROM mdl_question cq
                    LEFT JOIN mdl_qpg_question_type_master cqtm ON cqtm.id = '". $chapterDet['question_type_id'] ."'
                    WHERE cq.category =  '". $chapterDet['category_id'] ."'
                    AND cq.defaultmark =  '". $chapterDet['mark_per_question'] ."'
                    AND cq.complexity_id = '" . $complexy_type . "'
                    ORDER BY RAND( ) 
                    LIMIT 0,1";
            $values = GetAll($sql);

            $value = array();
            foreach($values as $value)
            {
                $questions[] = array('question' => $value['questiontext'],
                                    'type' => $value['question_type'],
                                    'question_id' => $value['id'],
                                    'question_paper_rules_id' => $chapterDet['id'],
                                    'chapter_id' => $chapterDet['category_id'],
                                    'complexity_id' => $value['complexity_id'],
                                    'question_type_id' => $chapterDet['question_type_id']);
            }

            if(count($values) <= 0)
            {
                $sql = "select name from mdl_question_categories mqc where mqc.id = '" . $chapterDet['category_id'] . "'";
                $chapName = GetOne($sql);

                if(!isset($value['question_type']))
                {
                    $tempType = $questionTypeNames[$chapterDet['question_type_id']];
                }
                else
                {
                    $tempType = $value['question_type'];
                }
                $diff = $chapterDet['no_of_question'] - count($values);
                for($i=0; $i < $diff; $i++)
                {
                    $questions[] = array('question' => "Questions Not Available in this Chapter - <b>" . $chapName . "</b>",
                                    'type' => $tempType,
                                    'question_id' => 0,
                                    'question_paper_rules_id' => $chapterDet['id'],
                                    'chapter_id' => $chapterDet['category_id'],
                                    'complexity_id' => $complexy_type,
                                    'question_type_id' => $chapterDet['question_type_id']);
                }
            }
        }
    }
}

function findMaxComplex($tempComplex)
{
    if($tempComplex[0] >= $tempComplex[1] && $tempComplex[0] >= $tempComplex[2])
    {
        $tempComplex[0] = $tempComplex[0] - 1;
        return array($tempComplex,"1");
    }
    elseif($tempComplex[1] >= $tempComplex[2])
    {
        $tempComplex[1] = $tempComplex[1] - 1;
        return array($tempComplex,"2");
    }
    else
    {
        $tempComplex[2] = $tempComplex[2] - 1;
        return array($tempComplex,"3");
    }
}

function complexity_logic($easy, $medium, $hard)
{
    if(($easy + $medium + $hard) < 100)
    {
        return array("Random");
    }
    else
    {
        $easy = round(($easy/10));
        $medium = round(($medium/10));
        $hard = round(($hard/10));
        
            while(($easy + $medium + $hard) != 10)
            if(($easy + $medium + $hard) > 10)
            {
                if($hard >= $easy && $hard >= $medium)
                {
                    $hard--;
                }
                elseif($medium >= $easy)
                {
                    $medium--;
                }
                else
                {
                    $easy--;
                }
            }
            else
            {
                if($easy >= $medium && $easy >= $hard)
                {
                    $easy++;
                }
                elseif($medium >= $hard)
                {
                    $medium++;
                }
                else
                {
                    $hard++;
                }
            }
            
            if(($easy%2 == 0) && ($medium%2 == 0) && ($hard%2 == 0))
            {
                $easy = $easy/2;
                $medium = $medium/2;
                $hard = $hard/2;
            }
            return array($easy, $medium, $hard);
    }
}
?>
<script type="text/javascript">
    popupinit();
</script>
<div class="qpgContent">
    <table align="center" style="width: 300px;border-color: #0f0f0f;border-style: solid;border-collapse: collapse;" border="1" cellpadding="2" cellspacing="0">
        <tr>
            <td>
                Subject Name
            </td>
            <td>
                <?php echo $questionRules[0]['course_name']; ?>
            </td>
        </tr>
        <tr>
            <td>
                Category Name
            </td>
            <td>
                <?php echo $questionRules[0]['category_name']; ?>
            </td>
        </tr>
        <tr>
            <td>
                Term Name
            </td>
            <td>
                <?php echo $questionRules[0]['term']; ?>
            </td>
        </tr>
        <tr>
            <td>
                Year
            </td>
            <td>
                <?php echo $questionRules[0]['year']; ?>
            </td>
        </tr>
        <tr>
            <td>
                Total Time
            </td>
            <td>
                <?php echo $questionRules[0]['total_time']; ?>
            </td>
        </tr>
    </table>
    
    <table>
        <tr>
            <td>
                <h3>Question Paper</h3>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                $section = array("1" => "UnKnown - Questions are not available in Questions DB","VSA" => "Section - A", "SA-1" => "Section - B", "SA-2" => "Section - C", "LA" => "Section - D", "MCQ" => "Section - E");
                $temp = "";
                foreach($questions as $key => $question)
                {
                    if($temp != $question['type'])
                    {
                        $temp = $question['type'];
                        echo "<h3>". $section[$question['type']] ."</h3>";
                    }
                    
                    if(substr($question['question'],0,3) == '<p>')
                    {
                        $paraStart = '<p>';
                        $paraEnd = '</p>';
                    }
                    else
                    {
                        $paraStart = '';
                        $paraEnd = '';
                    }
                ?>
                <div style="border-top: 0px solid #ccc;">
                    <table border="0">
                        <tr>
                            <td style="padding:0px 0px 0 0px;vertical-align: top;">
                                <?php echo $paraStart; ?><img src="images/summary.jpg" alt="M" title="Change Question Manually" onclick="changeQuesion_select('<?php echo $question['chapter_id']; ?>','<?php echo $question['question_type_id']; ?>','<?php echo $key; ?>')" style="cursor: pointer;width:18px;" /><?php echo $paraEnd; ?>
                            </td>
                            <td style="padding:0px 0px 0 0px;vertical-align: top;">
                                <?php echo $paraStart; ?><img src="images/refresh.png" alt="R" title="Change Question Automatically" onclick="changeQuesion('<?php echo $question['chapter_id']; ?>','<?php echo $question['question_type_id']; ?>','<?php echo $key; ?>')" style="cursor: pointer;width:16px;" /><?php echo $paraEnd; ?>
                            </td>
                            <td style="padding:0px 0px 0 0px;vertical-align: top;">
                                <?php echo $paraStart; ?><img src="images/<?php if($question['complexity_id'] == 1) echo 'easy.png" alt="R" title="Easy Question"'; elseif($question['complexity_id'] == "2") echo 'medium.png" alt="M" title="Medium Question"'; else echo 'hard.png" alt="H" title="Hard Question"'; ?> style="width:16px;" /><?php echo $paraEnd; ?>
                            </td>
                            <td style="padding:0px 20px 0 20px;vertical-align: top;">
                                <?php echo $paraStart; ?>
                                <?php echo "" . ($key+1) . "."; ?>
                                <?php echo $paraEnd; ?>
                            </td>
                            <td style="vertical-align: top;" question_id="<?php echo $question['question_id']; ?>" question_paper_rules_id="<?php echo $question['question_paper_rules_id']; ?>" class="questions" id="questionid_<?php echo $key; ?>">
                                <?php echo $question['question']; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php
                }
                ?>
            </td>
        </tr>
    </table>
    
    <table align="center">
        <tr>
            <td>
                <input type="button" value="<?php if($new == "1") { echo "Save"; } else { echo "Update"; } ?>" onclick="saveClicked_questionpaper()" />
            </td>
            <td>
                <input type="button" value="Cancel" onclick="location.href = 'QuestionPaperList.php';" />
            </td>
            <td>
                <input type="button" value="Print" <?php if($new == "1") { echo "disabled"; } ?> onclick="window.open('printQuestionPaper.php?questionPaperId=<?php echo $questionPaperId; ?>','_blank')" />
            </td>
        </tr>
    </table>
</div>

<div id="popup_box">	<!-- OUR PopupBox DIV-->
	<div id='popup_content' style='height:500px;overflow: auto; overflow-x: hidden;'></div>
	<a id="popupBoxClose">Close</a>	
        <div style='text-align: center;'>
            <input type="button" value="Select" onclick="PushQuestionToDiv()" />
            <input type="button" value="Cancel" onclick="unloadPopupBox()" /> 
        </div>
</div>