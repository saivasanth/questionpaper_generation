<?php
include_once 'Header.php';

$sql = "SELECT cq.*, cqtm.name as question_type
    FROM qpg_question cq
    LEFT JOIN qpg_question_type_master cqtm ON cqtm.id=cq.question_type_id
    WHERE cq.chapter_id='" . $_REQUEST['chapterId'] . "'";
$questions = GetAll($sql);
?>
<script type="text/javascript">
questionListInit();
</script>
<div class="qpgContent">
    <div class="qpgQuestionList">
        <table style="border:1px solid #cccccc;width:600px;" cellspacing="0" align="center">
            <tr style="background: #0099FF;">
                <td>
                    S.No
                </td>
                <td>
                    Question
                </td>
                <td>
                    Question Type
                </td>
                <td>
                    Action
                </td>
            </tr>
        <?php
        foreach($questions as $key => $question)
        {
        ?>
            <tr class="qpgQuestionRow" id="<?php echo $question['id'] ?>">
                <td>
                    <?php echo ($key + 1); ?>
                </td>
                <td>
                    <?php echo $question['question'] ?>
                </td>
                <td>
                    <?php echo $question['question_type'] ?>
                </td>
                <td>
                    View
                </td>
            </tr>
        <?php
        }
        ?>
        </table>
    </div>
    <div style="text-align: center;">
        <input type="button" value="Back" onclick="history.back()" />
    </div>
    <div class="qpgQuestionContents">
        <div id="qpgQuestionContainer">
            <br />
            <table style="width:600px;border-collapse: collapse;" align="center" border="1" cellspacing="0" cellpadding="10">
                <tr>
                    <td>
                        ID
                    </td>
                    <td>
                        <span id="questionIdContainer">
                            
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Question
                    </td>
                    <td>
                        <span id="questionContainer">
                            
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="button" value="Edit" onclick="loadPage('EditQuestion.php?questionId=','1')" />
                        <input type="button" value="Cancel" onclick="cancelClicked()" />
                        <input type="button" value="Delete" onclick="deleteQuestion()" />
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>