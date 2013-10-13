<?php
$noHtml = "1";
include_once 'Header.php';

$questionPaperId = $_REQUEST['questionPaperId'];
$sql = "SELECT cqp. * , ccm.name AS category_name, cq.id AS question_id, cq.questiontext, cqtm.id AS question_type_id, cq.category, cqtm.name AS question_type, cqpr.id AS rule_id
        FROM mdl_qpg_question_paper cqp
        LEFT JOIN mdl_qpg_question_paper_rules cqpr ON cqpr.question_paper_id = cqp.id
        LEFT JOIN mdl_qpg_question_paper_questions cqpq ON cqpq.question_paper_rules_id = cqpr.id
        LEFT JOIN mdl_question cq ON cq.id = cqpq.question_id
        LEFT JOIN mdl_qpg_question_type_master cqtm ON cqtm.id = cqpr.question_type_id
        LEFT JOIN mdl_question_categories ccm ON ccm.id = cqp.category_id
        WHERE cqp.id =  '$questionPaperId'
        ORDER BY cqpr.question_type_id ASC";
$questionRules = GetAll($sql);

$sql = "SELECT SUM(total_marks) total FROM cbsx_question_paper_rules WHERE question_paper_id = $questionPaperId";
$totalMarks = GetOne($sql);
?>

<div style='text-align: center;'><br>
    Sample Question Paper<br>
    Science<br>
    <?php echo $questionRules[0]['category_name']; ?><br>
    <?php echo $questionRules[0]['term']; ?> (<?php echo $questionRules[0]['year']; ?> - <?php echo ($questionRules[0]['year']+1); ?>)<br>
    <table align='center'>
        <tr>
            <td align='left'>
                TIME: <?php echo $questionRules[0]['total_time']; ?> hrs
            </td>
            <td align='right'>
                MM: <?php echo $totalMarks; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" align='left'>General Instructions</td>
        </tr>
        <tr>
            <td colspan="2">
                <table>
                    <tr>
                        <td>i) </td>
                        <td>
                            The question paper consists of two sections, A and B, You are to attend both the sections.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<?php

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


$section = array("1" => "UnKnown","VSA" => "Section - A", "SA-1" => "Section - B", "SA-2" => "Section - C", "LA" => "Section - D", "MCQ" => "Section - E");
$temp = "";
foreach($questions as $key => $question)
{
    if($temp != $question['type'])
    {
        $temp = $question['type'];
        echo "<br><div style='text-align:center;font-weight:bold;font-size:20px;text-decoration:underline;'>". $section[$question['type']] ."</div>";
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
            <td style="padding:0px 20px 0 20px;vertical-align: top;">
                <?php echo $paraStart; ?><?php echo "" . ($key+1) . "."; ?><?php echo $paraEnd; ?>
            </td>
            <td style="vertical-align: top;" question_id="<?php echo $question['question_id']; ?>" question_paper_rules_id="<?php echo $question['question_paper_rules_id']; ?>" class="questions" id="questionid_<?php echo $key; ?>">
                <?php echo $paraStart; ?><?php echo $question['question']; ?><?php echo $paraEnd; ?>
            </td>
        </tr>
    </table>
</div>
<?php
}
?>
<script type="text/javascript">
    print();
</script>