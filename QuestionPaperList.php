<?php
include_once 'Header.php';

$sql = "SELECT cqp . * , ccm.name AS category_name, mc.fullname as course_name
        FROM mdl_qpg_question_paper cqp
        LEFT JOIN mdl_question_categories ccm ON ccm.id = cqp.category_id
        LEFT JOIN mdl_course mc ON mc.id = cqp.course_id
        ORDER BY cqp.id DESC";
$questionPapers = GetAll($sql);
?>
<script type="text/javascript">
questionPaperListInit();
</script>
<div class="qpgContent">
    <div class="qpgQuestionPaperList">
        <table style="border:1px solid #cccccc;width:600px;" cellspacing="0" align="center">
            <tr style="background: #0099FF;">
                <td>
                    S.No
                </td>
                <td>
                    Subject
                </td>
                <td>
                    Category
                </td>
                <td>
                    Term
                </td>
                <td>
                    Year
                </td>
            </tr>
        <?php
        foreach($questionPapers as $key => $questionPaper)
        {
        ?>
            <tr class="qpgQuestionPaperRow" id="<?php echo $questionPaper['id'] ?>">
                <td>
                    <?php echo ($key + 1); ?>
                </td>
                <td>
                    <?php echo $questionPaper['course_name'] ?>
                </td>
                <td>
                    <?php echo $questionPaper['category_name'] ?>
                </td>
                <td>
                    <?php echo $questionPaper['term'] ?>
                </td>
                <td>
                    <?php echo $questionPaper['year'] ?>
                </td>
            </tr>
        <?php
        }
        ?>
        </table>
    </div>
    <div class="qpgQuestionPaperContents">
        <div id="qpgQuestionPaperContainer">
            <br />
            <table style="width:600px;border-collapse: collapse;" align="center" border="1" cellspacing="0" cellpadding="10">
                <tr>
                    <td id="qpgQuestionPaperRules">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="center">
                        <!--<input type="button" value="Edit" onclick="" disabled="" />-->
                        <input type="button" value="View Question Paper" onclick="loadPage('PrepareQuestions.php?questionPaperId=','1')" />
                        <input type="button" value="Cancel" onclick="cancelClicked()" />
                        <input type="button" value="Delete" onclick="deleteClicked()" />
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div style="text-align: center;">
        <br />
        <input type="button" value="Add New Question Paper Rule" onclick="loadPage('AddQuestionPaperRule.php')" />
    </div>
</div>